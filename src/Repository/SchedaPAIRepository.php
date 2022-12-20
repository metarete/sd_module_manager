<?php

namespace App\Repository;

use App\Entity\EntityPAI\SchedaPAI;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\WhereClause;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SchedaPAI>
 *
 * @method SchedaPAI|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchedaPAI|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchedaPAI[]    findAll()
 * @method SchedaPAI[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchedaPAIRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SchedaPAI::class);
    }

    public function add(SchedaPAI $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SchedaPAI $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function contaSchedePai( string $roleUser, int $idUser, ?string $stato=null): int
    {
        $totale = 0;

        if($roleUser == 'ROLE_ADMIN'){
            if($stato == null || $stato==""){
                $totale = $this->createQueryBuilder('s')
                ->select('count(s.id)')
                ->getQuery()
                ->getSingleScalarResult();
        }   
            else{
                $qb = $this->selectStatoSchedePai($stato);
                $totale = count($qb);     
            }
        }
        if($roleUser == 'ROLE_USER'){
            if($stato == null || $stato==""){
                $qb = $this->findUserSchedePai($idUser);
                $totale = count($qb);
            }
            else{
                $qb = $this->findUserSchedePai($idUser, $stato);
                $totale = count($qb);
            }
        }
        
        return $totale;

    }



    //funzioni per utenti User
    public function findUserSchedePai(int $idUser, string $stato = null, int $schedePerPagina = null, int $page = null): array
    {
        $qb = $this->createQueryBuilder('s')

        ->leftJoin('s.idOperatoreSecondarioInf', 's1')
        ->leftJoin('s.idOperatoreSecondarioTdr', 's2')
        ->leftJoin('s.idOperatoreSecondarioLog', 's3')
        ->leftJoin('s.idOperatoreSecondarioAsa', 's4')
        ->leftJoin('s.idOperatoreSecondarioOss', 's5')
        ->Where('s.idOperatorePrincipale = :id')
        ->orWhere('s1.id = :id')
        ->orWhere('s2.id = :id')
        ->orWhere('s3.id = :id')
        ->orWhere('s4.id = :id')
        ->orWhere('s5.id = :id')
        ->setParameter('id', $idUser);


        if($stato != null){
            $qb 
            ->andWhere('s.currentPlace = :stato')
            ->setParameter('stato', $stato);  
        }
        
        $qb->setFirstResult(($page - 1) * $schedePerPagina)->setMaxResults($schedePerPagina);
        return $qb ->getQuery()
                ->getResult();
        
        
    }


    //funzioni per utenti admin
    public function selectStatoSchedePai(string $stato, int $page = null, int $schedePerPagina = null): array
    {
        
        $qb = $this->createQueryBuilder('s')

        ->Where('s.currentPlace = :stato')
        ->setParameter('stato', $stato)
        ->orderBy('s.id', 'ASC');
      
        $qb->setFirstResult(($page - 1) * $schedePerPagina)->setMaxResults($schedePerPagina);
        return $qb 
                ->getQuery()
                ->getResult();
        
    }
    public function findStatoUsernameSchedePai(string $username, string $stato = null, int $schedePerPagina = null, int $page = null): array
    {
        $qb = $this->createQueryBuilder('s')

        ->leftJoin('s.idOperatoreSecondarioInf', 's1')
        ->leftJoin('s.idOperatoreSecondarioTdr', 's2')
        ->leftJoin('s.idOperatoreSecondarioLog', 's3')
        ->leftJoin('s.idOperatoreSecondarioAsa', 's4')
        ->leftJoin('s.idOperatoreSecondarioOss', 's5')
        ->Where('s.idOperatorePrincipale = :username')
        ->orWhere('s1.username = :username')
        ->orWhere('s2.username = :username')
        ->orWhere('s3.username = :username')
        ->orWhere('s4.username = :username')
        ->orWhere('s5.username = :username')
        ->setParameter('username', $username);


        if($stato != null){
            $qb 
            ->andWhere('s.currentPlace = :stato')
            ->setParameter('stato', $stato);  
        }
        
        $qb->setFirstResult(($page - 1) * $schedePerPagina)->setMaxResults($schedePerPagina);
        return $qb ->getQuery()
                ->getResult();
        
        
    }

    /**
     * @return SchedaPAI[] Returns an array of SchedaPAI objects
    */
    public function findByState($value): array
    {
        return $this->createQueryBuilder('s')
           ->andWhere('s.currentPlace = :currentPlace')
            ->setParameter('currentPlace', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneBySomeField($value): ?SchedaPAI
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByState($value): ?SchedaPAI
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.currentPlace = :currentPlace')
            ->setParameter('currentPlace', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOneByProgetto($value): ?SchedaPAI
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.idProgetto = :idProgetto')
            ->setParameter('idProgetto', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function updateSchedaByIdprogetto($idProgetto,$idAssistito, $dataInizio, $dataFine): void
    {
        $queryBuilder = $this->createQueryBuilder('u')
        ->update(null, null)
        ->set('u.dataFine', ':dataFine')
        ->set('u.dataInizio', ':dataInizio')
        ->set('u.idAssistito', ':idAssistito')
        ->where('u.idProgetto = :idProgetto')
        ->setParameter('dataInizio', $dataInizio)
        ->setParameter('dataFine', $dataFine)
        ->setParameter('idAssistito', $idAssistito)
        ->setParameter('idProgetto', $idProgetto)
        ->getQuery()
        ->execute();
    }

    

   

}
