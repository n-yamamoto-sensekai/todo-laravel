@extends('layouts.app')
@section('title', '$taskGroup->name')
@section('content')
    <div class="mb-6">
        <a
            href="{{ route('task-groups.index') }}"
            class="text-blue-600"
        >
            タスクグループ一覧へ戻る
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6">
        {{ $taskGroup->name }} のタスク
    </h1>

    {{-- フラッシュメッセージ --}}
    <x-flash-message />

    {{-- フィルター --}}
    <div class="mb-4 flex gap-2">
        <x-filter-link
            href="{{ route('task-groups.show', ['taskGroup' => $taskGroup, 'filter' => 'all']) }}" :active="$filter === 'all'"
        >
            すべて
        </x-filter-link>
        <x-filter-link
            href="{{ route('task-groups.show', ['taskGroup' => $taskGroup, 'filter' => 'active']) }}" :active="$filter === 'active'"
        >
            未完了
        </x-filter-link>
        <x-filter-link
            href="{{ route('task-groups.show', ['taskGroup' => $taskGroup, 'filter' => 'completed']) }}" :active="$filter === 'completed'"
        >
            完了済み
        </x-filter-link>
    </div>

    <ul class="space-y-3">
        @forelse ($tasks as $task)
            <x-task-item :task="$task" />
        @empty
            <li
                class="border border-dashed rounded p-4 text-gray-500"
            >
                このグループにはタスクがまだありません。
            </li>
        @endforelse
    </ul>

    {{-- 新規タスクフォーム --}}
    <form
        action="{{ route('tasks.store') }}"
        method="POST"
        class="mt-6 flex gap-2"
    >
        @csrf
        <input
            type="hidden"
            name="task_group_id"
            value="{{ $taskGroup->id }}"  {{-- 所属するグループを一緒に送る --}}
        >
        <x-text-input name="title" :value="old('title')" />

        <x-primary-button>
            + 追加
        </x-primary-button>

    </form>
    <x-input-error name="title" />

    <x-task-modal />
@endsection
