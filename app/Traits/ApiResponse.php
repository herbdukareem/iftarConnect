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
    public function apiResponse(bool $status = false, $data = null, int $statusCode = 200, array $headers = []): \Illuminate\Http\JsonResponse
    {
        $response = [
            'error' => $status,
            'status' => $statusCode,
            'responseBody' => $data,
        ];

        return response()->json($response, 200, $headers);
    }
}
// Client ID: 1
// Client secret: H1kuDBgPpaKdb6kyBaYb5uemCbVwexa6Lkx9hMpg
// Password grant client created successfully.
// Client ID: 2
// Client secret: GHMHATzheysEiWmBkBuUyXektSkvq6xFq6xiH5Vn