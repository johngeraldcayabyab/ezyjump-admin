<x-wallet-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Dashboard') }}"/>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">


{{--                    <h5--}}
{{--                        class="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">--}}
{{--                        <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"--}}
{{--                             fill="currentColor"--}}
{{--                             viewBox="0 0 20 20">--}}
{{--                            <path--}}
{{--                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>--}}
{{--                        </svg>--}}
{{--                        Order Information--}}
{{--                    </h5>--}}
{{--                    <div class="mb-6 text-sm text-gray-500 dark:text-gray-400">--}}
{{--                        <div class="grid grid-cols-2 gap-4">--}}
{{--                            <div class="col-span-2">--}}
{{--                                <div class="text-gray-500 dark:text-gray-400 font-medium"><b>Tenant ID:</b></div>--}}
{{--                                <div>123</div>--}}
{{--                            </div>--}}
{{--                            <div class="col-span-2">--}}
{{--                                <div class="text-gray-500 dark:text-gray-400 font-medium"><b>Account Name:</b></div>--}}
{{--                                <div>123</div>--}}
{{--                            </div>--}}
{{--                            <div class="col-span-2">--}}
{{--                                <div class="text-gray-500 dark:text-gray-400 font-medium"><b>Username:</b></div>--}}
{{--                                <div>123</div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}


                                        {{ __("You're logged in the wallet!") }}
                </div>
            </div>
        </div>
    </div>
</x-wallet-app-layout>



