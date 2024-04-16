<x-wallet-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Payments') }}"/>
    </x-slot>

    <x-wallet-payments-tab></x-wallet-payments-tab>

    <div
        x-data="table({
            route: '{{route('wallet.payments-2.index')}}',
            fields: [
                'created_at',
                'order_id',
                'transaction_id',
                'gcash_ref',
                'arx_status',
                'amount',
                'actions',
            ],
            search: {
                field: 'transaction_id',
                label: 'Transaction ID',
                value: '',
                status: 'ALL'
            },
            webhookFields:[
                'created_at',
                'id',
                'retry_count',
                'status',
                'actions'
            ],
            webhookLoading: true,
            webhooks: [],
            webhookEntityId: ''
        })"
    >
        <x-modal name="webhooks" maxWidth="3xl" focusable>
            <x-table shadow="shadow-none">
                <x-slot:head>
                    <template x-for="field in webhookFields">
                        <x-th text="field"></x-th>
                    </template>
                </x-slot:head>
                <x-slot:body>
                    <template x-for="webhook in webhooks" :key="webhook.id">
                        <tr>
                            <x-td text="webhook.created_at"></x-td>
                            <x-td text="webhook.id"></x-td>
                            <x-td text="webhook.retry_count"></x-td>
                            <x-td classes="tagColor(webhook.status)" text="titleCase(webhook.status)"></x-td>
                            <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
                                <button
                                    x-on:click="retryWebhook('{{route('wallet.webhooks.retry')}}', webhook.id)"
                                    type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                                >Retry
                                </button>
                            </td>
                        </tr>
                    </template>
                </x-slot:body>
            </x-table>
            <div class="m-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>
            </div>
        </x-modal>
        <x-filter route="{{route('wallet.payments-2.index')}}">
            <x-slot:searches>
                <x-filter-field field="transaction_id" label="Transaction ID"></x-filter-field>
                <x-filter-field field="order_id" label="Order ID"></x-filter-field>
                <x-filter-field field="gcash_reference_number" label="Gcash Ref"></x-filter-field>
            </x-slot:searches>
            <x-slot:statuses>
                <option value='INITIAL'>Initial</option>
                <option value='PROCESSED'>Processed</option>
                <option value='PENDING'>Pending</option>
                <option value='SUCCESS'>Success</option>
                <option value='FAILED'>Failed</option>
                <option value='REJECTED'>Rejected</option>
                <option value='ERROR'>Error</option>
                <option value='THIRD_PARTY_ERROR'>Third Party Error</option>
                <option value='MANUAL_SYNC_REQUIRED'>Manual Sync Required</option>
            </x-slot:statuses>
        </x-filter>
        <x-table>
            <x-slot:pagination>
                <x-pagination-link x-on:click="fetchData(links.prev)" label="Next"/>
                <x-pagination-link x-on:click="fetchData(links.next)" label="Prev"/>
            </x-slot:pagination>
            <x-slot:head>
                <template x-for="field in fields">
                    <x-th text="field"></x-th>
                </template>
            </x-slot:head>
            <x-slot:body>
                <template x-if="!loading && data.length > 0">
                    <template x-for="order in data" :key="order.id">
                        <tr>
                            <x-td text="order.created_at"></x-td>
                            <x-td text="order.order_id"></x-td>
                            <x-td text="order.transaction_id"></x-td>
                            <x-td text="order.gcash_reference_number"></x-td>
                            <x-td classes="tagColor(order.arx_status)"
                                  text="titleCase(order.arx_status)"></x-td>
                            <x-td text="currency(order.amount)"></x-td>
                            <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
                                <button
                                    x-on:click="sync('{{route('wallet.payments-2.sync')}}', order.id)"
                                    type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                                >Sync
                                </button>
                                <button
                                    type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-teal-500 rounded border-teal-500 hover:bg-teal-600 focus:ring-4 focus:outline-none focus:ring-teal-300 dark:bg-teal-600 dark:hover:bg-teal-700 dark:focus:ring-teal-800"
                                    x-on:click="{webhooks, webhookLoading, webhookEntityId} = await fetchWebhooks('{{route('wallet.webhooks.index')}}', order.id); $dispatch('open-modal', 'webhooks');"
                                >Webhooks
                                </button>
                            </td>
                        </tr>
                    </template>
                </template>
            </x-slot:body>
        </x-table>
    </div>
    <x-slot:script>
        <script>
            function sync(url, id, callback = null) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id,
                        entity_type: 'ASUKA_CASHIN',
                        _token: "{{ csrf_token() }}"
                    }),
                }).then(response => response.json())
                    .then(response => {
                        if (response.sync_status === 200) {
                            alert('Sync success');
                            if (callback) {
                                callback();
                            }
                        } else {
                            if (response.message && response.message.includes('403 Forbidden')) {
                                alert('Please login again to sync!');
                            } else {
                                alert(response.message);
                            }
                        }
                    });
            }


            function retryWebhook(url, id, callback = null) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: id,
                        _token: "{{ csrf_token() }}"
                    }),
                }).then(response => response.json())
                    .then(response => {
                        if (response.retry_status === 200) {
                            alert('Retry success');
                            if (callback) {
                                callback();
                            }
                        } else {
                            if (response.message && response.message.includes('403 Forbidden')) {
                                alert('Please login again to retry!');
                            } else {
                                alert(response.message);
                            }
                        }
                    });
            }

            async function fetchWebhooks(url, entityId) {
                let queryString = objectToQueryString({
                    field: 'entity_id',
                    value: entityId,
                    entity_type: 'ASUKA_CASHIN'
                });
                url = `${url}?${queryString}`;
                return fetch(url)
                    .then(response => response.json())
                    .then(response => ({
                        webhooks: response.data,
                        webhookLoading: false,
                        webhookEntityId: entityId,
                    }));
            }
        </script>
    </x-slot:script>
</x-wallet-app-layout>
