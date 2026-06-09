@extends('layouts.app')

@section('title', 'タスク編集')

@section('content')
        <h1 class="text-3xl font-bold text-blue-600 mb-6">タスク編集</h1>

        <form action="{{ route('tasks.update', $task) }}" method="POST" class="mb-6">
            @csrf
            @method('PUT') {{-- LaravelにPUTリクエストとして送信 --}}

            <div class="flex gap-2">

                <x-text-input name="title" :value="old('title', $task->title)" />

                <x-primary-button>
                    更新
                </x-primary-button>

            </div>

            <x-input-error name="title" />

        </form>

        <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:underline">
            一覧に戻る
        </a>
    </div>
@endsection
