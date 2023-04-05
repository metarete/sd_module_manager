<?php

namespace App\Repository;

use App\Entity\Obiettivi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Obiettivi>
 *
 * @method Obiettivi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Obiettivi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Obiettivi[]    findAll()
 * @method Obiettivi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObiettiviRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Obiettivi::class);
    }

    public function add(Obiettivi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Obiettivi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function contaObiettivi(): int
    {
        return $this->createQueryBuilder('s')
        ->select('count(s.id)')
        ->getQuery()
        ->getSingleScalarResult();

    }

    //    /**
    //     * @return Obiettivi[] Returns an array of Obiettivi objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Obiettivi
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
