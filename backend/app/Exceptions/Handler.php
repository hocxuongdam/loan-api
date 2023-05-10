<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        switch (get_class($e)) {
            case ModelNotFoundException::class:
                return self::makeErrorResponse('Not found', 404);

            case AuthorizationException::class:
                return self::makeErrorResponse('Unauthorized', 403);

            default:
                if (config('app.env') != 'production') {
                    return parent::render($request, $e);
                }

                return self::makeErrorResponse('Server error', 500);
        }
    }

    private static function makeErrorResponse(string $message, int $status): \Illuminate\Http\JsonResponse
    {
        return response()->json(data: [
            'success' => false,
            'message' => $message,
        ], status: $status);
    }
}
