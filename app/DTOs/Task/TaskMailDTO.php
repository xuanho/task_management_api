<?php

namespace App\DTOs\Task;

class TaskMailDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string $title, public string $description, public string $name, public string $status)
    {
        //
    }
}
