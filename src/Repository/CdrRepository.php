<?php

namespace App\Repository;

use App\Entity\EntityPAI\Cdr;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cdr>
 *
 * @method Cdr|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cdr|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cdr[]    findAll()
 * @method Cdr[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CdrRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cdr::class);
    }

    public function add(Cdr $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cdr $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCdrPerScheda($idSchedaPai): int
    {
        return $this->createQueryBuilder('b')
            ->select('count(b.id)')
            ->andWhere('b.schedaPAI = :schedaPaiId')
            ->setParameter('schedaPaiId', $idSchedaPai)
            ->getQuery()
            ->getSingleScalarResult();
    }
//    /**
//     * @return Cdr[] Returns an array of Cdr objects
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

//    public function findOneBySomeField($value): ?Cdr
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
