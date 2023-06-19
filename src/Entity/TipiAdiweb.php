<?php

namespace App\Entity;

use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use App\Repository\TipiAdiwebRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TipiAdiwebRepository::class)]
class TipiAdiweb
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column(length: 255)]
    private ?string $descrizione = null;

    #[ORM\Column]
    private ?int $codice = null;

    #[ORM\Column]
    private ?int $adiwebIdPrestazione = null;

    #[ORM\ManyToMany(targetEntity: ValutazioneFiguraProfessionale::class, mappedBy: 'tipiAdiwebs')]
    private Collection $valutazioneProfessionale;

    #[ORM\Column(length: 255)]
    private ?string $tipoOperatore = null;

    public function __construct()
    {
        $this->valutazioneProfessionale = new ArrayCollection();
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

    public function getDescrizione(): ?string
    {
        return $this->descrizione;
    }

    public function setDescrizione(string $descrizione): self
    {
        $this->descrizione = $descrizione;

        return $this;
    }

    public function getCodice(): ?int
    {
        return $this->codice;
    }

    public function setCodice(int $codice): self
    {
        $this->codice = $codice;

        return $this;
    }

    public function getAdiwebIdPrestazione(): ?int
    {
        return $this->adiwebIdPrestazione;
    }

    public function setAdiwebIdPrestazione(int $adiwebIdPrestazione): self
    {
        $this->adiwebIdPrestazione = $adiwebIdPrestazione;

        return $this;
    }

    /**
     * @return Collection<int, ValutazioneFiguraProfessionale>
     */
    public function getValutazioneProfessionale(): Collection
    {
        return $this->valutazioneProfessionale;
    }

    public function addValutazioneProfessionale(ValutazioneFiguraProfessionale $valutazioneProfessionale): self
    {
        if (!$this->valutazioneProfessionale->contains($valutazioneProfessionale)) {
            $this->valutazioneProfessionale->add($valutazioneProfessionale);
        }

        return $this;
    }

    public function removeValutazioneProfessionale(ValutazioneFiguraProfessionale $valutazioneProfessionale): self
    {
        $this->valutazioneProfessionale->removeElement($valutazioneProfessionale);

        return $this;
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
}
