@extends('layouts.app')
@section('title', 'タスクグループ編集')
@section('content')
    <div class="mb-6">
        <a
            href="{{ route('task-groups.index') }}"
            class="text-blue-600 hover:underline"
        >
            タスクグループ一覧へ戻る
        </a>
    </div>

    <h1 class="text-2xl font-bold mb-6">
        タスクグループ編集
    </h1>

    <form
        action="{{ route('task-groups.update', $taskGroup) }}"
        method="POST"
        class="space-y-4"
    >
        @csrf
        @method('PUT')
        <div>
            <label class="block mb-1 font-semibold">
                グループ名
            </label>
            <input
                type="text"
                name="name"
                value="{{ old('name', $taskGroup->name) }}"
                class="border rounded px-3 py-2 w-full"
            />
            <x-input-error name="name" />
        </div>
        <x-primary-button>
            更新
        </x-primary-button>
    </form>
@endsection