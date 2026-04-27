<?php

namespace App\Repositories\Email;

use App\Interfaces\Email\EmailLogReponsitoryInterface;
use App\Models\Task\Email\EmailLog;

class EmailLogRepository implements EmailLogReponsitoryInterface
{
    public function create(array $data): EmailLog
    {
        return EmailLog::create($data);

    }

    public function updateStatus(int $id, array $data): void
    {
        $emailLog = EmailLog::query()->findOrFail($id);
        $emailLog->update($data);
    }
}
