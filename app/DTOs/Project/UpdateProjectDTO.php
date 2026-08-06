<?php

namespace App\DTOs\Project;

class UpdateProjectDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string $name) {}

    public static function fromArray(array $data): self
    {
        return new self(
            $data['name'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
