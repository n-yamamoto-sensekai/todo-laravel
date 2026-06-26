@extends('layouts.app')
@section('title', 'タスクグループ編集')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-600">タスクグループ編集</h1>

        <x-normal-link href="{{ route('task-groups.index') }}">
            グループ一覧へ戻る →
        </x-normal-link>
    </div>

    <form
        action="{{ route('task-groups.update', $taskGroup) }}"
        method="POST"
        class="space-y-4"
    >
        @csrf
        @method('PUT')
        <label class="block mb-1">
            グループ名
        </label>
        <div class="flex justify-between gap-2">
            <input
                type="text"
                name="name"
                value="{{ old('name', $taskGroup->name) }}"
                class="border rounded px-3 py-2 flex-1"
            />
            <x-primary-button>
                更新
            </x-primary-button>
        </div>
        
        <x-input-error name="name" />

    </form>
@endsection