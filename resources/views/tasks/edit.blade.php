@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-blue-600">タスク編集</h1>

        <x-normal-link href="{{ route('tasks.index') }}">
            一覧に戻る →
        </x-normal-link>
    </div>

    <form action="{{ route('tasks.update', $task) }}" method="POST" class="mb-6">
        @csrf
        @method('PUT') {{-- LaravelにPUTリクエストとして送信 --}}

        <x-text-input name="title" :value="old('title', $task->title)" />

        <x-input-error name="title" />

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">
                    期限
                </label>
                <input
                    type="date"
                    name="due_date"
                    value="{{ old('due_date', $task->due_date) }}"
                    class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <x-input-error name="due_date"/>
            </div>

            <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700">
                    メモ
                </label>
                <textarea
                    name="memo"
                    rows="4"
                    class="mt-1 w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >{{ old('memo', $task->memo) }}</textarea>

                <x-input-error name="memo"/>
            </div>

            <div class="mt-4 w-max ml-auto">
                <x-primary-button>
                    更新
                </x-primary-button>
            </div>
    </form>
@endsection
