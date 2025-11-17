<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
  public function create($data)
  {
    return Order::create($data);
  }

  public function findByUser($userId)
  {
    return Order::where('user_id', $userId)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  public function find($orderId)
  {
    return Order::find($orderId);
  }

  public function update($orderId, $data)
  {
    $order = Order::findOrFail($orderId);
    $order->update($data);
    return $order;
  }
}