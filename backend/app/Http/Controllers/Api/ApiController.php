<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    /**
     * Return generic json response with the given data.
     *
     * @param $data
     * @param int $statusCode
     * @param string $message
     * @param array $headers
     * @return JsonResponse
     */
    protected function respond($data, int $statusCode = 200, string $message = '', array $headers = []): JsonResponse
    {
        $newData = [];

        // Check for response without transformer
        if (!isset($data['data'])) {
            $newData['data'] = $data;
        } else {
            $newData = $data;
        }

        $newData['success'] = true;
        $newData['message'] = $message;

        return response()->json($newData, $statusCode, $headers);
    }

    /**
     * Respond with success.
     *
     * @param $data
     * @param string $message
     * @return JsonResponse
     */
    protected function respondSuccess($data, string $message = 'Success'): JsonResponse
    {
        return $this->respond($data, 200, $message);
    }

    /**
     * Respond with created.
     *
     * @param $data
     * @param string $message
     * @return JsonResponse
     */
    protected function respondCreated($data, string $message = 'Created'): JsonResponse
    {
        return $this->respond($data, 201, $message);
    }

    /**
     * Respond with unauthorized.
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function respondUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->respondError($message, 401);
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function respondError(string $message, int $statusCode): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
