<?php

use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\Route;
use App\Contents\TaskGroup\TaskGroupController;
use App\Contents\Task\TaskController;

Route::get('/', function () {
    return redirect()->route('task-groups.index');
});

Route::resource('task-groups', TaskGroupController::class)
    ->except(['create']);

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
