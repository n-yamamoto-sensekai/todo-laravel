@extends('layouts.app')

@section('title', 'Todo')

@section('content')
    <h1 class="text-3xl font-bold text-blue-600 mb-6">タスクリスト</h1>

    {{-- フラッシュメッセージ --}}
    <x-flash-message />

    {{-- タスク一覧  --}}
    <ul class="space-y-2">
    @forelse ($tasks as $task)
        <li class="border rounded p-4 flex justify-between items-center">
            <span>{{ $task->title }}</span>

            <div class="flex gap-2">
                <a 
                    href="{{ route('tasks.edit', $task) }}"
                    class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700"
                >
                    編集
                </a>

                {{-- HTMLフォームは基本的に GET, POSTしか送れない --}}
                <form 
                    action="{{ route('tasks.destroy', $task) }}"
                    method="POST"
                    style="display: inline;"
                    onsubmit="return confirm('本当に削除しますか？');"
                >
                    {{-- Laravelに DELETEリクエストとして送るためのBlade記述 --}}
                    @csrf
                    @method('DELETE')
                    <x-danger-button>
                        削除
                    </x-danger-button>
                </form>
            </div>
        </li>
    @empty
        <li class="rounded border border-dashed p-4 text-gray-500">
        タスクはまだありません。
        </li>
    @endforelse
    </ul>

    {{-- 新規タスクフォーム --}}
    <form action="{{ route('tasks.store') }}" method="POST" class="mt-6">
    @csrf

    <div class=" flex gap-2">
        {{-- `:value` と:をつけることでこの属性の中身はただの文字列ではなく、PHPとして実行するという意味になる` --}}
        <x-text-input name="title" :value="old('title')" />

        <x-primary-button>
            + 追加
        </x-primary-button>

    </div>

    <x-input-error name="title" />

    </form>
@endsection
