<?php

namespace App\DTOs\Project;

class StoreProjectDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string $name, public int $ownerId) {}

    public static function fromArray(array $data): self
    {
        return new self($data['name'], $data['owner_id']);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'owner_id' => $this->ownerId,
        ];
    }
}
