<div
    x-cloak
    x-data="{
            loading: true,
            swiftpayOrders: [],
            links: [],
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
            }
        }"
    x-init="({loading, swiftpayOrders, links} = await fetchSwiftpayOrders('{{route('swiftpay_query_orders.index')}}', {...search, ...getDateFromAndTo()}))"
>
    <div class="pt-10">
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
                    </div>
                </div>
                <div class="flex mb-3 items-center justify-between">
                    <div date-rangepicker class="flex items-center">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input
                                id="date_from"
                                type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date start"
                            >
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
                            <input
                                id="date_to"
                                type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Select date end"
                            >
                        </div>
                    </div>

                    <button type="submit"
                            class="p-2.5 text-sm font-medium h-full text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800">
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
        </x-slot:body>
    </x-table>
</div>

<script>
    function getDateFromAndTo() {
        const dateFrom = document.querySelector('#date_from').value.trim();
        const dateTo = document.querySelector('#date_to').value.trim();
        let sdasd = {
            dateFrom: isValidDateFormat(dateFrom) ? convertDateFormat(dateFrom) : null,
            dateTo: isValidDateFormat(dateTo) ? convertDateFormat(dateTo) : null,
        };
        if (!sdasd.dateFrom || !sdasd.dateTo) {
            sdasd = {};
        }
        return sdasd;
    }

    function convertDateFormat(dateString) {
        return dayjs(dateString, 'MM/DD/YYYY').format('YYYY-MM-DD');
    }

    function isValidDateFormat(dateString) {
        return dayjs(dateString, 'MM/DD/YYYY', true).isValid();
    }

    async function fetchSwiftpayOrders(url, params = {}) {
        let queryString = null;
        if ((params.value && params.value.length) || (params.dateFrom && params.dateTo)) {
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

    function convertToTitleCase(str, delimiter) {
        if (!str) {
            return '';
        }
        return str
            .toString()
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