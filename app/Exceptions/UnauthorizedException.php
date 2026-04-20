<?php

namespace App\Exceptions;

use Exception;

class UnauthorizedException extends ApiException
{
    protected $message = 'invalid credentials';
    
    public function __construct(string $message = "invalid credentials ")
    {
        parent::__construct($message, 'AUTH_INVALID_CREDENTIALS', 401);
    }
}
