<?php

namespace App\Interfaces\Mail;

use App\DTOs\Task\TaskMailDTO;

interface MailServiceInterface {
    public function sendTaskCreated(string $email, TaskMailDTO $taskMailDTO):void;
}