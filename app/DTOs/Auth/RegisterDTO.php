<?php

namespace App\DTOs\Auth;

class RegisterDTO
{
    public function __construct(public string $name, public string $email, public string $password) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self($data['name'], $data['email'], $data['password']);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
