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
    #[Assert\NotBlank]
    private $conclusioni;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $motivazioneChiusura;

    #[ORM\OneToOne(targetEntity: SchedaPAI::class, mappedBy: 'idChiusuraServizio',  cascade: ['persist'])]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idChiusuraServizio', cascade:['persist'])]
    private $autoreChiusuraServizio;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMotivazioneChiusura(): ?string
    {
        return $this->motivazioneChiusura;
    }

    public function setMotivazioneChiusura(?string $motivazioneChiusura): self
    {
        $this->motivazioneChiusura = $motivazioneChiusura;

        return $this;
    }

    public function getSchedaPAI(): ?SchedaPAI
    {
        return $this->schedaPAI;
    }

    public function setSchedaPAI(?SchedaPAI $schedaPAI): self
    {
        // unset the owning side of the relation if necessary
        if ($schedaPAI === null && $this->schedaPAI !== null) {
            $this->schedaPAI->setIdChiusuraServizio(null);
        }

        // set the owning side of the relation if necessary
        if ($schedaPAI !== null && $schedaPAI->getIdChiusuraServizio() !== $this) {
            $schedaPAI->setIdChiusuraServizio($this);
        }

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

    public function getAutoreChiusuraServizio(): ?User
    {
        return $this->autoreChiusuraServizio;
    }

    public function setAutoreChiusuraServizio(?User $autoreChiusuraServizio): self
    {
        $this->autoreChiusuraServizio = $autoreChiusuraServizio;

        return $this;
    }
}
