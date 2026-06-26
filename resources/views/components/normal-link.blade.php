@props(['href'])
<a 
    href="{{ $href }}"
    class="block px-3 py-1 rounded border text-gray-600 border-gray-500 hover:bg-gray-50"
>
    {{ $slot }}
</a>
