<x-app-layout>
    <div
        x-data="table({
            route: '{{route('swiftpay_query_orders.index')}}',
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
                label: 'Transaction ID',
                value: '',
                status: 'ALL'
            },
            swiftpayCallbackFields:[
                'id',
                'created_at',
                'reference_id',
                'retries',
                'status'
            ],
            swiftpayCallbacksLoading: true,
            swiftpayCallbacks: [],
            swiftpayCallbackReferenceId: ''
        })"
    >
        <x-modal name="callbacks" maxWidth="3xl" focusable>
            <x-table shadow="shadow-none">
                <x-slot:head>
{{--                    <template x-for="field in swiftpayCallbackFields">--}}
{{--                        <th class="border-b dark:border-slate-600 font-medium p-2 text-slate-400 text-left"--}}
{{--                            x-data="{ column: titleCase(field)}"--}}
{{--                        >--}}
{{--                            <div class="flex items-center w-full"--}}
{{--                                 x-text="column">--}}
{{--                            </div>--}}
{{--                        </th>--}}
{{--                    </template>--}}
                </x-slot:head>
                <x-slot:body>
{{--                    <template x-for="swiftpayCallback in swiftpayCallbacks">--}}
{{--                        <tr>--}}
{{--                            <template x-for="field in swiftpayCallbackFields">--}}
{{--                                <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">--}}
{{--                            <span--}}
{{--                                x-show="field === 'id'"--}}
{{--                                x-text="swiftpayCallback[field]">--}}
{{--                            </span>--}}
{{--                                    <span--}}
{{--                                        x-show="field.includes('created_at')"--}}
{{--                                        x-text="swiftpayCallback[field]">--}}
{{--                                                </span>--}}
{{--                                    <span--}}
{{--                                        x-show="field.includes('reference_id')"--}}
{{--                                        x-text="swiftpayCallback[field]">--}}
{{--                                                </span>--}}
{{--                                    <span--}}
{{--                                        x-show="field.includes('retries')"--}}
{{--                                        x-text="swiftpayCallback[field]">--}}
{{--                                                </span>--}}
{{--                                    <span x-show="field.includes('status')">--}}
{{--                                                    <div--}}
{{--                                                        class="text-xs inline-flex items-center leading-sm px-3 py-1 rounded-full"--}}
{{--                                                        :class="tagColorCallback(swiftpayCallback[field])"--}}
{{--                                                        x-text="titleCase(swiftpayCallback[field])">--}}
{{--                                                    </div>--}}
{{--                                                </span>--}}
{{--                                </td>--}}
{{--                            </template>--}}
{{--                        </tr>--}}
{{--                    </template>--}}
                </x-slot:body>
            </x-table>
            <div class="m-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Close') }}
                </x-secondary-button>

{{--                <x-primary-button class="ml-3"--}}
{{--                                  x-on:click="retryCallback('{{route('swiftpay.retry-callback')}}', swiftpayCallbackReferenceId);"--}}
{{--                                  x-bind:data-swiftpay-callback-reference-id="swiftpayCallbackReferenceId"--}}
{{--                >--}}
{{--                    {{ __('Retry Callback') }}--}}
{{--                </x-primary-button>--}}
            </div>
        </x-modal>

        <x-filter route="{{route('swiftpay_query_orders.index')}}">
            <x-slot:searches>
                <x-filter-field field="transaction_id" label="Transaction ID"></x-filter-field>
                <x-filter-field field="reference_number" label="Reference Number"></x-filter-field>
                <x-filter-field field="gcash_reference" label="Gcash Reference"></x-filter-field>
            </x-slot:searches>
            <x-slot:statuses>
                <option value='CANCELED'>Cancelled</option>
                <option value='EXECUTED'>Executed</option>
                <option value='EXPIRED'>Expired</option>
                <option value='FAILED'>Failed</option>
                <option value='INITIAL'>Initial</option>
                <option value='PENDING'>Pending</option>
                <option value='REJECTED'>Rejected</option>
                <option value='SETTLED'>Settled</option>
                <option value='FOR_ARCHIVING'>For Archive</option>
            </x-slot:statuses>
        </x-filter>
        <x-table>
            <x-slot:pagination>
                <x-pagination-link x-on:click="fetchData(links.prev)" label="Next"/>
                <x-pagination-link x-on:click="fetchData(links.next)" label="Prev"/>
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
                            <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
                                <button
                                    x-on:click="syncSwift('{{route('swiftpay.sync')}}', order.id)"
                                    type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                                >Sync
                                </button>
                                <button
                                    type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-teal-500 rounded border-teal-500 hover:bg-teal-600 focus:ring-4 focus:outline-none focus:ring-teal-300 dark:bg-teal-600 dark:hover:bg-teal-700 dark:focus:ring-teal-800"
                                    x-on:click="{swiftpayCallbacks, swiftpayCallbacksLoading, swiftpayCallbackReferenceId} = await fetchSwiftpayCallbacks('{{route('swiftpay-callback.index')}}', order.reference_number); $dispatch('open-modal', 'callbacks');"
                                >Callbacks
                                </button>
                            </td>
                        </tr>
                    </template>
                </template>
            </x-slot:body>
        </x-table>

        <script>
            function syncSwift(url, id, callback = null) {
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
                        if (response.sync_status === 200) {
                            alert('Sync success');
                            if (callback) {
                                callback();
                            }
                        } else {
                            alert(response.message);
                        }
                    });
            }

            async function fetchSwiftpayCallbacks(url, referenceNumber) {
                let queryString = objectToQueryString({
                    field: 'reference_id',
                    value: referenceNumber
                });
                url = `${url}?${queryString}`;
                return fetch(url)
                    .then(response => response.json())
                    .then(response => ({
                        swiftpayCallbacks: response.data,
                        swiftpayCallbacksLoading: false,
                        swiftpayCallbackReferenceId: referenceNumber,
                    }));
            }

            function tagColorCallback(status) {
                let bgColor = 'bg-slate-200';
                let textColor = 'text-gray-700';
                if (status === 'SUCCESS') {
                    bgColor = 'bg-green-200';
                    textColor = 'text-green-700';
                } else if (status === 'FAILED') {
                    bgColor = 'bg-orange-200';
                    textColor = 'text-orange-700';
                } else if (status === 'PROCESSING') {
                    bgColor = 'bg-blue-200';
                    textColor = 'text-blue-700';
                }
                return `${bgColor} ${textColor}`;
            }
        </script>
    </div>

</x-app-layout>

