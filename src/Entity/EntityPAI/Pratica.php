<?php

namespace App\Entity\EntityPAI;

use App\Repository\PraticaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PraticaRepository::class)]
class Pratica
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $codice = null;

    #[ORM\OneToMany(mappedBy: 'adiwebPratica', targetEntity: SchedaPAI::class, cascade:['persist'])]
    private Collection $schedaPAIs;

    public function __construct()
    {
        $this->schedaPAIs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->codice;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCodice(): ?string
    {
        return $this->codice;
    }

    public function setCodice(string $codice): self
    {
        $this->codice = $codice;

        return $this;
    }

    /**
     * @return Collection<int, SchedaPAI>
     */
    public function getSchedaPais(): Collection
    {
        return $this->schedaPAIs;
    }

    public function addSchedaPAI(SchedaPAI $schedaPAI): self
    {
        if (!$this->schedaPAIs->contains($schedaPAI)) {
            $this->schedaPAIs->add($schedaPAI);
            $schedaPAI->setAdiwebPratica($this);
        }

        return $this;
    }

    public function removeSchedaPAI(SchedaPAI $schedaPAI): self
    {
        if ($this->schedaPAIs->removeElement($schedaPAI)) {
            // set the owning side to null (unless already changed)
            if ($schedaPAI->getAdiwebPratica() === $this) {
                $schedaPAI->setAdiwebPratica(null);
            }
        }

        return $this;
    }   
}
