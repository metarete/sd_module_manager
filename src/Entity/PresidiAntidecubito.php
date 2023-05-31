<?php

namespace App\Entity;

use App\Entity\EntityPAI\Braden;
use App\Repository\PresidiAntidecubitoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PresidiAntidecubitoRepository::class)]
class PresidiAntidecubito
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\ManyToMany(targetEntity: Braden::class, mappedBy: 'presidiAntidecubito')]
    private Collection $braden;

    public function __construct()
    {
        $this->braden = new ArrayCollection();
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
     * @return Collection<int, Braden>
     */
    public function getBraden(): Collection
    {
        return $this->braden;
    }

    public function addBraden(Braden $braden): self
    {
        if (!$this->braden->contains($braden)) {
            $this->braden->add($braden);
        }

        return $this;
    }

    public function removeBraden(Braden $braden): self
    {
        $this->braden->removeElement($braden);

        return $this;
    }
}
