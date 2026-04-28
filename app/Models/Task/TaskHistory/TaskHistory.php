<?php

namespace App\Models\Task\TaskHistory;

use App\Models\Task\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TaskHistory extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function oldStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'old_status_id');
    }

    public function newStatus()
    {
        return $this->belongsTo(TaskStatus::class, 'new_status_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
