<x-gateway-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Transactions') }}"/>
    </x-slot>
    <x-transactions-tab></x-transactions-tab>
    <x-swiftpay-qr-orders></x-swiftpay-qr-orders>
</x-gateway-app-layout>
