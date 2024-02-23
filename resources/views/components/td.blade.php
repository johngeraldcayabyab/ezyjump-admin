@if(!isset($classes))
    <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm" x-text="{{$text}}"></td>
@else
    <td class="px-3 py-3 border-b border-gray-200 bg-white text-sm">
        <div class="text-xs inline-flex items-center leading-sm px-3 py-1 rounded-full"
             :class="{{$classes}}"
             x-text="{{$text}}"></div>
    </td>
@endif

