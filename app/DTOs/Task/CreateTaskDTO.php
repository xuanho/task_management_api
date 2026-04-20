<?php

namespace App\DTOs\Task;

class CreateTaskDTO 
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string $title, public string $description, public int $status)
    {
    }
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'user_id' => $this->user_id,
        ];
    }
    public static function fromArray(array $data): self
    {
        return new self($data['title'], $data['description'], $data['status']);
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    public function getUserId(): int
    {
        return $this->user_id;
    }
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

}
