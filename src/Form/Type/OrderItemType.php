<?php

namespace App\Form\Type;

use App\Entity\OrderItem;
use App\Form\Transformer\ProductHiddenTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{

    /**
     * @var ProductHiddenTransformer
     */
    private $transformer;

    /**
     * @param ProductHiddenTransformer $transformer
     */
    public function __construct(ProductHiddenTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', HiddenType::class)
            ->add('amount', TextType::class, [
                'attr' => [
                    'data-role' => 'amount',
                    'readonly'  => true
                ]
            ])
        ;

        $builder
            ->get('product')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderItem::class,
        ]);
    }
}