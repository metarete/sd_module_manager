<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\PainadRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PainadRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_painad')]

class Painad
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataValutazione;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $respiro;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $vocalizzazione;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $espressioneFacciale;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $linguaggioDelCorpo;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $consolabilita;

    #[ORM\Column(type: 'integer')]
    private $totale = 0;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idPainad')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idPainad')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $autorePainad;

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

    public function getRespiro(): ?int
    {
        return $this->respiro;
    }

    public function setRespiro(int $respiro): self
    {
        $this->respiro = $respiro;

        return $this;
    }

    public function getVocalizzazione(): ?int
    {
        return $this->vocalizzazione;
    }

    public function setVocalizzazione(int $vocalizzazione): self
    {
        $this->vocalizzazione = $vocalizzazione;

        return $this;
    }

    public function getEspressioneFacciale(): ?int
    {
        return $this->espressioneFacciale;
    }

    public function setEspressioneFacciale(int $espressioneFacciale): self
    {
        $this->espressioneFacciale = $espressioneFacciale;

        return $this;
    }

    public function getLinguaggioDelCorpo(): ?int
    {
        return $this->linguaggioDelCorpo;
    }

    public function setLinguaggioDelCorpo(int $linguaggioDelCorpo): self
    {
        $this->linguaggioDelCorpo = $linguaggioDelCorpo;

        return $this;
    }

    public function getConsolabilita(): ?int
    {
        return $this->consolabilita;
    }

    public function setConsolabilita(int $consolabilita): self
    {
        $this->consolabilita = $consolabilita;

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
        return $this->autorePainad;
    }

    public function setOperatore(?User $autorePainad): self
    {
        $this->autorePainad = $autorePainad;

        return $this;
    }
}