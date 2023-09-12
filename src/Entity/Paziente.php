<?php

namespace App\Entity;

use App\Entity\EntityPAI\SchedaPAI;
use App\Repository\PazienteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PazienteRepository::class)]
class Paziente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column(length: 255)]
    private ?string $cognome = null;

    #[ORM\Column(length: 255)]
    private ?string $codiceFiscale;

    #[ORM\Column(length: 255)]
    private ?string $indirizzo;
    
    #[ORM\Column(length: 255)]
    private ?string $comune;

    #[ORM\Column(length: 255)]
    private ?string $provincia;

    #[ORM\Column(length: 255)]
    private ?int $cap;

    #[ORM\Column]
    private ?int $idSdManager;

    #[ORM\OneToMany(mappedBy: 'assistito', targetEntity: SchedaPAI::class, cascade:['persist'])]
    private Collection $schedaPAIs;

    #[ORM\Column(length: 180, nullable:true)]
    private ?string $emailFiguraDiRiferimento;

    public function __construct()
    {
        $this->schedaPAIs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCognome(): ?string
    {
        return $this->cognome;
    }

    public function setCognome(string $cognome): self
    {
        $this->cognome = $cognome;

        return $this;
    }

    public function getCodiceFiscale(): ?string
    {
        return $this->codiceFiscale;
    }

    public function setCodiceFiscale(string $codiceFiscale): self
    {
        $this->codiceFiscale = $codiceFiscale;

        return $this;
    }

    public function getIndirizzo(): ?string
    {
        return $this->indirizzo;
    }

    public function setIndirizzo(string $indirizzo): self
    {
        $this->indirizzo = $indirizzo;

        return $this;
    }

    public function getComune(): ?string
    {
        return $this->comune;
    }

    public function setComune(string $comune): self
    {
        $this->comune = $comune;

        return $this;
    }

    public function getProvincia(): ?string
    {
        return $this->provincia;
    }

    public function setProvincia(string $provincia): self
    {
        $this->provincia = $provincia;

        return $this;
    }

    public function getCap(): ?int
    {
        return $this->cap;
    }

    public function setCap(int $cap): self
    {
        $this->cap = $cap;

        return $this;
    }   
    public function getIdSdManager(): ?int
    {
        return $this->idSdManager;
    }

    public function setIdSdManager(int $idSdManager): self
    {
        $this->idSdManager = $idSdManager;

        return $this;
    }

    /**
     * @return Collection<int, SchedaPAI>
     */
    public function getSchedaPAIs(): Collection
    {
        return $this->schedaPAIs;
    }

    public function addSchedaPAI(SchedaPAI $schedaPAI): self
    {
        if (!$this->schedaPAIs->contains($schedaPAI)) {
            $this->schedaPAIs->add($schedaPAI);
            $schedaPAI->setAssistito($this);
        }

        return $this;
    }

    public function removeSchedaPAI(SchedaPAI $schedaPAI): self
    {
        if ($this->schedaPAIs->removeElement($schedaPAI)) {
            // set the owning side to null (unless already changed)
            if ($schedaPAI->getAssistito() === $this) {
                $schedaPAI->setAssistito(null);
            }
        }

        return $this;
    }   

    public function getEmailFiguraDiRiferimento(): ?string
    {
        return $this->emailFiguraDiRiferimento;
    }

    public function setEmailFiguraDiRiferimento(?string $emailFiguraDiRiferimento): self
    {
        $this->emailFiguraDiRiferimento = $emailFiguraDiRiferimento;

        return $this;
    }
}
