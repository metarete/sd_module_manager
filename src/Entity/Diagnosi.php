<?php

namespace App\Entity;

use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use App\Entity\EntityPAI\ValutazioneGenerale;
use App\Repository\DiagnosiRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DiagnosiRepository::class)]
class Diagnosi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private $codice;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private $descrizione;

    #[ORM\ManyToMany(mappedBy: 'diagnosi', targetEntity: ValutazioneFiguraProfessionale::class)]
    private $valutazioneFiguraProfessionale;

    #[ORM\ManyToMany(mappedBy: 'diagnosi', targetEntity: ValutazioneGenerale::class)]
    private $valutazioneGenerale;

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

    public function getDescrizione(): ?string
    {
        return $this->descrizione;
    }

    public function setDescrizione(string $descrizione): self
    {
        $this->descrizione = $descrizione;

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

    /**
     * @return Collection<int, ValutazioneGenerale>
     */
    public function getValutazioneGenerale()
    {
        return $this->valutazioneGenerale;
    }

    public function addValutazioneGenerale(ValutazioneGenerale $valutazioneGenerale): self
    {
        if (!$this->valutazioneGenerale->contains($valutazioneGenerale)) {
            $this->valutazioneGenerale[] = $valutazioneGenerale;
            
        }

        return $this;
    }

    public function removeValutazioneGenerale(ValutazioneGenerale $valutazioneGenerale): self
    {
        if ($this->valutazioneGenerale->removeElement($valutazioneGenerale)) {
            // set the owning side to null (unless already changed)
           
        }

        return $this;
    }
}