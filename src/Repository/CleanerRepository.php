<?php

namespace App\Repository;

use App\Entity\Cleaner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Cleaner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cleaner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cleaner[]    findAll()
 * @method Cleaner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CleanerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cleaner::class);
    }

    public function findForPager()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->getQuery();
    }

    public function findByDiff($string, $category, $maxPrice, $minPrice)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.brand like :string')
            ->orWhere('c.model like :string')
            ->setParameter('string', $string)
            ->orWhere('c.category=:category')
            ->setParameter('category', $category)
            ->andWhere('c.price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice)
            ->andWhere('c.price >= :minPrice')
            ->setParameter('minPrice', $minPrice)
            ->getQuery()
            ->getResult();
    }

//    public function find($maxPrice, $minPrice, $string, $category){
//        return $this->createQueryBuilder('c')
//            ->select('c')
//            ->getQuery();
//    }

    // /**
    //  * @return Cleaner[] Returns an array of Cleaner objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Cleaner
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
