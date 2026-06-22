<?php

namespace App\Contents\Task;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskRegisterService
{
    public function execute(Request $request): Task
    {
        return Task::create([
            'title' => $request->input('title'),
            'task_group_id' => $request->input('task_group_id'),
        ]);
    }
}
