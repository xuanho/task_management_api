<?php

namespace App\Models\Task;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\TaskStatus;
class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'status_id',
        'user_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function status()
    {
        return $this->belongsTo(TaskStatus::class, 'status_id');
    }
}
