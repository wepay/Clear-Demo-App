<?php

namespace App\Services;

use App\Entity\MerchantInfo;
use App\Entity\User;
use App\Wepay\Exceptions\WepayConfigurationException;
use App\Wepay\Exceptions\WePayRequestException;
use App\Wepay\Exceptions\WepayServerException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    /**
     * @var WepayService
     */
    private $wepayService;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param WepayService                 $wepayService
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface       $em
     */
    public function __construct(WepayService $wepayService, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $em)
    {
        $this->wepayService    = $wepayService;
        $this->passwordEncoder = $passwordEncoder;
        $this->em              = $em;
    }

    /**
     * @param User   $user
     * @param string $country
     *
     * @throws WePayRequestException
     * @throws WepayConfigurationException
     * @throws WepayServerException
     */
    public function createMerchant($user, $country)
    {
        $user->setRoles(['ROLE_MERCHANT']);

        $legalEntityId = $this->wepayService->createLegalEntity($country);

        $merchantInfo = new MerchantInfo();
        $merchantInfo->setUser($user);
        $merchantInfo->setLegalEntityId($legalEntityId);
        $merchantInfo->setCountry($country);

        $user->setMerchantInfo($merchantInfo);

        $this->em->persist($user);
        $this->em->persist($merchantInfo);
        $this->em->flush();
    }
}