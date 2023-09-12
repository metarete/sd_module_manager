<?php

namespace App\Service;

use App\Entity\EntityPAI\SchedaPAI;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CreazionePdfSchedaPaiService extends AbstractController
{
    private $altraTipologiaAssistenzaService;
    private $bisogniService;

    public function __construct(AltraTipologiaAssistenzaService $altraTipologiaAssistenzaService, BisogniService $bisogniService,)
    {
        $this->altraTipologiaAssistenzaService = $altraTipologiaAssistenzaService;
        $this->bisogniService = $bisogniService;
    }

    public function generaDompdf(string $html): Dompdf
    {
        //configurazione delle opzioni di dompdf
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isPhpEnabled', true);

        // Crea l'oggetto dompdf con le configurazioni scelte
        $dompdf = new Dompdf($pdfOptions);

        // Carica l'HTML all'oggetto Dompdf
        $dompdf->loadHtml($html);


        // Setup tipologia foglio e orientamento per la stampa'
        $dompdf->setPaper('A4', 'portrait');

        // Costruisco il PDF
        $dompdf->render();
        return $dompdf;
    }
    public function salvaPdf(SchedaPAI $schedaPAI, string $html)
    {
        $dataCreazione = date("d-m-Y");

        $assistito = $schedaPAI->getAssistito();

        //genera l'oggetto dompdf
        $dompdf = $this->generaDompdf($html);

        //Imposto il titolo
        $dompdf->addInfo('Title', "Scheda-PAI-di-" . $assistito->getNome() . "-" . $assistito->getCognome() . "-" . $dataCreazione);

        //salva il pdf come stringa
        $output = $dompdf->output();

        $nomePdf = "/tmp/Scheda-PAI-di-" . $assistito->getNome() . "-" . $assistito->getCognome() . "-" . $dataCreazione .".pdf";
        
        //salva il pdf come file nella cartella tmp
        file_put_contents($nomePdf, $output);
        
    }

    public function visualizzaPdf(string $html, SchedaPAI $schedaPAI)
    {
        $dataCreazione = date("d-m-Y");
        $assistito = $schedaPAI->getAssistito();
        
        //crea l'oggetto pdf
        $dompdf = $this->generaDompdf($html);

        //Imposto il titolo
        $dompdf->addInfo('Title', "Scheda-PAI-di-" . $assistito->getNome() . "-" . $assistito->getCognome() . "-" . $dataCreazione);
        
        $nomePdf = "Scheda-PAI-di-" . $assistito->getNome() . "-" . $assistito->getCognome() . "-" . $dataCreazione . "-" . ".pdf";

        //visualizza pdf nella scheda del browser
        $dompdf->stream($nomePdf, [
            "Attachment" => false,  
        ]);
    }

    public function generaHtmlPerPdf(SchedaPAI $schedaPAI): string
    {
        $dataCreazione = date("d-m-Y");
        //salvo nelle variabili i loghi da utilizzare nel pdf
        $img = file_get_contents(
            __DIR__ . "/../../public/image/Logo_ProgettoAssistenza_450x450.png"
        );
        $imgLogoMetarete = file_get_contents(
            __DIR__ . "/../../public/image/logo-metarete.png"
        );
        $image64 = base64_encode($img);
        $image64Metarete = base64_encode($imgLogoMetarete);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('template_pdf.html.twig', [
            'title' => "Scheda PAI",
            'scheda_pai' => $schedaPAI,
            'valutazione_generale' => $schedaPAI->getIdValutazioneGenerale(),
            'valutazioni_figura_professionale' => $schedaPAI->getIdValutazioneFiguraProfessionale(),
            'parere_mmg' => $schedaPAI->getIdParereMmg(),
            'barthels' => $schedaPAI->getIdBarthel(),
            'bradens' => $schedaPAI->getIdBraden(),
            'spmsqs' => $schedaPAI->getIdSpmsq(),
            'tinettis' => $schedaPAI->getIdTinetti(),
            'vass' => $schedaPAI->getIdVas(),
            'lesionis' => $schedaPAI->getIdLesioni(),
            'painads' => $schedaPAI->getIdPainad(),
            'cdrs' => $schedaPAI->getCdrs(),
            'chiusura_servizio' => $schedaPAI->getIdChiusuraServizio(),
            'assistito' => $schedaPAI->getAssistito(),
            'altra_tipologia_assistenza' => $this->altraTipologiaAssistenzaService->getAltreTipologieAssistenza($schedaPAI->getIdValutazioneGenerale()),
            'bisogni' => $this->bisogniService->getBisogni($schedaPAI->getIdValutazioneGenerale()),
            'image64' => $image64,
            'image64Metarete' => $image64Metarete,
            'dataCreazione' => $dataCreazione,
        ]);

        return $html;
    }

}