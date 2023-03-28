<?php

namespace App\Service;

use DateTime;
use App\Entity\User;
use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\Paziente;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SDManagerClientApiService
{
    private $client;
    private $entityManager;
    private $userPasswordHasher;
    private $params;

    private $codiceResponseProgetti;
    private $codiceResponseOperatori;
    private $codiceResponseAssistiti;

    private $numeroProgettiScaricati = 0;
    private $numeroProgettiAggiornati = 0;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
        $this->params = $params;
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
        if($this->codiceResponseProgetti != 200){
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
        if($this->codiceResponseOperatori != 200){
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
        if($this->codiceResponseAssistiti != 200){
            $content = [];
            return $content;
        }
        
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }

    public function sincOperatori()
    {
        $pass = $this->params->get('app.testing_password_user');

        $utenti = $this->getOperatori();
        if($this->codiceResponseOperatori != 200){
            return;
        }
         
        $userRepository = $this->entityManager->getRepository(User::class);
        //faccio passare tutti gli utenti scaricati
        for ($i = 0; $i < count($utenti); $i++) {
            $userUtente = $utenti[$i]['username'];
            // se è nuovo lo creo
            if ($userRepository->findOneByUsername($userUtente) == null) {
                $utente = new User;
                //se c'è l'email su SD manager proseguo
                if (!empty($utenti[$i]['emails'])) {
                    $email = $utenti[$i]['emails'][0]['email'];
                    //se l'email non è già stata assegnata ad un altro utente registro il nuovo utente
                    if ($userRepository->findOneByEmail($email) == null) {
                        $utente->setEmail($email);
                        $password = $pass;
                        $hashedPassword = $this->userPasswordHasher->hashPassword(
                            $utente,
                            $password
                        );

                        $utente->setPassword($hashedPassword);
                        $utente->setName($utenti[$i]['nome']);
                        $utente->setSurname($utenti[$i]['cognome']);
                        $role[0] = 'ROLE_USER';
                        $utente->setRoles($role);
                        $utente->setUsername($userUtente);
                        $utente->setSdManagerOperatore(true);

                        $userRepository->add($utente, true);
                    }
                }
                //se c'è già e ha la mail aggiorno la mail
            } else if (!empty($utenti[$i]['emails'])){
                //altro if controllo mail
                $email = $utenti[$i]['emails'][0]['email'];
                $userRepository->updateUserByUsername($userUtente, $utenti[$i]['nome'], $utenti[$i]['cognome'], $email);
            }
        }
    }

    public function sincProgetti(string $dataInizio, string $dataFine)
    {
        $progetti = $this->getProgetti($dataInizio, $dataFine);
        if($this->codiceResponseProgetti != 200){
            return;
        }
        
        $schedaPAIRepository = $this->entityManager->getRepository(SchedaPAI::class);
        //faccio passare i progetti scaricati uno ad uno
        for ($i = 0; $i < count($progetti); $i++) {
            $idProgetto = $progetti[$i]['id_progetto'];
            //se il progetto è attivo e deve avere la scheda pai
            if ($progetti[$i]['scheda_pai'] == 1 && $progetti[$i]['stato_progetto']=='ATTIVO') {
                $dataInizio = DateTime::createfromformat('d-m-Y', $progetti[$i]['data_inizio']);
                $dataFine = DateTime::createfromformat('d-m-Y', $progetti[$i]['data_fine']);
                $idAssistito = $progetti[$i]['id_utente'];
                $nomeProgetto = $progetti[$i]['nome'];
                //se non c'è già lo creo da zero
                if ($schedaPAIRepository->findOneByProgetto($idProgetto) == null) {
                    $schedaPai = new SchedaPAI;
                    $schedaPai->setDataInizio($dataInizio);
                    $schedaPai->setDataFine($dataFine);
                    $schedaPai->setIdAssistito($idAssistito);
                    $schedaPai->setIdProgetto($idProgetto);
                    $schedaPai->setNomeProgetto($nomeProgetto);
                    $schedaPai->setCurrentPlace('nuova');
                    $schedaPai->setIdConsole('demo');
                    $schedaPAIRepository->add($schedaPai, true);
                    $this->numeroProgettiScaricati++;
                } else {
                    //se c'è già 
                    $schedaPai = $schedaPAIRepository->findOneByProgetto($idProgetto);
                    //se è stata spostata in avanti la data di fine ed era in stato di attesa di chiusura lo riattivo
                    if($dataFine > $schedaPai->getDataFine() && $dataFine > date("d-m-Y") && $schedaPai->getCurrentPlace()=='in_attesa_di_chiusura'){
                        $statoAttivo = 'attiva';
                        $schedaPAIRepository->riattivaSchedaByIdprogetto($idProgetto, $idAssistito, $dataInizio, $dataFine, $nomeProgetto, $statoAttivo);
                        $this->numeroProgettiAggiornati++;
                    }
                    //aggiorno i dati 
                    else{
                        $schedaPAIRepository->updateSchedaByIdprogetto($idProgetto, $idAssistito, $dataInizio, $dataFine, $nomeProgetto);
                        $this->numeroProgettiAggiornati++;
                    }
                }
            }
        }
    }
    public function sincAssistiti()
    {
        $assistiti = $this->getAssistiti();
        if($this->codiceResponseAssistiti != 200){
            return;
        }
        $assistitiRepository = $this->entityManager->getRepository(Paziente::class);
        for ($i = 0; $i < count($assistiti); $i++) {
            $cf = $assistiti[$i]['cf'];
            $indirizzo = $assistiti[$i]['indirizzi'][0]['indirizzo'];
            $comune = $assistiti[$i]['indirizzi'][0]['comune'];
            $provincia = $assistiti[$i]['indirizzi'][0]['provincia'];
            $cap = $assistiti[$i]['indirizzi'][0]['cap'];
            if ($assistitiRepository->findOneByCf($cf) == null) {
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
                $assistitiRepository->updateAssistitiByCf($cf, $assistiti[$i]['nome'], $assistiti[$i]['cognome'], $indirizzo, $comune, $provincia, $cap);
            }
        }
    }
}
