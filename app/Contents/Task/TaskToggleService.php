<?php

namespace App\Contents\Task;

use App\Models\Task;

class TaskToggleService
{
    public function execute(Task $task): Task
    {
        $task->update([
            'is_done' => ! $task->is_done,
        ]);

        return $task;
    }
}
