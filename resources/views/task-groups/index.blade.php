@extends('layouts.app')
@section('title', 'タスクグループ一覧')
@section('content')
    <h1 class="text-2xl font-bold mb-6">タスクグループ一覧</h1>

    <x-page-error name="todo" />

    <div class="mb-6">
        <a
            href="{{ route('tasks.index') }}"
            class="block"
        >
            全タスク一覧へ
        </a>
    </div>

    <x-flash-message />

    {{-- 新規グループフォーム --}}
    <form
        action="{{ route('task-groups.store') }}"
        method="POST"
        class="flex gap-2"
    >
        @csrf
        <x-text-input name="name" :value="old('name')" />
        <x-primary-button>
            追加
        </x-primary-button>
    </form>

    <x-input-error name="name" />

    <ul class="mt-6 grid grid-cols-3 gap-3">
        @forelse ($taskGroups as $taskGroup)
            <li
                class="border rounded p-4 grid-item hover:bg-gray-50"
            >
                <a
                    href="{{ route('task-groups.show', $taskGroup) }}"
                    class="block"
                >
                    <div class="font-semibold">
                        {{ $taskGroup->name }}
                    </div>
                    <div class="text-sm text-gray-500">
                        {{-- task_count があればそれを使う。なければ tasks の Collection を数える。 --}}
                        タスク数：{{ $taskGroup->task_count ?? $taskGroup->tasks->count() }}
                    </div>
                </a>
                <div class="mt-3 flex justify-end items-center gap-2">
                    <a
                        href="{{ route('task-groups.edit', $taskGroup) }}"
                        class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700"
                    >
                        編集
                    </a>
                    <form 
                        action="{{ route('task-groups.destroy', $taskGroup) }}"
                        method="POST"
                        onsubmit="return confirm('このグループを削除しますか?');"
                    >
                        @csrf
                        @method('DELETE')
                        <x-danger-button>
                            削除
                        </x-danger-button>
                    </form>
                </div>
            </li>
        @empty
            <li
                class="border border-dashed rounded p-4 text-gray-500"
            >
                タスクグループはありません。
            </li>
        @endforelse
    </ul>
@endsection