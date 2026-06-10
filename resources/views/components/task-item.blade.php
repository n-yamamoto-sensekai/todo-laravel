@props(['task'])
<li class="border rounded p-4 flex justify-between items-center">
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
