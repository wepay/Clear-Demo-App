<?php

namespace App\Services;

use App\Entity\Product;
use App\Objects\Cart;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @param SessionInterface $session
     * @param ManagerRegistry $doctrine
     */
    public function __construct(SessionInterface $session, ManagerRegistry $doctrine)
    {
        $this->session  = $session;
        $this->doctrine = $doctrine;
    }

    /**
     * @param Product $product
     * @param int     $amount
     */
    public function addToCart(Product $product, int $amount)
    {
        $cart = $this->getCart();

        $cartItem = $cart->getCartItem($product->getId());
        $cartItem->setAmount($cartItem->getAmount() + $amount);

        $this->saveCart($cart);
    }

    /**
     * @return Cart
     */
    public function getCart()
    {
        if ($this->session->has('cart')) {
            return $this->session->get('cart');
        }

        $cart = new Cart();

        return $cart;
    }

    /**
     * @param Cart $cart
     */
    public function saveCart($cart)
    {
        $this->session->set('cart', $cart);
    }

    /**
     * Clear cart
     */
    public function clearCart()
    {
        $this->session->remove('cart');
    }

    /**
     * @param int $productId
     */
    public function removeCartItem($productId)
    {
        $cart = $this->getCart();

        $cart->removeCartItem($productId);

        $this->saveCart($cart);
    }

    public function getCartProducts()
    {
        $cart = $this->getCart();
        $products = [];

        foreach ($cart->getCartItems() as $cartItem) {
            $product = $this->doctrine->getRepository(Product::class)
                ->find($cartItem->getProductId());

            $products[$product->getId()] = $product;
        }

        return $products;
    }

    /**
     * @return bool
     */
    public function hasCartItems()
    {
        return $this->getCart()->hasCartItems();
    }
}