@props(['task'])
<li 
    id="task-item-{{ $task->id }}"  {{-- Ajaxで更新時JS側で見つけられるように --}}
    class="border rounded p-4 flex justify-between items-center"
>
    <div>
        <button
            type="button"
            id="task-title-{{ $task->id }}"
            class="js-open-task-model text-left {{ $task->is_done ? 'line-through text-gray-400' : '' }}"
            data-id="{{ $task->id }}"
            data-title="{{ $task->title }}"
            data-due-date="{{ $task->due_date }}"
            data-memo="{{ $task->memo }}"
            data-is-done="{{ $task->is_done ? 1 : 0 }}"
        >
            {{ $task->title }}
        </button>

        <span
            id="task-status-label-{{ $task->id }}"
            class="ml-2 text-sm {{ $task->is_done ? 'text-green-600' : 'text-gray-500' }}"
        >
            {{ $task->is_done ? '完了' : '未完了' }}
        </span>

        <p {{-- JS側でidで探せるよう、due_dateがない場合も空の<p>を置く --}}
            id="task-due-date-{{ $task->id }}"
            class="ml-2 text-sm text-gray-500"
        >
            @if ($task->due_date)
                期限：{{ $task->due_date }}
            @endif
        </p>

        <p 
            id="task-memo-{{ $task->id }}"
            class="mt-1 text-sm text-gray-500"
        >
            @if ($task->memo)
                メモ：{{ Str::limit($task->memo, 30) }}
            @endif
        </p>
    </div>


    <div class="flex gap-2">

        {{-- 完了フラグ --}}
        <form
            action="{{ route('tasks.toggle', $task) }}"
            method="POST"
            class="js-toggle-task-form"
        >
            @csrf
            @method('PATCH')

            <button
                type="submit"
                id="task-toggle-button-{{ $task->id }}"
                class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700"
            >
                {{ $task->is_done ? '未完了に戻す' : '完了' }}
            </button>
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
