<?php

namespace App\Models;

use App\Models\Task\TaskHistory\TaskHistory;
use Database\Factories\TaskStatusFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskStatus extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'color', 'order'];

    public function oldTaskHistories()
    {
        return $this->hasMany(TaskHistory::class, 'old_status_id');
    }

    public function newTaskHistories()
    {
        return $this->hasMany(TaskHistory::class, 'new_status_id');
    }

    public static function newFactory()
    {
        return TaskStatusFactory::new();
    }
}
