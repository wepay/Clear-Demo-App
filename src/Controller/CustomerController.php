<?php

namespace App\Controller;

use App\Entity\Order;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    /**
     * @Route("/customer/orders", name="customer_orders")
     *
     * @return Response
     */
    public function orders()
    {
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findCustomerOrders($this->getUser());

        return $this->render('customer/orders.html.twig', [
            'orders' => $orders
        ]);
    }
}
