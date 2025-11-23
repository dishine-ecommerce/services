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
        $request->input('payment_method'),
        $request->input('is_reseller'),
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

  public function history()
  {
    try {
      $orders = $this->orderService->getHistory();
      return response()->json([
        'orders' => $orders,
        'message' => 'Order history retrieved successfully',
      ]);
    } catch (\Exception $e) {
      $statusCode = $e->getCode();
      return response()->json([
        'error' => $e->getMessage()
      ], $statusCode);
    }
  }
}