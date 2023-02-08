<?php

namespace App\Repository;

use App\Entity\EntityPAI\ValutazioneGenerale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ValutazioneGenerale>
 *
 * @method ValutazioneGenerale|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValutazioneGenerale|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValutazioneGenerale[]    findAll()
 * @method ValutazioneGenerale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValutazioneGeneraleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValutazioneGenerale::class);
    }

    public function add(ValutazioneGenerale $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ValutazioneGenerale $entity, bool $flush = false): void
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

//    /**
//     * @return ValutazioneGenerale[] Returns an array of ValutazioneGenerale objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ValutazioneGenerale
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
