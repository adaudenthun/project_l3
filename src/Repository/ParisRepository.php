<?php

namespace App\Repository;

use App\Entity\Paris;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Paris|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paris|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paris[]    findAll()
 * @method Paris[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParisRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Paris::class);
    }

//    /**
//     * @return Paris[] Returns an array of Paris objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Paris
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
