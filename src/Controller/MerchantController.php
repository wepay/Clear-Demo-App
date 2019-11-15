<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\Type\AccountType;
use App\Objects\PayoutUs;
use App\Services\WepayService;
use App\Wepay\Exceptions\WepayConfigurationException;
use App\Wepay\Exceptions\WePayRequestException;
use App\Wepay\Exceptions\WepayServerException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MerchantController extends AbstractController
{
    /**
     * @Route("/merchant/orders", name="merchant_orders")
     *
     * @return Response
     */
    public function orders()
    {
        $orders = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findSellerOrders($this->getUser());

        return $this->render('merchant/orders.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/merchant/info/us", name="merchant_info_us")
     *
     * @param Request $request
     * @param WepayService $wepayService
     *
     * @return Response
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function infoUS(Request $request, WepayService $wepayService)
    {
        $merchantInfo = $this->getUser()->getMerchantInfo();

        if ($request->isMethod('POST') && $request->get('legalEntityToken'))
        {
            $token = $request->get('legalEntityToken');

            $wepayService->updateLegalEntity($merchantInfo->getLegalEntityId(), $token);
        }

        $merchantInfoUs = $wepayService->getLegalEntityUS($merchantInfo->getLegalEntityId());

        return $this->render('merchant/info_us.html.twig', [
            'merchantInfo' => $merchantInfoUs
        ]);
    }

    /**
     * @Route("/merchant/account", name="merchant_account")
     *
     * @param Request      $request
     * @param WepayService $wepayService
     *
     * @return Response
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function account(Request $request, WepayService $wepayService)
    {
        $merchantInfo = $this->getUser()->getMerchantInfo();

        if (!$merchantInfo->getAccountId()) {
            $accountId = $wepayService->createAccount($merchantInfo->getLegalEntityId());
            $merchantInfo->setAccountId($accountId);

            $em = $this->getDoctrine()->getManager();
            $em->persist($merchantInfo);
            $em->flush();
        }

        $account = $wepayService->getAccount($merchantInfo->getAccountId());

        $form = $this->createForm(AccountType::class, $account);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $wepayService->updateAccount($merchantInfo->getAccountId(), $account);

            $merchantInfo->setName($account->getName());

            $em = $this->getDoctrine()->getManager();
            $em->persist($merchantInfo);
            $em->flush();
        }

        return $this->render('merchant/account.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/merchant/payout/us", name="merchant_payout_us")
     *
     * @param Request      $request
     * @param WepayService $wepayService
     *
     * @return Response
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function payoutUS(Request $request, WepayService $wepayService) {
        $merchantInfo = $this->getUser()->getMerchantInfo();
        $token        = $request->get('payoutToken');

        if ($request->isMethod('POST') && $token) {
            if (!$merchantInfo->getPayoutId()) {
                $payoutId = $wepayService->createPayout($merchantInfo->getLegalEntityId(), $token);

                $merchantInfo->setPayoutId($payoutId);

                $em = $this->getDoctrine()->getManager();
                $em->persist($merchantInfo);
                $em->flush();
            }
        }

        if ($merchantInfo->getPayoutId()) {
            $payoutUs = $wepayService->getPayoutUS($merchantInfo->getPayoutId());
        } else {
            $payoutUs = new PayoutUs();
        }

        return $this->render('merchant/payout_us.html.twig', [
            'payout' => $payoutUs
        ]);
    }

    /**
     * @Route("/merchant/disputes", name="merchant_disputes")
     *
     * @param WepayService $wepayService
     *
     * @return Response
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function disputes(WepayService $wepayService)
    {
        $disputes = $wepayService->disputesCollection($this->getUser()->getMerchantInfo()->getAccountId());

        return $this->render('merchant/disputes.html.twig', [
            'disputes' => $disputes
        ]);
    }

    /**
     * @Route("/merchant/dispute/{disputeId}/concede", name="merchant_dispute_concede")
     *
     * @param string       $disputeId
     * @param WepayService $wepayService
     *
     * @return RedirectResponse
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function concedeDispute($disputeId, WepayService $wepayService)
    {
        $wepayService->concedeDispute($disputeId);

        $this->addFlash('dispute', 'Dispute successfully conceded');

        return $this->redirectToRoute('merchant_disputes');
    }

    /**
     * @Route("/merchant/dispute/attach", name="merchant_dispute_attach")
     *
     * @param Request      $request
     * @param WepayService $wepayService
     *
     * @return RedirectResponse
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function attachFile(Request $request, WepayService $wepayService)
    {
        $disputeId  = $request->get('disputeId');
        $documentId = $request->get('documentId');

        if ($request->isMethod('POST') && $documentId && $disputeId) {
            $wepayService->addDocument($disputeId, $documentId);

            $this->addFlash('dispute', 'Document successfully attached');
        }

        return $this->redirectToRoute('merchant_disputes');
    }
}
