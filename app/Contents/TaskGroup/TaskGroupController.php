<?php

namespace App\Contents\TaskGroup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskGroup;

class TaskGroupController extends Controller
{
    private TaskGroupIndexRetrieveService $indexRetrieveService;
    private TaskGroupShowService $showService;
    private TaskGroupRegisterService $registerService;
    private TaskGroupUpdateService $updateService;
    private TaskGroupDestroyService $destroyService;

    public function __construct(
        TaskGroupIndexRetrieveService $indexRetrieveService,
        TaskGroupShowService $showService,
        TaskGroupRegisterService $registerService,
        TaskGroupUpdateService $updateService,
        TaskGroupDestroyService $destroyService,
    ){
        $this->indexRetrieveService = $indexRetrieveService;
        $this->showService = $showService;
        $this->registerService = $registerService;
        $this->updateService = $updateService;
        $this->destroyService = $destroyService;
    }

    public function index()
    {
        $taskGroups = $this->indexRetrieveService->execute();
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
        $this->destroyService->execute($taskGroup);
        return redirect()->route('task-groups.index')->with('message','タスクグループを削除しました');
    }
}
