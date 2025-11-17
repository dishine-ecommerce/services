<?php

namespace App\Services;

use App\Repositories\CartRepository;

class CartService
{
    protected $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function getUserCart($userId)
    {
        return $this->cartRepository->findByUser($userId);
    }

    public function addToCart($data)
    {
      return $this->cartRepository->add($data);
    }

    public function updateCartQuantity($cartId, $quantity)
    {
        return $this->cartRepository->updateQuantity($cartId, $quantity);
    }

    public function removeFromCart($cartId)
    {
        return $this->cartRepository->remove($cartId);
    }

    public function clearUserCart($userId)
    {
        return $this->cartRepository->clearByUser($userId);
    }

    public function getCartItem($cartId)
    {
        return $this->cartRepository->find($cartId);
    }
}
