<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transactions') }}
        </h2>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div
                        x-cloak
                        x-data="{swiftpayOrders: [], meta : {}, fields:[
                        'created_at',
                        'transaction_id',
                        'reference_number',
                        'institution_code',
                        'order_status',
                        'amount',
{{--                            'id',--}}
{{--                            'tenant_id',--}}
{{--                            'updated_at',--}}
{{--                            'version',--}}
{{--                            'callback_url',--}}
{{--                            'address1',--}}
{{--                            'address2',--}}
{{--                            'city',--}}
{{--                            'country',--}}
{{--                            'customer_name',--}}
{{--                            'email',--}}
{{--                            'phone',--}}
{{--                            'postcode',--}}
{{--                            'state',--}}
{{--                            'generate_customer_redirect_url',--}}
{{--                            'generate_customer_redirect_url_flag',--}}


{{--                            'net_amount',--}}
{{--                            'transaction_fee',--}}
{{--                            'vat',--}}
{{--                            'payment_id',--}}
{{--                            'signature',--}}
{{--                            'transaction_id'--}}
                        ]}"
                        x-init="fetch('{{route('swiftpay_orders.index')}}')
    .then(response => response.json())
    .then(response => { swiftpayOrders = response.data; meta = response.meta })"
                    >
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
                                        <template x-for="link in meta.links" :key="link.label">
                                            <div>
                                                <template x-if="link.label.includes('Previous')">
                                                    <a href="#"
                                                       class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                                       x-on:click="fetch(link.url)
    .then(response => response.json())
    .then(response => { swiftpayOrders = response.data; meta = response.meta })"
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
                                                       x-text="link.label"></a>
                                                </template>


                                                <template
                                                    x-if="!link.label.includes('Previous') && !link.label.includes('Next') && !link.active">
                                                    <a href="#"
                                                       class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                                       x-text="link.label"
                                                       x-on:click="fetch(link.url)
    .then(response => response.json())
    .then(response => { swiftpayOrders = response.data; meta = response.meta })"
                                                    ></a>
                                                </template>

                                                <template x-if="link.label.includes('Next')">
                                                    <a href="#"
                                                       class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                                                       x-on:click="fetch(link.url)
    .then(response => response.json())
    .then(response => { swiftpayOrders = response.data; meta = response.meta })"
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
                                        <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 text-left"
                                            x-html="snakeCaseToTitleCase(field)"></th>
                                    </template>
                                </tr>
                                </thead>
                                <tbody>
                                <template x-for="swiftpayOrder in swiftpayOrders"
                                          :key="swiftpayOrder.id">
                                    <tr>
                                        <template x-for="field in fields" :key="field">
                                            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                                <template x-if="field.includes('order_status')">
                                                    <span x-html="statuses(swiftpayOrder[field])"></span>
                                                </template>
                                                <template x-if="!field.includes('order_status')">
                                                    <span x-text="swiftpayOrder[field]"></span>
                                                </template>
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
    function snakeCaseToTitleCase(str) {
        return str
            .split('_')
            .map(word => word.charAt(0).toUpperCase() + word.slice(1))
            .join(' ');
    }

    function toTitleCase(str) {
        return str.replace(/\w\S*/g, function (txt) {
            return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
        });
    }

    function statuses(status) {
        let bgColor = 'white';
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
        return (`<div class="text-xs inline-flex items-center leading-sm px-3 py-1 ${bgColor} ${textColor} rounded-full">${toTitleCase(status)}</div>`);
    }
</script>
