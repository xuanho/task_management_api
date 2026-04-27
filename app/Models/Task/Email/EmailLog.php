<?php

namespace App\Models\Task\Email;

use App\Enums\Email\EmailStatus;
use App\Enums\Email\EmailType;
use App\Models\Task\Task;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $guarded = [];

    protected $casts =
        [
            'type' => EmailType::class,
            'status' => EmailStatus::class,

        ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');

    }
}
