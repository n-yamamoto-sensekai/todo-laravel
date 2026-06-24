<?php

namespace App\Contents\Task;

use App\Exceptions\TodoException;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskUpdateService
{
    public function execute(Task $task, Request $request): Task
    {
        if ($task->is_done) {
            throw new TodoException(
                'TASK_ALREADY_COMPLETED',
                '完了済みタスクは編集できません。'
            );
        }

        $task->update([
            'title' => $request->input('title'),
            'due_date' => $request->input('due_date'),
            'memo' => $request->input('memo'),
            'task_group_id' => $request->input('task_group_id'),
        ]);

        return $task->load('taskGroup');  // 最新のリレーションを読み込み
    }
}
