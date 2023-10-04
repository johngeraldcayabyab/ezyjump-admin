{{--<div--}}
{{--    class="pt-10"--}}
{{--    x-data="{--}}
{{--                       total_amount_yesterday : 0,--}}
{{--                       total_amount_today : 0--}}
{{--                    }"--}}
{{--    x-init="({total_amount_yesterday, total_amount_today} = await fetchSwiftpayOrdersStatistics('{{route('swiftpay_query_orders.statistics')}}'))"--}}
{{-->--}}
{{--    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-wrap">--}}
{{--        <div--}}
{{--            class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">--}}
{{--            <div class="px-5 p-5">--}}
{{--                <div class="flex items-center justify-between">--}}
{{--                    <h1--}}
{{--                        class="font-semibold text-xl text-gray-800 leading-tight"--}}
{{--                        x-text="total_amount_today">--}}
{{--                    </h1>--}}
{{--                </div>--}}
{{--                <div class="flex items-center justify-between">--}}
{{--                    Today's total amount--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div--}}
{{--            class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 ml-5">--}}
{{--            <div class="px-5 p-5">--}}
{{--                <div class="flex items-center justify-between">--}}
{{--                    <h1--}}
{{--                        class="font-semibold text-xl text-gray-800 leading-tight"--}}
{{--                        x-text="total_amount_yesterday">--}}
{{--                    </h1>--}}
{{--                </div>--}}
{{--                <div class="flex items-center justify-between">--}}
{{--                    Yesterday's total amount--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

<script>
    function fetchSwiftpayOrdersStatistics(url) {
        return fetch(url)
            .then(response => response.json())
            .then(response => response);
    }
</script>
