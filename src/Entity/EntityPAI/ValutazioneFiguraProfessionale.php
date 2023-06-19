<?php

namespace App\Entity\EntityPAI;

use App\Entity\TipiAdiweb;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\Obiettivi;
use App\Entity\Diagnosi;
use App\Repository\ValutazioneFiguraProfessionaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    #[Assert\NotBlank]
    private $tipoOperatore;

    #[ORM\ManyToMany(targetEntity: Diagnosi::class, inversedBy: 'valutazioneFiguraProfessionale', cascade:['persist'])]
    private $diagnosi;

    #[ORM\ManyToMany(targetEntity: Obiettivi::class, inversedBy: 'valutazioneFiguraProfessionale', cascade:['persist'])]
    private $obiettivi;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frequenza;

    #[ORM\Column(type: 'text', nullable: true)]
    private $osservazioni;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idValutazioneFiguraProfessionale', cascade:['persist'])]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idValutazioneFiguraProfessionale', cascade:['persist'])]
    private $autoreValutazioneProfessionale;

    #[ORM\ManyToMany(targetEntity: TipiAdiweb::class, inversedBy: 'valutazioneProfessionale', cascade:['persist'])]
    private Collection $tipiAdiwebs;

    public function __construct()
    {
        $this->obiettivi = new ArrayCollection();
        $this->diagnosi = new ArrayCollection();
        $this->tipiAdiwebs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTipoOperatore()
    {
        return $this->tipoOperatore;
    }

    public function setTipoOperatore($tipoOperatore): self
    {
        $this->tipoOperatore = $tipoOperatore;

        return $this;
    }

    public function getFrequenza(): ?string
    {
        return $this->frequenza;
    }

    public function setFrequenza(?string $frequenza): self
    {
        $this->frequenza = $frequenza;

        return $this;
    }

    public function getOsservazioni(): ?string
    {
        return $this->osservazioni;
    }

    public function setOsservazioni(?string $osservazioni): self
    {
        $this->osservazioni = $osservazioni;

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

    /**
     * @return Collection<int, Obiettivi>
     */
    public function getObiettivi(): Collection
    {
        return $this->obiettivi;
    }

    public function addObiettivi(Obiettivi $obiettivi): self
    {
        if (!$this->obiettivi->contains($obiettivi)) {
            $this->obiettivi->add($obiettivi);
        }

        return $this;
    }

    public function removeObiettivi(Obiettivi $obiettivi): self
    {
        $this->obiettivi->removeElement($obiettivi);

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

    public function getAutoreValutazioneProfessionale(): ?User
    {
        return $this->autoreValutazioneProfessionale;
    }

    public function setAutoreValutazioneProfessionale(?User $autoreValutazioneProfessionale): self
    {
        $this->autoreValutazioneProfessionale = $autoreValutazioneProfessionale;

        return $this;
    }

    /**
     * @return Collection<int, TipiAdiweb>
     */
    public function getTipiAdiwebs(): Collection
    {
        return $this->tipiAdiwebs;
    }

    public function addTipiAdiwebs(TipiAdiweb $tipiAdiweb): self
    {
        if (!$this->tipiAdiwebs->contains($tipiAdiweb)) {
            $this->tipiAdiwebs->add($tipiAdiweb);
            $tipiAdiweb->addValutazioneProfessionale($this);
        }

        return $this;
    }

    public function removeTipiAdiwebs(TipiAdiweb $tipiAdiweb): self
    {
        if ($this->tipiAdiwebs->removeElement($tipiAdiweb)) {
            $tipiAdiweb->removeValutazioneProfessionale($this);
        }

        return $this;
    }
}
