@extends('layouts.app')

@section('title', 'Todo')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-600">タスクリスト</h1>

        <x-normal-link href="{{ route('task-groups.index') }}">
            グループ一覧へ →
        </x-normal-link>
    </div>

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
    <ul
        id="task-list"
        class="space-y-2"
        data-current-filter="{{ $filter }}"
        data-current-task-group-id=""
    >
    @forelse ($tasks as $task)
        
        {{-- タスク内容 --}}
        <x-task-item :task="$task"/>

    @empty
        <li class="rounded border border-dashed p-4 text-gray-500">
        タスクはまだありません。
        </li>
    @endforelse
    </ul>

    {{-- 新規タスクフォーム --}}
    <form
        action="{{ route('tasks.store') }}"
        method="POST"
        class="sticky bottom-0 mt-4 py-4 bg-white"
    >
        @csrf

        <div class="flex gap-2">
            {{-- `:value` と:をつけることでこの属性の中身はただの文字列ではなく、PHPとして実行するという意味になる` --}}
            <x-text-input name="title" :value="old('title')" />

            <x-primary-button>
                + 追加
            </x-primary-button>
        </div>

        <x-input-error name="title" />

    </form>

    {{-- モーダル --}}
    <x-task-modal :task-groups="$taskGroups"/>
@endsection
