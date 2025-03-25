<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller {

    //common response for success
    public function successResponse($message, $data = null, $code = 200) {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }

    //common response for error
    public function errorResponse($message, $code) {
        return response()->json([
            'message' => $message
        ], $code);
    }

    //common response for not found
    public function notFoundResponse($message) {
        return $this->errorResponse($message, 404);
    }

    //common response for internal server error
    public function internalServerErrorResponse($message) {
        return $this->errorResponse($message, 500);
    }

    //common response for unauthorized
    public function unauthorizedResponse($message) {
        return $this->errorResponse($message, 401);
    }

    //common response for forbidden
    public function forbiddenResponse($message) {
        return $this->errorResponse($message, 403);
    }

    //common response for validation error
    public function validationErrorResponse($message, $errors) {
        return response()->json([
            'message' => $message,
            'errors' => $errors
        ], 422);
    }

    //common response for bad request
    public function badRequestResponse($message) {
        return $this->errorResponse($message, 400);
    }
}
