<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo</title>

  {{-- vite読み込み --}}
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="p-8">
  <div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold text-blue-600 mb-6">タスクリスト</h1>

    {{-- フラッシュメッセージ --}}
    @if (session('message'))
      <p class="mb-4 rounded bg-green-100 px-4 py-2 text-green-700">
        {{ session('message') }}
      </p>
    @endif

    <ul class="space-y-2">
      @forelse ($tasks as $task)
        <li class="border rounded p-4 flex justify-between items-center">
          <span>{{ $task->title }}</span>

          {{-- HTMLフォームは基本的に GET, POSTしか送れない --}}
          <form 
            action="{{ url('/tasks/' . $task->id) }}"
            method="POST"
            style="display: inline;"
            onsubmit="return confirm('本当に削除しますか？');"
          >
            {{-- Laravelに DELETEリクエストとして送るためのBlade記述 --}}
            @csrf
            @method('DELETE')

            <button
              type="submit"
              class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
            >
              削除
            </button>
          </form>
        </li>
      @empty
        <li class="rounded border border-dashed p-4 text-gray-500">
          タスクはまだありません。
        </li>
      @endforelse
    </ul>

    <form action="{{ url('/tasks') }}" method="POST" class="mt-6">
      @csrf

      <div class=" flex gap-2">
        <input
          type="text"
          name="title"
          value="{{ old('title') }}"
          class="border rounded px-3 py-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button
          type="submit"
          class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
        >
          + 追加
        </button>
      </div>

      @error('title')
        <p class="mt-2 text-sm text-red-600">エラー： {{ $message }}</p>
      @enderror
    </form>
  </div>
</body>
</html>
