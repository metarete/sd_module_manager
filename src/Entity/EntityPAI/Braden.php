<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\BradenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BradenRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_braden')]

class Braden
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $percezioneSensoriale;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $umidita;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $attivita;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $mobilita;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $nutrizione;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $frizioneScivolamento;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $totale;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idBraden', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idBraden', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $autoreBraden;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPercezioneSensoriale(): ?int
    {
        return $this->percezioneSensoriale;
    }

    public function setPercezioneSensoriale(int $percezioneSensoriale): self
    {
        $this->percezioneSensoriale = $percezioneSensoriale;

        return $this;
    }

    public function getUmidita(): ?int
    {
        return $this->umidita;
    }

    public function setUmidita(int $umidita): self
    {
        $this->umidita = $umidita;

        return $this;
    }

    public function getAttivita(): ?int
    {
        return $this->attivita;
    }

    public function setAttivita(int $attivita): self
    {
        $this->attivita = $attivita;

        return $this;
    }

    public function getMobilita(): ?int
    {
        return $this->mobilita;
    }

    public function setMobilita(int $mobilita): self
    {
        $this->mobilita = $mobilita;

        return $this;
    }

    public function getNutrizione(): ?int
    {
        return $this->nutrizione;
    }

    public function setNutrizione(int $nutrizione): self
    {
        $this->nutrizione = $nutrizione;

        return $this;
    }

    public function getFrizioneScivolamento(): ?int
    {
        return $this->frizioneScivolamento;
    }

    public function setFrizioneScivolamento(int $frizioneScivolamento): self
    {
        $this->frizioneScivolamento = $frizioneScivolamento;

        return $this;
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

    public function getTotale(): ?int
    {
        return $this->totale;
    }

    public function setTotale(?int $totale): self
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
        return $this->autoreBraden;
    }

    public function setOperatore(?User $autoreBraden): self
    {
        $this->autoreBraden = $autoreBraden;

        return $this;
    }

    public function getAutoreBraden(): ?User
    {
        return $this->autoreBraden;
    }

    public function setAutoreBraden(?User $autoreBraden): self
    {
        $this->autoreBraden = $autoreBraden;

        return $this;
    }
}
