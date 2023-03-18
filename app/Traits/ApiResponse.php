<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Returns a JSON response with the standard format.
     *
     * @param string $status The status of the response (success, error, etc.)
     * @param string $message The message to be included in the response
     * @param mixed $data The data to be included in the response
     * @param int $statusCode The HTTP status code to be used in the response
     * @param array $headers An array of headers to be included in the response
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiResponse(string $status, string $message, $data = null, int $statusCode = 200, array $headers = []): \Illuminate\Http\JsonResponse
    {
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode, $headers);
    }
}
