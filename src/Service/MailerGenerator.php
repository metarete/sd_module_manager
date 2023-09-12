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
                    "assistito" => $schede[$i]->getAssistito()->getNome() . "  " . $schede[$i]->getAssistito()->getCognome(),
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
                    "assistito" => $schede[$i]->getAssistito()->getNome() . "  " . $schede[$i]->getAssistito()->getCognome(),
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
                    "assistito" => $schede[$i]->getAssistito()->getNome() . "  " . $schede[$i]->getAssistito()->getCognome(),
                    "motivazione" => $schede[$i]->getIdChiusuraServizio()->getConclusioni(),
                    "stato" => $schede[$i]->getCurrentPlace(),
                    "link" => 'https://' . $console . '.sdmanager.it/index.php?module=Servizi.Domiciliari&func=progetti_edit&type=admin'
                ];
                array_push($testo, $riga);
            }
        }
        return $testo;
    }

    private function creaTestoEmailVerifica($testo, $schede):array
    {
        if ($schede != null) {
            for ($i = 0; $i < count($schede); $i++) {
                $riga = [
                    "id" => $schede[$i]->getId(),
                    "nome_progetto" => $schede[$i]->getNomeProgetto(),
                    "data_inizio" => $schede[$i]->getDataInizio()->format('d/m/Y'),
                    "data_fine" => $schede[$i]->getDataFine()->format('d/m/Y'),
                    "assistito" => $schede[$i]->getAssistito()->getNome() . "  " . $schede[$i]->getAssistito()->getCognome(),
                    "stato" => $schede[$i]->getCurrentPlace(),
                ];
                array_push($testo, $riga);
            }
        }
        return $testo;
    }

    private function creaTestoEmailInAttesaDiChiusura($testo, $schede):array
    {
        if ($schede != null) {
            for ($i = 0; $i < count($schede); $i++) {
                $riga = [
                    "id" => $schede[$i]->getId(),
                    "nome_progetto" => $schede[$i]->getNomeProgetto(),
                    "data_inizio" => $schede[$i]->getDataInizio()->format('d/m/Y'),
                    "data_fine" => $schede[$i]->getDataFine()->format('d/m/Y'),
                    "assistito" => $schede[$i]->getAssistito()->getNome() . "  " . $schede[$i]->getAssistito()->getCognome(),
                    "stato" => $schede[$i]->getCurrentPlace(),
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
        
        $calendarIcon = $url .'/image/calendar-day-solid.png';
        
        $separatoreTop = $url . '/image/09461c6c-3517-429e-99a2-64810982a104.png';
       
        $frecciaLink = $url . '/image/next_1.png';
       
        $separatoreDown = $url . '/image/bottom_rounded_15.png';
       
        $immagineNuove = $url . '/image/marker-solid.png';
        
        $immagineChiuse = $url . '/image/circle-xmark-solid.png';
        
        $immagineChiuseConRinnovo = $url . '/image/registered-solid.png';
        
        $immagineVerifica = $url . '/image/hourglass-start-solid-2.png';

        $immagineInAttesaDiChiusuraConRinnovo = $url . '/image/hourglass-start-solid-3.png';

        $immagineInAttesaDiChiusura = $url . '/image/hourglass-start-solid-4.png';

        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $schedeNuove = $schedaPAIRepository->findBy(['currentPlace' => 'nuova']);
        $schedeChiuse = $schedaPAIRepository->findBy(['currentPlace' => 'chiusa']);
        $schedeChiuseConRinnovo = $schedaPAIRepository->findBy(['currentPlace' => 'chiusa_con_rinnovo']);
        $schedeInVerifica = $schedaPAIRepository->findBy(['currentPlace' => 'verifica']);
        $schedeInAttesaDiChiusura = $schedaPAIRepository->findBy(['currentPlace' => 'in_attesa_di_chiusura']);
        $schedeInAttesaDiChiusuraConRinnovo = $schedaPAIRepository->findBy(['currentPlace' => 'in_attesa_di_chiusura_con_rinnovo']);
        $utenti = $userRepository->findAll();
        $admin = [];
        $testoEmailNuove = [];
        $testoEmailChiuse = [];
        $testoEmailChiuseConRinnovo = [];
        $testoEmailVerifica = [];
        $testoEmailInAttesaDiChiusura = [];
        $testoEmailInAttesaDiChiusuraConRinnovo = [];
        $testoEmailNuove = $this->creaTestoEmailNuove($testoEmailNuove, $schedeNuove);
        $testoEmailChiuse = $this->creaTestoEmailChiuse($testoEmailChiuse, $schedeChiuse);
        $testoEmailChiuseConRinnovo = $this->creaTestoEmailChiuseConRinnovo($testoEmailChiuseConRinnovo, $schedeChiuseConRinnovo);
        $testoEmailVerifica = $this->creaTestoEmailVerifica($testoEmailVerifica, $schedeInVerifica);
        $testoEmailInAttesaDiChiusura = $this->creaTestoEmailInAttesaDiChiusura($testoEmailInAttesaDiChiusura, $schedeInAttesaDiChiusura);
        $testoEmailInAttesaDiChiusuraConRinnovo = $this->creaTestoEmailInAttesaDiChiusura($testoEmailInAttesaDiChiusuraConRinnovo, $schedeInAttesaDiChiusuraConRinnovo);

        for ($i = 0; $i < count($utenti); $i++) {
            $roles = $utenti[$i]->getRoles();
            if (in_array("ROLE_ADMIN", $roles)) {
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
            } elseif($operatore->isStato() == false){
                //operatore non attivo
            }else {


                $email = (new TemplatedEmail())
                    ->from($sender)
                    ->to($stringaMail)
                    ->subject('Email per admin')
                    ->htmlTemplate("/email_admin.html.twig")
                    ->context([
                        "testoEmailNuove" => $testoEmailNuove,
                        "testoEmailChiuse" => $testoEmailChiuse,
                        "testoEmailChiuseConRinnovo" => $testoEmailChiuseConRinnovo,
                        "testoEmailVerifica" => $testoEmailVerifica,
                        "testoEmailInAttesaDiChiusura" => $testoEmailInAttesaDiChiusura,
                        "testoEmailInAttesaDiChiusuraConRinnovo" => $testoEmailInAttesaDiChiusuraConRinnovo,
                        "schedeNuove" =>  $schedeNuove,
                        "schedeChiuse" => $schedeChiuse,
                        "schedeChiuseConRinnovo" => $schedeChiuseConRinnovo,
                        "schedeInVerifica" => $schedeInVerifica,
                        "schedeInAttesaDiChiusura" => $schedeInAttesaDiChiusura,
                        "schedeInAttesaDiChiusuraConRinnovo" => $schedeInAttesaDiChiusuraConRinnovo,
                        "calendarIcon" => $calendarIcon,
                        "separatoreTop" => $separatoreTop,
                        "frecciaLink" => $frecciaLink,
                        "separatoreDown" => $separatoreDown,
                        "immagineNuove" => $immagineNuove,
                        "immagineChiuse" => $immagineChiuse,
                        "immagineChiuseConRinnovo" => $immagineChiuseConRinnovo,
                        "immagineVerifica" => $immagineVerifica,
                        "immagineInAttesaDiChiusuraConRinnovo" => $immagineInAttesaDiChiusuraConRinnovo,
                        "immagineInAttesaDiChiusura" => $immagineInAttesaDiChiusura,
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
        
        $calendarIcon = $url .'/image/calendar-day-solid.png';
        
        $separatoreTop = $url . '/image/09461c6c-3517-429e-99a2-64810982a104.png';
       
        $frecciaLink = $url . '/image/next_1.png';
       
        $separatoreDown = $url . '/image/bottom_rounded_15.png';

        $immagineAttive = $url . '/image/circle-check-solid.png';
        
        $immagineScaleRitardi = $url . '/image/stopwatch-solid.png';
        
        $immagineValutazioni = $url . '/image/user-doctor-solid.png';
       
        $immagineScadute = $url . '/image/hourglass-start-solid.png';

        $immagineVerifica = $url . '/image/hourglass-start-solid-2.png';
        
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $arraySchedeApprovate = $schedaPAIRepository->findBy(['currentPlace' => 'approvata']);
        $arraySchedeAttive = $schedaPAIRepository->findBy(['currentPlace' => 'attiva']);
        $arraySchedeVerifica = $schedaPAIRepository->findBy(['currentPlace' => 'verifica']);
        $arraySchedeInAttesaDiChiusuraConRinnovo = $schedaPAIRepository->findBy(['currentPlace' => 'in_attesa_di_chiusura_con_rinnovo']);
        $arraySchedeInAttesaDiChiusura = $schedaPAIRepository->findBy(['currentPlace' => 'in_attesa_di_chiusura']);
        $arraySchedeAttive = array_merge($arraySchedeAttive, $arraySchedeInAttesaDiChiusura, $arraySchedeInAttesaDiChiusuraConRinnovo, $arraySchedeVerifica);
        $arraySchedeInAttesaDiChiusura = array_merge($arraySchedeInAttesaDiChiusura, $arraySchedeInAttesaDiChiusuraConRinnovo);
        $arrayOperatori = $userRepository->findAll();
        $testoApprovata = '
        Attiva le scale seguenti in cui sei assegnato come operatore principale: abilita le scale di valutazione, imposta la frequenza di compilazione e compila la valutazione generale.';
        $testoRitardi = '
        Ci sono delle schede di valutazione in ritardo rispetto alla frequenza stabilita.';
        $testoChiusura = '
        Le seguenti schede necessitano di chiusura poichè scadute o in attesa di chiusura. Compilare le scale mancanti se necessario e la chiusura del servizio.';
        $testoAttiva = '
        Le seguenti schede attive hanno delle schede di valutazione professionale mancanti; compilarle al più presto.';
        $testoVerifica = '
        Le seguenti schede scadono tra pochi giorni. Selezionare dalle operazioni se sarà necessario un rinnovo o una chiusura definitiva.';
        for ($i = 0; $i < count($arrayOperatori); $i++) {
            $idOperatore = $arrayOperatori[$i]->getId();
            $flagSchedaApprovata = false;
            $descrizioneSchedeApprovate = [];
            $descrizioneRitardi =  [];
            $descrizioneSchedeDaChiudere = [];
            $descrizioneValutazioneProfessionale = [];
            $descrizioneSchedeVerifica = [];
            $flagRitardi = false;
            $flagSchedeDaChiudere = false;
            $flagValutazioneProfessionale = false;
            $flagSchedeVerifica = false;
            for ($j = 0; $j < count($arraySchedeApprovate); $j++) {
                $idOperatorePrincipale = $arraySchedeApprovate[$j]->getIdOperatorePrincipale()->getId();
                if ($idOperatore == $idOperatorePrincipale) {
                    $flagSchedaApprovata = true;
                    $riga = [
                        "id" => $arraySchedeApprovate[$j]->getId(),
                        "nome_progetto" => $arraySchedeApprovate[$j]->getNomeProgetto(),
                        "data_inizio" => $arraySchedeApprovate[$j]->getDataInizio()->format('d/m/Y'),
                        "data_fine" => $arraySchedeApprovate[$j]->getDataFine()->format('d/m/Y'),
                        "assistito" => $arraySchedeApprovate[$j]->getAssistito()->getNome() . "  " . $arraySchedeApprovate[$j]->getAssistito()->getCognome(),
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
                            "assistito" => $arraySchedeAttive[$t]->getAssistito()->getNome() . "  " . $arraySchedeAttive[$t]->getAssistito()->getCognome(),
                            "valore" => $valore,
                        ];
                        array_push($descrizioneValutazioneProfessionale, $riga);
                    }

                    $riga = [
                        "nome_progetto" => $arraySchedeAttive[$t]->getNomeProgetto(),
                        "data_inizio" => $arraySchedeAttive[$t]->getDataInizio()->format('d/m/Y'),
                        "data_fine" => $arraySchedeAttive[$t]->getDataFine()->format('d/m/Y'),
                        "assistito" => $arraySchedeAttive[$t]->getAssistito()->getNome() . "  " . $arraySchedeAttive[$t]->getAssistito()->getCognome(),
                        "barthel" => "Nessuna",
                        "braden" => "Nessuna",
                        "spmsq" => "Nessuna",
                        "tinetti" => "Nessuna",
                        "vas" => "Nessuna",
                        "lesioni" => "Nessuna",
                        "painad" => "Nessuna",
                        "cdr" => "Nessuna"
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
                    $arraySchedeAttive[$t]->setPainadNumberToday();
                    $arraySchedeAttive[$t]->setCorrectPainadNumberToday();
                    $arraySchedeAttive[$t]->setCdrNumberToday();
                    $arraySchedeAttive[$t]->setCorrectCdrNumberToday();

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
                    if ($arraySchedeAttive[$t]->getPainadNumberToday() <= $arraySchedeAttive[$t]->getCorrectPainadNumberToday() && $arraySchedeAttive[$t]->isAbilitaPainad() == true) {
                        $flagRitardi = true;
                        $riga["painad"] = 'si';
                    }
                    if ($arraySchedeAttive[$t]->getCdrNumberToday() <= $arraySchedeAttive[$t]->getCorrectCdrNumberToday() && $arraySchedeAttive[$t]->isAbilitaCdr() == true) {
                        $flagRitardi = true;
                        $riga["cdr"] = 'si';
                    }
                    if ($riga["barthel"] == 'si' || $riga["braden"] == 'si' || $riga["spmsq"] == 'si' || $riga["tinetti"] == 'si' || $riga["vas"] == 'si' || $riga["lesioni"] == 'si'|| $riga["painad"] == 'si' || $riga["cdr"] == 'si') {
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
                        "assistito" => $arraySchedeInAttesaDiChiusura[$z]->getAssistito()->getNome() . "  " . $arraySchedeInAttesaDiChiusura[$z]->getAssistito()->getCognome(),
                        "stato" => $arraySchedeInAttesaDiChiusura[$z]->getCurrentPlace(),
                    ];
                    array_push($descrizioneSchedeDaChiudere, $riga);
                }
            }


            for ($a = 0; $a < count($arraySchedeVerifica); $a++) {
                $idOperatorePrincipale = $arraySchedeVerifica[$a]->getIdOperatorePrincipale()->getId();
                if ($idOperatore == $idOperatorePrincipale) {
                    $flagSchedeVerifica = true;
                    $riga = [
                        "id" => $arraySchedeVerifica[$a]->getId(),
                        "nome_progetto" => $arraySchedeVerifica[$a]->getNomeProgetto(),
                        "data_inizio" => $arraySchedeVerifica[$a]->getDataInizio()->format('d/m/Y'),
                        "data_fine" => $arraySchedeVerifica[$a]->getDataFine()->format('d/m/Y'),
                        "assistito" => $arraySchedeVerifica[$a]->getAssistito()->getNome() . "  " . $arraySchedeVerifica[$a]->getAssistito()->getCognome(),
                        "stato" => $arraySchedeVerifica[$a]->getCurrentPlace(),
                    ];
                    array_push($descrizioneSchedeVerifica, $riga);
                }
            }


            $testoApprovata1 = $testoApprovata;
            $testoRitardi1 = $testoRitardi;
            $testoChiusura1 = $testoChiusura;
            $testoAttiva1 = $testoAttiva;
            $testoVerifica1 = $testoVerifica;
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
            if ($flagSchedeVerifica == false) {
                $testoVerifica1 = '';
                $descrizioneSchedeVerifica = [];
            }
            if ($flagSchedaApprovata == false && $flagRitardi == false && $flagSchedeDaChiudere == false && $flagValutazioneProfessionale == false && $flagSchedeVerifica == false) {
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
                        'testoVerifica1' => $testoVerifica1,
                        'descrizioneSchedeVerifica' => $descrizioneSchedeVerifica,
                        "calendarIcon" => $calendarIcon,
                        "separatoreTop" => $separatoreTop,
                        "frecciaLink" => $frecciaLink,
                        "separatoreDown" => $separatoreDown,
                        "immagineAttive" => $immagineAttive,
                        "immagineScaleRitardi" => $immagineScaleRitardi,
                        "immagineValutazioni" => $immagineValutazioni,
                        "immagineScadute" => $immagineScadute,
                        "immagineVerifica" => $immagineVerifica,
                        "url" => $url,
                        "operatore" => $operatore
                    ]);


                $this->mailer->send($email);
            }
        }
    }

    public function EmailCaregiver(SchedaPAI $schedaPAI)
    {
        $sender = $this->params->get('app.mailer_notification_sender');
        $dataCreazione = date("d-m-Y");
        $assistito = $schedaPAI->getAssistito();
        $nomePdf = "Scheda-PAI-di-" . $assistito->getNome() . "-" . $assistito->getCognome() . "-" . $dataCreazione .".pdf";

        $email = (new TemplatedEmail())
                ->from($sender)
                ->to($assistito->getEmailFiguraDiRiferimento())
                ->subject('Scheda PAI')
                //allegati
                ->attachFromPath("/tmp/".$nomePdf, 'Scheda-PAI.pdf')
                ->htmlTemplate("/email_caregiver.html.twig")
                //parametri per html
                ->context([
                    'assistito' => $assistito
                ]);
        $this->mailer->send($email);
    }
}
