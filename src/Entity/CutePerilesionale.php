<?php

namespace App\Entity;

use App\Entity\EntityPAI\Lesioni;
use App\Repository\CutePerilesionaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CutePerilesionaleRepository::class)]
class CutePerilesionale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\ManyToMany(targetEntity: Lesioni::class, mappedBy: 'cutePerilesionale', cascade:['persist'])]
    private Collection $lesioni;

    public function __construct()
    {
        $this->lesioni = new ArrayCollection();
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

    /**
     * @return Collection<int, Lesioni>
     */
    public function getLesioni(): Collection
    {
        return $this->lesioni;
    }

    public function addLesioni(Lesioni $lesioni): self
    {
        if (!$this->lesioni->contains($lesioni)) {
            $this->lesioni->add($lesioni);
            $lesioni->addCutePerilesionale($this);
        }

        return $this;
    }

    public function removeLesioni(Lesioni $lesioni): self
    {
        if ($this->lesioni->removeElement($lesioni)) {
            $lesioni->removeCutePerilesionale($this);
        }

        return $this;
    }
}
