<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    /**
     * إرجاع استجابة نجاح (Success Response)
     */
    public function successResponse($data = null, string $message = 'تمت العملية بنجاح', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data
        ], $statusCode, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * إرجاع استجابة خطأ (Error Response)
     */
    public function errorResponse(string $message = 'حدث خطأ ما', int $statusCode = 400, $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * إرجاع استجابة لعدم الصلاحية أو عدم المصادقة
     */
    public function unauthorizedResponse(string $message = 'غير مصرح لك بالوصول'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * إرجاع استجابة لعنصر غير موجود
     */
    public function notFoundResponse(string $message = 'العنصر غير موجود'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }
}
