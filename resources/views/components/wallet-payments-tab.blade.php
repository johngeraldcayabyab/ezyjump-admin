<div class="mt-5 max-w-7xl mx-auto sm:px-6 lg:px-8">
    <ul class="text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg shadow sm:flex dark:divide-gray-700 dark:text-gray-400">
        <li class="w-full">
            <a href="{{route('wallet.payments-1.view')}}"
               class="inline-block w-full p-4 rounded-l-lg focus:ring-4 focus:ring-blue-300 focus:outline-none {{ request()->routeIs('wallet.payments-1.view') || request()->routeIs('wallet.payments-1.view') ? 'text-gray-900 bg-gray-100  active dark:bg-gray-700 dark:text-white' : 'focus:outline-none bg-white hover:text-gray-700 hover:bg-gray-50  dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700' }}"
               aria-current="page">Channel 1</a>
        </li>
        <li class="w-full">
            <a href="{{route('wallet.payments-2.view')}}"
               class="inline-block w-full p-4 rounded-r-lg focus:ring-4 focus:ring-blue-300 focus:outline-none {{  request()->routeIs('wallet.payments-2.view') ? 'text-gray-900 bg-gray-100  active dark:bg-gray-700 dark:text-white' : 'focus:outline-none bg-white hover:text-gray-700 hover:bg-gray-50  dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700' }}">Channel 2</a>
        </li>
    </ul>
</div>
