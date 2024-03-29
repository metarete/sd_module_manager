<?php

namespace App\Repository;

use App\Entity\CondizioneLesione;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CondizioneLesione>
 *
 * @method CondizioneLesione|null find($id, $lockMode = null, $lockVersion = null)
 * @method CondizioneLesione|null findOneBy(array $criteria, array $orderBy = null)
 * @method CondizioneLesione[]    findAll()
 * @method CondizioneLesione[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CondizioneLesioneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CondizioneLesione::class);
    }

    public function add(CondizioneLesione $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CondizioneLesione $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CondizioneLesione[] Returns an array of CondizioneLesione objects
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

//    public function findOneBySomeField($value): ?CondizioneLesione
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
