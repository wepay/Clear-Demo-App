<?php

namespace App\Form\Type;

use App\Entity\Order;
use App\Form\Transformer\ProductHiddenTransformer;
use App\Objects\Cart;
use App\Objects\MerchantInfoUS;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MerchantInfoUSType extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('socialSecurityNumber', TextType::class)
            ->add('dateOfBirth', BirthdayType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MerchantInfoUS::class,
            'method'     => 'POST'
        ]);
    }

    public function getParent()
    {
        return MerchantInfoType::class;
    }


}
