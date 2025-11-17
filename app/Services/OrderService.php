<?php

namespace App\Services;

use App\Repositories\CartRepository;
use App\Repositories\OrderItemRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
  protected $orderRepo;
  protected $orderItemRepo;
  protected $paymentRepo;
  protected $cartRepo;

  public function __construct()
  {
    $this->orderRepo = new OrderRepository();
    $this->orderItemRepo = new OrderItemRepository();
    $this->paymentRepo = new PaymentRepository();
    $this->cartRepo = new CartRepository();
  }

  public function createOrder(int $userId, array $cartIds, string $paymentMethod)
  {
    $cartIds = array_values(array_unique($cartIds));

    if (empty($cartIds)) {
      throw new \Exception('Cart items are required', 422);
    }

    return DB::transaction(function () use ($userId, $cartIds, $paymentMethod) {
      $cartItems = $this->cartRepo->findByUserAndIds($userId, $cartIds, true);

      if ($cartItems->isEmpty()) {
        throw new \Exception('Cart items not found', 404);
      }

      if ($cartItems->count() !== count($cartIds)) {
        throw new \Exception('Some cart items are invalid', 422);
      }

      $totalAmount = $cartItems->sum(function ($item) {
        return $item->price * $item->quantity;
      });

      $order = $this->orderRepo->create([
        'user_id' => $userId,
        'total_amount' => $totalAmount,
        'status' => 'pending',
      ]);

      $this->orderItemRepo->createMany(
        $cartItems->map(function ($cartItem) use ($order) {
          $product = $cartItem->product ?? optional($cartItem->productVariant)->product;
          $variant = $cartItem->productVariant;

          return [
            'order_id' => $order->id,
            'product_id' => $cartItem->product_id,
            'product_variant_id' => $cartItem->product_variant_id,
            'product_name' => $product?->name ?? 'Unknown Product',
            'variant_name' => $variant?->variant_code,
            'quantity' => $cartItem->quantity,
            'price' => $cartItem->price,
            'subtotal' => $cartItem->price * $cartItem->quantity,
          ];
        })->toArray()
      );

      $this->paymentRepo->create([
        'transaction_id' => (string) Str::uuid(),
        'user_id' => $userId,
        'order_id' => $order->id,
        'amount' => $totalAmount,
        'method' => $paymentMethod,
        'status' => 'pending',
      ]);

      $this->cartRepo->removeByIds($cartIds, $userId);

      return $order->load(['items', 'payment']);
    });
  }
}