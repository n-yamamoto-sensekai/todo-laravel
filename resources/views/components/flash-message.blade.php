@if (session('message'))
    <p class="mb-4 rounded bg-green-100 px-4 py-2 text-green-700">
        {{ session('message') }}
    </p>
@endif
