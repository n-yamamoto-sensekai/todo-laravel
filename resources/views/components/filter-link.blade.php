@props(['href', 'active' => false])

<a 
    href="{{ $href }}"
    class=" px-3 py-1 rounded {{ $active ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} "
>
    {{ $slot }}
</a>
