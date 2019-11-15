<?php

namespace App\Objects;

class Cart {

    /**
     * @var CartItem[]
     */
    private $cartItems = [];

    /**
     * @return CartItem[]
     */
    public function getCartItems(): array
    {
        return $this->cartItems;
    }

    /**
     * @param CartItem[] $cartItems
     */
    public function setCartItems(array $cartItems): void
    {
        $this->cartItems = $cartItems;
    }

    /**
     * @return bool
     */
    public function hasCartItems()
    {
        return count($this->cartItems) ? true : false;
    }

    /**
     * @param int $productId
     *
     * @return CartItem
     */
    public function getCartItem($productId)
    {
        foreach ($this->cartItems as $cartItem) {
            if ($cartItem->getProductId() === $productId) {
                return $cartItem;
            }
        }

        $cartItem = new CartItem();
        $cartItem->setProductId($productId);

        $this->cartItems[] = $cartItem;

        return $cartItem;
    }

    /**
     * @param int $productId
     */
    public function removeCartItem($productId)
    {
        foreach ($this->cartItems as $key => $cartItem) {
            if ($cartItem->getProductId() === $productId) {
                unset($this->cartItems[$key]);

                break;
            }
        }
    }
}
