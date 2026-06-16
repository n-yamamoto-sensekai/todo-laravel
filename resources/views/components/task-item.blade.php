@props(['task'])

{{-- 期限の表示分け --}}
@php
    $dueDateText = null;
    $dueDateClass = 'text-gray-500';

    if ($task->due_date) {
        if ($task->due_date->isToday()) {
            $dueDateText = '今日';
        } elseif ($task->due_date->isYesterday()) {
            $dueDateText = '昨日';
        } elseif ($task->due_date->isTomorrow()) {
            $dueDateText = '明日';
        } else {
            $dueDateText = $task->due_date->format('Y-m-d');
        }

        $isOverdue = $task->due_date->lt(today());  // lessThan()の省略形 返り値はboolean

        if ($isOverdue && ! $task->is_done) {
            $dueDateClass = 'text-red-600';
        }
    }
@endphp

<li 
    id="task-item-{{ $task->id }}"  {{-- Ajaxで更新時JS側で見つけられるように --}}
    class="border rounded size-full p-4 flex justify-between items-center gap-3"
>
    <div class="flex-1">
        <button
            type="button"
            id="task-title-{{ $task->id }}"
            class="block js-open-task-modal text-left {{ $task->is_done ? 'line-through text-gray-400' : '' }}"
            data-id="{{ $task->id }}"
            data-title="{{ $task->title }}"
            data-due-date="{{ $task->due_date?->format('Y-m-d') }}"
            data-memo="{{ $task->memo }}"
            data-is-done="{{ $task->is_done ? 1 : 0 }}"
            data-task-group-id="{{ $task->task_group_id }}"
        >
            {{ $task->title }}
        </button>

        <div class="mt-1 flex">
            <p
                id="task-group-name-{{ $task->id }}"
                class="text-sm text-gray-500 {{ $task->taskGroup ? 'mr-3' : null }}"
            >
                @if ($task->taskGroup)
                    グループ：{{ $task->taskGroup->name }}
                @endif
            </p>
            <span
                id="task-status-label-{{ $task->id }}"
                class="rounded-2xl px-1.5 text-sm {{ $task->is_done ? 'text-green-600 bg-green-100' : 'text-gray-500 bg-gray-200' }}"
            >
                {{ $task->is_done ? '完了' : '未完了' }}
            </span>
        </div>

        <div class="mt-1 flex items-center">
            <p {{-- JS側でidで探せるよう、due_dateがない場合も空の<p>を置く --}}
                id="task-due-date-{{ $task->id }}"
                class="text-sm {{ $dueDateClass }} {{ $task->due_date ? 'mr-3' : null }}"
            >
                @if ($dueDateText)
                    期限：{{ $dueDateText }}
                @endif
            </p>

            <p 
                id="task-memo-{{ $task->id }}"
                class="text-sm text-gray-500"
            >
                @if ($task->memo)
                    メモ：{{ Str::limit($task->memo, 30) }}
                @endif
            </p>
        </div>
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
            class="px-3 py-1 bg-gray-600 text-white rounded hover:bg-gray-700 hidden"
        >
            編集
        </a>

        {{-- HTMLフォームは基本的に GET, POSTしか送れない --}}
        <form 
            action="{{ route('tasks.destroy', $task) }}"
            method="POST"
            style="display: none;"
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
