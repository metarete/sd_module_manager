<?php

namespace App\Entity\EntityPAI;

use App\Entity\User;
use App\Repository\TinettiRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TinettiRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_tinetti')]

class Tinetti
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
    private $equilibrioSeduto;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $sedia;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $alzarsi;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $stazioneEretta;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $stazioneErettaProlungata;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $romberg;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $rombergSensibilizzato;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $girarsi1;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $girarsi2;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $sedersi;

    #[ORM\Column(type: 'integer')]
    private $totaleEquilibrio = 0;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $inizioDeambulazione;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $piedeDx;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $piedeDx2;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $piedeSx;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $piedeSx2;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $simmetriaPasso;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $continuitaPasso;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $traiettoria;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $tronco;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $cammino;

    #[ORM\Column(type: 'integer')]
    private $totaleAndatura = 0;

    #[ORM\Column(type: 'integer')]
    private $totale = 0;

    #[ORM\ManyToOne(targetEntity: SchedaPAI::class, inversedBy: 'idTinetti', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private $schedaPAI;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idTinetti', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $autoreTinetti;

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

    public function getEquilibrioSeduto(): ?int
    {
        return $this->equilibrioSeduto;
    }

    public function setEquilibrioSeduto(int $equilibrioSeduto): self
    {
        $this->equilibrioSeduto = $equilibrioSeduto;

        return $this;
    }

    public function getSedia(): ?int
    {
        return $this->sedia;
    }

    public function setSedia(int $sedia): self
    {
        $this->sedia = $sedia;

        return $this;
    }

    public function getAlzarsi(): ?int
    {
        return $this->alzarsi;
    }

    public function setAlzarsi(int $alzarsi): self
    {
        $this->alzarsi = $alzarsi;

        return $this;
    }

    public function getStazioneEretta(): ?int
    {
        return $this->stazioneEretta;
    }

    public function setStazioneEretta(int $stazioneEretta): self
    {
        $this->stazioneEretta = $stazioneEretta;

        return $this;
    }

    public function getStazioneErettaProlungata(): ?int
    {
        return $this->stazioneErettaProlungata;
    }

    public function setStazioneErettaProlungata(int $stazioneErettaProlungata): self
    {
        $this->stazioneErettaProlungata = $stazioneErettaProlungata;

        return $this;
    }

    public function getRomberg(): ?int
    {
        return $this->romberg;
    }

    public function setRomberg(int $romberg): self
    {
        $this->romberg = $romberg;

        return $this;
    }

    public function getRombergSensibilizzato(): ?int
    {
        return $this->rombergSensibilizzato;
    }

    public function setRombergSensibilizzato(int $rombergSensibilizzato): self
    {
        $this->rombergSensibilizzato = $rombergSensibilizzato;

        return $this;
    }

    public function getGirarsi1(): ?int
    {
        return $this->girarsi1;
    }

    public function setGirarsi1(int $girarsi1): self
    {
        $this->girarsi1 = $girarsi1;

        return $this;
    }

    public function getGirarsi2(): ?int
    {
        return $this->girarsi2;
    }

    public function setGirarsi2(int $girarsi2): self
    {
        $this->girarsi2 = $girarsi2;

        return $this;
    }

    public function getSedersi(): ?int
    {
        return $this->sedersi;
    }

    public function setSedersi(int $sedersi): self
    {
        $this->sedersi = $sedersi;

        return $this;
    }

    public function getTotaleEquilibrio(): ?int
    {
        return $this->totaleEquilibrio;
    }

    public function setTotaleEquilibrio(int $totaleEquilibrio): self
    {
        $this->totaleEquilibrio = $totaleEquilibrio;

        return $this;
    }

    public function getInizioDeambulazione(): ?int
    {
        return $this->inizioDeambulazione;
    }

    public function setInizioDeambulazione(int $inizioDeambulazione): self
    {
        $this->inizioDeambulazione = $inizioDeambulazione;

        return $this;
    }

    public function getPiedeDx(): ?int
    {
        return $this->piedeDx;
    }

    public function setPiedeDx(int $piedeDx): self
    {
        $this->piedeDx = $piedeDx;

        return $this;
    }

    public function getPiedeDx2(): ?int
    {
        return $this->piedeDx2;
    }

    public function setPiedeDx2(int $piedeDx2): self
    {
        $this->piedeDx2 = $piedeDx2;

        return $this;
    }

    public function getPiedeSx(): ?int
    {
        return $this->piedeSx;
    }

    public function setPiedeSx(int $piedeSx): self
    {
        $this->piedeSx = $piedeSx;

        return $this;
    }

    public function getPiedeSx2(): ?int
    {
        return $this->piedeSx2;
    }

    public function setPiedeSx2(int $piedeSx2): self
    {
        $this->piedeSx2 = $piedeSx2;

        return $this;
    }

    public function getSimmetriaPasso(): ?int
    {
        return $this->simmetriaPasso;
    }

    public function setSimmetriaPasso(int $simmetriaPasso): self
    {
        $this->simmetriaPasso = $simmetriaPasso;

        return $this;
    }

    public function getContinuitaPasso(): ?int
    {
        return $this->continuitaPasso;
    }

    public function setContinuitaPasso(int $continuitaPasso): self
    {
        $this->continuitaPasso = $continuitaPasso;

        return $this;
    }

    public function getTraiettoria(): ?int
    {
        return $this->traiettoria;
    }

    public function setTraiettoria(int $traiettoria): self
    {
        $this->traiettoria = $traiettoria;

        return $this;
    }

    public function getTronco(): ?int
    {
        return $this->tronco;
    }

    public function setTronco(int $tronco): self
    {
        $this->tronco = $tronco;

        return $this;
    }

    public function getCammino(): ?int
    {
        return $this->cammino;
    }

    public function setCammino(int $cammino): self
    {
        $this->cammino = $cammino;

        return $this;
    }

    public function getTotaleAndatura(): ?int
    {
        return $this->totaleAndatura;
    }

    public function setTotaleAndatura(int $totaleAndatura): self
    {
        $this->totaleAndatura = $totaleAndatura;

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
        return $this->autoreTinetti;
    }

    public function setOperatore(?User $autoreTinetti): self
    {
        $this->autoreTinetti = $autoreTinetti;

        return $this;
    }

    public function getAutoreTinetti(): ?User
    {
        return $this->autoreTinetti;
    }

    public function setAutoreTinetti(?User $autoreTinetti): self
    {
        $this->autoreTinetti = $autoreTinetti;

        return $this;
    }
}
