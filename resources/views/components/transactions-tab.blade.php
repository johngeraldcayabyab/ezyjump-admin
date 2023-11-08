@if(auth()->user()->tenant_id === 'admin')
    <div class="mt-5 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <ul class="text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400">
            <li class="w-full">
                <a href="#"
                   class="inline-block w-full p-4 text-gray-900 bg-gray-100 rounded-l-lg focus:ring-4 focus:ring-blue-300 active focus:outline-none dark:bg-gray-700 dark:text-white"
                   aria-current="page">Swiftpay</a>
            </li>
            <li class="w-full">
                <a href="#"
                   class="inline-block w-full p-4 bg-white rounded-r-lg hover:text-gray-700 hover:bg-gray-50 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700">Gcash</a>
            </li>
        </ul>

    </div>
@endif
