{{--<x-app-layout>--}}
{{--    <x-slot name="header">--}}
{{--        <x-page-header title="{{ __('Dashboard') }}"/>--}}
{{--    </x-slot>--}}
{{--    <div class="py-12">--}}
{{--        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">--}}
{{--            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">--}}
{{--                <div class="p-6 text-gray-900">--}}
{{--                    {{ __("You're logged in!") }}--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</x-app-layout>--}}


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
                {{--                <template x-for="(link, index) in links">--}}
                {{--                    <div class="inline-block">--}}
                {{--                        <template x-if="index.includes('prev')">--}}
                {{--                            <x-pagination-link--}}
                {{--                                x-on:click="{swiftpayOrdersLoading, swiftpayOrders, links} = await fetchSwiftpayOrders(link, {...search, ...getDateFromAndTo()})"--}}
                {{--                                label="Prev"/>--}}
                {{--                        </template>--}}
                {{--                        <template x-if="index.includes('next')">--}}

                {{--                            <x-pagination-link--}}
                {{--                                x-on:click="{swiftpayOrdersLoading, swiftpayOrders, links} = await fetchSwiftpayOrders(link, {...search, ...getDateFromAndTo()})"--}}
                {{--                                label="Next"/>--}}
                {{--                        </template>--}}
                {{--                    </div>--}}
                {{--                </template>--}}
            </x-slot:pagination>
            <x-slot:head>
                <template x-for="field in fields">
                    <x-th text="convertToTitleCase(field)"></x-th>
                </template>
            </x-slot:head>
            <x-slot:body>
                <template x-if="!loading && orders.length > 0">
                    <template x-for="order in orders" :key="order.id">
                        <tr>
                            <x-td text="order.created_at"></x-td>
                            <x-td text="order.transaction_id"></x-td>
                            <x-td text="order.reference_number"></x-td>
                            <x-td classes="tagColor(order.order_status)"
                                  text="convertToTitleCase(order.order_status)"></x-td>
                            <x-td text="toCurrency(order.amount)"></x-td>
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
