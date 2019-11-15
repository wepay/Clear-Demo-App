<?php

namespace App\Repository;

use App\Entity\MerchantInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MerchantInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method MerchantInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method MerchantInfo[]    findAll()
 * @method MerchantInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MerchantInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MerchantInfo::class);
    }

    // /**
    //  * @return MerchantInfo[] Returns an array of MerchantInfo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MerchantInfo
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
