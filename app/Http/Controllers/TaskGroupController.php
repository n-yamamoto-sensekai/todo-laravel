<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        $query = $taskGroup->tasks();

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

        return view('task-groups.show', compact('taskGroup', 'tasks', 'filter'));
    }
}
