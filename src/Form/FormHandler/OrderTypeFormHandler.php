<?php

namespace App\Form\FormHandler;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\Type\OrderType;
use App\Services\CartService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Common\Persistence\ManagerRegistry;

class OrderTypeFormHandler {

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var CartService
     */
    private $cartService;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @param FormFactoryInterface $formFactory
     * @param RequestStack         $requestStack
     * @param CartService          $cartService
     */
    public function __construct(FormFactoryInterface $formFactory, ManagerRegistry $doctrine,  RequestStack $requestStack, CartService $cartService)
    {
        $this->formFactory = $formFactory;
        $this->doctrine    = $doctrine;
        $this->request = $requestStack->getCurrentRequest();
        $this->cartService = $cartService;
    }

    /**
     * @return FormInterface
     */
    public function buildForm() {
        $order = $this->createOrder();

        $form = $this->formFactory->create(OrderType::class, $order);

        $form->handleRequest($this->request);

        return $form;
    }

    private function createOrder()
    {
        $cart = $this->cartService->getCart();
        $order = new Order();

        foreach ($cart->getCartItems() as $cartItem) {
            $product = $this->doctrine
                ->getRepository(Product::class)
                ->find($cartItem->getProductId());

            $orderItem = new OrderItem();
            $orderItem->setAmount($cartItem->getAmount());
            $orderItem->setProduct($product);

            $order->addOrderItem($orderItem);
        }

        return $order;
    }

}