<?php

namespace App\Repository;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Func;
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

    public function contaSchedeScadenzario(string $roleUser, int $idUser, ?string $stato = null): int
    {
        $totale = 0;

        if ($roleUser == 'ROLE_ADMIN' || $roleUser == 'ROLE_SUPERADMIN') {
            if ($stato == null || $stato == "") {
                $totale = $this->createQueryBuilder('s')
                    ->select('count(s.id)')
                    ->getQuery()
                    ->getSingleScalarResult();
            } else {
                $qb = $this->selectStatoSchedePai($stato);
                $totale = count($qb);
            }
        }
        if ($roleUser == 'ROLE_USER') {
            if ($stato == null || $stato == "") {
                $qb = $this->findUserSchedePai($idUser);
                $totale = count($qb);
            } else {
                $qb = $this->findUserSchedePai($idUser, $stato);
                $totale = count($qb);
            }
        }

        return $totale;
    }

    public function contaSchedePai(?int $operatore = null, ?string $stato = null, ?string $pratica = null): int
    {
        $totale = 0;

        if ($pratica == null || $pratica == "") {
            if ($operatore == null || $operatore == "") {
                if ($stato == null || $stato == "") {
                    $totale = $this->createQueryBuilder('s')
                        ->select('count(s.id)')
                        ->getQuery()
                        ->getSingleScalarResult();
                } else {
                    $qb = $this->createQueryBuilder('s')
                        ->Where('s.currentPlace = :stato')
                        ->setParameter('stato', $stato)
                        ->orderBy('s.id', 'ASC')
                        ->getQuery()
                        ->getResult();

                    $totale = count($qb);
                }
            }
            else{
                if ($stato == null || $stato == "") {
                    $qb = $this->findSchedePaiConOperatore($operatore);
                    $totale = count($qb);
                } else {
                    $qb = $this->findSchedePaiConOperatore($operatore);
                    $n = 0;
                    
                    foreach($qb as $scheda){
                        if($scheda->getCurrentPlace() == $stato)
                            $n = $n+1;
                    }
                    
                    $totale = $n;
                }
            }
        }
        else{
            if ($operatore == null || $operatore == "") {
                if ($stato == null || $stato == "") {
                    $qb = $this->findBy(['adiwebPratica' => $pratica], array('id' => 'DESC'));
                    $totale = count($qb);
                } else {
                    $qb = $this->findBy(['adiwebPratica' => $pratica], array('id' => 'DESC'));
                    $n = 0;

                    foreach($qb as $scheda){
                        if($scheda->getCurrentPlace() == $stato)
                            $n = $n+1;
                    }
                    
                    $totale = $n;
                }
            }
            else{
                if ($stato == null || $stato == "") {
                    $qb = $this->findBy(['adiwebPratica' => $pratica], array('id' => 'DESC'));
                    $qb1 = $this->findSchedePaiConOperatore($operatore);
                    $qb2 = array_intersect($qb, $qb1);
                    $totale = count($qb2);
                } else {
                    $qb = $this->findBy(['adiwebPratica' => $pratica], array('id' => 'DESC'));
                    $qb1 = $this->findSchedePaiConOperatore($operatore);
                    $qb2 = array_intersect($qb, $qb1);
                    $n = 0;

                    foreach($qb2 as $scheda){
                        if($scheda->getCurrentPlace() == $stato)
                            $n = $n+1;
                    }
                    
                    $totale = $n;
                    
                }
            }
        }

        return $totale;
    }

    public function contaSchedeInRicerca(?string $input = null): int
    {
        $totale = 0;

        if($input != null && $input != ""){
            $qb = $this->createQueryBuilder('s')
            ->andWhere('s.nomeProgetto LIKE :input')
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
            ->andWhere('s.nomeProgetto LIKE :input')
            ->setParameter('input', '%'.$input.'%')
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult();

            return $qb;
        }

        return null;
    }

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


        if ($stato != null) {
            $qb
                ->andWhere('s.currentPlace = :stato')
                ->setParameter('stato', $stato);
        }

        $qb->setFirstResult(($page - 1) * $schedePerPagina)->setMaxResults($schedePerPagina);
        return $qb->getQuery()
            ->getResult();
    }


    public function findOperatorePrincipaleScadenzario(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        $chiusuraForzata = 'chiusura_forzata';

        $query = $queryBuilder
            ->Where('s.idOperatorePrincipale = :id')
            ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusuraForzata'))
            ->setParameter('id', $idUser)
            ->setParameter('chiusa', $chiusa)
            ->setParameter('chiusaRinnovo', $chiusaRinnovo)
            ->setParameter('chiusuraForzata', $chiusuraForzata);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioInfScadenzario(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        $chiusuraForzata = 'chiusura_forzata';

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioInf', 's1')
            ->Where('s1.id = :id')
            ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusuraForzata'))
            ->setParameter('id', $idUser)
            ->setParameter('chiusa', $chiusa)
            ->setParameter('chiusaRinnovo', $chiusaRinnovo)
            ->setParameter('chiusuraForzata', $chiusuraForzata);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioTdrScadenzario(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        $chiusuraForzata = 'chiusura_forzata';

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioTdr', 's1')
            ->Where('s1.id = :id')
            ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusuraForzata'))
            ->setParameter('id', $idUser)
            ->setParameter('chiusa', $chiusa)
            ->setParameter('chiusaRinnovo', $chiusaRinnovo)
            ->setParameter('chiusuraForzata', $chiusuraForzata);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioLogScadenzario(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        $chiusuraForzata = 'chiusura_forzata';

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioLog', 's1')
            ->Where('s1.id = :id')
            ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusuraForzata'))
            ->setParameter('id', $idUser)
            ->setParameter('chiusa', $chiusa)
            ->setParameter('chiusaRinnovo', $chiusaRinnovo)
            ->setParameter('chiusuraForzata', $chiusuraForzata);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioAsaScadenzario(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        $chiusuraForzata = 'chiusura_forzata';

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioAsa', 's1')
            ->Where('s1.id = :id')
            ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusuraForzata'))
            ->setParameter('id', $idUser)
            ->setParameter('chiusa', $chiusa)
            ->setParameter('chiusaRinnovo', $chiusaRinnovo)
            ->setParameter('chiusuraForzata', $chiusuraForzata);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioOssScadenzario(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');
        $expr = $queryBuilder->expr();
        $chiusa = 'chiusa';
        $chiusaRinnovo = 'chiusa_con_rinnovo';
        $chiusuraForzata = 'chiusura_forzata';

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioOss', 's1')
            ->Where('s1.id = :id')
            ->andWhere($expr->neq('s.currentPlace', ':chiusa'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusaRinnovo'))
            ->andWhere($expr->neq('s.currentPlace', ':chiusuraForzata'))
            ->setParameter('id', $idUser)
            ->setParameter('chiusa', $chiusa)
            ->setParameter('chiusaRinnovo', $chiusaRinnovo)
            ->setParameter('chiusuraForzata', $chiusuraForzata);
        return $query->getQuery()
            ->getResult();
    }

    /*public function paginaElencoSchede(array $schedePais,int $schedePerPagina = null, int $page = null): array
    {
        
        $schedePais->setFirstResult(($page - 1) * $schedePerPagina)->setMaxResults($schedePerPagina);
        return $qb ->getQuery()
                ->getResult();
        
    }*/

    public function findOperatorePrincipaleSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $query = $queryBuilder
            ->Where('s.idOperatorePrincipale = :id')
            ->setParameter('id', $idUser);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioInfSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioInf', 's1')
            ->Where('s1.id = :id')
            ->setParameter('id', $idUser);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioTdrSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioTdr', 's1')
            ->Where('s1.id = :id')
            ->setParameter('id', $idUser);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioLogSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioLog', 's1')
            ->Where('s1.id = :id')
            ->setParameter('id', $idUser);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioAsaSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioAsa', 's1')
            ->Where('s1.id = :id')
            ->setParameter('id', $idUser);
        return $query->getQuery()
            ->getResult();
    }

    public function findOperatoreSecondarioOssSchedePai(int $idUser): array
    {
        $queryBuilder = $this->createQueryBuilder('s');

        $query = $queryBuilder
            ->innerJoin('s.idOperatoreSecondarioOss', 's1')
            ->Where('s1.id = :id')
            ->setParameter('id', $idUser);
        return $query->getQuery()
            ->getResult();
    }

    public function findSchedePaiConOperatore(int $idUser): array
    {
        // schede pai in cui è presente l'utente come operatore principale o secondario
        $principale = $this->findOperatorePrincipaleSchedePai($idUser);
        $secondarioInf = $this->findOperatoreSecondarioInfSchedePai($idUser);
        $secondarioTdr = $this->findOperatoreSecondarioTdrSchedePai($idUser);
        $secondarioLog = $this->findOperatoreSecondarioLogSchedePai($idUser);
        $secondarioAsa = $this->findOperatoreSecondarioAsaSchedePai($idUser);
        $secondarioOss = $this->findOperatoreSecondarioOssSchedePai($idUser);
        $schedaPais = array_unique(array_merge($principale, $secondarioInf, $secondarioTdr, $secondarioLog, $secondarioAsa, $secondarioOss));
        return $schedaPais;
    }


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

    public function findOneBySomeField($value): ?SchedaPAI
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.id = :id')
            ->setParameter('id', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function updateSchedaByIdprogetto($idProgetto, $assistito, $dataInizio, $dataFine, $nomeProgetto, $statoSDManager, $adiwebPratica, $adiwebProtocollo): void
    {
        $queryBuilder = $this->createQueryBuilder('u')
            ->update('App\Entity\EntityPAI\SchedaPAI', 'u')
            ->set('u.dataFine', ':dataFine')
            ->set('u.dataInizio', ':dataInizio')
            ->set('u.assistito', ':assistito')
            ->set('u.nomeProgetto', ':nomeProgetto')
            ->set('u.statoSDManager', ':statoSDManager')
            ->set('u.adiwebPratica', ':adiwebPratica')
            ->set('u.adiwebProtocollo', ':adiwebProtocollo')
            ->where('u.idProgetto = :idProgetto')
            ->setParameter('dataInizio', $dataInizio)
            ->setParameter('dataFine', $dataFine)
            ->setParameter('assistito', $assistito)
            ->setParameter('idProgetto', $idProgetto)
            ->setParameter('nomeProgetto', $nomeProgetto)
            ->setParameter('statoSDManager', $statoSDManager)
            ->setParameter('adiwebPratica', $adiwebPratica)
            ->setParameter('adiwebProtocollo', $adiwebProtocollo)
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
}
