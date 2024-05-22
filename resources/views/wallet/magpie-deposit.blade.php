<x-wallet-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Payments') }}"/>
    </x-slot>

    <x-wallet-payments-tab></x-wallet-payments-tab>

    <div
        x-data="table({
            route: '{{route('wallet.payments-3.index')}}',
            fields: [
                'created_at',
                'order_id',
                'transaction_id',
                'gcash_ref',
                'status',
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
                'event_type',
                'status',
            ],
            webhookLoading: true,
            webhooks: [],
            webhookEntityId: ''
        })"
    >
        <x-filter route="{{route('wallet.payments-3.index')}}">
            <x-slot:searches>
                <x-filter-field field="transaction_id" label="Transaction ID"></x-filter-field>
                <x-filter-field field="order_id" label="Order ID"></x-filter-field>
                <x-filter-field field="gcash_reference_number" label="Gcash Ref"></x-filter-field>
            </x-slot:searches>
            <x-slot:statuses>
                <option value='INITIAL'>Initial</option>
                <option value='PROCESSING'>Processing</option>
                <option value='PENDING'>Pending</option>
                <option value='SUCCESS'>Success</option>
                <option value='FAILED'>Failed</option>
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
                            <x-td classes="tagColor(order.status)"
                                  text="titleCase(order.status)"></x-td>
                            <x-td text="currency(order.amount)"></x-td>


                            <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
                                @if(in_array('DASHBOARD_ADMIN', session('user_metadata')['permissions']))
                                    <button
                                        x-on:click="forcePay('{{route('wallet.payments-3.force-pay')}}', order.id)"
                                        type="button"
                                        class="px-3 py-2 text-xs font-medium text-center text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                                    >Force Pay
                                    </button>
                                @endif
                            </td>

                        </tr>
                    </template>
                </template>
            </x-slot:body>
        </x-table>
    </div>
    <x-slot:script>
        <script>
            function forcePay(url, id, callback = null) {
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
                        if (response.force_pay_status === 200) {
                            alert('Force pay sent!');
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
        </script>
    </x-slot:script>
</x-wallet-app-layout>
