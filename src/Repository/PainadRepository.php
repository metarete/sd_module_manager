<?php

namespace App\Repository;

use App\Entity\EntityPAI\Painad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Painad>
 *
 * @method Painad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Painad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Painad[]    findAll()
 * @method Painad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PainadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Painad::class);
    }

    public function add(Painad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Painad $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function contaSchede(): int
    {
        return $this->createQueryBuilder('s')
        ->select('count(s.id)')
        ->getQuery()
        ->getSingleScalarResult();

    }

    public function findByPainadPerScheda($idSchedaPai): int
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->andWhere('b.schedaPAI = :schedaPaiId')
            ->setParameter('schedaPaiId', $idSchedaPai)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Painad[] Returns an array of Painad objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Painad
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
