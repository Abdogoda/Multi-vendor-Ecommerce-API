<?php

namespace App\Traits;

use Illuminate\Http\Response;


trait ApiResponseTrait{
   /**
   * Return a successful response with data.
   *
   * @param mixed $data
   * @param string|null $message
   * @return \Illuminate\Http\JsonResponse
   */
  public function successResponse($data = null, $message = 'Success', $statusCode = Response::HTTP_OK){
    return response()->json([
        'success' => true,
        'message' => $message,
        'data'    => $data
    ], $statusCode);
  }

  /**
   * Return an error response with an error message.
   *
   * @param string $message
   * @param int $statusCode
   * @return \Illuminate\Http\JsonResponse
   */
  public function errorResponse($message = 'Error', $statusCode = Response::HTTP_BAD_REQUEST){
    return response()->json([
        'success' => false,
        'message' => $message,
        'data'    => null
    ], $statusCode);
  }

  /**
   * Return a response with validation errors.
   *
   * @param array $errors
   * @return \Illuminate\Http\JsonResponse
   */
  public function validationErrorResponse(array $errors, $message = 'Validation Error', $statusCode = Response::HTTP_UNPROCESSABLE_ENTITY){
    return response()->json([
        'success' => false,
        'message' => $message,
        'errors'  => $errors
    ], $statusCode);
  }
}