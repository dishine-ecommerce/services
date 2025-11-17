<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Services\OrderService;

class OrderController
{
  protected $orderService;

  public function __construct() {
    $this->orderService = new OrderService();
  }

  public function order(CreateOrderRequest $request)
  {
    try {
      $userId = $request->user()->id;
      $order = $this->orderService->createOrder(
        $userId,
        $request->input('cart_ids', []),
        $request->input('payment_method')
      );

      return response()->json([
        'order' => $order,
        'message' => 'Order created successfully',
      ], 201);
    } catch (\Exception $e) {
      $statusCode = $e->getCode();
      if (!is_int($statusCode) || $statusCode < 400 || $statusCode > 599) {
        $statusCode = 500;
      }

      return response()->json([
        'error' => $e->getMessage()
      ], $statusCode);
    }
  }
}