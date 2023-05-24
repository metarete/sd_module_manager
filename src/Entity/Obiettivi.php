<?php

namespace App\Entity;

use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use App\Repository\ObiettiviRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ObiettiviRepository::class)]
class Obiettivi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private $titolo;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private $descrizione;

    #[ORM\Column(type: 'boolean')]
    private $stato;

    #[ORM\ManyToMany(mappedBy: 'obiettivi', targetEntity: ValutazioneFiguraProfessionale::class)]
    private $valutazioneFiguraProfessionale;

    public function __construct()
    {
        $this->valutazioneFiguraProfessionale = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitolo(): ?string
    {
        return $this->titolo;
    }

    public function setTitolo(string $titolo): self
    {
        $this->titolo = $titolo;

        return $this;
    }

    public function getDescrizione(): ?string
    {
        return $this->descrizione;
    }

    public function setDescrizione(string $descrizione): self
    {
        $this->descrizione = $descrizione;

        return $this;
    }

    public function isStato(): ?bool
    {
        return $this->stato;
    }

    public function setStato(bool $stato): self
    {
        $this->stato = $stato;

        return $this;
    }

    /**
     * @return Collection<int, ValutazioneFiguraProfessionale>
     */
    public function getValutazioneFiguraProfessionale(): Collection
    {
        return $this->valutazioneFiguraProfessionale;
    }

    public function addValutazioneFiguraProfessionale(ValutazioneFiguraProfessionale $valutazioneFiguraProfessionale): self
    {
        if (!$this->valutazioneFiguraProfessionale->contains($valutazioneFiguraProfessionale)) {
            $this->valutazioneFiguraProfessionale->add($valutazioneFiguraProfessionale);
            $valutazioneFiguraProfessionale->addObiettivi($this);
        }

        return $this;
    }

    public function removeValutazioneFiguraProfessionale(ValutazioneFiguraProfessionale $valutazioneFiguraProfessionale): self
    {
        if ($this->valutazioneFiguraProfessionale->removeElement($valutazioneFiguraProfessionale)) {
            $valutazioneFiguraProfessionale->removeObiettivi($this);
        }

        return $this;
    }

}