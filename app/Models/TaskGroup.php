<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskGroup extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    // $taskGroup->$tasks で紐づくタスク一覧を取れるようになる
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
