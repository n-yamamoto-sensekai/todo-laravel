<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskGroupRequest;
use App\Models\TaskGroup;
use App\Contents\TaskGroup\TaskGroupShowService;

class TaskGroupController extends Controller
{
    private TaskGroupShowService $showService;

    public function __construct(TaskGroupShowService $showService)
    {
        $this->showService = $showService;
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
        TaskGroup::create([
            'name'=> $request->input('name'),
        ]);

        return redirect()->route('task-groups.index')->with('message','タスクグループを追加しました');
    }

    public function edit(TaskGroup $taskGroup)
    {
        return view('task-groups.edit', compact('taskGroup'));
    }

    public function update(TaskGroupRequest $request, TaskGroup $taskGroup)
    {
        $taskGroup->update([
            'name'=> $request->input('name'),
        ]);

        return redirect()->route('task-groups.index')->with('message','グループ名を更新しました');
    }

    public function destroy(TaskGroup $taskGroup)
    {
        $taskGroup->delete();
        return redirect()->route('task-groups.index')->with('message','タスクグループを削除しました');
    }
}
