<?php

namespace App\Interfaces;

interface TaskQueueInterface
{
    public function sendTaskCreatedEmail(int $taskId, int $emailLogId): void;

    public function sendTaskUpdatedEmail(int $taskId, int $emailLogId): void;
}
