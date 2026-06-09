@props(['name', 'value' => '']) {{-- valueが渡されなかった場合がから文字にする --}}

<input
    type="text"
    name="{{ $name }}"
    value="{{ $value }}"
    class="border rounded px-3 py-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
>
