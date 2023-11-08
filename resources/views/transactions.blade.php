<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Transactions') }}"/>
    </x-slot>
    @if(auth()->user()->tenant_id === 'admin')
        <x-transactions-tab></x-transactions-tab>
        <x-swiftpay-orders></x-swiftpay-orders>
    @else
        @if(auth()->user()->channel === 'swiftpay')
            <x-swiftpay-orders></x-swiftpay-orders>
        @elseif(auth()->user()->channel === 'gcashstatic')
            <x-gcash-orders></x-gcash-orders>
        @endif
    @endif
</x-app-layout>



