<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Task::factory()を使えるようにする
    use HasFactory;
    protected $fillable = ['title', 'is_done', 'due_date', 'memo']; // Strong Parameterのような感じ（受け付けるリクエストの要素）
}
