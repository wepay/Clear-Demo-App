<?php

namespace App\Controller;

use App\Services\OrderService;
use App\Services\WepayService;
use App\Wepay\Exceptions\WepayConfigurationException;
use App\Wepay\Exceptions\WePayRequestException;
use App\Wepay\Exceptions\WepayServerException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/checkout/{key}", name="checkout")
     *
     * @param string $key
     *
     * @param Request      $request
     * @param OrderService $orderService
     * @param WepayService $wepayService
     *
     * @return RedirectResponse|Response
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function index($key, Request $request, OrderService $orderService, WepayService $wepayService)
    {
        $orderItems = $orderService->getTmpOrderItems($key);

        if (!$orderItems) {
            return $this->redirectToRoute('cart');
        }

        $token = $request->get('paymentToken');
        $total = $orderService->getTotalPrice($orderItems);

        if ($request->isMethod('POST') && $token) {
            $paymentId = $wepayService->createPayment($total, $token);

            $orderService->makeOrders($orderItems, $paymentId, $this->getUser());
            $orderService->clearTmpOrderItems($key);

            return $this->redirectToRoute('customer_orders');
        }

        return $this->render('order/checkout.html.twig', [
            'orderItems' => $orderItems,
            'total'      => $total
        ]);
    }
}
