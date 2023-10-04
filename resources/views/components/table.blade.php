<div class="py-10">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex items-center justify-between bg-white px-4 py-3 sm:px-6">
                    <div class="flex flex-1 items-center justify-end">
                        {{$pagination}}
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="border-collapse table-auto w-full text-sm">
                        <thead>
                        <tr>
                            {{$head}}
                        </tr>
                        </thead>
                        <tbody>
                        {{$body}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
