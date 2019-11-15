<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Services\UserService;
use App\Wepay\Exceptions\WepayConfigurationException;
use App\Wepay\Exceptions\WePayRequestException;
use App\Wepay\Exceptions\WepayServerException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserService $userService
     *
     * @return RedirectResponse|Response
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserService $userService)
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $userService->createMerchant($user, $form->get('country')->getData());

            return $this->redirectToRoute('shop');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
