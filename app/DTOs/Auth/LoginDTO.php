<?php

namespace App\DTOs\Auth;

class LoginDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string $email, public string $password) {}

    public function toArray(): array
    {
        return [
            'email' => $this->email,
            'password' => $this->password,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self($data['email'], $data['password']);
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
