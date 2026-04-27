<?php

namespace App\Interfaces\Email;

use App\Models\Task\Email\EmailLog;

interface EmailLogReponsitoryInterface
{
    public function create(array $data): EmailLog;

    public function updateStatus(int $id, array $data): void;
}
