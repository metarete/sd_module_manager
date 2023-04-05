<?php

namespace App\Entity;

use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use App\Repository\ObiettiviRepository;
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

    public function isStato(): bool
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
    public function getValutazioneFiguraProfessionale()
    {
        return $this->valutazioneFiguraProfessionale;
    }

    public function addValutazioneFiguraProfessionale(ValutazioneFiguraProfessionale $valutazioneFiguraProfessionale): self
    {
        if (!$this->valutazioneFiguraProfessionale->contains($valutazioneFiguraProfessionale)) {
            $this->valutazioneFiguraProfessionale[] = $valutazioneFiguraProfessionale;
            
        }

        return $this;
    }

    public function removeValutazioneFiguraProfessionale(ValutazioneFiguraProfessionale $valutazioneFiguraProfessionale): self
    {
        if ($this->valutazioneFiguraProfessionale->removeElement($valutazioneFiguraProfessionale)) {
            // set the owning side to null (unless already changed)
           
        }

        return $this;
    }

}