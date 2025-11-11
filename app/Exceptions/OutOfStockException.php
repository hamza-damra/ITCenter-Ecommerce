<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class OutOfStockException extends Exception
{
    protected $product;
    protected $requested;
    protected $available;

    public function __construct($product, $requested, $available)
    {
        $this->product = $product;
        $this->requested = $requested;
        $this->available = $available;
        
        parent::__construct(
            "Insufficient stock for {$product->name}. Requested: {$requested}, Available: {$available}"
        );
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
                'product' => $this->product->name,
                'available' => $this->available,
                'error' => 'Out of Stock'
            ], 400);
        }

        return redirect()->back()
            ->with('error', $this->getMessage())
            ->withInput();
    }
}
