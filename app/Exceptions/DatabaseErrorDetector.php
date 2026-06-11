<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;

class DatabaseErrorDetector
{
    public static function isUniqueViolation(QueryException $e): bool
    {
        return isset($e->errorInfo[1]) && $e->errorInfo[1] === 1062;
    }
}
