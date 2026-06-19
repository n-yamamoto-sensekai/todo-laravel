<?php
namespace App\Contents\Task;

use App\Contents\TaskGroup\TaskGroupUpdateService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    private TaskIndexRetrieveService $indexRetrieveService;
    private TaskRegisterService $registerService;
    private TaskUpdateService $updateService;

    public function __construct(
        TaskIndexRetrieveService $indexRetrieveService,
        TaskRegisterService $registerService,
        TaskUpdateService $updateService
    ) {
        $this->indexRetrieveService = $indexRetrieveService;
        $this->registerService = $registerService;
        $this->updateService = $updateService;
    }

    // 一覧表示
    public function index(Request $request)
    {
        $data = $this->indexRetrieveService->execute($request);
        return view("tasks.index", $data);
    }

    // 追加
    public function store(TaskRequest $request) // TaskRequestのrules()を使って入力チェックしてくれる
    {
        $this->registerService->execute($request);

        if ($request->filled('task_group_id')) {
            return redirect()
                ->route('task-groups.show', $request->input('task_group_id'))
                ->with('message', 'タスクを追加しました');
        }

        return redirect()
            ->route('tasks.index')
            ->with('message', 'タスクを追加しました');
    }

    // 編集画面表示
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // 更新
    public function update(TaskRequest $request, Task $task)
    {
        $task = $this->updateService->execute($task, $request);

        // Ajaxリクエストの場合jsonでレスポンスを返す
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'タスクを更新しました。',
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'is_done' => $task->is_done,
                    'due_date' => $task->due_date?->format('Y-m-d'),
                    'memo' => $task->memo,
                    'task_group_id' => $task->task_group_id,
                    'task_group_name' => $task->taskGroup?->name,
                ],
            ]);
        }

        return redirect()->route('tasks.index')->with('message','タスクを更新しました。');
    }

    // 完了フラグの切り替え
    public function toggle(Request $request, Task $task)
    {
        $task->update([
            'is_done' => ! $task->is_done,  // !：真偽値を逆にする演算子
        ]);

        // Ajaxリクエストの場合jsonでレスポンスを返す
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'タスクの状態を更新しました',
                'task' => [
                    'id' => $task->id,
                    'title' => $task->title,
                    'is_done' => $task->is_done,
                    'due_date' => $task->due_date?->format('Y-m-d'),
                    'memo' => $task->memo,
                    'task_group_id' => $task->task_group_id,
                    'task_group_name' => $task->taskGroup?->name,
                ],
            ]);
        }

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
