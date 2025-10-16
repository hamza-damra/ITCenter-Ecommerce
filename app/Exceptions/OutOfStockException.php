<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class OutOfStockException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Product is out of stock',
            'error' => 'Out of Stock'
        ], 400);
    }
}
