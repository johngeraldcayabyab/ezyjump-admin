<li>
    <button
        type="button"
        class="inline-flex w-full px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white"
        x-on:click="search = {field: '{{$field}}', label: '{{$label}}'}"
    >
        {{$label}}
    </button>
</li>
