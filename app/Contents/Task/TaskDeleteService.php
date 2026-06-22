<?php

namespace App\Contents\Task;

use App\Models\Task;

class TaskDeleteService
{
    public function execute(Task $task): int
    {
        $taskId = $task->id;
        $task->delete();
        return $taskId;
    }
}
