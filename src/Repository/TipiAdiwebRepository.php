<?php

namespace App\Repository;

use App\Entity\TipiAdiweb;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TipiAdiweb>
 *
 * @method TipiAdiweb|null find($id, $lockMode = null, $lockVersion = null)
 * @method TipiAdiweb|null findOneBy(array $criteria, array $orderBy = null)
 * @method TipiAdiweb[]    findAll()
 * @method TipiAdiweb[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TipiAdiwebRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TipiAdiweb::class);
    }

    public function add(TipiAdiweb $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TipiAdiweb $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return TipiAdiweb[] Returns an array of TipiAdiweb objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TipiAdiweb
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
