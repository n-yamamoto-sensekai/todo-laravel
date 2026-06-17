<?php

namespace App\Contents\TaskGroup;

use App\Models\TaskGroup;
use Illuminate\Http\Request;

class TaskGroupRegisterService
{
    public function execute(Request $request): TaskGroup
    {  
        return TaskGroup::create([
            'name'=> $request->input('name'),
        ]);
    }
}
