<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Task::factory()を使えるようにする
    use HasFactory;
    protected $fillable = ['title', 'is_done', 'due_date', 'memo', 'task_group_id']; // Strong Parameterのような感じ（受け付けるリクエストの要素）

    protected $casts = [
        'is_done' => 'boolean',
        'due_date' => 'date',
    ];

    public function taskGroup()
    {
        return $this->belongsTo(TaskGroup::class);
    }
}
