<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo</title>
</head>
<body>
  <h1>タスクリスト</h1>

  {{-- フラッシュメッセージ --}}
  @if (session('message'))
    <p>{{ session('message') }}</p>
  @endif

  <ul>
    @forelse ($tasks as $task)
      <li>
        {{ $task->title }}

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

          <button type="submit">削除</button>
        </form>
      </li>
    @empty
      <li>タスクはまだありません。</li>
    @endforelse
  </ul>

  <form action="{{ url('/tasks') }}" method="POST">
    @csrf
    <input type="text" name="title" value="{{ old('title') }}">
    <button type="submit">+</button>

    @error('title')
      <p>エラー： {{ $message }}</p>
    @enderror
  </form>
</body>
</html>
