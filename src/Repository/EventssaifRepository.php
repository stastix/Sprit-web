<?php

namespace App\Repository;

use App\Entity\Eventssaif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Eventssaif>
 *
 * @method Eventssaif|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eventssaif|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eventssaif[]    findAll()
 * @method Eventssaif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventssaifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Eventssaif::class);
    }

//    /**
//     * @return Eventssaif[] Returns an array of Eventssaif objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Eventssaif
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
