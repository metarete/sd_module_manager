<?php

namespace App\Entity;

use App\Repository\PazienteRepository;
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
}
