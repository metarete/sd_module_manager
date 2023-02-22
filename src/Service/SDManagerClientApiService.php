<?php

namespace App\Service;

use DateTime;
use App\Entity\User;
use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\Paziente;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SDManagerClientApiService
{
    private $client;
    private $entityManager;
    private $userPasswordHasher;

    private $codiceResponseProgetti;
    private $codiceResponseOperatori;
    private $codiceResponseAssistiti;

    private $numeroProgettiScaricati = 0;
    private $numeroProgettiAggiornati = 0;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;
        $this->userPasswordHasher = $userPasswordHasher;
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

        $response = $this->client->request(
            'GET',
            'https://demo.sdmanager.it/ws/1.0/mSADManager/list-progetti/' . $dataInizio . "/" . $dataFine
        );
        
        $this->codiceResponseProgetti = $response->getStatusCode();
        if($this->codiceResponseProgetti != 200){
            $content = [];
            return $content;
        }
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }
    public function getOperatori(): array
    {
        $response = $this->client->request(
            'GET',
            'https://demo.sdmanager.it/ws/1.0/mSADManager/list-operatori/'
        );

        $this->codiceResponseOperatori = $response->getStatusCode();
        if($this->codiceResponseOperatori != 200){
            $content = [];
            return $content;
        }
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }
    public function getAssistiti(): array
    {
        $response = $this->client->request(
            'GET',
            'https://demo.sdmanager.it/ws/1.0/mSADManager/list-utenti/'
        );

        $this->codiceResponseAssistiti = $response->getStatusCode();
        if($this->codiceResponseAssistiti != 200){
            $content = [];
            return $content;
        }
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        return $content;
    }

    public function sincOperatori()
    {
        $utenti = $this->getOperatori();
        if($this->codiceResponseOperatori != 200){
            return;
        }
            
        $userRepository = $this->entityManager->getRepository(User::class);
        for ($i = 0; $i < count($utenti); $i++) {
            $userUtente = $utenti[$i]['username'];
            if ($userRepository->findOneByUsername($userUtente) == null) {
                $utente = new User;
                if ($utenti[$i]['emails'] != null) {
                    $email = $utenti[$i]['emails'][0]['email'];
                    if ($userRepository->findOneByEmail($email) == null) {
                        $utente->setEmail($email);
                        $password = 'prova1';
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
            } else {
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
        for ($i = 0; $i < count($progetti); $i++) {
            $idProgetto = $progetti[$i]['id_progetto'];
            if ($progetti[$i]['scheda_pai'] == 1) {
                $dataInizio = DateTime::createfromformat('d-m-Y', $progetti[$i]['data_inizio']);
                $dataFine = DateTime::createfromformat('d-m-Y', $progetti[$i]['data_fine']);
                $idAssistito = $progetti[$i]['id_utente'];
                if ($schedaPAIRepository->findOneByProgetto($idProgetto) == null) {
                    $schedaPai = new SchedaPAI;
                    $schedaPai->setDataInizio($dataInizio);
                    $schedaPai->setDataFine($dataFine);
                    $schedaPai->setIdAssistito($idAssistito);
                    $schedaPai->setIdProgetto($idProgetto);
                    $schedaPai->setCurrentPlace('nuova');
                    $schedaPai->setIdConsole('demo');
                    $schedaPAIRepository->add($schedaPai, true);
                    $this->numeroProgettiScaricati++;
                } else {
                    $schedaPAIRepository->updateSchedaByIdprogetto($idProgetto, $idAssistito, $dataInizio, $dataFine);
                    $this->numeroProgettiAggiornati++;
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
