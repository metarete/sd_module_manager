<?php

namespace App\Controller;

use App\Entity\AudioPrivacy;
use App\Entity\Paziente;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/audio/privacy')]
class AudioPrivacyController extends AbstractController
{
    #[Route('/new', name: 'app_audio_privacy_new', methods: ['POST', 'GET'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        //controllo di attivazione tramite variabile nel file env
        $this->denyAccessUnlessGranted('attivazione_audio_privacy');

        $idPaziente = $request->query->get('id');
        $pazienteRepository = $entityManager->getRepository(Paziente::class);
        $paziente = $pazienteRepository->findOneBy(['id' => $idPaziente]);

        return $this->renderForm('audio_privacy/new.html.twig', [
            'paziente' => $paziente
        ]);
    }

    //metodo per javascript
    #[Route('/salva', name: 'app_audio_privacy_salva', methods: ['POST', 'GET'])]
    public function save(Request $request, EntityManagerInterface $entityManager): Response
    {
        //controllo di attivazione tramite variabile nel file env
        $this->denyAccessUnlessGranted('attivazione_audio_privacy');

        $audioPrivacy = new AudioPrivacy();
        $response = new Response();
        $pazienteRepository = $entityManager->getRepository(Paziente::class);

        if ($request->getContent() != null) {
            
            //trasformo in array indicizzato l'array del javascript
            $json = json_decode($request->getContent(), true);
            $arrayBlob = $json['testArray'];
            $idPaziente = $json['idPaziente'];
            $paziente = $pazienteRepository->findOneBy(['id' => $idPaziente]);
            //rimozione dei valori nulli dall'array indicizzato
            $arrayBlob = array_values(array_filter($arrayBlob));
            $audioPrivacy->setAudio($arrayBlob);
            $audioPrivacy->setAssistito($paziente);
            $entityManager->persist($audioPrivacy);
            $entityManager->flush();
            $response->setStatusCode(Response::HTTP_CREATED);
        }
        else{
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        
        return $response;

    }

    #[Route('/{id}/edit', name: 'app_audio_privacy_edit', methods: ['POST', 'GET'])]
    public function edit(Paziente $paziente): Response
    {
        //controllo di attivazione tramite variabile nel file env
        $this->denyAccessUnlessGranted('attivazione_audio_privacy');

        $audioPrivacy = $paziente->getAudioPrivacy();

        return $this->renderForm('audio_privacy/edit.html.twig', [
            'audio_privacy' => $audioPrivacy,
        ]);
    }

    //metodo per javascript
    #[Route('/modifica', name: 'app_audio_privacy_modifica', methods: ['POST', 'GET'])]
    public function update(Request $request, EntityManagerInterface $entityManager): Response
    {
        //controllo di attivazione tramite variabile nel file env
        $this->denyAccessUnlessGranted('attivazione_audio_privacy');

        $response = new Response();
        $audioPrivacyRepository = $entityManager->getRepository(AudioPrivacy::class);

        if ($request->getContent() != null) {

            $json = json_decode($request->getContent(), true);
            $arrayBlob = $json['testArray'];
            $idAudioPrivacy = $json['idAudioPrivacy'];
            $arrayElementiDaEliminare = $json['arrayElementiDaEliminare'];
            $audioPrivacy = $audioPrivacyRepository->findOneBy(['id' => $idAudioPrivacy]);
            $audio = $audioPrivacy->getAudio();
            
            //eliminazione degli audio 
            for($i=0; $i<count($arrayElementiDaEliminare); $i++){
                unset($audio[$arrayElementiDaEliminare[$i]]);
            }

            $arrayBlob = array_values(array_filter($arrayBlob));
            $arrayBlobTotali = array_merge($arrayBlob, $audio);
            $audioPrivacy->setAudio($arrayBlobTotali);
            $entityManager->persist($audioPrivacy);
            $entityManager->flush();
            $response->setStatusCode(Response::HTTP_CREATED);
        }
        else{
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        
        return $response;

    }

    #[Route('/{id}/delete', name: 'app_audio_privacy_delete', methods: ['POST', 'GET'])]
    public function delete(Request $request, Paziente $paziente, EntityManagerInterface $entityManager): Response
    {
        //controllo di attivazione tramite variabile nel file env
        $this->denyAccessUnlessGranted('attivazione_audio_privacy');

        $audioPrivacy = $paziente->getAudioPrivacy();

        if ($this->isCsrfTokenValid('delete' . $audioPrivacy->getId(), $request->query->get('_token'))) {
            $entityManager->remove($audioPrivacy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_paziente_index', [], Response::HTTP_SEE_OTHER);
    }
}
