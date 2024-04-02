<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Transactions') }}"/>
    </x-slot>
    <x-transactions-tab></x-transactions-tab>
    <x-swiftpay-orders></x-swiftpay-orders>
</x-app-layout>



