<?php

namespace App\Repositories;

use App\Models\Cart;

class CartRepository
{
    public function findByUser($userId)
    {
        return Cart::where('user_id', $userId)->get();
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

    public function find($cartId)
    {
        return Cart::find($cartId);
    }

    public function clearByUser($userId)
    {
        return Cart::where('user_id', $userId)->delete();
    }
}
