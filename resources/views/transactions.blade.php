<x-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Transactions') }}"/>
    </x-slot>
    @if(auth()->user()->isAdmin())
        <x-transactions-tab></x-transactions-tab>
        <x-swiftpay-orders></x-swiftpay-orders>
    @else
        @if(auth()->user()->channel === 'swiftpay')
            <x-swiftpay-orders></x-swiftpay-orders>
        @elseif(auth()->user()->channel === 'swiftpay-qr')
            <x-swiftpay-qr-orders></x-swiftpay-qr-orders>
        @endif
    @endif
</x-app-layout>



