<?php

namespace App\Contents\TaskGroup;

use App\Models\TaskGroup;
use Illuminate\Http\Request;

class TaskGroupShowService
{
    public function execute(TaskGroup $taskGroup, Request $request): array
    {
        // リクエストからフィルター情報取得
        $filter = $request->query("filter", "all");

        // タスクをフィルターで絞り込み
        $query = $taskGroup->tasks();  // HasMany リレーションオブジェクト（内部にEloquent Builderを持つ）

        if ($filter === 'active') {
            $query->where('is_done', false);
        }

        if ($filter === 'completed') {
            $query->where('is_done', true);
        }

        // 絞り込んだタスクを 完了ステータス > 作成日時 に並び替えて取得
        $tasks = $query
            ->orderBy('is_done')
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->latest()
            ->get()  // ここでSQL発行
            ->each(fn($task) => $task->setRelation('taskGroup', $taskGroup));
            // setRelation(string $relation, mixed $value)：指定したリレーションをモデルにセット（DB保存ではない）
            // 実行中のTaskモデルに、読み込み済みリレーションとして $taskGroup を持たせる

        // タスクグループを取得（モーダル用）
        $taskGroups = TaskGroup::orderBy('name')->get();

        return [
            'taskGroup' => $taskGroup,
            'tasks' => $tasks,
            'filter' => $filter,
            'taskGroups' => $taskGroups,
        ];
    }
}
