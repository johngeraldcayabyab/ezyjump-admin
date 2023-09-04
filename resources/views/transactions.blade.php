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
                        x-data="{swiftPayOrders: [], 'isLoading': true, links: []}"
                        x-init="fetch('http://0.0.0.0/api/swiftpay_orders')
    .then(response => response.json())
    .then(response => { swiftPayOrders = response.data; isLoading = false; links = response.meta.links })"
                    >
                        <h1 x-show="isLoading">Loading...</h1>
                        <table x-show="!isLoading"  class="border-collapse table-auto w-full text-sm">
                            <thead>
                            <tr>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 text-left">ID</th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 text-left">Created At</th>
                                <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 text-left">Tenant ID</th>
                            </tr>
                            </thead>
                            <tbody>
                            <template x-for="swiftPayOrder in swiftPayOrders" :key="swiftPayOrder.id">
                                <tr>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" x-text="swiftPayOrder.id"></td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" x-text="swiftPayOrder.created_at"></td>
                                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" x-text="swiftPayOrder.tenant_id"></td>
                                </tr>
                            </template>

                            <nav>
                                <ul class="inline-flex -space-x-px text-sm">
                            <template x-for="link in links" :key="link.label">
                                        <li>
                                            <a href="#" class="" x-text="link.label"></a>
                                        </li>
                            </template>
                                </ul>
                            </nav>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
