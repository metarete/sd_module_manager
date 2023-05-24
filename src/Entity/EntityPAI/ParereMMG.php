<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\ParereMMGRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ParereMMGRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_parere_mmg')]

class ParereMMG
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private $parere;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private $descrizione;

    #[ORM\OneToOne(targetEntity: SchedaPAI::class, mappedBy: 'idParereMmg',  cascade: ['persist'])]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idParereMmg', cascade:['persist'])]
    private $autoreParereMmg;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isParere(): ?string
    {
        return $this->parere;
    }

    public function setParere(string $parere): self
    {
        $this->parere = $parere;

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

    public function getSchedaPAI(): ?SchedaPAI
    {
        return $this->schedaPAI;
    }

    public function setSchedaPAI(?SchedaPAI $schedaPAI): self
    {
        // unset the owning side of the relation if necessary
        if ($schedaPAI === null && $this->schedaPAI !== null) {
            $this->schedaPAI->setIdParereMmg(null);
        }

        // set the owning side of the relation if necessary
        if ($schedaPAI !== null && $schedaPAI->getIdParereMmg() !== $this) {
            $schedaPAI->setIdParereMmg($this);
        }

        $this->schedaPAI = $schedaPAI;

        return $this;
    }
    public function getOperatore(): ?User
    {
        return $this->autoreParereMmg;
    }

    public function setOperatore(?User $autoreParereMmg): self
    {
        $this->autoreParereMmg = $autoreParereMmg;

        return $this;
    }

    public function getParere(): ?string
    {
        return $this->parere;
    }

    public function getAutoreParereMmg(): ?User
    {
        return $this->autoreParereMmg;
    }

    public function setAutoreParereMmg(?User $autoreParereMmg): self
    {
        $this->autoreParereMmg = $autoreParereMmg;

        return $this;
    }
}
