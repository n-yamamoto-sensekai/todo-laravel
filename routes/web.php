<?php

use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\TaskGroupController;
use App\Http\Controllers\TaskController;

Route::get('/task-groups', [TaskGroupController::class, 'index'])
    ->name('task-groups.index');

Route::post('/task-groups', [TaskGroupController::class, 'store'])
    ->name('task-groups.store');

Route::get('/task-groups/{taskGroup}', [TaskGroupController::class, 'show'])
    ->name('task-groups.show');

Route::get('/task-groups/{taskGroup}/edit', [TaskGroupController::class, 'edit'])
    ->name('task-groups.edit');

Route::put ('/task-groups/{taskGroup}', [TaskGroupController::class, 'update'])
    ->name('task-groups.update');

Route::delete ('/task-groups/{taskGroup}', [TaskGroupController::class, 'destroy'])
    ->name('task-groups.destroy');

// Todoの完了フラグ変更用独自Route
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])
    ->name('tasks.toggle');

// 一括完了
Route::patch('/tasks/mark-all-done', [TaskController::class,'markAllDone'])
    ->name('tasks.markAllDone');

// 一括未完了
Route::patch('/tasks/mark-all-undone', [TaskController::class,'markAllUndone'])
    ->name('tasks.markAllUndone');

// 完了タスクの一括削除
Route::delete('/tasks/completed', [TaskController::class,'destroyCompleted'])
    ->name('tasks.destroyCompleted');

// CRUD用のRouteをまとめて作る書き方
Route::resource('tasks', TaskController::class)
    ->only(['index','store', 'edit', 'update', 'destroy']);
