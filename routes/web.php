<?php

use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\TaskController;

// Todoの完了フラグ変更用独自Route
Route::patch('/tasks/{task}/toggle', [TaskController::class, 'toggle'])
    ->name('tasks.toggle');

// CRUD用のRouteをまとめて作る書き方
Route::resource('tasks', TaskController::class)
    ->only(['index','store', 'edit', 'update', 'destroy']);
