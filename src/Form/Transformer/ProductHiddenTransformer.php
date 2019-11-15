<?php

namespace App\Form\Transformer;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class ProductHiddenTransformer  implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param  Product|null $product
     * @return string
     */
    public function transform($productId)
    {
        if (!$productId) {
            return;
        }

        $product = $this->entityManager
            ->getRepository(Product::class)
            ->find($productId)
        ;

        if (null === $product) {
            throw new TransformationFailedException(sprintf(
                'A product with id "%s" does not exist!',
                $productId
            ));
        }

        return $product;
    }

    /**
     * @param  string $productId
     * @return Product|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($product)
    {
        if (null === $product) {
            return '';
        }

        return $product->getId();


    }
}