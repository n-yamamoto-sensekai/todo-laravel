<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'title' => 'required|max:255',
        ]);

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
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'=> 'required|max:255',
        ]);

        $task->update([
            'title'=> $request->input('title'),
        ]);

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
    public function destroy(Task $task)  // Route Model Binding
    {
        $task->delete();
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
