<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $productsData = $this->getProductsData();

        foreach ($productsData as $productData) {
            $product = new Product();

            $product->setName($productData['name']);
            $product->setPrice($productData['price']);
            $product->setDescription($productData['description']);
            $product->setImage($productData['image']);
            $product->setOwner($this->getReference($productData['seller_key']));

            $manager->persist($product);
        }

        $manager->flush();
    }

    protected function getProductsData(): ?array
    {
        return [
            [
                'name'        => 'Product 1',
                'price'       => '10.00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc lobortis malesuada urna, at porta ligula dictum eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut nunc vitae dolor consectetur molestie at in est. Morbi sit amet turpis lacus. Vivamus ipsum magna, molestie eget orci eu, finibus accumsan est',
                'image'       => 'product_1.jpg',
                'seller_key'  => UserFixtures::MERCHANT_1_REFERENCE
            ],

            [
                'name'        => 'Product 2',
                'price'       => '20.50',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc lobortis malesuada urna, at porta ligula dictum eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut nunc vitae dolor consectetur molestie at in est. Morbi sit amet turpis lacus. Vivamus ipsum magna, molestie eget orci eu, finibus accumsan est',
                'image'       => 'product_2.jpg',
                'seller_key'  => UserFixtures::MERCHANT_1_REFERENCE
            ],

            [
                'name'        => 'Product 3',
                'price'       => '30.00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc lobortis malesuada urna, at porta ligula dictum eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut nunc vitae dolor consectetur molestie at in est. Morbi sit amet turpis lacus. Vivamus ipsum magna, molestie eget orci eu, finibus accumsan est',
                'image'       => 'product_3.jpg',
                'seller_key'  => UserFixtures::MERCHANT_1_REFERENCE
            ],

            [
                'name'        => 'Product 4',
                'price'       => '40.00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc lobortis malesuada urna, at porta ligula dictum eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut nunc vitae dolor consectetur molestie at in est. Morbi sit amet turpis lacus. Vivamus ipsum magna, molestie eget orci eu, finibus accumsan est',
                'image'       => 'product_4.jpg',
                'seller_key'  => UserFixtures::MERCHANT_1_REFERENCE
            ],

            [
                'name'        => 'Product 5',
                'price'       => '15.33',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc lobortis malesuada urna, at porta ligula dictum eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut nunc vitae dolor consectetur molestie at in est. Morbi sit amet turpis lacus. Vivamus ipsum magna, molestie eget orci eu, finibus accumsan est',
                'image'       => 'product_1.jpg',
                'seller_key'  => UserFixtures::MERCHANT_2_REFERENCE
            ],

            [
                'name'        => 'Product 6',
                'price'       => '25.00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc lobortis malesuada urna, at porta ligula dictum eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut nunc vitae dolor consectetur molestie at in est. Morbi sit amet turpis lacus. Vivamus ipsum magna, molestie eget orci eu, finibus accumsan est',
                'image'       => 'product_2.jpg',
                'seller_key'  => UserFixtures::MERCHANT_2_REFERENCE
            ],

            [
                'name'        => 'Product 7',
                'price'       => '35.00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc lobortis malesuada urna, at porta ligula dictum eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut nunc vitae dolor consectetur molestie at in est. Morbi sit amet turpis lacus. Vivamus ipsum magna, molestie eget orci eu, finibus accumsan est',
                'image'       => 'product_3.jpg',
                'seller_key'  => UserFixtures::MERCHANT_2_REFERENCE
            ],

            [
                'name'        => 'Product 8',
                'price'       => '45.00',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc lobortis malesuada urna, at porta ligula dictum eget. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse ut nunc vitae dolor consectetur molestie at in est. Morbi sit amet turpis lacus. Vivamus ipsum magna, molestie eget orci eu, finibus accumsan est',
                'image'       => 'product_4.jpg',
                'seller_key'  => UserFixtures::MERCHANT_2_REFERENCE
            ]
        ];
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
