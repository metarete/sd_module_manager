<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BarthelRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BarthelRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_barthel')]

class Barthel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $alimentazione;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $bagnoDoccia;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $igienePersonale;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $abbigliamento;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $continenzaIntestinale;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $continenzaUrinaria;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $toilet;

    #[ORM\Column(type: 'integer',  nullable: true)]
    private $totaleValutazioneFunzionale;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $trasferimentoLettoSedia;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $scale;

    //#[ORM\Column(type: 'boolean')]
    //private $deambulazione;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $deambulazioneValida;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $usoCarrozzina;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $totale;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idBarthel', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $autoreBarthel;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idBarthel', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private $schedaPAI;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataValutazione(): ?\DateTimeInterface
    {
        return $this->dataValutazione;
    }

    public function setDataValutazione(\DateTimeInterface $dataValutazione): self
    {
        $this->dataValutazione = $dataValutazione;

        return $this;
    }

    public function getAlimentazione(): ?int
    {
        return $this->alimentazione;
    }

    public function setAlimentazione(int $alimentazione): self
    {
        $this->alimentazione = $alimentazione;

        return $this;
    }

    public function getBagnoDoccia(): ?int
    {
        return $this->bagnoDoccia;
    }

    public function setBagnoDoccia(int $bagnoDoccia): self
    {
        $this->bagnoDoccia = $bagnoDoccia;

        return $this;
    }

    public function getIgienePersonale(): ?int
    {
        return $this->igienePersonale;
    }

    public function setIgienePersonale(int $igienePersonale): self
    {
        $this->igienePersonale = $igienePersonale;

        return $this;
    }

    public function getAbbigliamento(): ?int
    {
        return $this->abbigliamento;
    }

    public function setAbbigliamento(int $abbigliamento): self
    {
        $this->abbigliamento = $abbigliamento;

        return $this;
    }

    public function getContinenzaIntestinale(): ?int
    {
        return $this->continenzaIntestinale;
    }

    public function setContinenzaIntestinale(int $continenzaIntestinale): self
    {
        $this->continenzaIntestinale = $continenzaIntestinale;

        return $this;
    }

    public function getContinenzaUrinaria(): ?int
    {
        return $this->continenzaUrinaria;
    }

    public function setContinenzaUrinaria(int $continenzaUrinaria): self
    {
        $this->continenzaUrinaria = $continenzaUrinaria;

        return $this;
    }

    public function getToilet(): ?int
    {
        return $this->toilet;
    }

    public function setToilet(int $toilet): self
    {
        $this->toilet = $toilet;

        return $this;
    }

    public function getTotaleValutazioneFunzionale(): ?int
    {
        return $this->totaleValutazioneFunzionale;
    }

    public function setTotaleValutazioneFunzionale(?int $totaleValutazioneFunzionale): self
    {
        $this->totaleValutazioneFunzionale = $totaleValutazioneFunzionale;

        return $this;
    }

    public function getTrasferimentoLettoSedia(): ?int
    {
        return $this->trasferimentoLettoSedia;
    }

    public function setTrasferimentoLettoSedia(int $trasferimentoLettoSedia): self
    {
        $this->trasferimentoLettoSedia = $trasferimentoLettoSedia;

        return $this;
    }

    public function getScale(): ?int
    {
        return $this->scale;
    }

    public function setScale(int $scale): self
    {
        $this->scale = $scale;

        return $this;
    }

    public function getDeambulazioneValida(): ?int
    {
        return $this->deambulazioneValida;
    }

    public function setDeambulazioneValida(int $deambulazioneValida): self
    {
        $this->deambulazioneValida = $deambulazioneValida;

        return $this;
    }

    public function getUsoCarrozzina(): ?int
    {
        return $this->usoCarrozzina;
    }

    public function setUsoCarrozzina(?int $usoCarrozzina): self
    {
        $this->usoCarrozzina = $usoCarrozzina;

        return $this;
    }

    public function getTotale(): ?int
    {
        return $this->totale;
    }

    public function setTotale(?int $totale): self
    {
        $this->totale = $totale;

        return $this;
    }

    public function getSchedaPAI(): ?SchedaPAI
    {
        return $this->schedaPAI;
    }

    public function setSchedaPAI(?SchedaPAI $schedaPAI): self
    {
        $this->schedaPAI = $schedaPAI;

        return $this;
    }
    public function getOperatore(): ?User
    {
        return $this->autoreBarthel;
    }

    public function setOperatore(?User $autoreBarthel): self
    {
        $this->autoreBarthel = $autoreBarthel;

        return $this;
    }

    public function getAutoreBarthel(): ?User
    {
        return $this->autoreBarthel;
    }

    public function setAutoreBarthel(?User $autoreBarthel): self
    {
        $this->autoreBarthel = $autoreBarthel;

        return $this;
    }
}
