<?php

namespace App\Contents\Task;

use App\Models\Task;

class TaskDeleteCompletedService
{
    public function execute(): void
    {
        Task::query()
            ->where('is_done', true)
            ->delete();
    }
}
