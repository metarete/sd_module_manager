<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\ChiusuraForzataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChiusuraForzataRepository::class)]
class ChiusuraForzata
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $data = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $conclusioni = null;

    #[ORM\Column(type:"MotivazioneChiusuraForzata", nullable:false)]
    #[Assert\NotBlank]
    private $motivazioneChiusuraForzata;

    #[ORM\OneToOne(targetEntity: SchedaPAI::class, mappedBy: 'idChiusuraForzata',  cascade: ['persist'])]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idChiusuraForzata', cascade:['persist'])]
    private $autoreChiusuraForzata;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getConclusioni(): ?string
    {
        return $this->conclusioni;
    }

    public function setConclusioni(string $conclusioni): static
    {
        $this->conclusioni = $conclusioni;

        return $this;
    }

    public function getMotivazioneChiusuraForzata()
    {
        return $this->motivazioneChiusuraForzata;
    }

    public function setMotivazioneChiusuraForzata($motivazioneChiusuraForzata): self
    {
        $this->motivazioneChiusuraForzata = $motivazioneChiusuraForzata;

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
            $this->schedaPAI->setIdChiusuraForzata(null);
        }

        // set the owning side of the relation if necessary
        if ($schedaPAI !== null && $schedaPAI->getIdChiusuraForzata() !== $this) {
            $schedaPAI->setIdChiusuraForzata($this);
        }

        $this->schedaPAI = $schedaPAI;

        return $this;
    }

    public function getOperatore(): ?User
    {
        return $this->autoreChiusuraForzata;
    }

    public function setOperatore(?User $autoreChiusuraForzata): self
    {
        $this->autoreChiusuraForzata = $autoreChiusuraForzata;

        return $this;
    }

    public function getAutoreChiusuraForzata(): ?User
    {
        return $this->autoreChiusuraForzata;
    }

    public function setAutoreChiusuraForzata(?User $autoreChiusuraForzata): self
    {
        $this->autoreChiusuraForzata = $autoreChiusuraForzata;

        return $this;
    }
}
