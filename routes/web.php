<?php

use Illuminate\Console\View\Components\Task;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\TaskController;
Route::get('/tasks', [TaskController::class, 'index']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
