<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Models\Task;

class TaskController extends Controller
{
    // 一覧表示
    public function index(Request $request)
    {
        // フィルター機能
        $filter = $request->query("filter", "all");  // URLにfilterがない場合allを使う

        $query = Task::query(); // Taskテーブルからデータを取得する準備をする

        if ($filter === 'active') {
            $query->where('is_done', false);  // 条件で絞り込む
        }

        if ($filter === 'completed') {
            $query->where('is_done', true);
        }

        // 完了ステータス順 > 新規順 に並び替えて実際に取得
        $tasks = $query
            ->orderBy('is_done')
            ->latest()
            ->get();

        return view("tasks.index", compact("tasks", "filter"));
    }

    // 追加
    public function store(TaskRequest $request) // TaskRequestのrules()を使って入力チェックしてくれる
    {
        Task::create([
            // params[:title]的な感じ
            'title' => $request->input('title'),
        ]);

        return redirect()->route('tasks.index')->with('message', 'タスクを追加しました');  // フラッシュメッセージ
    }

    // 編集画面表示
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // 更新
    public function update(TaskRequest $request, Task $task)
    {
        $task->update([
            'title'=> $request->input('title'),
            'due_date' => $request->input('due_date'),
            'memo' => $request->input('memo'),
        ]);

        // Ajaxリクエストの場合jsonでレスポンスを返す
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'タスクを更新しました。',
                'task' => $task,
            ]);
        }

        return redirect()->route('tasks.index')->with('message','タスクを更新しました。');
    }

    // 完了フラグの切り替え
    public function toggle(Task $task)
    {
        $task->update([
            'is_done' => ! $task->is_done,  // !：真偽値を逆にする演算子
        ]);

        return redirect()->route('tasks.index')->with('message','タスクの状態を更新しました');
    }

    // 一括完了
    public function markAllDone()
    {
        Task::query()->update([
            'is_done' => true,
        ]);
        
        return redirect()->route('tasks.index')->with('message','すべてのタスクを完了にしました');
    }

	// 一括未完了
    public function markAllUndone()
    {
        Task::query()->update([
            'is_done' => false,
        ]);
        
        return redirect()->route('tasks.index')->with('message','すべてのタスクを未完了にしました');
    }

    // 削除
    public function destroy(Request $request, Task $task)  // Route Model Binding
    {
        $taskId = $task->id;
        $task->delete();

        // Ajaxリクエストの場合jsonでレスポンスを返す
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'タスクを削除しました',
                'task_id' => $taskId,
            ]);
        }

        return redirect()->route('tasks.index')->with('message', 'タスクを削除しました');
    }

	// 完了タスクの一括削除
	public function destroyCompleted()
	{
		Task::query()
			->where('is_done', true)
			->delete();

		return redirect()->route('tasks.index')->with('message','完了済みタスクを削除しました。');
	}

}
