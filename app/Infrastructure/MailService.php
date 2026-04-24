<?php

namespace App\Infrastructure;

use App\DTOs\Task\TaskMailDTO;
use App\Interfaces\Mail\MailServiceInterface;
use App\Mail\Task\TaskCreatedMail;
use App\Mail\Task\TaskUpdatedMail;
use Illuminate\Support\Facades\Mail;


class MailService implements MailServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function sendTaskCreated(string $email, TaskMailDTO $taskMailDTO):void
    {
        Mail::to($email)->send(new TaskCreatedMail($taskMailDTO));
    }

    public function sendTaskUpdated(string $email, TaskMailDTO $taskMailDTO):void
    {
        Mail::to($email)->send(new TaskUpdatedMail($taskMailDTO));
    }
}
