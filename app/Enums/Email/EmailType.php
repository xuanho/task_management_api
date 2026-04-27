<?php

namespace App\Enums\Email;

enum EmailType: string
{
    case TASK_CREATED = 'task_created';
    case STATUS_CHANGED = 'status_changed';
}
