<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\CdrRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CdrRepository::class)]
class Cdr
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dataValutazione = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $memoria = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $orientamento = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $giudizio = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attivitaSociali = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $casa = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $curaPersonale = null;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'cdrs', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?SchedaPAI $schedaPAI = null;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'cdrs', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?User $autoreCdr = null;

    #[ORM\Column]
    private ?bool $cdr4 = null;

    #[ORM\Column]
    private ?bool $cdr5 = null;

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

    public function getMemoria(): ?string
    {
        return $this->memoria;
    }

    public function setMemoria(?string $memoria): self
    {
        $this->memoria = $memoria;

        return $this;
    }

    public function getOrientamento(): ?string
    {
        return $this->orientamento;
    }

    public function setOrientamento(?string $orientamento): self
    {
        $this->orientamento = $orientamento;

        return $this;
    }

    public function getGiudizio(): ?string
    {
        return $this->giudizio;
    }

    public function setGiudizio(?string $giudizio): self
    {
        $this->giudizio = $giudizio;

        return $this;
    }

    public function getAttivitaSociali(): ?string
    {
        return $this->attivitaSociali;
    }

    public function setAttivitaSociali(?string $attivitaSociali): self
    {
        $this->attivitaSociali = $attivitaSociali;

        return $this;
    }

    public function getCasa(): ?string
    {
        return $this->casa;
    }

    public function setCasa(?string $casa): self
    {
        $this->casa = $casa;

        return $this;
    }

    public function getCuraPersonale(): ?string
    {
        return $this->curaPersonale;
    }

    public function setCuraPersonale(?string $curaPersonale): self
    {
        $this->curaPersonale = $curaPersonale;

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

    public function getAutoreCdr(): ?User
    {
        return $this->autoreCdr;
    }

    public function setAutoreCdr(?User $autoreCdr): self
    {
        $this->autoreCdr = $autoreCdr;

        return $this;
    }

    public function isCdr4(): ?bool
    {
        return $this->cdr4;
    }

    public function setCdr4(bool $cdr4): self
    {
        $this->cdr4 = $cdr4;

        return $this;
    }

    public function isCdr5(): ?bool
    {
        return $this->cdr5;
    }

    public function setCdr5(bool $cdr5): self
    {
        $this->cdr5 = $cdr5;

        return $this;
    }
}
