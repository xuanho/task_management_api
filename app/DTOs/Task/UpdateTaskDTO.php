<?php

namespace App\DTOs\Task;

use App\DTOs\Task\BaseDTO;
class UpdateTaskDTO extends BaseDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(?string $title = null, ?string $description = null, ?int $status = null )
    {
        parent::__construct($title, $description, $status);
    }
    public static function fromArray(array $data): self
    {
        return new self($data['title'] ?? null, $data['description'] ?? null, $data['status'] ?? null);
    }
}
