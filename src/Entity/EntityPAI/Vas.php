<?php

namespace App\Entity\EntityPAI;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\VasRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VasRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_vas')]

class Vas
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
    private $paziente;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $data;

    #[ORM\Column(type: 'time')]
    private $ora;

    #[ORM\Column(type: 'string', length: 255)]
    private $base2;

    #[ORM\Column(type: 'string', length: 255)]
    private $pz;

    #[ORM\Column(type: 'string', length: 255)]
    private $esito;

    #[ORM\Column(type: 'integer')]
    private $rilevanzaMonitoraggio;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $farmaci;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $altro;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idVas')]
    private $schedaPAI;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaziente(): ?string
    {
        return $this->paziente;
    }

    public function setPaziente(string $paziente): self
    {
        $this->paziente = $paziente;

        return $this;
    }

    public function getData(): ?\DateTimeInterface
    {
        return $this->data;
    }

    public function setData(\DateTimeInterface $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getOra(): ?\DateTimeInterface
    {
        return $this->ora;
    }

    public function setOra(\DateTimeInterface $ora): self
    {
        $this->ora = $ora;

        return $this;
    }

    public function getBase2(): ?string
    {
        return $this->base2;
    }

    public function setBase2(string $base2): self
    {
        $this->base2 = $base2;

        return $this;
    }

    public function getPz(): ?string
    {
        return $this->pz;
    }

    public function setPz(string $pz): self
    {
        $this->pz = $pz;

        return $this;
    }

    public function getEsito(): ?string
    {
        return $this->esito;
    }

    public function setEsito(string $esito): self
    {
        $this->esito = $esito;

        return $this;
    }

    public function getRilevanzaMonitoraggio(): ?int
    {
        return $this->rilevanzaMonitoraggio;
    }

    public function setRilevanzaMonitoraggio(int $rilevanzaMonitoraggio): self
    {
        $this->rilevanzaMonitoraggio = $rilevanzaMonitoraggio;

        return $this;
    }

    public function isFarmaci(): ?bool
    {
        return $this->farmaci;
    }

    public function setFarmaci(?bool $farmaci): self
    {
        $this->farmaci = $farmaci;

        return $this;
    }

    public function isAltro(): ?bool
    {
        return $this->altro;
    }

    public function setAltro(?bool $altro): self
    {
        $this->altro = $altro;

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
}
