<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegraDeNegocioException extends Exception
{
    public function __construct(string $mensagem)
    {
        parent::__construct($mensagem, Response::HTTP_CONFLICT);
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], Response::HTTP_CONFLICT);
    }
}
