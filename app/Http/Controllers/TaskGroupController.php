<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskGroupRequest;
use App\Models\TaskGroup;

class TaskGroupController extends Controller
{
    public function index()
    {
        $taskGroups = TaskGroup::latest()->get();
        return view('task-groups.index', compact('taskGroups'));
    }

    public function show(TaskGroup $taskGroup, Request $request)
    {
        // フィルター機能
        $filter = $request->query("filter", "all");
        $query = $taskGroup->tasks()->with('taskGroup');

        if ($filter === 'active') {
            $query->where('is_done', false);
        }

        if ($filter === 'completed') {
            $query->where('is_done', true);
        }

        // 完了ステータス順 > 新規順 に並び替えて実際に取得
        $tasks = $query
            ->orderBy('is_done')
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->latest()
            ->get();

        $taskGroups = TaskGroup::orderBy('name')->get();

        return view('task-groups.show', compact('taskGroup', 'tasks', 'filter', 'taskGroups'));
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
