<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\SPMSQRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SPMSQRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_spmsq')]

class SPMSQ
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\Column(type: 'boolean')]
    private $giornoOggi;

    #[ORM\Column(type: 'boolean')]
    private $giornoSettimana;

    #[ORM\Column(type: 'boolean')]
    private $posto;

    #[ORM\Column(type: 'boolean')]
    private $indirizzo;

    #[ORM\Column(type: 'boolean')]
    private $anni;

    #[ORM\Column(type: 'boolean')]
    private $dataNascita;

    #[ORM\Column(type: 'boolean')]
    private $presidenteAttuale;

    #[ORM\Column(type: 'boolean')]
    private $presidentePrecedente;

    #[ORM\Column(type: 'boolean')]
    private $cognomeMadre;

    #[ORM\Column(type: 'boolean')]
    private $sottrazione;

    #[ORM\Column(type: 'integer')]
    private $totale;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idSpmsq')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idSpmsq')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $autoreSpmsq;

    public function getId(): ?int
    {
        return $this->id;
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

    public function isGiornoOggi(): ?bool
    {
        return $this->giornoOggi;
    }

    public function setGiornoOggi(bool $giornoOggi): self
    {
        $this->giornoOggi = $giornoOggi;

        return $this;
    }

    public function isGiornoSettimana(): ?bool
    {
        return $this->giornoSettimana;
    }

    public function setGiornoSettimana(bool $giornoSettimana): self
    {
        $this->giornoSettimana = $giornoSettimana;

        return $this;
    }

    public function isPosto(): ?bool
    {
        return $this->posto;
    }

    public function setPosto(bool $posto): self
    {
        $this->posto = $posto;

        return $this;
    }

    public function isIndirizzo(): ?bool
    {
        return $this->indirizzo;
    }

    public function setIndirizzo(bool $indirizzo): self
    {
        $this->indirizzo = $indirizzo;

        return $this;
    }

    public function isAnni(): ?bool
    {
        return $this->anni;
    }

    public function setAnni(bool $anni): self
    {
        $this->anni = $anni;

        return $this;
    }

    public function isDataNascita(): ?bool
    {
        return $this->dataNascita;
    }

    public function setDataNascita(bool $dataNascita): self
    {
        $this->dataNascita = $dataNascita;

        return $this;
    }

    public function isPresidenteAttuale(): ?bool
    {
        return $this->presidenteAttuale;
    }

    public function setPresidenteAttuale(bool $presidenteAttuale): self
    {
        $this->presidenteAttuale = $presidenteAttuale;

        return $this;
    }

    public function isPresidentePrecedente(): ?bool
    {
        return $this->presidentePrecedente;
    }

    public function setPresidentePrecedente(bool $presidentePrecedente): self
    {
        $this->presidentePrecedente = $presidentePrecedente;

        return $this;
    }

    public function isCognomeMadre(): ?bool
    {
        return $this->cognomeMadre;
    }

    public function setCognomeMadre(bool $cognomeMadre): self
    {
        $this->cognomeMadre = $cognomeMadre;

        return $this;
    }

    public function isSottrazione(): ?bool
    {
        return $this->sottrazione;
    }

    public function setSottrazione(bool $sottrazione): self
    {
        $this->sottrazione = $sottrazione;

        return $this;
    }

    public function getTotale(): ?int
    {
        return $this->totale;
    }

    public function setTotale(int $totale): self
    {
        $this->totale = $totale;

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
        return $this->autoreSpmsq;
    }

    public function setOperatore(?User $autoreSpmsq): self
    {
        $this->autoreSpmsq = $autoreSpmsq;

        return $this;
    }
}
