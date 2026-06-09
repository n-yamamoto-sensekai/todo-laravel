<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    // 一覧表示
    public function index()
    {
        // 新しい順にタスク一覧を取得
        $tasks = Task::latest()->get();
        return view("tasks.index", compact("tasks"));
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

    // 削除
    public function destroy(Task $task)  // Route Model Binding
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('message', 'タスクを削除しました');
    }
}
