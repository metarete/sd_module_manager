<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EntityPAI\SchedaPAI;
use App\Repository\ValutazioneFiguraProfessionaleRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ValutazioneFiguraProfessionaleRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_valutazione_figura_professionale')]
class ValutazioneFiguraProfessionale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type:"TipoOperatore", nullable:false)]
    private $tipoOperatore;

    #[ORM\Column(type: 'text')]
    private $diagnosiProfessionale;

    #[ORM\Column(type: 'text')]
    private $obbiettiviDaRaggiungere;

    #[ORM\Column(type: 'text')]
    private $tipoEFrequenza;

    #[ORM\Column(type: 'text')]
    private $modalitaTempiMonitoraggio;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idValutazioneFiguraProfessionale')]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idValutazioneFiguraProfessionale')]
    private $autoreValutazioneProfessionale;

    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getTipoOperatore(): ?string
    {
        return $this->tipoOperatore;
    }

    public function setTipoOperatore(string $tipoOperatore): self
    {
        $this->tipoOperatore = $tipoOperatore;
        
        return $this;
    }

    public function getDiagnosiProfessionale(): ?string
    {
        return $this->diagnosiProfessionale;
    }

    public function setDiagnosiProfessionale(string $diagnosiProfessionale): self
    {
        $this->diagnosiProfessionale = $diagnosiProfessionale;

        return $this;
    }

    public function getObbiettiviDaRaggiungere(): ?string
    {
        return $this->obbiettiviDaRaggiungere;
    }

    public function setObbiettiviDaRaggiungere(string $obbiettiviDaRaggiungere): self
    {
        $this->obbiettiviDaRaggiungere = $obbiettiviDaRaggiungere;

        return $this;
    }

    public function getTipoEFrequenza(): ?string
    {
        return $this->tipoEFrequenza;
    }

    public function setTipoEFrequenza(string $tipoEFrequenza): self
    {
        $this->tipoEFrequenza = $tipoEFrequenza;

        return $this;
    }

    public function getModalitaTempiMonitoraggio(): ?string
    {
        return $this->modalitaTempiMonitoraggio;
    }

    public function setModalitaTempiMonitoraggio(string $modalitaTempiMonitoraggio): self
    {
        $this->modalitaTempiMonitoraggio = $modalitaTempiMonitoraggio;

        return $this;
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
        return $this->autoreValutazioneProfessionale;
    }

    public function setOperatore(?User $autoreValutazioneProfessionale): self
    {
        $this->autoreValutazioneProfessionale = $autoreValutazioneProfessionale;

        return $this;
    }
}
