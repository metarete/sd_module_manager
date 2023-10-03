<?php

namespace App\Repository;

use App\Entity\AudioPrivacy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AudioPrivacy>
 *
 * @method AudioPrivacy|null find($id, $lockMode = null, $lockVersion = null)
 * @method AudioPrivacy|null findOneBy(array $criteria, array $orderBy = null)
 * @method AudioPrivacy[]    findAll()
 * @method AudioPrivacy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudioPrivacyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AudioPrivacy::class);
    }

//    /**
//     * @return AudioPrivacy[] Returns an array of AudioPrivacy objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AudioPrivacy
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
