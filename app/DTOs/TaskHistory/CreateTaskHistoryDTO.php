<?php

namespace App\DTOs\TaskHistory;

class CreateTaskHistoryDTO
{
    public function __construct(public int $taskId, public ?int $oldStatusId, public int $newStatusId, public ?int $changedBy) {}

    public function toArray(): array
    {
        return [
            'task_id' => $this->taskId,
            'old_status_id' => $this->oldStatusId,
            'new_status_id' => $this->newStatusId,
            'changed_by' => $this->changedBy,
        ];
    }
}
