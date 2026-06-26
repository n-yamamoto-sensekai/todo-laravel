@extends('layouts.app')
@section('title', '$taskGroup->name')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-600">{{ $taskGroup->name }}</h1>

        <x-normal-link href="{{ route('task-groups.index') }}">
            グループ一覧へ戻る →
        </x-normal-link>
    </div>

    {{-- フラッシュメッセージ --}}
    <x-flash-message />

    {{-- フィルター --}}
    <div class="mb-4 flex gap-2">
        <x-filter-link
            href="{{ route('task-groups.show', ['task_group' => $taskGroup, 'filter' => 'all']) }}" :active="$filter === 'all'"
        >
            すべて
        </x-filter-link>
        <x-filter-link
            href="{{ route('task-groups.show', ['task_group' => $taskGroup, 'filter' => 'active']) }}" :active="$filter === 'active'"
        >
            未完了
        </x-filter-link>
        <x-filter-link
            href="{{ route('task-groups.show', ['task_group' => $taskGroup, 'filter' => 'completed']) }}" :active="$filter === 'completed'"
        >
            完了済み
        </x-filter-link>
    </div>

    <ul
        id="task-list"
        class="space-y-2"
        data-current-filter="{{ $filter }}"
        data-current-task-group-id="{{ $taskGroup->id }}"
    >
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
        class="sticky bottom-0 mt-4 py-4 bg-white"
    >
        @csrf

        <div class="flex gap-2">
            <input
                type="hidden"
                name="task_group_id"
                value="{{ $taskGroup->id }}"  {{-- 所属するグループを一緒に送る --}}
            >
            <x-text-input name="title" :value="old('title')" />

            <x-primary-button>
                + 追加
            </x-primary-button>
        </div>

        <x-input-error name="title" />

    </form>

    <x-task-modal :task-groups="$taskGroups" />
@endsection
