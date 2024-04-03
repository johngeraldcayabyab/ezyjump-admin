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
                                    <div class="bg-slate-100 rounded p-2">{{Auth::user()->name}}</div>
                                </div>
                                <div class="col-span-2">
                                    <div class="text-gray-500 dark:text-gray-300 font-medium"><b>API KEY:</b></div>
                                    <div class="bg-slate-100 rounded p-2 relative">
                                        {{Auth::user()->merchantKey->api_key}}
                                        <button class="absolute top-0 right-0 z-10 m-1" onclick="copyToClipboard('{{Auth::user()->merchantKey->api_key}}')">
                                            <svg class="w-6 h-6 text-gray-400 dark:text-white" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                 viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                                      d="M14 4v3a1 1 0 0 1-1 1h-3m4 10v1a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2m11-3v10a1 1 0 0 1-1 1h-7a1 1 0 0 1-1-1V7.87a1 1 0 0 1 .24-.65l2.46-2.87a1 1 0 0 1 .76-.35H18a1 1 0 0 1 1 1Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-span-2">
                                    <div class="text-gray-500 dark:text-gray-300 font-medium"><b>SECRET KEY:</b></div>
                                    <div class="bg-slate-100 rounded p-2 relative">
                                        {{Auth::user()->merchantKey->secret_key}}
                                        <button class="absolute top-0 right-0 z-10 m-1" onclick="copyToClipboard('{{Auth::user()->merchantKey->secret_key}}')">
                                            <svg class="w-6 h-6 text-gray-400 dark:text-white" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                 viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                                      d="M14 4v3a1 1 0 0 1-1 1h-3m4 10v1a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2m11-3v10a1 1 0 0 1-1 1h-7a1 1 0 0 1-1-1V7.87a1 1 0 0 1 .24-.65l2.46-2.87a1 1 0 0 1 .76-.35H18a1 1 0 0 1 1 1Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-span-2">
                                    <div class="text-gray-500 dark:text-gray-300 font-medium"><b>MERCHANT ID:</b></div>
                                    <div class="bg-slate-100 rounded p-2 relative">
                                        {{Auth::user()->merchantKey->merchant_id}}
                                        <button class="absolute top-0 right-0 z-10 m-1" onclick="copyToClipboard('{{Auth::user()->merchantKey->merchant_id}}')">
                                            <svg class="w-6 h-6 text-gray-400 dark:text-white" aria-hidden="true"
                                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                                 viewBox="0 0 24 24">
                                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2"
                                                      d="M14 4v3a1 1 0 0 1-1 1h-3m4 10v1a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1h2m11-3v10a1 1 0 0 1-1 1h-7a1 1 0 0 1-1-1V7.87a1 1 0 0 1 .24-.65l2.46-2.87a1 1 0 0 1 .76-.35H18a1 1 0 0 1 1 1Z"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <x-slot:script>
        <script>
            function copyToClipboard(text) {
                const el = document.createElement('textarea');
                el.value = text;
                document.body.appendChild(el);
                el.select();
                document.execCommand('copy');
                document.body.removeChild(el);
                alert('Copied to clipboard');
            }
        </script>
    </x-slot:script>
</x-wallet-app-layout>



