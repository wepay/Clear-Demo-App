<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public const MERCHANT_1_REFERENCE = 'user-merchant1';
    public const MERCHANT_2_REFERENCE = 'user-merchant2';

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $merchantUser1 = new User();
        $merchantUser1->setUsername('merchant1');
        $password = $this->encoder->encodePassword($merchantUser1, 'merchant1');
        $merchantUser1->setPassword($password);
        $merchantUser1->setRoles(['ROLE_MERCHANT']);

        $this->addReference('user-merchant1', $merchantUser1);

        $merchantUser2 = new User();
        $merchantUser2->setUsername('merchant2');
        $password = $this->encoder->encodePassword($merchantUser2, 'merchant2');
        $merchantUser2->setPassword($password);
        $merchantUser2->setRoles(['ROLE_MERCHANT']);

        $this->addReference('user-merchant2', $merchantUser2);

        $customerUser1 = new User();
        $customerUser1->setUsername('customer1');
        $password = $this->encoder->encodePassword($customerUser1, 'customer1');
        $customerUser1->setPassword($password);
        $customerUser1->setRoles(['ROLE_CUSTOMER']);

        $customerUser2 = new User();
        $customerUser2->setUsername('customer2');
        $password = $this->encoder->encodePassword($customerUser2, 'customer2');
        $customerUser2->setPassword($password);
        $customerUser2->setRoles(['ROLE_CUSTOMER']);

        $manager->persist($merchantUser1);
        $manager->persist($merchantUser2);
        $manager->persist($customerUser1);
        $manager->persist($customerUser2);

        $manager->flush();
    }
}
