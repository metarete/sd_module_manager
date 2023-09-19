<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Entity\Diagnosi;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ValutazioneGeneraleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ValutazioneGeneraleRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_valutazione_generale')]
class ValutazioneGenerale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank]
    #[Assert\Type(\DateTime::class)]
    private $data_valutazione;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    #[Assert\GreaterThan(-1)]
    private $n_componenti_nucleo_abitativo;

    #[ORM\Column(type: 'boolean')]
    private $rischio_infettivo;

    #[ORM\ManyToMany(targetEntity: Diagnosi::class, inversedBy: 'valutazioneGenerale', cascade:['persist'])]
    private $diagnosi;

    #[ORM\Column(type:"Valutazione", nullable:false)]
    #[Assert\NotBlank]
    private $tipologia_valutazione;

    #[ORM\Column(type: 'PANF', nullable:false)]
    #[Assert\NotBlank]
    private $panf;

    #[ORM\Column(type: 'FANF', nullable:false)]
    #[Assert\NotBlank]
    private $fanf;

    #[ORM\Column(type: 'ISS', nullable:false)]
    #[Assert\NotBlank]
    private $iss;

    #[ORM\Column(type: 'Autonomia', nullable:false)]
    #[Assert\NotBlank]
    private $uso_servizi_igenici;

    #[ORM\Column(type: 'Autonomia', nullable:false)]
    #[Assert\NotBlank]
    private $abbigliamento;

    #[ORM\Column(type: 'Autonomia', nullable:false)]
    #[Assert\NotBlank]
    private $alimentazione;

    #[ORM\Column(type: 'Autonomia', nullable:false)]
    #[Assert\NotBlank]
    private $indicatore_deambulazione;

    #[ORM\Column(type: 'Autonomia', nullable:false)]
    #[Assert\NotBlank]
    private $igene_personale;

    #[ORM\Column(type: 'Disturbi', nullable:false)]
    #[Assert\NotBlank]
    private $cognitivita;

    #[ORM\Column(type: 'Disturbi', nullable:false)]
    #[Assert\NotBlank]
    private $comportamento;

    #[ORM\OneToOne(targetEntity: SchedaPAI::class, mappedBy: 'idValutazioneGenerale',  cascade: ['persist'])]
    private $schedaPAI;

    #[ORM\Column(type: 'boolean')]
    private $buonoSociale;

    #[ORM\Column(type: 'boolean')]
    private $trasporti;

    #[ORM\Column(type: 'boolean')]
    private $voucherSociale;

    #[ORM\Column(type: 'boolean')]
    private $sad;

    #[ORM\Column(type: 'boolean')]
    private $pasti;

    #[ORM\Column(type: 'boolean')]
    private $assistenzaDomiciliare;

    #[ORM\Column(type: 'boolean')]
    private $contributoCaregiver;

    #[ORM\Column(type: 'boolean')]
    private $broncoaspirazione;

    #[ORM\Column(type: 'boolean')]
    private $ventiloTerapia;

    #[ORM\Column(type: 'boolean')]
    private $alimentazioneAssistita;

    #[ORM\Column(type: 'boolean')]
    private $alimentazioneParenterale;

    #[ORM\Column(type: 'boolean')]
    private $eliminazioneUrina;

    #[ORM\Column(type: 'boolean')]
    private $educazioneTerapeutica;

    #[ORM\Column(type: 'boolean')]
    private $ulcereTerzoQuartoGrado;

    #[ORM\Column(type: 'boolean')]
    private $ulcereCutaneeTerzoQuartoGrado;

    #[ORM\Column(type: 'boolean')]
    private $prelieviVenosiOccasionali;

    #[ORM\Column(type: 'boolean')]
    private $telemetria;

    #[ORM\Column(type: 'boolean')]
    private $gestioneCatetere;

    #[ORM\Column(type: 'boolean')]
    private $controlloDolore;

    #[ORM\Column(type: 'boolean')]
    private $assistenzaNonOncologica;

    #[ORM\Column(type: 'boolean')]
    private $trattamentoOrtopedico;

    #[ORM\Column(type: 'boolean')]
    private $supervisioneContinua;

    #[ORM\Column(type: 'boolean')]
    private $assistenzaAdl;

    #[ORM\Column(type: 'boolean')]
    private $ossigenoTerapia;

    #[ORM\Column(type: 'boolean')]
    private $tracheotomia;

    #[ORM\Column(type: 'boolean')]
    private $alimentazioneEnterale;

    #[ORM\Column(type: 'boolean')]
    private $gestioneStomia;

    #[ORM\Column(type: 'boolean')]
    private $alterazioneSonno;

    #[ORM\Column(type: 'boolean')]
    private $ulcerePrimoSecondoGrado;

    #[ORM\Column(type: 'boolean')]
    private $ulcereCutaneePrimoSecondoGrado;

    #[ORM\Column(type: 'boolean')]
    private $prelieviVenosiNonOccasionali;

    #[ORM\Column(type: 'boolean')]
    private $ecg;

    #[ORM\Column(type: 'boolean')]
    private $proceduraTerapeutica;

    #[ORM\Column(type: 'boolean')]
    private $trasfusioni;

    #[ORM\Column(type: 'boolean')]
    private $assistenzaOncologica;

    #[ORM\Column(type: 'boolean')]
    private $trattamentoNeurologico;

    #[ORM\Column(type: 'boolean')]
    private $trattamentoMantenimento;

    #[ORM\Column(type: 'boolean')]
    private $assistenzaIadl;

    #[ORM\Column(type: 'boolean')]
    private $supportoCaregiver;
    
    #[ORM\ManyToOne(targetEntity: "App\Entity\User", inversedBy: 'idValutazioneGenerale', cascade:['persist'])]
    private $autoreValutazioneGenerale;

    public function __construct()
    {
        $this->diagnosi = new ArrayCollection();
        
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataValutazione(): ?\DateTimeInterface
    {
        return $this->data_valutazione;
    }

    public function setDataValutazione(\DateTimeInterface $data_valutazione): self
    {
        $this->data_valutazione = $data_valutazione;

        return $this;
    }

    public function getNComponentiNucleoAbitativo(): ?int
    {
        return $this->n_componenti_nucleo_abitativo;
    }

    public function setNComponentiNucleoAbitativo(int $n_componenti_nucleo_abitativo): self
    {
        $this->n_componenti_nucleo_abitativo = $n_componenti_nucleo_abitativo;

        return $this;
    }

    public function isRischioInfettivo(): ?bool
    {
        return $this->rischio_infettivo;
    }

    public function setRischioInfettivo(bool $rischio_infettivo): self
    {
        $this->rischio_infettivo = $rischio_infettivo;

        return $this;
    }

    /**
     * @return Collection<int, Diagnosi>
     */
    public function getDiagnosi(): Collection
    {
        return $this->diagnosi;
    }

    public function addDiagnosi(Diagnosi $diagnosi): self
    {
        if (!$this->diagnosi->contains($diagnosi)) {
            $this->diagnosi->add($diagnosi);
        }

        return $this;
    }

    public function removeDiagnosi(Diagnosi $diagnosi): self
    {
        $this->diagnosi->removeElement($diagnosi);

        return $this;
    }

    public function getTipologiaValutazione()
    {
        return $this->tipologia_valutazione;
    }

    public function setTipologiaValutazione($tipologia_valutazione): self
    {
        $this->tipologia_valutazione = $tipologia_valutazione;

        return $this;
    }

    public function getPanf()
    {
        return $this->panf;
    }

    public function setPanf($panf): self
    {
        $this->panf = $panf;

        return $this;
    }

    public function getFanf()
    {
        return $this->fanf;
    }

    public function setFanf($fanf): self
    {
        $this->fanf = $fanf;

        return $this;
    }

    public function getIss()
    {
        return $this->iss;
    }

    public function setIss($iss): self
    {
        $this->iss = $iss;

        return $this;
    }

    public function getUsoServiziIgenici()
    {
        return $this->uso_servizi_igenici;
    }

    public function setUsoServiziIgenici($uso_servizi_igenici): self
    {
        $this->uso_servizi_igenici = $uso_servizi_igenici;

        return $this;
    }

    public function getAbbigliamento()
    {
        return $this->abbigliamento;
    }

    public function setAbbigliamento($abbigliamento): self
    {
        $this->abbigliamento = $abbigliamento;

        return $this;
    }

    public function getAlimentazione()
    {
        return $this->alimentazione;
    }

    public function setAlimentazione($alimentazione): self
    {
        $this->alimentazione = $alimentazione;

        return $this;
    }

    public function getIndicatoreDeambulazione()
    {
        return $this->indicatore_deambulazione;
    }

    public function setIndicatoreDeambulazione($indicatore_deambulazione): self
    {
        $this->indicatore_deambulazione = $indicatore_deambulazione;

        return $this;
    }

    public function getIgenePersonale()
    {
        return $this->igene_personale;
    }

    public function setIgenePersonale($igene_personale): self
    {
        $this->igene_personale = $igene_personale;

        return $this;
    }

    public function getCognitivita()
    {
        return $this->cognitivita;
    }

    public function setCognitivita($cognitivita): self
    {
        $this->cognitivita = $cognitivita;

        return $this;
    }

    public function getComportamento()
    {
        return $this->comportamento;
    }

    public function setComportamento($comportamento): self
    {
        $this->comportamento = $comportamento;

        return $this;
    }

    public function getSchedaPAI(): ?SchedaPAI
    {
        return $this->schedaPAI;
    }

    public function setSchedaPAI(?SchedaPAI $schedaPAI): self
    {
        // unset the owning side of the relation if necessary
        if ($schedaPAI === null && $this->schedaPAI !== null) {
            $this->schedaPAI->setIdValutazioneGenerale(null);
        }

        // set the owning side of the relation if necessary
        if ($schedaPAI !== null && $schedaPAI->getIdValutazioneGenerale() !== $this) {
            $schedaPAI->setIdValutazioneGenerale($this);
        }

        $this->schedaPAI = $schedaPAI;

        return $this;
    }

    public function isBuonoSociale(): ?bool
    {
        return $this->buonoSociale;
    }

    public function setBuonoSociale(bool $buonoSociale): self
    {
        $this->buonoSociale = $buonoSociale;

        return $this;
    }

    public function isTrasporti(): ?bool
    {
        return $this->trasporti;
    }

    public function setTrasporti(bool $trasporti): self
    {
        $this->trasporti = $trasporti;

        return $this;
    }

    public function isVoucherSociale(): ?bool
    {
        return $this->voucherSociale;
    }

    public function setVoucherSociale(bool $voucherSociale): self
    {
        $this->voucherSociale = $voucherSociale;

        return $this;
    }

    public function isSad(): ?bool
    {
        return $this->sad;
    }

    public function setSad(bool $sad): self
    {
        $this->sad = $sad;

        return $this;
    }

    public function isPasti(): ?bool
    {
        return $this->pasti;
    }

    public function setPasti(bool $pasti): self
    {
        $this->pasti = $pasti;

        return $this;
    }

    public function isAssistenzaDomiciliare(): ?bool
    {
        return $this->assistenzaDomiciliare;
    }

    public function setAssistenzaDomiciliare(bool $assistenzaDomiciliare): self
    {
        $this->assistenzaDomiciliare = $assistenzaDomiciliare;

        return $this;
    }

    public function isContributoCaregiver(): ?bool
    {
        return $this->contributoCaregiver;
    }

    public function setContributoCaregiver(bool $contributoCaregiver): self
    {
        $this->contributoCaregiver = $contributoCaregiver;

        return $this;
    }

    public function isBroncoaspirazione(): ?bool
    {
        return $this->broncoaspirazione;
    }

    public function setBroncoaspirazione(bool $broncoaspirazione): self
    {
        $this->broncoaspirazione = $broncoaspirazione;

        return $this;
    }

    public function isVentiloTerapia(): ?bool
    {
        return $this->ventiloTerapia;
    }

    public function setVentiloTerapia(bool $ventiloTerapia): self
    {
        $this->ventiloTerapia = $ventiloTerapia;

        return $this;
    }

    public function isAlimentazioneAssistita(): ?bool
    {
        return $this->alimentazioneAssistita;
    }

    public function setAlimentazioneAssistita(bool $alimentazioneAssistita): self
    {
        $this->alimentazioneAssistita = $alimentazioneAssistita;

        return $this;
    }

    public function isAlimentazioneParenterale(): ?bool
    {
        return $this->alimentazioneParenterale;
    }

    public function setAlimentazioneParenterale(bool $alimentazioneParenterale): self
    {
        $this->alimentazioneParenterale = $alimentazioneParenterale;

        return $this;
    }

    public function isEliminazioneUrina(): ?bool
    {
        return $this->eliminazioneUrina;
    }

    public function setEliminazioneUrina(bool $eliminazioneUrina): self
    {
        $this->eliminazioneUrina = $eliminazioneUrina;

        return $this;
    }

    public function isEducazioneTerapeutica(): ?bool
    {
        return $this->educazioneTerapeutica;
    }

    public function setEducazioneTerapeutica(bool $educazioneTerapeutica): self
    {
        $this->educazioneTerapeutica = $educazioneTerapeutica;

        return $this;
    }

    public function isUlcereTerzoQuartoGrado(): ?bool
    {
        return $this->ulcereTerzoQuartoGrado;
    }

    public function setUlcereTerzoQuartoGrado(bool $ulcereTerzoQuartoGrado): self
    {
        $this->ulcereTerzoQuartoGrado = $ulcereTerzoQuartoGrado;

        return $this;
    }

    public function isUlcereCutaneeTerzoQuartoGrado(): ?bool
    {
        return $this->ulcereCutaneeTerzoQuartoGrado;
    }

    public function setUlcereCutaneeTerzoQuartoGrado(bool $ulcereCutaneeTerzoQuartoGrado): self
    {
        $this->ulcereCutaneeTerzoQuartoGrado = $ulcereCutaneeTerzoQuartoGrado;

        return $this;
    }

    public function isPrelieviVenosiOccasionali(): ?bool
    {
        return $this->prelieviVenosiOccasionali;
    }

    public function setPrelieviVenosiOccasionali(bool $prelieviVenosiOccasionali): self
    {
        $this->prelieviVenosiOccasionali = $prelieviVenosiOccasionali;

        return $this;
    }

    public function isTelemetria(): ?bool
    {
        return $this->telemetria;
    }

    public function setTelemetria(bool $telemetria): self
    {
        $this->telemetria = $telemetria;

        return $this;
    }

    public function isGestioneCatetere(): ?bool
    {
        return $this->gestioneCatetere;
    }

    public function setGestioneCatetere(bool $gestioneCatetere): self
    {
        $this->gestioneCatetere = $gestioneCatetere;

        return $this;
    }

    public function isControlloDolore(): ?bool
    {
        return $this->controlloDolore;
    }

    public function setControlloDolore(bool $controlloDolore): self
    {
        $this->controlloDolore = $controlloDolore;

        return $this;
    }

    public function isAssistenzaNonOncologica(): ?bool
    {
        return $this->assistenzaNonOncologica;
    }

    public function setAssistenzaNonOncologica(bool $assistenzaNonOncologica): self
    {
        $this->assistenzaNonOncologica = $assistenzaNonOncologica;

        return $this;
    }

    public function isTrattamentoOrtopedico(): ?bool
    {
        return $this->trattamentoOrtopedico;
    }

    public function setTrattamentoOrtopedico(bool $trattamentoOrtopedico): self
    {
        $this->trattamentoOrtopedico = $trattamentoOrtopedico;

        return $this;
    }

    public function isSupervisioneContinua(): ?bool
    {
        return $this->supervisioneContinua;
    }

    public function setSupervisioneContinua(bool $supervisioneContinua): self
    {
        $this->supervisioneContinua = $supervisioneContinua;

        return $this;
    }

    public function isAssistenzaAdl(): ?bool
    {
        return $this->assistenzaAdl;
    }

    public function setAssistenzaAdl(bool $assistenzaAdl): self
    {
        $this->assistenzaAdl = $assistenzaAdl;

        return $this;
    }

    public function isOssigenoTerapia(): ?bool
    {
        return $this->ossigenoTerapia;
    }

    public function setOssigenoTerapia(bool $ossigenoTerapia): self
    {
        $this->ossigenoTerapia = $ossigenoTerapia;

        return $this;
    }

    public function isTracheotomia(): ?bool
    {
        return $this->tracheotomia;
    }

    public function setTracheotomia(bool $tracheotomia): self
    {
        $this->tracheotomia = $tracheotomia;

        return $this;
    }

    public function isAlimentazioneEnterale(): ?bool
    {
        return $this->alimentazioneEnterale;
    }

    public function setAlimentazioneEnterale(bool $alimentazioneEnterale): self
    {
        $this->alimentazioneEnterale = $alimentazioneEnterale;

        return $this;
    }

    public function isGestioneStomia(): ?bool
    {
        return $this->gestioneStomia;
    }

    public function setGestioneStomia(bool $gestioneStomia): self
    {
        $this->gestioneStomia = $gestioneStomia;

        return $this;
    }

    public function isAlterazioneSonno(): ?bool
    {
        return $this->alterazioneSonno;
    }

    public function setAlterazioneSonno(bool $alterazioneSonno): self
    {
        $this->alterazioneSonno = $alterazioneSonno;

        return $this;
    }

    public function isUlcerePrimoSecondoGrado(): ?bool
    {
        return $this->ulcerePrimoSecondoGrado;
    }

    public function setUlcerePrimoSecondoGrado(bool $ulcerePrimoSecondoGrado): self
    {
        $this->ulcerePrimoSecondoGrado = $ulcerePrimoSecondoGrado;

        return $this;
    }

    public function isUlcereCutaneePrimoSecondoGrado(): ?bool
    {
        return $this->ulcereCutaneePrimoSecondoGrado;
    }

    public function setUlcereCutaneePrimoSecondoGrado(bool $ulcereCutaneePrimoSecondoGrado): self
    {
        $this->ulcereCutaneePrimoSecondoGrado = $ulcereCutaneePrimoSecondoGrado;

        return $this;
    }

    public function isPrelieviVenosiNonOccasionali(): ?bool
    {
        return $this->prelieviVenosiNonOccasionali;
    }

    public function setPrelieviVenosiNonOccasionali(bool $prelieviVenosiNonOccasionali): self
    {
        $this->prelieviVenosiNonOccasionali = $prelieviVenosiNonOccasionali;

        return $this;
    }

    public function isEcg(): ?bool
    {
        return $this->ecg;
    }

    public function setEcg(bool $ecg): self
    {
        $this->ecg = $ecg;

        return $this;
    }

    public function isProceduraTerapeutica(): ?bool
    {
        return $this->proceduraTerapeutica;
    }

    public function setProceduraTerapeutica(bool $proceduraTerapeutica): self
    {
        $this->proceduraTerapeutica = $proceduraTerapeutica;

        return $this;
    }

    public function isTrasfusioni(): ?bool
    {
        return $this->trasfusioni;
    }

    public function setTrasfusioni(bool $trasfusioni): self
    {
        $this->trasfusioni = $trasfusioni;

        return $this;
    }

    public function isAssistenzaOncologica(): ?bool
    {
        return $this->assistenzaOncologica;
    }

    public function setAssistenzaOncologica(bool $assistenzaOncologica): self
    {
        $this->assistenzaOncologica = $assistenzaOncologica;

        return $this;
    }

    public function isTrattamentoNeurologico(): ?bool
    {
        return $this->trattamentoNeurologico;
    }

    public function setTrattamentoNeurologico(bool $trattamentoNeurologico): self
    {
        $this->trattamentoNeurologico = $trattamentoNeurologico;

        return $this;
    }

    public function isTrattamentoMantenimento(): ?bool
    {
        return $this->trattamentoMantenimento;
    }

    public function setTrattamentoMantenimento(bool $trattamentoMantenimento): self
    {
        $this->trattamentoMantenimento = $trattamentoMantenimento;

        return $this;
    }

    public function isAssistenzaIadl(): ?bool
    {
        return $this->assistenzaIadl;
    }

    public function setAssistenzaIadl(bool $assistenzaIadl): self
    {
        $this->assistenzaIadl = $assistenzaIadl;

        return $this;
    }

    public function isSupportoCaregiver(): ?bool
    {
        return $this->supportoCaregiver;
    }

    public function setSupportoCaregiver(bool $supportoCaregiver): self
    {
        $this->supportoCaregiver = $supportoCaregiver;

        return $this;
    }

    public function getOperatore(): ?User
    {
        return $this->autoreValutazioneGenerale;
    }

    public function setOperatore(?User $autoreValutazioneGenerale): self
    {
        $this->autoreValutazioneGenerale = $autoreValutazioneGenerale;

        return $this;
    }

    public function getAutoreValutazioneGenerale(): ?User
    {
        return $this->autoreValutazioneGenerale;
    }

    public function setAutoreValutazioneGenerale(?User $autoreValutazioneGenerale): self
    {
        $this->autoreValutazioneGenerale = $autoreValutazioneGenerale;

        return $this;
    }
   
}
