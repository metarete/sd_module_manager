<?php

namespace App\Repository;

use App\Entity\Paziente;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Paziente>
 *
 * @method Paziente|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paziente|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paziente[]    findAll()
 * @method Paziente[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PazienteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Paziente::class);
    }

    public function add(Paziente $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Paziente $entity, bool $flush = false): void
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
    public function findOneByCf($value): ?Paziente
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.codiceFiscale = :codiceFiscale')
            ->setParameter('codiceFiscale', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function updateAssistitiByCf($cf, $nome, $cognome, $indirizzo, $comune, $provincia, $cap): void
    {
        $queryBuilder = $this->createQueryBuilder('u')
        ->update(null, null)
        ->set('u.nome', ':nome')
        ->set('u.cognome', ':cognome')
        ->set('u.indirizzo', ':indirizzo')
        ->set('u.comune', ':comune')
        ->set('u.provincia', ':provincia')
        ->set('u.cap', ':cap')
        ->where('u.codiceFiscale = :cf')
        ->setParameter('nome', $nome)
        ->setParameter('cognome', $cognome)
        ->setParameter('indirizzo', $indirizzo)
        ->setParameter('comune', $comune)
        ->setParameter('provincia', $provincia)
        ->setParameter('cap', $cap)
        ->setParameter('cf', $cf)
        ->getQuery()
        ->execute();
    }

    public function getNameById($value): string
    {
        return $this->createQueryBuilder('u')
        ->select('u.nome')
        ->where('u.idSdManager = :id')
        ->setParameter('id', $value)
        ->getQuery()
        ->getSingleScalarResult();
    }

    public function getSurnameById($value): string
    {
        return $this->createQueryBuilder('u')
        ->select('u.cognome')
        ->where('u.idSdManager = :id')
        ->setParameter('id', $value)
        ->getQuery()
        ->getSingleScalarResult();
    }

//    /**
//     * @return Paziente[] Returns an array of Paziente objects
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

    public function findOneById($value): ?Paziente
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idSdManager = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
