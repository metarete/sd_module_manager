<?php

namespace App\Repository;

use App\Entity\Diagnosi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Diagnosi>
 *
 * @method Diagnosi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Diagnosi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Diagnosi[]    findAll()
 * @method Diagnosi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiagnosiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Diagnosi::class);
    }

    public function add(Diagnosi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Diagnosi $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function contaDiagnosi(): int
    {
        return $this->createQueryBuilder('s')
        ->select('count(s.id)')
        ->getQuery()
        ->getSingleScalarResult();

    }

    public function deleteAll(){

        $query = $this->createQueryBuilder('e')
                 ->delete()
                 ->getQuery()
                 ->execute();
        return $query;
    }

    public function findBySerchBar(string $input): array
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.descrizione LIKE :input')
            ->setParameter('input', '%'.$input.'%')
            ->getQuery()
            ->getResult()
        ;
    }
    //    /**
    //     * @return Diagnosi[] Returns an array of Diagnosi objects
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

    //    public function findOneBySomeField($value): ?Diagnosi
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
