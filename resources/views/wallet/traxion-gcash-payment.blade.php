<x-wallet-app-layout>
    <x-slot name="header">
        <x-page-header title="{{ __('Payments') }}"/>
    </x-slot>


    <div
        x-data="table({
            route: '{{route('wallet.payments.index')}}',
            fields: [
                'created_at',
                'reference_number',
                'transaction_id',
                'Gcash Ref',
                'transaction_status',
                'amount',
            ],
            search: {
                field: 'transaction_id',
                label: 'Transaction ID',
                value: '',
                status: 'ALL'
            }
        })"
    >
        <x-filter route="{{route('wallet.payments.index')}}">
            <x-slot:searches>
                <x-filter-field field="transaction_id" label="Transaction ID"></x-filter-field>
                <x-filter-field field="reference_number" label="Reference Number"></x-filter-field>
                <x-filter-field field="gcash_reference" label="Gcash Reference"></x-filter-field>
            </x-slot:searches>
            <x-slot:statuses>
                <option value='INITIAL'>Initial</option>
                <option value='SUCCESS'>Success</option>
                <option value='FAILED'>Failed</option>
                <option value='PENDING'>Pending</option>
                <option value='MANUAL_VERIFICATION'>Manual Verification</option>
            </x-slot:statuses>
        </x-filter>
        <x-table>
            <x-slot:pagination>
                <x-pagination-link x-on:click="fetchData(links.prev)" label="Next"/>
                <x-pagination-link x-on:click="fetchData(links.next)" label="Prev"/>
            </x-slot:pagination>
            <x-slot:head>
                <template x-for="field in fields">
                    <x-th text="titleCase(field)"></x-th>
                </template>
            </x-slot:head>
            <x-slot:body>
                <template x-if="!loading && data.length > 0">
                    <template x-for="order in data" :key="order.id">
                        <tr>
                            <x-td text="order.created_at"></x-td>
                            <x-td text="order.reference_number"></x-td>
                            <x-td text="order.transaction_id"></x-td>
                            <x-td text="order.third_party_reference_number"></x-td>
                            <x-td classes="tagColor(order.output.transaction_status)"
                                  text="titleCase(order.output.transaction_status)"></x-td>
                            <x-td text="currency(order.output.amount)"></x-td>
                        </tr>
                    </template>
                </template>
            </x-slot:body>
        </x-table>
    </div>

</x-wallet-app-layout>