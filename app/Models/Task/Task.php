<?php

namespace App\Models\Task;

use App\Models\Task\Email\EmailLog;
use App\Models\Task\TaskHistory\TaskHistory;
use App\Models\TaskStatus;
use App\Models\User;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status_id',
        'user_id',
        'project_id',
        'assigned_to',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class, 'task_id');
    }

    public function taskHistories()
    {
        return $this->hasMany(TaskHistory::class, 'task_id');
    }

    protected static function newFactory()
    {
        return TaskFactory::new();

    }
}
