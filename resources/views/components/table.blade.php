@props([
    'shadow' => 'shadow-sm',
])

<div class="py-5">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden {{$shadow}} sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if (isset($pagination))
                    <div class="flex items-center justify-between bg-white px-4 py-3 sm:px-6">
                        <div class="flex flex-1 items-center justify-end">
                            {{$pagination}}
                        </div>
                    </div>
                @endif
                <div class="overflow-x-auto">
                    <table class="border-collapse table-auto w-full text-sm">
                        @if (isset($head))
                            <thead>
                            <tr>
                                {{$head}}
                            </tr>
                            </thead>
                        @endif
                            @if (isset($body))
                                <tbody>
                                {{$body}}
                                </tbody>
                            @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
