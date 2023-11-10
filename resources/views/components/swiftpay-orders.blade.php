<div
    x-cloak
    x-data="{
            loading: true,
            swiftpayOrders: [],
            links: [],
            fields: [
                'created_at',
                'transaction_id',
                'reference_number',
                'order_status',
                'amount',
                'actions'
            ],
            search: {
                field: 'transaction_id',
                label: 'Transaction Id',
                value: '',
                status: 'ALL'
            }
        }"
    x-init="({loading, swiftpayOrders, links} = await fetchSwiftpayOrders('{{route('swiftpay_query_orders.index')}}', {...search, ...getDateFromAndTo()}))"
>
    <div class="pt-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form
                @submit.prevent="{loading, swiftpayOrders, links} = await fetchSwiftpayOrders('{{route('swiftpay_query_orders.index')}}', {...search, ...getDateFromAndTo()})"
            >
                <div class="flex mb-3">
                    <label for="search-dropdown"
                           class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white"></label>
                    <button
                        id="dropdown-button"
                        data-dropdown-toggle="dropdown"
                        class="flex-shrink-0 z-10 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-900 bg-gray-100 border border-gray-300 rounded-l-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:focus:ring-gray-700 dark:text-white"
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
                            <li>
                                <button
                                    type="button"
                                    class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
                                    x-on:click="search = {field: 'gcash_reference', label: 'Gcash Reference'}"
                                >
                                    Gcash Reference
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
                    </div>
                </div>

                <div class="flex flex-col md:flex-row mb-3 items-start justify-between">

                    <div class="flex items-center mb-3 md:mb-0">
                        <div date-rangepicker class="flex items-center">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input id="date_from" type="text"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Select date start">
                            </div>
                            <span class="mx-4 text-gray-500">to</span>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input id="date_to" type="text"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="Select date end">
                            </div>
                        </div>

                        <select x-model="search.status"
                                class="ml-3 w-max md:w-auto bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <option selected value="ALL">All status</option>
                            <option value='CANCELED'>Cancelled</option>
                            <option value='EXECUTED'>Executed</option>
                            <option value='EXPIRED'>Expired</option>
                            <option value='FAILED'>Failed</option>
                            <option value='INITIAL'>Initial</option>
                            <option value='PENDING'>Pending</option>
                            <option value='REJECTED'>Rejected</option>
                            <option value='SETTLED'>Settled</option>
                            <option value='FOR_ARCHIVING'>For Archive</option>
                        </select>
                    </div>

                    <button type="submit"
                            class="w-full md:w-auto p-2.5 text-sm font-medium h-full text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
                        Filter
                    </button>

                </div>
            </form>
        </div>
    </div>

    <x-table>
        <x-slot:pagination>
            <template x-for="(link, index) in links">
                <div class="inline-block">
                    <template x-if="index.includes('prev')">
                        <x-pagination-link
                            x-on:click="{loading, swiftpayOrders, links} = await fetchSwiftpayOrders(link, {...search, ...getDateFromAndTo()})"
                            label="Prev"/>
                    </template>
                    <template x-if="index.includes('next')">

                        <x-pagination-link
                            x-on:click="{loading, swiftpayOrders, links} = await fetchSwiftpayOrders(link, {...search, ...getDateFromAndTo()})"
                            label="Next"/>
                    </template>
                </div>
            </template>
        </x-slot:pagination>
        <x-slot:head>
            <template x-for="field in fields" :key="field">
                <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 text-left"
                    x-data="{ column: convertToTitleCase(field)}"
                >
                    <div class="flex items-center w-full"
                         x-text="column">
                    </div>
                </th>
            </template>
        </x-slot:head>
        <x-slot:body>
            <template x-for="swiftpayOrder in swiftpayOrders"
                      :key="swiftpayOrder.id">
                <tr>
                    <template x-for="field in fields" :key="field">
                        <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
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
                            <span
                                x-show="field === 'actions'"
                            >
                                <button
                                    x-on:click="syncSwift('{{route('swiftpay.sync')}}', swiftpayOrder['id'])"
                                    type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                                >Sync
                                </button>
                            </span>
                        </td>
                    </template>
                </tr>
            </template>
        </x-slot:body>
    </x-table>
</div>

<x-slot:script>
    <script>
        function getDateFromAndTo() {
            const dateFrom = document.querySelector('#date_from').value.trim();
            const dateTo = document.querySelector('#date_to').value.trim();
            let dateRange = {
                dateFrom: isValidDateFormat(dateFrom) ? convertDateFormat(dateFrom) : null,
                dateTo: isValidDateFormat(dateTo) ? convertDateFormat(dateTo) : null,
            };
            if (!dateRange.dateFrom || !dateRange.dateTo) {
                dateRange = {};
            }
            return dateRange;
        }

        function syncSwift(url, id, callback = null) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    id: id
                }),
            }).then(response => response.json())
                .then(response => {
                    if (response.sync_status === 200) {
                        alert('Sync success');
                        if (callback) {
                            callback();
                        }
                    }
                });
        }

        async function fetchSwiftpayOrders(url, params = {}) {
            let queryString = null;
            if ((params.value && params.value.length) || (params.dateFrom && params.dateTo) || params.status) {
                queryString = objectToQueryString(params);
            }
            if (url.includes('cursor') && queryString) {
                url = `${url}&${queryString}`;
            } else if (!url.includes('cursor') && queryString) {
                url = `${url}?${queryString}`;
            }
            return fetch(url)
                .then(response => response.json())
                .then(response => ({
                    swiftpayOrders: response.data,
                    links: response.links,
                    loading: false,
                }));
        }



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
</x-slot:script>
