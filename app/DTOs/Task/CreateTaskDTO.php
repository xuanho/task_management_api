<?php

namespace App\DTOs\Task;

use App\DTOs\Task\BaseDTO;
class CreateTaskDTO extends BaseDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(string $title, string $description, int $status_id)
    {
        parent::__construct($title, $description, $status_id);
    }

    public static function fromArray(array $data): self
    {
        return new self($data['title'], $data['description'], $data['status_id']);
    }
   

}
