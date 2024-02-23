<x-app-layout>
    <div
        x-data="table({
            fields: [
                'created_at',
                'transaction_id',
                'reference_number',
                'order_status',
                'amount',
            ],
            route: '{{route('swiftpay_query_orders.index')}}',
        })"
    >
        <x-table>
            <x-slot:pagination>
                <x-pagination-link x-on:click="fetch(links.prev)" label="Next"/>
                <x-pagination-link x-on:click="fetch(links.next)" label="Prev"/>
            </x-slot:pagination>
            <x-slot:head>
                <template x-for="field in fields">
                    <x-th text="titleCase(field)"></x-th>
                </template>
            </x-slot:head>
            <x-slot:body>
                <template x-if="!loading && data.length > 0">
                    <template x-for="order in data" :key="order.id">
                        <tr>
                            <x-td text="order.created_at"></x-td>
                            <x-td text="order.transaction_id"></x-td>
                            <x-td text="order.reference_number"></x-td>
                            <x-td classes="tagColor(order.order_status)" text="titleCase(order.order_status)"></x-td>
                            <x-td text="currency(order.amount)"></x-td>
                        </tr>
                    </template>
                </template>
            </x-slot:body>
        </x-table>
    </div>
</x-app-layout>
<script>
    function tagColor(status) {
        let bgColor = 'bg-slate-200';
        let textColor = 'text-gray-700';
        if (status === 'CANCELED') {
            bgColor = 'bg-red-200';
            textColor = 'text-red-700';
        } else if (status === 'EXECUTED') {
            bgColor = 'bg-green-200';
            textColor = 'text-green-700';
        } else if (status === 'EXPIRED') {
            bgColor = 'bg-slate-200';
            textColor = 'text-gray-700';
        } else if (status === 'FAILED') {
            bgColor = 'bg-orange-200';
            textColor = 'text-orange-700';
        } else if (status === 'INITIAL') {
            bgColor = 'bg-blue-200';
            textColor = 'text-blue-700';
        } else if (status === 'PENDING') {
            bgColor = 'bg-orange-200';
            textColor = 'text-orange-700';
        } else if (status === 'REJECTED') {
            bgColor = 'bg-red-200';
            textColor = 'text-red-700';
        } else if (status === 'SETTLED') {
            bgColor = 'bg-green-200';
            textColor = 'text-green-700';
        } else if (status === 'FOR_ARCHIVE') {
            bgColor = 'bg-slate-200';
            textColor = 'text-gray-700';
        }
        return `${bgColor} ${textColor}`;
    }
</script>
