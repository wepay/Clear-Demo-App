<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Order[]
     */
    public function findCustomerOrders($user)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.customer = :user')
            ->setParameter('user', $user)
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Order[]
     */
    public function findSellerOrders($merchant)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.merchant = :merchant')
            ->setParameter('merchant', $merchant)
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
