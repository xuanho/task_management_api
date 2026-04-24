<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class ApiException extends Exception
{
    protected int $statusCode;

    protected string $errorCode;

    public function __construct(string $message = '', string $errorCode = 'GENERAL_ERROR', int $statusCode = 400)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public static function format(Throwable $e): array
    {
        $status = 500;
        $errorCode = 'SERVER_ERROR';
        $message = 'Something went wrong';
        $details = null;
        if ($e instanceof ApiException) {
            $status = $e->getStatusCode();
            $errorCode = $e->getErrorCode();
            $message = $e->getMessage();
        } elseif ($e instanceof AuthenticationException) {
            $status = 401;
            $errorCode = 'AUTH_UNAUTHORIZED';
            $message = 'Unauthenticated';
        } elseif ($e instanceof ValidationException) {
            $status = 422;
            $errorCode = 'VALIDATION_ERROR';
            $message = 'Validation error';
            $details = $e->errors();
        } elseif ($e instanceof QueryException) {
            $status = 500;
            $errorCode = 'DATABASE_ERROR';
            $message = 'Database error';
        } elseif ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
            $status = 404;
            $errorCode = 'RESOURCE_NOT_FOUND';
            $message = 'Resource not found';
        }

        return [
            'status' => $status,
            'error' => [
                'code' => $errorCode,
                'message' => app()->isProduction() ? $message : $e->getMessage(),
                'details' => app()->isProduction() ? null : $details,
            ],
        ];
    }
}
