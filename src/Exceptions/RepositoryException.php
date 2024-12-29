<?php

namespace Laymont\PatternRepository\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Throwable;

class RepositoryException extends Exception implements Responsable
{
    /**
     * Constructor de la excepción personalizada.
     */
    public function __construct(string $message = 'An error occurred in Repository', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function toResponse($request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => 'Error de repositorio',
            'message' => $this->getMessage(),
        ], 500);
    }
}
