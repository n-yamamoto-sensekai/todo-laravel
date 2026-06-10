@extends('layouts.app')

@section('title', 'Todo')

@section('content')
    <h1 class="text-3xl font-bold text-blue-600 mb-6">タスクリスト</h1>

    {{-- フラッシュメッセージ --}}
    <x-flash-message />

    {{-- フィルター --}}
    <div class="mb-4 flex gap-2">
        <x-filter-link href="{{ route('tasks.index') }}" :active="$filter === 'all'" >
            すべて
        </x-filter-link>
        <x-filter-link href="{{ route('tasks.index', ['filter' => 'active']) }}" :active="$filter === 'active'" >
            未完了
        </x-filter-link>
        <x-filter-link href="{{ route('tasks.index', ['filter' => 'completed']) }}" :active="$filter === 'completed'" >
            完了済み
        </x-filter-link>
    </div>

    {{-- 一括操作 --}}
    <div class="mb-4 flex gap-2">
        <form action="{{ route('tasks.markAllDone') }}" method="POST">
            @csrf
            @method('PATCH')

            <x-success-button>
                すべて完了する
            </x-success-button>
        </form>
        <form action="{{ route('tasks.markAllUndone') }}" method="POST">
            @csrf
            @method('PATCH')

            <x-success-button>
                すべて未完了に戻す
            </x-success-button>
        </form>

        <form 
            action="{{ route('tasks.destroyCompleted') }}"
            method="POST"
            onsubmit="return confirm('完了済みタスクをすべて削除しますか？');"
        >
            @csrf
            @method('DELETE')
            <x-danger-button>
                完了済みタスクを削除
            </x-danger-button>
        </form>
    </div>

    {{-- タスク一覧  --}}
    <ul class="space-y-2">
    @forelse ($tasks as $task)
        <li class="border rounded p-4 flex justify-between items-center">

            <div>
                <span class="{{ $task->is_done ? 'line-through text-gray-400' : '' }}">
                    {{ $task->title }}
                </span>
                <span class="ml-2 text-sm {{ $task->is_done ? 'text-green-600' : 'text-gray-500' }}">
                    {{ $task->is_done ? '完了' : '未完了' }}
                </span>    
            </div>


            <div class="flex gap-2">

                {{-- 完了フラグ --}}
                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <x-success-button>
                        {{ $task->is_done ? '未完了に戻す' : '完了' }}
                    </x-success-button>
                </form>

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
