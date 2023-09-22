<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>
    <div
        x-cloak
        x-data="{
            loading: true,
            swiftpayOrders: [],
            meta: {
                links: [],
                from: 0,
                to: 0,
                total: 0
            },
            fields: [
                'id',
                'created_at',
                'transaction_id',
                'reference_number',
                'order_status',
                'amount'
            ],
            search: {
                field: 'transaction_id',
                label: 'Transaction Id',
                value: ''
            },
            statistics: {
                total_amount_yesterday : 0,
                total_amount_today : 0
            }
        }"
        x-init="({loading, swiftpayOrders, meta} = await fetchSwiftpayOrders('{{route('swiftpay_query_orders.index')}}', search))"
    >
{{--        <div--}}
{{--            class="pt-10"--}}
{{--            x-data="{--}}
{{--               total_amount_yesterday : 0,--}}
{{--               total_amount_today : 0--}}
{{--            }"--}}
{{--            x-init="({total_amount_yesterday, total_amount_today} = await fetchSwiftpayOrdersStatistics('{{route('swiftpay_query_orders.statistics')}}'))"--}}
{{--        >--}}
{{--            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-wrap">--}}
{{--                <div--}}
{{--                    class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">--}}
{{--                    <div class="px-5 p-5">--}}
{{--                        <div class="flex items-center justify-between">--}}
{{--                            <h1--}}
{{--                                class="font-semibold text-xl text-gray-800 leading-tight"--}}
{{--                                x-text="total_amount_today">--}}
{{--                            </h1>--}}
{{--                        </div>--}}
{{--                        <div class="flex items-center justify-between">--}}
{{--                            Today's total amount--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div--}}
{{--                    class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 ml-5">--}}
{{--                    <div class="px-5 p-5">--}}
{{--                        <div class="flex items-center justify-between">--}}
{{--                            <h1--}}
{{--                                class="font-semibold text-xl text-gray-800 leading-tight"--}}
{{--                                x-text="total_amount_yesterday">--}}
{{--                            </h1>--}}
{{--                        </div>--}}
{{--                        <div class="flex items-center justify-between">--}}
{{--                            Yesterday's total amount--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}


        <div class="pt-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <form
                    @submit.prevent="{loading, swiftpayOrders, meta} = await fetchSwiftpayOrders('{{route('swiftpay_query_orders.index')}}', search)">
                    <div class="flex">
                        <label for="search-dropdown"
                               class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Your
                            Email</label>
                        <button
                            id="dropdown-button"
                            data-dropdown-toggle="dropdown"
                            class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white dark:border-gray-600"
                            type="button"
                            x-html="search.label"
                        >
                        </button>
                        <div id="dropdown"
                             class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdown-button">
                                <li>
                                    <button
                                        type="button"
                                        class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        x-on:click="search = {field: 'transaction_id', label: 'Transaction Id'}"
                                    >
                                        Transaction Id
                                    </button>
                                </li>
                                <li>
                                    <button
                                        type="button"
                                        class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                        x-on:click="search = {field: 'reference_number', label: 'Reference Number'}"
                                    >
                                        Reference Number
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="relative w-full">
                            <input
                                type="search"
                                id="search-dropdown"
                                class="block p-2.5 w-full z-20 text-sm text-gray-900 bg-gray-50 rounded-r-lg border-l-gray-50 border-l-2 border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-l-gray-700  dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:border-blue-500"
                                placeholder="Search Transaction id, Reference number..."
                                x-model="search.value"
                            >
                            <button type="submit"
                                    class="absolute top-0 right-0 p-2.5 text-sm font-medium h-full text-white bg-indigo-600 rounded-r-lg border bg-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                     viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                                <span class="sr-only">Search</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="py-10">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">

                        <div
                            class="flex items-center justify-between bg-white px-4 py-3 sm:px-6">
                            <div class="flex flex-1 justify-between sm:hidden">
                                <a href="#"
                                   class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                                <a href="#"
                                   class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                            </div>
                            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Showing
                                        <span class="font-medium" x-text="meta.from"></span>
                                        to
                                        <span class="font-medium" x-text="meta.to">10</span>
                                        of
                                        <span class="font-medium" x-text="meta.total"></span>
                                        results
                                    </p>
                                </div>
                                <div>
                                    <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm"
                                         aria-label="Pagination">
                                        <template x-for="link in meta.links"
                                                  :key="link.label === '...' ? Math.random() : link.label;">
                                            <div>
                                                <template x-if="link.label.includes('Previous')">
                                                    <a href="#"
                                                       class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                                       x-on:click="{loading, swiftpayOrders, meta} = await fetchSwiftpayOrders(link.url, search)"
                                                    >
                                                        <span class="sr-only">Previous</span>
                                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                             aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                  d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                    </a>
                                                </template>

                                                <template
                                                    x-if="!link.label.includes('Previous') && !link.label.includes('Next') && link.active">
                                                    <a href="#" aria-current="page"
                                                       class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                                       x-text="link.label"
                                                    ></a>
                                                </template>

                                                <template
                                                    x-if="!link.label.includes('Previous') && !link.label.includes('Next') && !link.active">
                                                    <a href="#"
                                                       class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                                       x-text="link.label"
                                                       x-on:click="{loading, swiftpayOrders, meta} = await fetchSwiftpayOrders(link.url, search)"
                                                    ></a>
                                                </template>

                                                <template x-if="link.label.includes('Next')">
                                                    <a href="#"
                                                       class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                                       x-on:click="{loading, swiftpayOrders, meta} = await fetchSwiftpayOrders(link.url, search)"
                                                    >
                                                        <span class="sr-only">Next</span>
                                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                                             aria-hidden="true">
                                                            <path fill-rule="evenodd"
                                                                  d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                    </a>
                                                </template>
                                            </div>
                                        </template>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="border-collapse table-auto w-full text-sm">
                                <thead>
                                <tr>
                                    <template x-for="field in fields" :key="field">
                                        <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 text-left"
                                            x-data="{ column: convertToTitleCase(field)}"
                                        >
                                            {{--                                            <div class="flex items-center w-full"--}}
                                            {{--                                                 x-html="`${column} <a href='#'><svg class='w-3 h-3 ml-1.5' aria-hidden='true' xmlns='http://www.w3.org/2000/svg' fill='currentColor' viewBox='0 0 24 24'><path d='M8.574 11.024h6.852a2.075 2.075 0 0 0 1.847-1.086 1.9 1.9 0 0 0-.11-1.986L13.736 2.9a2.122 2.122 0 0 0-3.472 0L6.837 7.952a1.9 1.9 0 0 0-.11 1.986 2.074 2.074 0 0 0 1.847 1.086Zm6.852 1.952H8.574a2.072 2.072 0 0 0-1.847 1.087 1.9 1.9 0 0 0 .11 1.985l3.426 5.05a2.123 2.123 0 0 0 3.472 0l3.427-5.05a1.9 1.9 0 0 0 .11-1.985 2.074 2.074 0 0 0-1.846-1.087Z'/></svg></a>`">--}}
                                            {{--                                            </div>--}}
                                            <div class="flex items-center w-full"
                                                 x-text="column">
                                            </div>
                                        </th>
                                    </template>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="swiftpayOrder in swiftpayOrders"
                                          :key="swiftpayOrder.id">
                                    <tr>
                                        <template x-for="field in fields" :key="field">
                                            <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
                                                <span
                                                    x-show="field === 'id'"
                                                    x-text="swiftpayOrder[field]">
                                                </span>
                                                <span
                                                    x-show="field.includes('created_at')"
                                                    x-text="swiftpayOrder[field]">
                                                </span>
                                                <span
                                                    x-show="field.includes('transaction_id')"
                                                    x-text="swiftpayOrder[field]">
                                                </span>
                                                <span
                                                    x-show="field.includes('reference_number')"
                                                    x-text="swiftpayOrder[field]">
                                                </span>
                                                <span x-show="field.includes('order_status')">
                                                    <div
                                                        class="text-xs inline-flex items-center leading-sm px-3 py-1 rounded-full"
                                                        :class="tagColor(swiftpayOrder[field])"
                                                        x-text="convertToTitleCase(swiftpayOrder[field])">
                                                    </div>
                                                </span>
                                                <span
                                                    x-show="field.includes('amount')"
                                                    x-text="toCurrency(swiftpayOrder[field])"
                                                ></span>
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


<script>
    async function fetchSwiftpayOrders(url, params = {}) {
        let queryString = '';
        if (params.value && params.value.length) {
            queryString = objectToQueryString(params);
            if (url.includes('page')) {
                url = `${url}&${queryString}`;
            } else {
                url = `${url}?${queryString}`;
            }
        }
        return fetch(url)
            .then(response => response.json())
            .then(response => ({
                swiftpayOrders: response.data,
                meta: response.meta,
                loading: false,
            }));
    }

    function fetchSwiftpayOrdersStatistics(url) {
        return fetch(url)
            .then(response => response.json())
            .then(response => response);
    }

    function convertToTitleCase(str, delimiter) {
        if (!str) {
            return '';
        }
        return str
            .replace(/_/g, ' ') // Replace underscores with spaces
            .split(delimiter)
            .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
            .join(' ');
    }

    function tagColor(status) {
        let bgColor = 'bg-white';
        let textColor = 'text-gray-700';
        if (status === 'CANCELED') {
            bgColor = 'bg-red-200';
            textColor = 'text-red-700';
        } else if (status === 'EXECUTED') {
            bgColor = 'bg-green-200';
            textColor = 'text-green-700';
        } else if (status === 'EXPIRED') {
            bgColor = 'bg-white';
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
        }
        return `${bgColor} ${textColor}`;
    }

    function objectToQueryString(obj) {
        const params = new URLSearchParams();
        for (const key in obj) {
            if (obj.hasOwnProperty(key)) {
                params.append(key, obj[key]);
            }
        }
        return params.toString();
    }

    function toCurrency(num) {
        let money = (num ? num : 0).toLocaleString('en-US', {maximumFractionDigits: 2});
        return `₱${money}`;
    }
</script>
