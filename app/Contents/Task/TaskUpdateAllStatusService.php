<?php

namespace App\Contents\Task;

use App\Models\Task;

class TaskUpdateAllStatusService
{
    public function execute(bool $isDone): void
    {
        Task::query()->update([
            'is_done' => $isDone,
        ]);
    }
}
