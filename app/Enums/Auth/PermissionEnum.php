<?php

namespace App\Enums\Auth;

enum PermissionEnum: string
{
    case TASK_CREATE = 'task.create';
    case TASK_UPDATE = 'task.update';
    case TASK_DELETE = 'task.delete';
}
