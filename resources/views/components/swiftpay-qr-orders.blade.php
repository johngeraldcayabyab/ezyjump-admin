<div
    x-data="table({
            route: '{{route('gateway.swiftpay_qr_query_orders.index')}}',
            fields: [
                'id',
                'created_at',
                'transaction_id',
                'status',
                'amount'
            ],
            search: {
                field: 'transaction_id',
                label: 'Transaction ID',
                value: '',
                status: 'ALL'
            }
        })"
>
    <x-filter route="{{route('gateway.swiftpay_qr_query_orders.index')}}">
        <x-slot:searches>
            <x-filter-field field="transaction_id" label="Transaction ID"></x-filter-field>
            <x-filter-field field="id" label="ID"></x-filter-field>
        </x-slot:searches>
        <x-slot:statuses>
            <option value='CANCELLED'>Cancelled</option>
            <option value='EXECUTED'>Executed</option>
            <option value='EXPIRED'>Expired</option>
            <option value='INITIAL'>Initial</option>
            <option value='PENDING'>Pending</option>
            <option value='REJECTED'>Rejected</option>
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
                        <x-td text="order.id"></x-td>
                        <x-td text="order.created_at"></x-td>
                        <x-td text="order.transaction_id"></x-td>
                        <x-td classes="tagColor(order.status)" text="titleCase(order.status)"></x-td>
                        <x-td text="currency(order.amount)"></x-td>
                    </tr>
                </template>
            </template>
        </x-slot:body>
    </x-table>
</div>
