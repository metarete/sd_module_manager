<?php

namespace App\Repository;

use App\Entity\EntityPAI\ChiusuraForzata;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ChiusuraForzata>
 *
 * @method ChiusuraForzata|null find($id, $lockMode = null, $lockVersion = null)
 * @method ChiusuraForzata|null findOneBy(array $criteria, array $orderBy = null)
 * @method ChiusuraForzata[]    findAll()
 * @method ChiusuraForzata[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ChiusuraForzataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChiusuraForzata::class);
    }

    public function add(ChiusuraForzata $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ChiusuraForzata $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ChiusuraForzata[] Returns an array of ChiusuraForzata objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ChiusuraForzata
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
