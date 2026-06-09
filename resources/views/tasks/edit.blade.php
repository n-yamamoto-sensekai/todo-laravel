@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
        <h1 class="text-3xl font-bold text-blue-600 mb-6">タスク編集</h1>

        <form action="{{ route('tasks.update', $task) }}" method="POST" class="mb-6">
            @csrf
            @method('PUT') {{-- LaravelにPUTリクエストとして送信 --}}

            <div class="flex gap-2">
                <input
                    type="text"
                    name="title"
                    value="{{ old('title', $task->title) }}" {{-- old('title') があればそれを表示、なければ $task->title を表示 --}}
                    class="border rounded px-3 py-2 flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >

                <button
                    type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    更新
                </button>
            </div>

            @error('title')
                <p class="mt-2 text-sm text-red-600">エラー： {{ $message }}</p>
            @enderror
        </form>

        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline">
            一覧に戻る
        </a>
    </div>
@endsection
