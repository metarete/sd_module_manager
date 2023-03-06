<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

class MailerGenerator
{
    private $mailer;
    private $entityManager;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
    }

    private function creaTestoEmailNuove($testo, $schede): array
    {
        if ($schede != null) {
            for ($i = 0; $i < count($schede); $i++) {
                $riga = [
                    "id" => $schede[$i]->getId(),
                    "data_inizio" => $schede[$i]->getDataInizio()->format('d-m-Y'),
                    "data_fine" => $schede[$i]->getDataFine()->format('d-m-Y'),
                    "assistito" => $schede[$i]->getNomeAssistito() . "  " . $schede[$i]->getCognomeAssistito(),
                    "stato" => $schede[$i]->getCurrentPlace(),
                    "link" => 'http://localhost:54001/scheda_pai/'];
                array_push($testo ,$riga);
            }
        }
        return $testo;
    }
    private function creaTestoEmailChiuse($testo, $schede): array
    {
        if ($schede != null) {
            for ($i = 0; $i < count($schede); $i++) {
                $riga = [
                    "id" => $schede[$i]->getId(),
                    "data_inizio" => $schede[$i]->getDataInizio()->format('d-m-Y'),
                    "data_fine" => $schede[$i]->getDataFine()->format('d-m-Y'),
                    "assistito" => $schede[$i]->getNomeAssistito() . "  " . $schede[$i]->getCognomeAssistito(),
                    "stato" => $schede[$i]->getCurrentPlace(),
                    "link" => 'http://localhost:54001/scheda_pai/'];
                array_push($testo ,$riga);
            }
        }
        return $testo;
    }
    private function creaTestoEmailChiuseConRinnovo($testo, $schede): array
    {
        if ($schede != null) {
            for ($i = 0; $i < count($schede); $i++) {
                $riga = [
                    "id" => $schede[$i]->getId(),
                    "data_inizio" => $schede[$i]->getDataInizio()->format('d-m-Y'),
                    "data_fine" => $schede[$i]->getDataFine()->format('d-m-Y'),
                    "assistito" => $schede[$i]->getNomeAssistito() . "  " . $schede[$i]->getCognomeAssistito(),
                    "stato" => $schede[$i]->getCurrentPlace(),
                    "link" => 'https://demo.sdmanager.it/index.php?module=Servizi.Domiciliari&func=progetti_edit&type=admin'];
                array_push($testo ,$riga);
            }
        }
        return $testo;
    }


    public function EmailAdmin()
    {

        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $schedeNuove = $schedaPAIRepository->findByState('nuova');
        $schedeChiuse = $schedaPAIRepository->findByState('chiusa');
        $schedeChiuseConRinnovo = $schedaPAIRepository->findByState('chiusa_con_rinnovo');
        $utenti = $userRepository->findAll();
        $admin = [];
        $testoEmailNuove = [];
        $testoEmailChiuse = [];
        $testoEmailChiuseConRinnovo = [];
        $testoEmailNuove = $this->creaTestoEmailNuove($testoEmailNuove, $schedeNuove);
        $testoEmailChiuse = $this->creaTestoEmailChiuse($testoEmailChiuse, $schedeChiuse);
        $testoEmailChiuseConRinnovo = $this->creaTestoEmailChiuseConRinnovo($testoEmailChiuseConRinnovo, $schedeChiuseConRinnovo);
        
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


            $email = (new TemplatedEmail())
                ->from('tecnico@metarete.it')
                ->to($stringaMail)
                ->subject('Email per admin')
                ->htmlTemplate("/email_admin.html.twig")
                    ->context([
                        "testoEmailNuove" => $testoEmailNuove,
                        "testoEmailChiuse" => $testoEmailChiuse,
                        "testoEmailChiuseConRinnovo" => $testoEmailChiuseConRinnovo,
                        "schedeNuove" =>  $schedeNuove,
                        "schedeChiuse" => $schedeChiuse,
                        "schedeChiuseConRinnovo" => $schedeChiuseConRinnovo,
                        
                    ]);



            $this->mailer->send($email);
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
        Attiva le scale seguenti in cui sei assegnato come operatore principale: abilita le scale di valutazione, imposta la frequenza di compilazione e compila la valutazione generale.';
        $testoRitardi = '
        Ci sono delle schede di valutazione in ritardo rispetto alla frequenza stabilita.';
        $testoChiusura = '
        Le seguenti schede necessitano di chiusura poichè scadute. Compilare le scale mancanti se necessario e la chiusura del servizio.';
        $testoAttiva = '
        Le seguenti schede attive hanno delle schede di valutazione professionale mancanti; compilarle al più presto.';
        for ($i = 0; $i < count($arrayOperatori); $i++) {
            $idOperatore = $arrayOperatori[$i]->getId();
            $flagSchedaApprovata = false;
            $descrizioneSchedeApprovate = [];
            $descrizioneRitardi =  [];
            $descrizioneSchedeDaChiudere = [];
            $descrizioneValutazioneProfessionale = [];
            $flagRitardi = false;
            $flagSchedeDaChiudere = false;
            $flagValutazioneProfessionale = false;
            for ($j = 0; $j < count($arraySchedeApprovate); $j++) {
                $idOperatorePrincipale = $arraySchedeApprovate[$j]->getIdOperatorePrincipale()->getId();
                if ($idOperatore == $idOperatorePrincipale) {
                    $flagSchedaApprovata = true;
                    $riga = [
                        "id" => $arraySchedeApprovate[$j]->getId(),
                        "data_inizio" => $arraySchedeApprovate[$j]->getDataInizio()->format('d-m-Y'),
                        "data_fine" => $arraySchedeApprovate[$j]->getDataFine()->format('d-m-Y'),
                        "assistito" => $arraySchedeApprovate[$j]->getNomeAssistito() . "  " . $arraySchedeApprovate[$j]->getCognomeAssistito(),
                        "stato" => $arraySchedeApprovate[$j]->getCurrentPlace(),
                        "link" => 'http://localhost:54001/scadenzario/'];
                    array_push($descrizioneSchedeApprovate ,$riga);
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
                        $valore = count($idOperatoriTotali) - $numeroValutazioneProfessionali;
                        $riga = [
                            "id" => $arraySchedeAttive[$t]->getId(),
                            "data_inizio" => $arraySchedeAttive[$t]->getDataInizio()->format('d-m-Y'),
                            "data_fine" => $arraySchedeAttive[$t]->getDataFine()->format('d-m-Y'),
                            "assistito" => $arraySchedeAttive[$t]->getNomeAssistito() . "  " . $arraySchedeAttive[$t]->getCognomeAssistito(),
                            "valore" => $valore,
                        ];
                        array_push($descrizioneValutazioneProfessionale, $riga);
                    }

                    $riga = [ 
                        "data_inizio" => $arraySchedeAttive[$t]->getDataInizio()->format('d-m-Y'),
                        "data_fine" => $arraySchedeAttive[$t]->getDataFine()->format('d-m-Y'),
                        "assistito" => $arraySchedeAttive[$t]->getNomeAssistito() . "  " . $arraySchedeAttive[$t]->getCognomeAssistito(),
                        "barthel" => "Nessuna",
                        "braden" => "Nessuna",
                        "spmsq" => "Nessuna",
                        "tinetti" => "Nessuna",
                        "vas" => "Nessuna",
                        "lesioni" => "Nessuna",
                    ];

                    $arraySchedeAttive[$t]->setBarthelNumberToday();
                    $arraySchedeAttive[$t]->setCorrectBarthelNumberToday();
                    $arraySchedeAttive[$t]->setBradenNumberToday();
                    $arraySchedeAttive[$t]->setCorrectBradenNumberToday();
                    $arraySchedeAttive[$t]->setSpmsqNumberToday();
                    $arraySchedeAttive[$t]->setCorrectSpmsqNumberToday();
                    $arraySchedeAttive[$t]->setTinettiNumberToday();
                    $arraySchedeAttive[$t]->setCorrectTinettiNumberToday(); 
                    $arraySchedeAttive[$t]->setVasNumberToday();
                    $arraySchedeAttive[$t]->setCorrectVasNumberToday();
                    $arraySchedeAttive[$t]->setLesioniNumberToday();
                    $arraySchedeAttive[$t]->setCorrectLesioniNumberToday();
                    
                    if ($arraySchedeAttive[$t]->getBarthelNumberToday() <= $arraySchedeAttive[$t]->getCorrectBarthelNumberToday() && $arraySchedeAttive[$t]->isAbilitaBarthel() == true) {
                        $flagRitardi = true;
                        $riga["barthel"] = 'si';
                        //$numeroBarthelInRitardo =  (int)($arraySchedeAttive[$t]->getNumeroBarthelCorretto()/($arraySchedeAttive[$t]->getNumeroBarthelCorretto()- $arraySchedeAttive[$t]->getNumeroBarthelAdOggi()));
                        //$descrizioneRitardi = $descrizioneRitardi .'-Barthel =>  '  . '<a href=' . 'http://localhost:54001/barthel/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Barthel</a>'.'<br>';
                    } if ($arraySchedeAttive[$t]->getBradenNumberToday() <= $arraySchedeAttive[$t]->getCorrectBradenNumberToday() && $arraySchedeAttive[$t]->isAbilitaBraden() == true) {
                        $flagRitardi = true;
                        $riga["braden"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Braden =>  '  . '<a href=' . 'http://localhost:54001/braden/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Braden</a>'.'<br>';
                    } if ($arraySchedeAttive[$t]->getSpmsqNumberToday() <= $arraySchedeAttive[$t]->getCorrectSpmsqNumberToday() && $arraySchedeAttive[$t]->isAbilitaSpmsq() == true) {
                        $flagRitardi = true;
                        $riga["spmsq"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Spmsq =>  ' . '<a href=' . 'http://localhost:54001/spmsq/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Spmsq</a>'.'<br>';
                    } if ($arraySchedeAttive[$t]->getTinettiNumberToday() <= $arraySchedeAttive[$t]->getCorrectTinettiNumberToday() && $arraySchedeAttive[$t]->isAbilitaTinetti() == true) {
                        $flagRitardi = true;
                        $riga["tinetti"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Tinetti =>  ' . '<a href=' . 'http://localhost:54001/tinetti/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Tinetti</a>'.'<br>';
                    } if ($arraySchedeAttive[$t]->getVasNumberToday() <= $arraySchedeAttive[$t]->getCorrectVasNumberToday() && $arraySchedeAttive[$t]->isAbilitaVas() == true) {
                        $flagRitardi = true;
                        $riga["vas"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Vas =>  ' . '<a href=' . 'http://localhost:54001/vas/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Vas</a>'.'<br>';
                    } if ($arraySchedeAttive[$t]->getLesioniNumberToday() <= $arraySchedeAttive[$t]->getCorrectLesioniNumberToday() && $arraySchedeAttive[$t]->isAbilitaLesioni() == true) {
                        $flagRitardi = true;
                        $riga["lesioni"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Lesioni =>  ' . '<a href=' . 'http://localhost:54001/lesioni/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Lesioni</a>'.'<br>';
                    }
                    if($riga["barthel"] == 'si' || $riga["braden"] == 'si' || $riga["spmsq"] == 'si' || $riga["tinetti"] == 'si' || $riga["vas"] == 'si' || $riga["lesioni"] == 'si'){
                        array_push($descrizioneRitardi, $riga);
                    }
                }
            }
            for ($z = 0; $z < count($arraySchedeInAttesaDiChiusura); $z++) {
                $idOperatorePrincipale = $arraySchedeInAttesaDiChiusura[$z]->getIdOperatorePrincipale()->getId();
                if ($idOperatore == $idOperatorePrincipale) {
                    $flagSchedeDaChiudere = true;
                    $riga = [
                        "id" => $arraySchedeInAttesaDiChiusura[$z]->getId(),
                        "data_inizio" => $arraySchedeInAttesaDiChiusura[$z]->getDataInizio()->format('d-m-Y'),
                        "data_fine" => $arraySchedeInAttesaDiChiusura[$z]->getDataFine()->format('d-m-Y'),
                        "assistito" => $arraySchedeInAttesaDiChiusura[$z]->getNomeAssistito() . "  " . $arraySchedeInAttesaDiChiusura[$z]->getCognomeAssistito(),
                        "stato" => $arraySchedeInAttesaDiChiusura[$z]->getCurrentPlace(),
                    ];
                    array_push($descrizioneSchedeDaChiudere, $riga);
                }
            }

            $testoApprovata1 = $testoApprovata;
            $testoRitardi1 = $testoRitardi;
            $testoChiusura1 = $testoChiusura;
            $testoAttiva1 = $testoAttiva;
            if ($flagSchedaApprovata == false) {
                $testoApprovata1 = '';
                $descrizioneSchedeApprovate = [];
            }
            if ($flagRitardi == false) {
                $testoRitardi1 = '';
                $descrizioneRitardi = [];
            }
            if ($flagSchedeDaChiudere == false) {
                $testoChiusura1 = '';
                $descrizioneSchedeDaChiudere = [];
            }
            if ($flagValutazioneProfessionale == false) {
                $testoAttiva1 = '';
                $descrizioneValutazioneProfessionale = [];
            }
            if ($flagSchedaApprovata == false && $flagRitardi == false && $flagSchedeDaChiudere == false && $flagValutazioneProfessionale == false) {
                //non invio l'email. l'operatore non ha nulla da fare.
            } else {
                $mail = $userRepository->findEmailById($idOperatore);
                $stringaMail = $mail[0];
                $stringaMail = implode(", ", $stringaMail);
                $testoEmailOperatori = "/email_operatori.html.twig";
                $email = (new TemplatedEmail())
                    ->from('tecnico@metarete.it')
                    ->to($stringaMail)
                    ->subject('Email per operatori')
                    ->htmlTemplate($testoEmailOperatori)
                    ->context([
                        'testoApprovata1' => $testoApprovata1,
                        'descrizioneSchedeApprovate' => $descrizioneSchedeApprovate,
                        'testoRitardi1' => $testoRitardi1,
                        'descrizioneRitardi' => $descrizioneRitardi,
                        'testoChiusura1' => $testoChiusura1,
                        'descrizioneSchedeDaChiudere' => $descrizioneSchedeDaChiudere,
                        'testoAttiva1' => $testoAttiva1,
                        'descrizioneValutazioneProfessionale' => $descrizioneValutazioneProfessionale,
                        
                    ]);


                $this->mailer->send($email);
            }
        }
    }
}
