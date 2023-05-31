<?php

namespace App\Repository;

use App\Entity\PresidiAntidecubito;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PresidiAntidecubito>
 *
 * @method PresidiAntidecubito|null find($id, $lockMode = null, $lockVersion = null)
 * @method PresidiAntidecubito|null findOneBy(array $criteria, array $orderBy = null)
 * @method PresidiAntidecubito[]    findAll()
 * @method PresidiAntidecubito[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PresidiAntidecubitoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PresidiAntidecubito::class);
    }

    public function add(PresidiAntidecubito $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PresidiAntidecubito $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function deleteAll(){

        $query = $this->createQueryBuilder('e')
                 ->delete()
                 ->getQuery()
                 ->execute();
        return $query;
    }

//    /**
//     * @return PresidiAntidecubito[] Returns an array of PresidiAntidecubito objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PresidiAntidecubito
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
