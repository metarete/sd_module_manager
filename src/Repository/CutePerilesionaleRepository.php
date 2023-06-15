<?php

namespace App\Repository;

use App\Entity\CutePerilesionale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CutePerilesionale>
 *
 * @method CutePerilesionale|null find($id, $lockMode = null, $lockVersion = null)
 * @method CutePerilesionale|null findOneBy(array $criteria, array $orderBy = null)
 * @method CutePerilesionale[]    findAll()
 * @method CutePerilesionale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CutePerilesionaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CutePerilesionale::class);
    }

    public function add(CutePerilesionale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CutePerilesionale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return CutePerilesionale[] Returns an array of CutePerilesionale objects
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

//    public function findOneBySomeField($value): ?CutePerilesionale
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
