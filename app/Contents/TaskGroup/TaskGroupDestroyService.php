<?php

namespace App\Contents\TaskGroup;

use App\Models\TaskGroup;
use App\Exceptions\TodoException;

class TaskGroupDestroyService
{
    public function execute(TaskGroup $taskGroup): void
    {
        if ($taskGroup->tasks()->exists()) {
            throw new TodoException('TE00002');
        }

        $taskGroup->delete();
    }
}
