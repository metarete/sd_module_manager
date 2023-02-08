<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\ChiusuraServizioRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChiusuraServizioRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_chiusura_servizio')]

class ChiusuraServizio
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $conclusioni;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\Column(type: 'boolean')]
    private $rinnovo = false;

    #[ORM\OneToOne(targetEntity: SchedaPAI::class, mappedBy: 'idChiusuraServizio',  cascade: ['persist'])]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idChiusuraServizio')]
    private $autoreChiusuraServizio;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRinnovo(): ?bool
    {
        return $this->rinnovo;
    }

    public function setRinnovo(bool $rinnovo): self
    {
        $this->rinnovo = $rinnovo;

        return $this;
    }

    public function getConclusioni(): ?string
    {
        return $this->conclusioni;
    }

    public function setConclusioni(string $conclusioni): self
    {
        $this->conclusioni = $conclusioni;

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
        return $this->autoreChiusuraServizio;
    }

    public function setOperatore(?User $autoreChiusuraServizio): self
    {
        $this->autoreChiusuraServizio = $autoreChiusuraServizio;

        return $this;
    }
}
