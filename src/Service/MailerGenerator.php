<?php

namespace App\Service;

use Symfony\Component\Mime\Email;
use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;

class MailerGenerator
{
    private $mailer;
    private $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }


    public function EmailAdmin()
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $numeroSchedeNuove = $schedaPAIRepository->findOneByState('nuova');
        $numeroSchedeChiuse = $schedaPAIRepository->findOneByState('chiusa');
        $numeroSchedeChiuseConRinnovo = $schedaPAIRepository->findOneByState('chiusa_con_rinnovo');
        $utenti = $userRepository->findAll();
        $admin = [];
        for ($i = 0; $i < count($utenti); $i++) {
            $roles = $utenti[$i]->getRoles();
            if ($roles[0] == 'ROLE_ADMIN') {
                array_push($admin, $utenti[$i]);
            }
        }
        for ($j = 0; $j < count($admin); $j++) {
            $id = $admin[$j]->getId();
            $mail = $userRepository->findEmailById($id);
            $stringaMail = $mail[0];
            $stringaMail = implode(", ", $stringaMail);


            if ($numeroSchedeNuove != null && $numeroSchedeChiuse == null && $numeroSchedeChiuseConRinnovo == null) {
                $email = (new Email())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per admin')
                    ->text('Esiste almeno una scheda nello stato Nuova che deve essere approvata');

                $this->mailer->send($email);
            }
            if ($numeroSchedeNuove != null && $numeroSchedeChiuse != null && $numeroSchedeChiuseConRinnovo == null) {
                $email = (new Email())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per admin')
                    ->text('Esiste almeno una scheda nello stato Nuova che deve essere approvata e una scheda che è stata chiusa.');

                $this->mailer->send($email);
            }
            if ($numeroSchedeNuove != null && $numeroSchedeChiuse != null && $numeroSchedeChiuseConRinnovo != null) {
                $email = (new Email())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per admin')
                    ->text('Esiste almeno una scheda nello stato Nuova che deve essere approvata e una scheda che è stata chiusa.
                Esiste anche una scheda chiusa che necessita di un rinnovo: occorre attivarsi per creare un nuovo Progetto in SD Manager.');

                $this->mailer->send($email);
            }
            if ($numeroSchedeNuove == null && $numeroSchedeChiuse != null && $numeroSchedeChiuseConRinnovo != null) {
                $email = (new Email())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per admin')
                    ->text('Esiste almeno  una scheda che è stata chiusa.Esiste anche una scheda chiusa che necessita di un rinnovo: occorre attivarsi per creare un nuovo Progetto in SD Manager.');

                $this->mailer->send($email);
            }
            if ($numeroSchedeNuove == null && $numeroSchedeChiuse == null && $numeroSchedeChiuseConRinnovo != null) {
                $email = (new Email())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per admin')
                    ->text('Esiste una scheda chiusa che necessita di un rinnovo: occorre attivarsi per creare un nuovo Progetto in SD Manager.');

                $this->mailer->send($email);
            }
            if ($numeroSchedeNuove == null && $numeroSchedeChiuse != null && $numeroSchedeChiuseConRinnovo == null) {
                $email = (new Email())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per admin')
                    ->text('Esiste almeno  una scheda che è stata chiusa.');

                $this->mailer->send($email);
            }
            if ($numeroSchedeNuove != null && $numeroSchedeChiuse == null && $numeroSchedeChiuseConRinnovo != null) {
                $email = (new Email())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per admin')
                    ->text('Esiste almeno una scheda nello stato Nuova che deve essere approvata.
                Esiste anche una scheda chiusa che necessita di un rinnovo: occorre attivarsi per creare un nuovo Progetto in SD Manager.');

                $this->mailer->send($email);
            }
        }
    }

    public function EmailOperatore()
    {
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $arraySchedeApprovate = $schedaPAIRepository->findByState('approvata');
        $arraySchedeAttive = $schedaPAIRepository->findByState('attiva');
        $arraySchedeInAttesaDiChiusura = $schedaPAIRepository->findByState('in_attesa_di_chiusura');
        $arrayOperatori = $userRepository->findAll();
        $testoApprovata = '
        --Esiste almeno una scheda in stato approvata in cui sei operatore principale che deve essere attivata. Abilitare le scale, impostare la frequenza di compilazione e compilare la valutazione generale.';
        $testoRitardi = '
        --Ci sono delle di valutazione in ritardo rispetto alla frequenza stabilita. Compilarle al più presto.';
        $testoChiusura = '
        --Esistono una o più schede che necessitano di chiusura. Compilare le scale mancanti se necessario e la chiusura del servizio.';
        $testoAttiva = '
        -- Esiste almeno una scheda in stato attiva in cui manca almeno una valutazione professionale.';
        for ($i = 0; $i < count($arrayOperatori); $i++) {
            $idOperatore = $arrayOperatori[$i]->getId();
            $flagSchedaApprovata = false;
            $flagRitardi = false;
            $flagSchedeDaChiudere = false;
            $flagValutazioneProfessionale = false;
            for ($j = 0; $j < count($arraySchedeApprovate); $j++) {
                $idOperatorePrincipale = $arraySchedeApprovate[$j]->getIdOperatorePrincipale()->getId();
                if ($idOperatore == $idOperatorePrincipale) {
                    $flagSchedaApprovata = true;
                }
            }
            for ($t = 0; $t < count($arraySchedeAttive); $t++) {
                $idOperatorePrincipale = $arraySchedeAttive[$t]->getIdOperatorePrincipale()->getId();
                $idOperatoreSecondarioInf = $arraySchedeAttive[$t]->getIdOperatoreSecondarioInf()->toArray();
                $idOperatoreSecondarioTdr = $arraySchedeAttive[$t]->getIdOperatoreSecondarioTdr()->toArray();
                $idOperatoreSecondarioLog = $arraySchedeAttive[$t]->getIdOperatoreSecondarioLog()->toArray();
                $idOperatoreSecondarioAsa = $arraySchedeAttive[$t]->getIdOperatoreSecondarioAsa()->toArray();
                $idOperatoreSecondarioOss = $arraySchedeAttive[$t]->getIdOperatoreSecondarioOss()->toArray();
                $numeroValutazioneProfessionali = count($arraySchedeAttive[$t]->getIdValutazioneFiguraProfessionale()->toArray());

                for ($p = 0; $p < count($idOperatoreSecondarioInf); $p++) {
                    $idOperatoreSecondarioInf[$p] = $idOperatoreSecondarioInf[$p]->getId();
                }
                for ($p = 0; $p < count($idOperatoreSecondarioTdr); $p++) {
                    $idOperatoreSecondarioTdr[$p] = $idOperatoreSecondarioTdr[$p]->getId();
                }
                for ($p = 0; $p < count($idOperatoreSecondarioLog); $p++) {
                    $idOperatoreSecondarioLog[$p] = $idOperatoreSecondarioLog[$p]->getId();
                }
                for ($p = 0; $p < count($idOperatoreSecondarioAsa); $p++) {
                    $idOperatoreSecondarioAsa[$p] = $idOperatoreSecondarioAsa[$p]->getId();
                }
                for ($p = 0; $p < count($idOperatoreSecondarioOss); $p++) {
                    $idOperatoreSecondarioOss[$p] = $idOperatoreSecondarioOss[$p]->getId();
                }
                $idOperatoriTotali = array_merge($idOperatoreSecondarioInf,  $idOperatoreSecondarioTdr,  $idOperatoreSecondarioLog,  $idOperatoreSecondarioAsa,  $idOperatoreSecondarioOss);
                if ($idOperatore == $idOperatorePrincipale || in_array($idOperatore, $idOperatoreSecondarioInf) || in_array($idOperatore, $idOperatoreSecondarioTdr) || in_array($idOperatore, $idOperatoreSecondarioLog) || in_array($idOperatore, $idOperatoreSecondarioAsa) || in_array($idOperatore, $idOperatoreSecondarioOss)) {
                    if (count($idOperatoriTotali) > $numeroValutazioneProfessionali) {
                        $flagValutazioneProfessionale = true;
                    }

                    if ($arraySchedeAttive[$t]->getNumeroBarthelAdOggi() <= $arraySchedeAttive[$t]->getNumeroBarthelAdOggiCorretto() && $arraySchedeAttive[$t]->isAbilitaBarthel() == true) {
                        $flagRitardi = true;
                    } elseif ($arraySchedeAttive[$t]->getNumeroBradenAdOggi() <= $arraySchedeAttive[$t]->getNumeroBradenAdOggiCorretto() && $arraySchedeAttive[$t]->isAbilitaBraden() == true) {
                        $flagRitardi = true;
                    } elseif ($arraySchedeAttive[$t]->getNumeroSpmsqAdOggi() <= $arraySchedeAttive[$t]->getNumeroSpmsqAdOggiCorretto() && $arraySchedeAttive[$t]->isAbilitaSpmsq() == true) {
                        $flagRitardi = true;
                    } elseif ($arraySchedeAttive[$t]->getNumeroTinettiAdOggi() <= $arraySchedeAttive[$t]->getNumeroTinettiAdOggiCorretto() && $arraySchedeAttive[$t]->isAbilitaTinetti() == true) {
                        $flagRitardi = true;
                    } elseif ($arraySchedeAttive[$t]->getNumeroVasAdOggi() <= $arraySchedeAttive[$t]->getNumeroVasAdOggiCorretto() && $arraySchedeAttive[$t]->isAbilitaVas() == true) {
                        $flagRitardi = true;
                    } elseif ($arraySchedeAttive[$t]->getNumeroLesioniAdOggi() <= $arraySchedeAttive[$t]->getNumeroLesioniAdOggiCorretto() && $arraySchedeAttive[$t]->isAbilitaLesioni() == true) {
                        $flagRitardi = true;
                    }
                }
            }
            for ($z = 0; $z < count($arraySchedeInAttesaDiChiusura); $z++) {
                $idOperatorePrincipale = $arraySchedeInAttesaDiChiusura[$z]->getIdOperatorePrincipale()->getId();
                if ($idOperatore == $idOperatorePrincipale) {
                    $flagSchedeDaChiudere = true;
                }
            }

            $testoApprovata1 = $testoApprovata;
            $testoRitardi1 = $testoRitardi;
            $testoChiusura1 = $testoChiusura;
            $testoAttiva1 = $testoAttiva;
            if ($flagSchedaApprovata == false) {
                $testoApprovata1 = '';
            }
            if ($flagRitardi == false) {
                $testoRitardi1 = '';
            }
            if ($flagSchedeDaChiudere == false) {
                $testoChiusura1 = '';
            }
            if ($flagValutazioneProfessionale == false) {
                $testoAttiva1 = '';
            }
            $testoEmail = $testoApprovata1 . $testoRitardi1 . $testoChiusura1 . $testoAttiva1;
            if ($flagSchedaApprovata == false && $flagRitardi == false && $flagSchedeDaChiudere == false && $flagValutazioneProfessionale == false) {
                //non invio l'email. l'operatore non ha nulla da fare.
            } else {
                $mail = $userRepository->findEmailById($idOperatore);
                $stringaMail = $mail[0];
                $stringaMail = implode(", ", $stringaMail);
                $email = (new Email())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per operatori principali')
                    ->text($testoEmail);

                $this->mailer->send($email);
            }
        }
    }
}
