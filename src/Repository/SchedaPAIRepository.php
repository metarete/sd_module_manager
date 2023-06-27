<?php

namespace App\Repository;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

        if($roleUser == 'ROLE_ADMIN' || $roleUser == 'ROLE_SUPERADMIN'){
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
    private function findUserSchedePai(int $idUser, string $stato = null, int $schedePerPagina = null, int $page = null): array
    {
        $qb = $this->createQueryBuilder('s')

        //conversione in 6 query diverse che restituiscono un array. 
        //poi unione degli array che viene passato al template
        //eliminare subito schede chiuse e chiuse con rinnovo
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
        ->setParameter('id', $idUser)
        ->groupBy('s.id');
        

        if($stato != null){
            $qb 
            ->andWhere('s.currentPlace = :stato')
            ->setParameter('stato', $stato);  
        }
        
        $qb->setFirstResult(($page - 1) * $schedePerPagina)->setMaxResults($schedePerPagina);
        return $qb ->getQuery()
                ->getResult();
        
        
    }


    public function findOperatorePrincipaleSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        
        $query = $queryBuilder
        ->Where('s.idOperatorePrincipale = :id')
        ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
        ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
        ->setParameter('id', $idUser)
        ->setParameter('chiusa', $chiusa)
        ->setParameter('chiusaRinnovo', $chiusaRinnovo);
        return $query ->getQuery()
                ->getResult();
    }

    public function findOperatoreSecondarioInfSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        
        $query = $queryBuilder
        ->innerJoin('s.idOperatoreSecondarioInf', 's1')
        ->Where('s1.id = :id')
        ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
        ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
        ->setParameter('id', $idUser)
        ->setParameter('chiusa', $chiusa)
        ->setParameter('chiusaRinnovo', $chiusaRinnovo);
        return $query ->getQuery()
                ->getResult();
    }

    public function findOperatoreSecondarioTdrSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        
        $query = $queryBuilder
        ->innerJoin('s.idOperatoreSecondarioTdr', 's1')
        ->Where('s1.id = :id')
        ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
        ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
        ->setParameter('id', $idUser)
        ->setParameter('chiusa', $chiusa)
        ->setParameter('chiusaRinnovo', $chiusaRinnovo);
        return $query ->getQuery()
                ->getResult();
    }

    public function findOperatoreSecondarioLogSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        
        $query = $queryBuilder
        ->innerJoin('s.idOperatoreSecondarioLog', 's1')
        ->Where('s1.id = :id')
        ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
        ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
        ->setParameter('id', $idUser)
        ->setParameter('chiusa', $chiusa)
        ->setParameter('chiusaRinnovo', $chiusaRinnovo);
        return $query ->getQuery()
                ->getResult();
    }

    public function findOperatoreSecondarioAsaSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        
        $query = $queryBuilder
        ->innerJoin('s.idOperatoreSecondarioAsa', 's1')
        ->Where('s1.id = :id')
        ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
        ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
        ->setParameter('id', $idUser)
        ->setParameter('chiusa', $chiusa)
        ->setParameter('chiusaRinnovo', $chiusaRinnovo);
        return $query ->getQuery()
                ->getResult();
    }

    public function findOperatoreSecondarioOssSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        
        $query = $queryBuilder
        ->innerJoin('s.idOperatoreSecondarioOss', 's1')
        ->Where('s1.id = :id')
        ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
        ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
        ->setParameter('id', $idUser)
        ->setParameter('chiusa', $chiusa)
        ->setParameter('chiusaRinnovo', $chiusaRinnovo);
        return $query ->getQuery()
                ->getResult();
    }

    /*public function paginaElencoSchede(array $schedePais,int $schedePerPagina = null, int $page = null): array
    {
        
        $schedePais->setFirstResult(($page - 1) * $schedePerPagina)->setMaxResults($schedePerPagina);
        return $qb ->getQuery()
                ->getResult();
        
    }*/


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
    public function findStatoIdSchedePai(string $id, string $stato = null, int $schedePerPagina = null, int $page = null): array
    {
        $qb = $this->createQueryBuilder('s')

        ->leftJoin('s.idOperatorePrincipale', 's0')
        ->leftJoin('s.idOperatoreSecondarioInf', 's1')
        ->leftJoin('s.idOperatoreSecondarioTdr', 's2')
        ->leftJoin('s.idOperatoreSecondarioLog', 's3')
        ->leftJoin('s.idOperatoreSecondarioAsa', 's4')
        ->leftJoin('s.idOperatoreSecondarioOss', 's5')
        ->Where('s0.id = :id')
        ->orWhere('s1.id = :id')
        ->orWhere('s2.id = :id')
        ->orWhere('s3.id = :id')
        ->orWhere('s4.id = :id')
        ->orWhere('s5.id = :id')
        ->setParameter('id', $id);


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

    public function updateSchedaByIdprogetto($idProgetto,$assistito, $dataInizio, $dataFine, $nomeProgetto, $statoSDManager): void
    {
        $queryBuilder = $this->createQueryBuilder('u')
        ->update('App\Entity\EntityPAI\SchedaPAI', 'u')
        ->set('u.dataFine', ':dataFine')
        ->set('u.dataInizio', ':dataInizio')
        ->set('u.assistito', ':assistito')
        ->set('u.nomeProgetto', ':nomeProgetto')
        ->set('u.statoSDManager', ':statoSDManager')
        ->where('u.idProgetto = :idProgetto')
        ->setParameter('dataInizio', $dataInizio)
        ->setParameter('dataFine', $dataFine)
        ->setParameter('assistito', $assistito)
        ->setParameter('idProgetto', $idProgetto)
        ->setParameter('nomeProgetto', $nomeProgetto)
        ->setParameter('statoSDManager', $statoSDManager)
        ->getQuery()
        ->execute();
    }

    public function riattivaSchedaByIdprogetto($idProgetto, $idAssistito, $dataInizio, $dataFine, $nomeProgetto, $statoAttivo): void
    {
        $queryBuilder = $this->createQueryBuilder('u')
        ->update(null, null)
        ->set('u.dataFine', ':dataFine')
        ->set('u.dataInizio', ':dataInizio')
        ->set('u.idAssistito', ':idAssistito')
        ->set('u.nomeProgetto', ':nomeProgetto')
        ->set('u.currentPlace', ':currentPlace')
        ->where('u.idProgetto = :idProgetto')
        ->setParameter('dataInizio', $dataInizio)
        ->setParameter('dataFine', $dataFine)
        ->setParameter('idAssistito', $idAssistito)
        ->setParameter('idProgetto', $idProgetto)
        ->setParameter('nomeProgetto', $nomeProgetto)
        ->setParameter('currentPlace', $statoAttivo)
        ->getQuery()
        ->execute();
    }

    public function countByState($value): int
    {
        return $this->createQueryBuilder('u')
        ->select('count(u.id)')
        ->andWhere('u.currentPlace = :currentPlace')
        ->setParameter('currentPlace', $value)
        ->getQuery()
        ->getSingleScalarResult();
        ;
    }

   

}
