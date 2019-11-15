<?php

namespace App\DataFixtures;

use App\Entity\MerchantInfo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MerchantInfoFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $merchantInfo1 = new MerchantInfo();
        $merchantInfo1->setUser($this->getReference(UserFixtures::MERCHANT_1_REFERENCE));
        $merchantInfo1->setLegalEntityId('73c21cf0-71fd-4590-936e-4217d26124cf');
        $merchantInfo1->setAccountId('8781e95c-273b-4d8d-bba9-e0ee7091d7c8');
        $merchantInfo1->setPayoutId('00000000-0000-0000-0000-0000013380ec');
        $merchantInfo1->setName('Merchant 1');
        $merchantInfo1->setCountry('US');

        $manager->persist($merchantInfo1);

        $merchantInfo2 = new MerchantInfo();
        $merchantInfo2->setUser($this->getReference(UserFixtures::MERCHANT_2_REFERENCE));
        $merchantInfo2->setLegalEntityId('a0570c41-21af-4e6c-8fbe-7797a4f64a3f');
        $merchantInfo2->setAccountId('6b97b51a-2af7-4a6e-bb78-b5d00e9f0aeb');
        $merchantInfo2->setPayoutId('00000000-0000-0000-0000-000001337149');
        $merchantInfo2->setName('Merchant 2');
        $merchantInfo2->setCountry('US');

        $manager->persist($merchantInfo2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}