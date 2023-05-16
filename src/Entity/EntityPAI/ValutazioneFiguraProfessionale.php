<?php

namespace App\Entity\EntityPAI;

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

    #[ORM\ManyToMany(targetEntity: Diagnosi::class, inversedBy: 'valutazioneFiguraProfessionale')]
    private $diagnosi;

    #[ORM\ManyToMany(targetEntity: Obiettivi::class, inversedBy: 'valutazioneFiguraProfessionale')]
    private $obiettivi;

    #[ORM\Column(type: 'text')]
    private $tipoEFrequenza;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idValutazioneFiguraProfessionale')]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idValutazioneFiguraProfessionale')]
    private $autoreValutazioneProfessionale;

    public function __construct()
    {
        $this->obiettivi = new ArrayCollection();
        $this->diagnosi = new ArrayCollection();
    }

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

    public function getTipoEFrequenza(): ?string
    {
        return $this->tipoEFrequenza;
    }

    public function setTipoEFrequenza(string $tipoEFrequenza): self
    {
        $this->tipoEFrequenza = $tipoEFrequenza;

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
            $this->obiettivi[] = $obiettivi;
        }

        return $this;
    }

    public function removeObiettivi(Obiettivi $obiettivi): self
    {
        if ($this->obiettivi->removeElement($obiettivi)) {
            // set the owning side to null (unless already changed)

        }

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
            $this->diagnosi[] = $diagnosi;
        }

        return $this;
    }

    public function removeDiagnosi(Diagnosi $diagnosi): self
    {
        if ($this->diagnosi->removeElement($diagnosi)) {
            // set the owning side to null (unless already changed)

        }

        return $this;
    }
}
