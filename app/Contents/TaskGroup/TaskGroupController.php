<?php

namespace App\Contents\TaskGroup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Contents\TaskGroup\TaskGroupRequest;
use App\Models\TaskGroup;
use App\Contents\TaskGroup\TaskGroupShowService;
use App\Contents\TaskGroup\TaskGroupRegisterService;
use App\Contents\TaskGroup\TaskGroupUpdateService;

class TaskGroupController extends Controller
{
    private TaskGroupShowService $showService;
    private TaskGroupRegisterService $registerService;
    private TaskGroupUpdateService $updateService;

    public function __construct(
        TaskGroupShowService $showService,
        TaskGroupRegisterService $registerService,
        TaskGroupUpdateService $updateService,
    ){
        $this->showService = $showService;
        $this->registerService = $registerService;
        $this->updateService = $updateService;
    }

    public function index()
    {
        $taskGroups = TaskGroup::latest()->get();
        return view('task-groups.index', compact('taskGroups'));
    }

    public function show(TaskGroup $taskGroup, Request $request)
    {
        $data = $this->showService->execute($taskGroup, $request);
        return view('task-groups.show', $data);
    }

    public function store(TaskGroupRequest $request)
    {
        $this->registerService->execute($request);
        return redirect()->route('task-groups.index')->with('message','タスクグループを追加しました');
    }

    public function edit(TaskGroup $taskGroup)
    {
        return view('task-groups.edit', compact('taskGroup'));
    }

    public function update(TaskGroupRequest $request, TaskGroup $taskGroup)
    {
        $this->updateService->execute($taskGroup, $request);
        return redirect()->route('task-groups.index')->with('message','グループ名を更新しました');
    }

    public function destroy(TaskGroup $taskGroup)
    {
        $taskGroup->delete();
        return redirect()->route('task-groups.index')->with('message','タスクグループを削除しました');
    }
}
