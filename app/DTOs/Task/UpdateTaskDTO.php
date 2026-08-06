<?php

namespace App\DTOs\Task;

class UpdateTaskDTO extends BaseDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(?string $title = null, ?string $description = null, ?int $status_id = null, ?int $assigned_to = null)
    {
        parent::__construct($title, $description, $status_id, null, null, $assigned_to);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['title'] ?? null, $data['description'] ?? null, $data['status_id'] ?? null, $data['assigned_to'] ?? null);
    }
}
