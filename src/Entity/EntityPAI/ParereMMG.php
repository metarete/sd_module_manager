<?php

namespace App\Entity\EntityPAI;

use App\Repository\ParereMMGRepository;
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
    #[Assert\Regex(
        pattern: '/^[^\d]+$/',
        message: 'Carattere non valido',
    )]
    private $nome;

    #[ORM\Column(type: 'string')]
    private $parere;

    #[ORM\Column(type: 'text', nullable: true)]
    private $descrizione;

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
}
