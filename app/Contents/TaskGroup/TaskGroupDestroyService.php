<?php

namespace App\Contents\TaskGroup;

use App\Models\TaskGroup;

class TaskGroupDestroyService
{
    public function execute(TaskGroup $taskGroup): void
    {  
        $taskGroup->delete();
    }
}
