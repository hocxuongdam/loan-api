<?php

namespace App\Exceptions;

class BaseException extends \Exception
{
    public const ERR_CODE_BAD_REQUEST = 400;

    public function render(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
        ], $this->getCode() ? $this->getCode() : self::ERR_CODE_BAD_REQUEST);
    }

    public function report()
    {
    }
}
