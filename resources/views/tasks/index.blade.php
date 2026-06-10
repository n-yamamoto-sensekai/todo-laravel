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

            {{-- タスク内容 --}}
            <div>
                <button
                    type="button"
                    class="js-open-task-model text-left {{ $task->is_done ? 'line-through text-gray-400' : '' }}"
                    data-id="{{ $task->id }}"
                    data-title="{{ $task->title }}"
                    data-due-date="{{ $task->due_date }}"
                    data-memo="{{ $task->memo }}"
                >
                    {{ $task->title }}
                </button>

                <span class="ml-2 text-sm {{ $task->is_done ? 'text-green-600' : 'text-gray-500' }}">
                    {{ $task->is_done ? '完了' : '未完了' }}
                </span>

                @if ($task->due_date)
                    <p class="ml-2 text-sm text-gray-500">
                        期限：{{ $task->due_date }}
                    </p>
                @endif

                @if ($task->memo)
                    <p class="mt-1 text-sm text-gray-500">
                        メモ：{{ Str::limit($task->memo, 30) }}
                    </p>
                @endif
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

    {{-- モーダル --}}
        <div id="task-modal" class="hidden fixed inset-0 z-50 bg-black/50">
            <div id="task-modal-backdrop" class="flex min-h-screen items-center justify-center p-4">
                <div class="w-full max-w-lg rounded bg-white p-6 shadow-lg">
                    <div class="mb-4 flex items-center justify-between">
                        
                        <h2 class="text-xl font-bold">タスク詳細</h2>

                        <button
                            type="button"
                            id="js-close-task-modal"
                            class="text-gray-500 hover:text-gray-700"
                        >
                            ×
                        </button>
                    </div>

                    {{-- 更新フォーム --}}
                    <form id="modal-task-form" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                タスク名
                            </label>
                            <input
                                type="text"
                                id="modal-task-title"
                                name="title"
                                class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                            </input>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                期限
                            </label>
                            <input
                                type="date"
                                id="modal-task-due-date"
                                name="due_date"
                                class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">
                                メモ
                            </label>
                            <textarea
                                id="modal-task-memo"
                                name="memo"
                                rows="4"
                                class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            ></textarea>
                        </div>

                        <div class="flex justify-end gap-2">
                            <button
                                type="button"
                                id="js-cancel-task-modal"
                                class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300"
                            >
                                閉じる
                            </button>

                            <button
                                type="button"
                                id="js-delete-task-form-modal"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                            >
                                削除
                            </button>

                            <x-primary-button>
                                更新
                            </x-primary-button>
                        </div>
                    </form>
                    <form
                        id="modal-task-delete-form"
                        method="POST"
                        class="hidden"
                    >
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

@endsection
