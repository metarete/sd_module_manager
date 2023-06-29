<?php

namespace App\Service;

use DateTime;
use App\Entity\User;
use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\Paziente;
use App\Entity\EntityPAI\Pratica;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SDManagerClientApiService
{
    private $client;
    private $entityManager;
    private $params;

    private $codiceResponseProgetti;
    private $codiceResponseOperatori;
    private $codiceResponseAssistiti;

    private $numeroProgettiScaricati = 0;
    private $numeroProgettiAggiornati = 0;

    private $setterCambioStatiDopoSincronizzazioneService;


    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager, ParameterBagInterface $params, SetterCambioStatiDopoSincronizzazioneService $setterCambioStatiDopoSincronizzazioneService)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->params = $params;
        $this->setterCambioStatiDopoSincronizzazioneService = $setterCambioStatiDopoSincronizzazioneService;
    }

    public function getCodiceResponseProgetti(): int
    {
        return $this->codiceResponseProgetti;
    }

    public function getCodiceResponseOperatori(): int
    {
        return $this->codiceResponseOperatori;
    }

    public function getCodiceResponseAssistiti(): int
    {
        return $this->codiceResponseAssistiti;
    }

    public function getNumeroProgettiScaricati(): int
    {
        return $this->numeroProgettiScaricati;
    }

    public function getNumeroProgettiAggiornati(): int
    {
        return $this->numeroProgettiAggiornati;
    }

    public function getProgetti(string $dataInizio, string $dataFine): array
    {
        $url = $this->params->get('app.ws_sdmanager_api_progetti');
        $response = $this->client->request(
            'GET',
            $url . $dataInizio . "/" . $dataFine
        );

        $this->codiceResponseProgetti = $response->getStatusCode();
        if ($this->codiceResponseProgetti != 200) {
            $content = [];
            return $content;
        }

        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }
    public function getOperatori(): array
    {
        $url = $this->params->get('app.ws_sdmanager_api_operatori');
        $response = $this->client->request(
            'GET',
            $url
        );

        $this->codiceResponseOperatori = $response->getStatusCode();
        if ($this->codiceResponseOperatori != 200) {
            $content = [];
            return $content;
        }

        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }
    public function getAssistiti(): array
    {
        $url = $this->params->get('app.ws_sdmanager_api_assistiti');
        $response = $this->client->request(
            'GET',
            $url
        );

        $this->codiceResponseAssistiti = $response->getStatusCode();
        if ($this->codiceResponseAssistiti != 200) {
            $content = [];
            return $content;
        }

        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }


    /*
        funzione per sincronizzare con Sd manager gli operatori
        
    */

    public function sincOperatori()
    {


        //tramite API ottengo gli operatori da Sd manager
        $utenti = $this->getOperatori();

        if ($this->codiceResponseOperatori != 200) {
            return;
        }
        //ottengo il repository degli utenti in locale
        $userRepository = $this->entityManager->getRepository(User::class);


        //faccio passare tutti gli utenti scaricati
        for ($i = 0; $i < count($utenti); $i++) {
            //verifico che l'utente ha lo username
            if (empty($utenti[$i]['username'])) {
                //lo salto. non ha lo username
            } else {
                if (empty($utenti[$i]['emails'])) {
                    //lo salto. non ha la mail
                } else {
                    //ha tutto. controllo se c'è gia
                    if ($userRepository->findOneBy(['username' => $utenti[$i]['username']]) == null) {
                        //utente nuovo. 
                        //controllo se esiste un utente che ha la stessa mail di questo nuovo utente
                        if ($userRepository->findOneBy(['email' => $utenti[$i]['emails'][0]['email']]) == null) {
                            //non esiste. creo l'utente
                            $utente = new User;
                            $utente->setEmail($utenti[$i]['emails'][0]['email']);
                            $utente->setName($utenti[$i]['nome']);
                            $utente->setSurname($utenti[$i]['cognome']);
                            $utente->setCf($utenti[$i]['cf']);
                            $utente->setPassword("");
                            $roles[0] = 'ROLE_USER';
                            $utente->setRoles($roles);
                            $utente->setUsername($utenti[$i]['username']);
                            $utente->setSdManagerOperatore(true);
                            $statoScaricato = $utenti[$i]['stato'];
                            if ($statoScaricato == '1') {
                                $stato = true;
                            } else {
                                $stato = false;
                            }
                            $utente->setStato($stato);

                            $userRepository->add($utente, true);
                        } else {
                            //ho già un utente con questa mail. lo salto
                        }
                    } else {
                        //utente che ho gia. aggiorno i dati
                        $statoScaricato = $utenti[$i]['stato'];
                        if ($statoScaricato == '1') {
                            $stato = true;
                        } else {
                            $stato = false;
                        }
                        $email = $utenti[$i]['emails'][0]['email'];
                        $userRepository->updateUserByUsername($utenti[$i]['username'], $utenti[$i]['nome'], $utenti[$i]['cognome'], $utenti[$i]['cf'], $email, $stato);
                    }
                }
            }
        }
    }

    public function sincProgetti(string $dataInizio, string $dataFine)
    {
        $progetti = $this->getProgetti($dataInizio, $dataFine);
        if ($this->codiceResponseProgetti != 200) {
            return;
        }
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        $praticaRepository = $this->entityManager->getRepository(Pratica::class);
        //faccio passare i progetti scaricati uno ad uno
        for ($i = 0; $i < count($progetti); $i++) {
            $idProgetto = $progetti[$i]['id_progetto'];
            
            //se il progetto ha la scheda pai sarà da creare o da modificare
            if ($progetti[$i]['scheda_pai'] == 1) {
                $dataInizio = DateTime::createfromformat('d-m-Y', $progetti[$i]['data_inizio']);
                $dataFine = DateTime::createfromformat('d-m-Y', $progetti[$i]['data_fine']);
                $idAssistito = $progetti[$i]['id_utente'];
                $nomeProgetto = $progetti[$i]['nome'];
                $statoSDManager = $progetti[$i]['stato_progetto'];
                $assistito = $assistitiRepository->findOneById($idAssistito);
                //se il progetto è adiweb mi servono pratica e protocollo
                if($progetti[$i]['tipologia'] == 'ADIWEB'){
                    //cerco la pratica tra quelle che ho gia
                    $pratica = $praticaRepository->findOneBy(["codice" => $progetti[$i]['adiweb_pratica']]);
                    //se non c'è la creo
                    if($pratica == null){
                        $adiwebPratica = new Pratica;
                        $adiwebPratica->setCodice($progetti[$i]['adiweb_pratica']);
                        $praticaRepository->add($adiwebPratica, true);
                    }
                    else{
                        $adiwebPratica = $pratica;
                    }
                    
                    $adiwebProtocollo = $progetti[$i]['adiweb_protocollo'];
                }
                else{
                    $adiwebPratica = null;
                    $adiwebProtocollo = null;
                }
                
                //se non c'è già lo creo da zero
                if ($schedaPAIRepository->findBy(['idProgetto' => $idProgetto]) == null) {
                    if($progetti[$i]['stato_progetto'] == 'ATTIVO'){
                        $schedaPai = new SchedaPAI;
                        $schedaPai->setDataInizio($dataInizio);
                        $schedaPai->setDataFine($dataFine);
                        $schedaPai->setAssistito($assistito);
                        $schedaPai->setIdProgetto($idProgetto);
                        $schedaPai->setNomeProgetto($nomeProgetto);
                        $schedaPai->setCurrentPlace('nuova');
                        $schedaPai->setStatoSDManager($statoSDManager);
                        $schedaPai->setAdiwebPratica($adiwebPratica);
                        $schedaPai->setAdiwebProtocollo($adiwebProtocollo);
                        $schedaPAIRepository->add($schedaPai, true);
                        $this->numeroProgettiScaricati++;
                    }
                } else {
                    //se c'è già 
                    $schedaPai = $schedaPAIRepository->findOneBy(['idProgetto' => $idProgetto]);
                    $dataInizio->format('d-m-Y');
                    $dataFine->format('d-m-Y');

                    //check per i cambiamenti di stato in base ai cambio data iniziale e finale
                    $this->setterCambioStatiDopoSincronizzazioneService->settaCambioStati($dataInizio, $dataFine, $schedaPai);
                    $schedaPAIRepository->updateSchedaByIdprogetto($idProgetto, $assistito, $dataInizio, $dataFine, $nomeProgetto, $statoSDManager, $adiwebPratica, $adiwebProtocollo);

                    $this->numeroProgettiAggiornati++;
                }
            }
        }
    }
    public function sincAssistiti()
    {
        $assistiti = $this->getAssistiti();
        if ($this->codiceResponseAssistiti != 200) {
            return;
        }
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        for ($i = 0; $i < count($assistiti); $i++) {
            $cf = $assistiti[$i]['cf'];
            $indirizzo = $assistiti[$i]['indirizzi'][0]['indirizzo'];
            $comune = $assistiti[$i]['indirizzi'][0]['comune'];
            $provincia = $assistiti[$i]['indirizzi'][0]['provincia'];
            $cap = $assistiti[$i]['indirizzi'][0]['cap'];
            if ($assistitiRepository->findOneBy(["idSdManager" => $assistiti[$i]['id_utente']]) === null) {
                $assistito = new Paziente;
                $assistito->setNome($assistiti[$i]['nome']);
                $assistito->setCognome($assistiti[$i]['cognome']);
                $assistito->setCodiceFiscale($assistiti[$i]['cf']);
                $assistito->setIndirizzo($indirizzo);
                $assistito->setComune($comune);
                $assistito->setProvincia($provincia);
                $assistito->setCap($cap);
                $assistito->setIdSdManager($assistiti[$i]['id_utente']);
                $assistitiRepository->add($assistito, true);
            } else {
                $assistitiRepository->updateAssistitiByIdSdManager($assistiti[$i]['id_utente'], $cf, $assistiti[$i]['nome'], $assistiti[$i]['cognome'], $indirizzo, $comune, $provincia, $cap);
            }
        }
    }
}
