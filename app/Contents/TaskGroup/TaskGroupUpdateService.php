<?php

namespace App\Contents\TaskGroup;

use App\Models\TaskGroup;
use Illuminate\Http\Request;

class TaskGroupUpdateService
{
    public function execute(TaskGroup $taskGroup, Request $request): TaskGroup
    {  
        $taskGroup->update([
            'name' => $request->input('name'),
        ]);

        return $taskGroup;
    }
}
