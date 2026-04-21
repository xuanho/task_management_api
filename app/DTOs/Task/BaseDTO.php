<?php

namespace App\DTOs\Task;

abstract class BaseDTO
{
    public function __construct( ?string $title = null, public ?string $description = null, public ?int $status = null, public ?int $user_id = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->user_id = $user_id;
    }
    public function toArray(): array
    {
        return array_filter([
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'user_id' => $this->user_id,
        ], fn($value) => !is_null($value));
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function getStatus(): ?int
    {
        return $this->status;
    }
    public function getUserId(): ?int
    {
        return $this->user_id;
    }
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }
}