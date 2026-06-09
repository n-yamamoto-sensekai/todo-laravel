{{-- エラー表示コンポーネント --}}
@props(['name'])  {{-- nameという値を外部から受け取る --}}
@error($name)
    <p class="mt-2 text-sm text-red-600">エラー：{{ $message }}</p>
@enderror
