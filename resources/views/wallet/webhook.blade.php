<x-wallet-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Payments') }}"/>
    </x-slot>
    <div
        x-data="table({
            route: '{{route('wallet.webhooks.index')}}',
            fields: [
                'created_at',
                'id',
                'entity_id',
                'retry_count',
                'event_type',
                'status',
                'actions'
            ],
            search: {
                field: 'id',
                label: 'ID',
                value: '',
                status: 'ALL'
            }
        })"
    >
        <x-filter route="{{route('wallet.webhooks.index')}}">
            <x-slot:searches>
                <x-filter-field field="id" label="ID"></x-filter-field>
                <x-filter-field field="entity_id" label="Entity ID"></x-filter-field>
            </x-slot:searches>
            <x-slot:statuses>
                <option value='SENT'>Sent</option>
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
                            <x-td text="order.id"></x-td>
                            <x-td text="order.entity_id"></x-td>
                            <x-td text="order.retry_count"></x-td>
                            <x-td text="order.event_type"></x-td>
                            <x-td classes="tagColor(order.status)" text="titleCase(order.status)"></x-td>
                            <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
                                <button
                                    x-on:click="retryWebhook('{{route('wallet.webhooks.retry')}}', order.id)"
                                    type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                                >Retry
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
                            alert(response.message);
                        }
                    });
            }
        </script>
    </x-slot:script>
</x-wallet-app-layout>
