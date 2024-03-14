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
            callbackFields:[
                'id',
                'created_at',
                'reference_id',
                'retries',
                'status'
            ],
            callbackLoading: true,
            callbacks: [],
            callbackReferenceId: ''
        })"
>
    <x-modal name="callbacks" maxWidth="3xl" focusable>
        <x-table shadow="shadow-none">
            <x-slot:head>
                <template x-for="field in callbackFields">
                    <x-th text="titleCase(field)"></x-th>
                </template>
            </x-slot:head>
            <x-slot:body>
                <template x-for="swiftpayCallback in callbacks">
                    <tr>
                        <x-td text="swiftpayCallback.id"></x-td>
                        <x-td text="swiftpayCallback.created_at"></x-td>
                        <x-td text="swiftpayCallback.reference_id"></x-td>
                        <x-td text="swiftpayCallback.retries"></x-td>
                        <x-td classes="tagColor(swiftpayCallback.status)"
                              text="titleCase(swiftpayCallback.status)"></x-td>
                    </tr>
                </template>
            </x-slot:body>
        </x-table>
        <div class="m-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>
            <x-primary-button class="ml-3"
                              x-on:click="retryCallback('{{route('swiftpay.retry-callback')}}', callbackReferenceId);"
                              x-bind:data-swiftpay-callback-reference-id="callbackReferenceId"
            >
                {{ __('Retry Callback') }}
            </x-primary-button>
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
                                x-on:click="sync('{{route('swiftpay.sync')}}', order.id)"
                                type="button"
                                class="px-3 py-2 text-xs font-medium text-center text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                            >Sync
                            </button>
                            <button
                                type="button"
                                class="px-3 py-2 text-xs font-medium text-center text-white bg-teal-500 rounded border-teal-500 hover:bg-teal-600 focus:ring-4 focus:outline-none focus:ring-teal-300 dark:bg-teal-600 dark:hover:bg-teal-700 dark:focus:ring-teal-800"
                                x-on:click="{callbacks, callbackLoading, callbackReferenceId} = await fetchCallbacks('{{route('swiftpay-callback.index')}}', order.reference_number); $dispatch('open-modal', 'callbacks');"
                            >Callbacks
                            </button>
                            @if(auth()->user()->type === 'admin')
                                <button
                                    x-on:click="await fetchDetails(order.id)"
                                    type="button"
                                    class="px-3 py-2 text-xs font-medium text-center text-white bg-indigo-600 rounded border-indigo-600 hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-purple-300 dark:bg-purple-600 dark:hover:bg-purple-700 dark:focus:ring-purple-800"
                                >View
                                </button>
                            @endif
                        </td>
                    </tr>
                </template>
            </template>
        </x-slot:body>
    </x-table>
</div>


<div class="text-center hidden fixed">
    <button
        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800"
        type="button" data-drawer-target="drawer-right-example" data-drawer-show="drawer-right-example"
        data-drawer-placement="right" aria-controls="drawer-right-example">
        Show right drawer
    </button>
</div>


<div id="drawer-right-example"
     class="fixed top-0 right-0 z-40 h-screen p-4 overflow-y-auto transition-transform translate-x-full bg-white w-80 dark:bg-gray-800"
     tabindex="-1" aria-labelledby="drawer-right-label">
    <h5 id="drawer-right-label"
        class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
        <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
             viewBox="0 0 20 20">
            <path
                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        Order Information
    </h5>
    <button type="button" data-drawer-hide="drawer-right-example" aria-controls="drawer-right-example"
            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 inline-flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
        </svg>
        <span class="sr-only">Close menu</span>
    </button>
    <div id="drawer-content" class="mb-6 text-sm text-gray-500 dark:text-gray-400">
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <div class="text-gray-500 dark:text-gray-400 font-medium"><b>Tenant ID:</b></div>
                <div id="tenant_id">${merchant.id}</div>
            </div>
            <div class="col-span-2">
                <div class="text-gray-500 dark:text-gray-400 font-medium"><b>Account Name:</b></div>
                <div id="account_name">${merchant.name}</div>
            </div>
        </div>
    </div>
</div>

<x-slot:script>
    <script>
        function sync(url, id, callback = null) {
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

        async function fetchCallbacks(url, referenceNumber) {
            let queryString = objectToQueryString({
                field: 'reference_id',
                value: referenceNumber
            });
            url = `${url}?${queryString}`;
            return fetch(url)
                .then(response => response.json())
                .then(response => ({
                    callbacks: response.data,
                    callbackLoading: false,
                    callbackReferenceId: referenceNumber,
                }));
        }

        function retryCallback(url, referenceNumber, callback = null) {
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    reference_id: referenceNumber,
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

        async function fetchDetails(id) {
            let url = "{{ route('swiftpay_query_orders.show', ':id') }}";
            url = url.replace(':id', id);
            fetch(url)
                .then(response => response.json())
                .then(json => {
                    const merchant = json.data.merchant;
                    document.querySelector('#tenant_id').innerHTML = merchant.id;
                    document.querySelector('#account_name').innerHTML = merchant.preferred_account;
                    document.querySelector('[data-drawer-show="drawer-right-example"]').click();
                });
        }
    </script>
</x-slot:script>
