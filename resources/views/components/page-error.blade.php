{{-- 例外メッセージ表示コンポーネント --}}
@props(['name'])  {{-- nameという値を外部から受け取る --}}
@if ($errors->has($name))
    <div class="mb-2 text-red-600">
        {{ $errors->first($name) }}
    </div>
@endif
