<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Transactions') }}"/>
    </x-slot>
    <x-transactions-tab></x-transactions-tab>
    <x-swiftpay-transactions></x-swiftpay-transactions>
</x-app-layout>



