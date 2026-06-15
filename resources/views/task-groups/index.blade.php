@extends('layouts.app')
@section('title', 'タスクグループ一覧')
@section('content')
    <h1 class="text-2xl font-bold mb-6">タスクグループ一覧</h1>

    <div class="mb-6">
        <a
            href="{{ route('tasks.index') }}"
            class="block"
        >
            全タスク一覧へ
        </a>
    </div>

    <ul class="space-y-3">
        @forelse ($taskGroups as $taskGroup)
            <li
                class="border rounded p-4 hover:bg-gray-50"
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