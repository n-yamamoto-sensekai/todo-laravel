<?php

namespace App\Contents\Task;

use App\Models\Task;
use App\Models\TaskGroup;
use Illuminate\Http\Request;

class TaskIndexRetrieveService
{
    public function execute(Request $request): array
    {
        // フィルター機能
        $filter = $request->query("filter", "all");  // URLにfilterがない場合allを使う

        $query = Task::with('taskGroup'); // Taskテーブルからデータを取得する準備をする（N+1問題対策に紐づくtaskGroupを一緒に取得）

        if ($filter === 'active') {
            $query->where('is_done', false);  // 条件で絞り込む
        }

        if ($filter === 'completed') {
            $query->where('is_done', true);
        }

        // 完了ステータス順 > 新規順 に並び替えて実際に取得
        $tasks = $query
            ->orderBy('is_done')
            ->orderByRaw('due_date IS NULL') // 期限がない => 1(true), ある => 0(false)
            ->orderBy('due_date')
            ->latest()
            ->get();

        // モーダルのグループ選択肢用
        $taskGroups = TaskGroup::orderBy('name')->get();

        return [
            'tasks' => $tasks,
            'filter' => $filter,
            'taskGroups' => $taskGroups,
        ];
    }
}
