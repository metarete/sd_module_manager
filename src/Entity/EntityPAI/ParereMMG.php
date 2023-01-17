<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\ParereMMGRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: ParereMMGRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_parere_mmg')]

class ParereMMG
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string')]
    private $parere;

    #[ORM\Column(type: 'text', nullable: true)]
    private $descrizione;

    #[ORM\OneToOne(targetEntity: SchedaPAI::class, mappedBy: 'idParereMmg',  cascade: ['persist'])]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idParereMmg')]
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

    public function setDescrizione(?string $descrizione): self
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
}
