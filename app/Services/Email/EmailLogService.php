<?php

namespace App\Services\Email;

use App\Enums\Email\EmailStatus;
use App\Interfaces\Email\EmailLogReponsitoryInterface;
use App\Models\Task\Email\EmailLog;

class EmailLogService
{
    public function __construct(private EmailLogReponsitoryInterface $emailLogReponsitory) {}

    public function create(array $data): EmailLog
    {
        return $this->emailLogReponsitory->create($data);
    }

    public function updateStatus(int $id, array $data): void
    {
        $this->emailLogReponsitory->updateStatus($id, $data);
    }
}
