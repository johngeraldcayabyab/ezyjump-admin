<x-gateway-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Dashboard') }}"/>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in the gateway!") }}
                </div>
            </div>
        </div>
    </div>
</x-gateway-app-layout>
