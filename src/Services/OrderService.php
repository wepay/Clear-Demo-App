<?php

namespace App\Services;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OrderService
{
    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * @param SessionInterface       $session
     * @param CartService            $cartService
     * @param EntityManagerInterface $em
     */
    public function __construct(SessionInterface $session, CartService $cartService, EntityManagerInterface $em)
    {
        $this->session     = $session;
        $this->cartService = $cartService;
        $this->em          = $em;
    }

    /**
     * @return string
     */
    public function prepareTempOrderItems()
    {
        $cart = $this->cartService->getCart();

        $products   = $this->cartService->getCartProducts();
        $orderItems = [];

        foreach ($cart->getCartItems() as $cartItem) {
            /** @var Product $product */
            $product = $products[$cartItem->getProductId()];

            $totalPrice = $product->getPrice() * $cartItem->getAmount();

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setAmount($cartItem->getAmount());
            $orderItem->setTotalPrice($totalPrice);

            $orderItems[] = $orderItem;
        }

        $key = uniqid();

        $this->session->set($key, $orderItems);

        return $key;
    }

    /**
     * @param string $key
     * @return OrderItem[]|null
     */
    public function getTmpOrderItems($key)
    {
        if ($this->session->has($key)) {
            return $this->session->get($key);
        }

        return [];
    }

    /**
     * @param OrderItem[] $orderItems
     * @return float
     */
    public function getTotalPrice($orderItems)
    {
        $total = 0;

        foreach ($orderItems as $orderItem) {
            $total += $orderItem->getTotalPrice();
        }

        return $total;
    }

    /**
     * @param OrderItem[] $orderItems
     * @param string      $paymentId
     * @param User        $user
     *
     * @throws Exception
     */
    public function makeOrders($orderItems, $paymentId, $user)
    {
        $orders     = [];

        foreach ($orderItems as $orderItem) {
            /** @var OrderItem $orderItem */
            $orderItem = $this->em->merge($orderItem);

            $owner = $orderItem->getProduct()->getOwner();

            if (isset($orders[$owner->getId()])) {
                $order = $orders[$owner->getId()];
            } else {
                $order = new Order();
                $order->setCustomer($user);
                $order->setMerchant($owner);
                $order->setPaymentId($paymentId);
                $order->setCreatedAt(new DateTime('now'));

                $orders[$owner->getId()] = $order;

                $this->em->persist($order);
            }

            $order->setTotalPrice($order->getTotalPrice() + $orderItem->getTotalPrice());

            $order->addOrderItem($orderItem);
            $orderItem->setOrder($order);

            $this->em->persist($orderItem);
        }

        $this->em->flush();
    }

    /**
     * @param string $key
     */
    public function clearTmpOrderItems($key)
    {
        $this->session->remove($key);
        $this->cartService->clearCart();
    }
}