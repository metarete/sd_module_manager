<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailerGenerator
{
    private $mailer;
    private $entityManager;
    private $params;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager, ParameterBagInterface $params)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->params = $params;
    }

    private function creaTestoEmailNuove($testo, $schede): array
    {
        $url = $this->params->get('app.site_url');
        if ($schede != null) {
            for ($i = 0; $i < count($schede); $i++) {
                $riga = [
                    "id" => $schede[$i]->getId(),
                    "nome_progetto" => $schede[$i]->getNomeProgetto(),
                    "data_inizio" => $schede[$i]->getDataInizio()->format('d/m/Y'),
                    "data_fine" => $schede[$i]->getDataFine()->format('d/m/Y'),
                    "assistito" => $schede[$i]->getNomeAssistito() . "  " . $schede[$i]->getCognomeAssistito(),
                    "stato" => $schede[$i]->getCurrentPlace(),
                    "link" => $url . '/scheda_pai/'
                ];
                array_push($testo, $riga);
            }
        }
        return $testo;
    }
    private function creaTestoEmailChiuse($testo, $schede): array
    {
        $url = $this->params->get('app.site_url');
        if ($schede != null) {
            for ($i = 0; $i < count($schede); $i++) {
                $riga = [
                    "id" => $schede[$i]->getId(),
                    "nome_progetto" => $schede[$i]->getNomeProgetto(),
                    "data_inizio" => $schede[$i]->getDataInizio()->format('d/m/Y'),
                    "data_fine" => $schede[$i]->getDataFine()->format('d/m/Y'),
                    "assistito" => $schede[$i]->getNomeAssistito() . "  " . $schede[$i]->getCognomeAssistito(),
                    "stato" => $schede[$i]->getCurrentPlace(),
                    "link" => $url . '/scheda_pai/'
                ];
                array_push($testo, $riga);
            }
        }
        return $testo;
    }
    private function creaTestoEmailChiuseConRinnovo($testo, $schede): array
    {
        $console = $this->params->get('app.ws_sdmanager_console_id');
        if ($schede != null) {
            for ($i = 0; $i < count($schede); $i++) {
                $riga = [
                    "id" => $schede[$i]->getId(),
                    "nome_progetto" => $schede[$i]->getNomeProgetto(),
                    "data_inizio" => $schede[$i]->getDataInizio()->format('d/m/Y'),
                    "data_fine" => $schede[$i]->getDataFine()->format('d/m/Y'),
                    "assistito" => $schede[$i]->getNomeAssistito() . "  " . $schede[$i]->getCognomeAssistito(),
                    "motivazione" => $schede[$i]->getIdChiusuraServizio()->getConclusioni(),
                    "stato" => $schede[$i]->getCurrentPlace(),
                    "link" => 'https://' . $console . '.sdmanager.it/index.php?module=Servizi.Domiciliari&func=progetti_edit&type=admin'
                ];
                array_push($testo, $riga);
            }
        }
        return $testo;
    }


    public function EmailAdmin()
    {
        $url = $this->params->get('app.site_url');
        $sender = $this->params->get('app.mailer_notification_sender');
        $img = file_get_contents(
            __DIR__ . "/../../public/image/logoCoop.jpg"
        );
        $logoCoop = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/calendar-day-solid.png"
        );
        $calendarIcon = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/09461c6c-3517-429e-99a2-64810982a104.png"
        );
        $separatoreTop = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/next_1.png"
        );
        $frecciaLink = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/bottom_rounded_15.png"
        );
        $separatoreDown = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/marker-solid.png"
        );
        $immagineNuove = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/circle-xmark-solid.png"
        );
        $immagineChiuse = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/registered-solid.png"
        );
        $immagineChiuseConRinnovo = base64_encode($img);
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
            $operatore = $admin[$j];
            $id = $admin[$j]->getId();
            $mail = $userRepository->findEmailById($id);
            $stringaMail = $mail[0];
            $stringaMail = implode(", ", $stringaMail);

            if ($schedeNuove == null && $schedeChiuse == null && $schedeChiuseConRinnovo == null) {
                //non c'è nulla da fare. non mando la mail
            } else {


                $email = (new TemplatedEmail())
                    ->from($sender)
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
                        "logoCoop" => $logoCoop,
                        "calendarIcon" => $calendarIcon,
                        "separatoreTop" => $separatoreTop,
                        "frecciaLink" => $frecciaLink,
                        "separatoreDown" => $separatoreDown,
                        "immagineNuove" => $immagineNuove,
                        "immagineChiuse" => $immagineChiuse,
                        "immagineChiuseConRinnovo" => $immagineChiuseConRinnovo,
                        "url" => $url,
                        "operatore" => $operatore
                    ]);



                $this->mailer->send($email);
            }
        }
    }

    public function EmailOperatore()
    {
        $url = $this->params->get('app.site_url');
        $sender = $this->params->get('app.mailer_notification_sender');
        $img = file_get_contents(
            __DIR__ . "/../../public/image/logoCoop.jpg"
        );
        $logoCoop = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/calendar-day-solid.png"
        );
        $calendarIcon = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/09461c6c-3517-429e-99a2-64810982a104.png"
        );
        $separatoreTop = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/next_1.png"
        );
        $frecciaLink = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/bottom_rounded_15.png"
        );
        $separatoreDown = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/logoCoop.jpg"
        );
        $logoCoop = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/circle-check-solid.png"
        );
        $immagineAttive = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/stopwatch-solid.png"
        );
        $immagineScaleRitardi = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/user-doctor-solid.png"
        );
        $immagineValutazioni = base64_encode($img);
        $img = file_get_contents(
            __DIR__ . "/../../public/image/hourglass-start-solid.png"
        );
        $immagineScadute = base64_encode($img);
        
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
                        "nome_progetto" => $arraySchedeApprovate[$j]->getNomeProgetto(),
                        "data_inizio" => $arraySchedeApprovate[$j]->getDataInizio()->format('d/m/Y'),
                        "data_fine" => $arraySchedeApprovate[$j]->getDataFine()->format('d/m/Y'),
                        "assistito" => $arraySchedeApprovate[$j]->getNomeAssistito() . "  " . $arraySchedeApprovate[$j]->getCognomeAssistito(),
                        "stato" => $arraySchedeApprovate[$j]->getCurrentPlace(),
                        "link" => $url . '/scadenzario/'
                    ];
                    array_push($descrizioneSchedeApprovate, $riga);
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
                            "nome_progetto" => $arraySchedeAttive[$t]->getNomeProgetto(),
                            "data_inizio" => $arraySchedeAttive[$t]->getDataInizio()->format('d/m/Y'),
                            "data_fine" => $arraySchedeAttive[$t]->getDataFine()->format('d/m/Y'),
                            "assistito" => $arraySchedeAttive[$t]->getNomeAssistito() . "  " . $arraySchedeAttive[$t]->getCognomeAssistito(),
                            "valore" => $valore,
                        ];
                        array_push($descrizioneValutazioneProfessionale, $riga);
                    }

                    $riga = [
                        "nome_progetto" => $arraySchedeAttive[$t]->getNomeProgetto(),
                        "data_inizio" => $arraySchedeAttive[$t]->getDataInizio()->format('d/m/Y'),
                        "data_fine" => $arraySchedeAttive[$t]->getDataFine()->format('d/m/Y'),
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
                    }
                    if ($arraySchedeAttive[$t]->getBradenNumberToday() <= $arraySchedeAttive[$t]->getCorrectBradenNumberToday() && $arraySchedeAttive[$t]->isAbilitaBraden() == true) {
                        $flagRitardi = true;
                        $riga["braden"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Braden =>  '  . '<a href=' . 'http://localhost:54001/braden/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Braden</a>'.'<br>';
                    }
                    if ($arraySchedeAttive[$t]->getSpmsqNumberToday() <= $arraySchedeAttive[$t]->getCorrectSpmsqNumberToday() && $arraySchedeAttive[$t]->isAbilitaSpmsq() == true) {
                        $flagRitardi = true;
                        $riga["spmsq"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Spmsq =>  ' . '<a href=' . 'http://localhost:54001/spmsq/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Spmsq</a>'.'<br>';
                    }
                    if ($arraySchedeAttive[$t]->getTinettiNumberToday() <= $arraySchedeAttive[$t]->getCorrectTinettiNumberToday() && $arraySchedeAttive[$t]->isAbilitaTinetti() == true) {
                        $flagRitardi = true;
                        $riga["tinetti"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Tinetti =>  ' . '<a href=' . 'http://localhost:54001/tinetti/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Tinetti</a>'.'<br>';
                    }
                    if ($arraySchedeAttive[$t]->getVasNumberToday() <= $arraySchedeAttive[$t]->getCorrectVasNumberToday() && $arraySchedeAttive[$t]->isAbilitaVas() == true) {
                        $flagRitardi = true;
                        $riga["vas"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Vas =>  ' . '<a href=' . 'http://localhost:54001/vas/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Vas</a>'.'<br>';
                    }
                    if ($arraySchedeAttive[$t]->getLesioniNumberToday() <= $arraySchedeAttive[$t]->getCorrectLesioniNumberToday() && $arraySchedeAttive[$t]->isAbilitaLesioni() == true) {
                        $flagRitardi = true;
                        $riga["lesioni"] = 'si';
                        //$descrizioneRitardi = $descrizioneRitardi .'-Lesioni =>  ' . '<a href=' . 'http://localhost:54001/lesioni/app_scheda_pai_index/new?id_pai='.$arraySchedeAttive[$t]->getId().'>Crea scala Lesioni</a>'.'<br>';
                    }
                    if ($riga["barthel"] == 'si' || $riga["braden"] == 'si' || $riga["spmsq"] == 'si' || $riga["tinetti"] == 'si' || $riga["vas"] == 'si' || $riga["lesioni"] == 'si') {
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
                        "nome_progetto" => $arraySchedeInAttesaDiChiusura[$z]->getNomeProgetto(),
                        "data_inizio" => $arraySchedeInAttesaDiChiusura[$z]->getDataInizio()->format('d/m/Y'),
                        "data_fine" => $arraySchedeInAttesaDiChiusura[$z]->getDataFine()->format('d/m/Y'),
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
                $operatore = $userRepository->findOneById($idOperatore);
                $mail = $userRepository->findEmailById($idOperatore);
                $stringaMail = $mail[0];
                $stringaMail = implode(", ", $stringaMail);
                $testoEmailOperatori = "/email_operatori.html.twig";
                $email = (new TemplatedEmail())
                    ->from($sender)
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
                        "logoCoop" => $logoCoop,
                        "calendarIcon" => $calendarIcon,
                        "separatoreTop" => $separatoreTop,
                        "frecciaLink" => $frecciaLink,
                        "separatoreDown" => $separatoreDown,
                        "immagineAttive" => $immagineAttive,
                        "immagineScaleRitardi" => $immagineScaleRitardi,
                        "immagineValutazioni" => $immagineValutazioni,
                        "immagineScadute" => $immagineScadute,
                        "url" => $url,
                        "operatore" => $operatore
                    ]);


                $this->mailer->send($email);
            }
        }
    }
}
