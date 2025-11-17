<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{
    public function findByUser($userId)
    {
        return Cart::with(['productVariant.product'])->where('user_id', $userId)->get();
    }

    public function add($data)
    {
        return Cart::create($data);
    }

    public function updateQuantity($cartId, $quantity)
    {
        $cart = Cart::findOrFail($cartId);
        $cart->quantity = $quantity;
        $cart->save();
        return $cart;
    }

    public function remove($cartId)
    {
        return Cart::destroy($cartId);
    }

    public function removeByIds(array $cartIds, int $userId)
    {
        return Cart::where('user_id', $userId)->whereIn('id', $cartIds)->delete();
    }

    public function find($cartId)
    {
        return Cart::with(['productVariant.product'])->find($cartId);
    }

    public function clearByUser($userId)
    {
        return Cart::where('user_id', $userId)->delete();
    }

    public function findByUserAndIds(int $userId, array $cartIds, bool $lockForUpdate = false)
    {
        $query = Cart::with(['productVariant.product', 'product'])
            ->where('user_id', $userId)
            ->whereIn('id', $cartIds);

        if ($lockForUpdate) {
            $query->lockForUpdate();
        }

        return $query->get();
    }
}
