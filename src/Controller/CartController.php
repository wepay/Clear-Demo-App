<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Type\CartType;
use App\Services\CartService;
use App\Services\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart")
     *
     * @param Request      $request
     * @param CartService  $cartService
     * @param OrderService $orderService
     *
     * @return Response
     */
    public function index(Request $request, CartService $cartService, OrderService $orderService)
    {
        $cart = $cartService->getCart();

        $form = $this->createForm(CartType::class, $cart);

        $products = $cartService->getCartProducts();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $cart = $form->getData();

            $cartService->saveCart($cart);

            $key = $orderService->prepareTempOrderItems();

            return $this->redirectToRoute('checkout', ['key' => $key]);
        }

        return $this->render('cart/index.html.twig', [
            'form'     => $form->createView(),
            'products' => $products
        ]);
    }

    /**
     * @Route("/cart/add", name="cart_add")
     *
     * @param Request     $request
     * @param CartService $cartService
     *
     * @return RedirectResponse
     */
    public function addToCart(Request $request, CartService $cartService)
    {
        $amount    = $request->get('amount', 1);
        $productId = $request->get('product_id');

        if (!$productId) {
            return $this->redirectToRoute('shop');
        }

        /** @var Product $product */
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($productId);

        if (!$product) {
            return $this->redirectToRoute('shop');
        }

        $cartService->addToCart($product, $amount);

        $this->addFlash(
            'add_to_cart',
            'Product successfully added to cart!'
        );

        return $this->redirectToRoute('product', ['id' => $product->getId()]);
    }

    /**
     * @Route("/cart/remove", name="cart_remove")
     *
     * @param Request     $request
     * @param CartService $cartService
     *
     * @return RedirectResponse
     */
    public function removeFromCart(Request $request, CartService $cartService)
    {
        $productId = (int) $request->get('product_id');

        if (!$productId) {
            return $this->redirectToRoute('cart');
        }

        $cartService->removeCartItem($productId);

        return $this->redirectToRoute('cart');
    }
}
