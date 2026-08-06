<?php

namespace App\DTOs\Task;

class CreateTaskDTO extends BaseDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(string $title, string $description, int $status_id, int $project_id, int $assigned_to)
    {
        parent::__construct($title, $description, $status_id, null, $project_id, $assigned_to);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['title'], $data['description'], $data['status_id'], $data['project_id'], $data['assigned_to']);
    }
}
