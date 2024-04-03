<x-wallet-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Dashboard') }}"/>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="lg:columns-2 md:columns-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-500 text-lg">
                            Merchant Information
                        </h1>
                        <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <div class="text-gray-500 dark:text-gray-300 font-medium"><b>MERCHANT NAME:</b>
                                    </div>
                                    <div class="bg-slate-100 rounded p-1">{{Auth::user()->name}}</div>
                                </div>
                                <div class="col-span-2">
                                    <div class="text-gray-500 dark:text-gray-300 font-medium"><b>API KEY:</b></div>
                                    <div class="bg-slate-100 rounded p-1">{{Auth::user()->merchantKey->api_key}}</div>
                                </div>
                                <div class="col-span-2">
                                    <div class="text-gray-500 dark:text-gray-300 font-medium"><b>SECRET KEY:</b></div>
                                    <div
                                        class="bg-slate-100 rounded p-1">{{Auth::user()->merchantKey->secret_key}}</div>
                                </div>
                                <div class="col-span-2">
                                    <div class="text-gray-500 dark:text-gray-300 font-medium"><b>MERCHANT ID:</b></div>
                                    <div
                                        class="bg-slate-100 rounded p-1">{{Auth::user()->merchantKey->merchant_id}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
</x-wallet-app-layout>



