<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function add(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);

        $this->add($user, true);
    }

    public function contaOperatori(): int
    {
        return $this->createQueryBuilder('s')
        ->select('count(s.id)')
        ->getQuery()
        ->getSingleScalarResult();

    }

    public function contaOperatoriInRicerca(?string $input = null): int
    {
        $totale = 0;

        if($input != null && $input != ""){
            $qb = $this->createQueryBuilder('s')
            ->orWhere('s.name LIKE :input')
            ->orWhere('s.email LIKE :input')
            ->orWhere('s.surname LIKE :input')
            ->setParameter('input', '%'.$input.'%')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();

            $totale = count($qb);
        }
        return $totale;
    }

    public function findByBarraRicerca(?string $input = null): array
    {
        
        if($input != null && $input != ""){
            $qb = $this->createQueryBuilder('s')
            ->orWhere('s.email LIKE :input')
            ->orWhere('s.name LIKE :input')
            ->orWhere('s.surname LIKE :input')
            ->setParameter('input', '%'.$input.'%')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();

            return $qb;
        }

        return null;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.id = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findEmailById($id): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.email')
            ->andWhere('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }
    public function findAllUsername(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.username')
            ->getQuery()
            ->getResult()
            ;

    }

    public function updateUserByUsername($username, $nome, $cognome, $cf,$email,$stato): void
    {
        $queryBuilder = $this->createQueryBuilder('u')
        ->update(null, null)
        ->set('u.name', ':nome')
        ->set('u.surname', ':cognome')
        ->set('u.cf', ':cf')
        ->set('u.email', ':email')
        ->set('u.stato', ':stato')
        ->where('u.username = :username')
        ->setParameter('nome', $nome)
        ->setParameter('cognome', $cognome)
        ->setParameter('cf', $cf)
        ->setParameter('email', $email)
        ->setParameter('stato', $stato)
        ->setParameter('username', $username)
        ->getQuery()
        ->execute();
    }
    

}
