<?php
namespace App\Contents\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    private TaskIndexRetrieveService $indexRetrieveService;
    private TaskRegisterService $registerService;
    private TaskUpdateService $updateService;
    private TaskToggleService $toggleService;
    private TaskUpdateAllStatusService $updateAllStatusService;
    private TaskDeleteService $deleteService;
    private TaskDeleteCompletedService $deleteCompletedService;

    public function __construct(
        TaskIndexRetrieveService $indexRetrieveService,
        TaskRegisterService $registerService,
        TaskUpdateService $updateService,
        TaskToggleService $toggleService,
        TaskUpdateAllStatusService $updateAllStatusService,
        TaskDeleteService $deleteService,
        TaskDeleteCompletedService $deleteCompletedService
    ) {
        $this->indexRetrieveService = $indexRetrieveService;
        $this->registerService = $registerService;
        $this->updateService = $updateService;
        $this->toggleService = $toggleService;
        $this->updateAllStatusService = $updateAllStatusService;
        $this->deleteService = $deleteService;
        $this->deleteCompletedService = $deleteCompletedService;
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
        $task = $this->toggleService->execute($task);

        // Ajaxリクエストの場合jsonでレスポンスを返す
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'タスクの状態を更新しました',
                'task' => [
                    'id' => $task->id,
                    'task_group_id' => $task->task_group_id,
                    'is_done' => $task->is_done,
                    'due_date' => $task->due_date?->format('Y-m-d'),
                ],
            ]);
        }

        return redirect()->route('tasks.index')->with('message','タスクの状態を更新しました');
    }

    // 一括完了
    public function markAllDone()
    {
        $this->updateAllStatusService->execute(true);
        return redirect()->route('tasks.index')->with('message','すべてのタスクを完了にしました');
    }

    // 一括未完了
    public function markAllUndone()
    {
        $this->updateAllStatusService->execute(false);
        return redirect()->route('tasks.index')->with('message','すべてのタスクを未完了にしました');
    }

    // 削除
    public function destroy(Request $request, Task $task)  // Route Model Binding
    {
        $taskId = $this->deleteService->execute($task);

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
        $this->deleteCompletedService->execute();
        return redirect()->route('tasks.index')->with('message','完了済みタスクを削除しました。');
    }
}
