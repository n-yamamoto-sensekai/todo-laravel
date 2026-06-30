<?php

namespace App\Contents\TaskGroup;

use App\Models\TaskGroup;
use Illuminate\Database\Eloquent\Collection;

class TaskGroupIndexRetrieveService
{
    public function execute(): Collection
    {  
        return TaskGroup::withCount('tasks')
            ->latest()
            ->get();
    }
}
