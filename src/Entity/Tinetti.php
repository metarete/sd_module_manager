<?php

namespace App\Entity;

use App\Repository\TinettiRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TinettiRepository::class)]
class Tinetti
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $nome;

    #[ORM\Column(type: 'date')]
    private $dataValutazione;

    #[ORM\Column(type: 'integer')]
    private $equilibrioSeduto;

    #[ORM\Column(type: 'integer')]
    private $sedia;

    #[ORM\Column(type: 'integer')]
    private $alzarsi;

    #[ORM\Column(type: 'integer')]
    private $stazioneEretta;

    #[ORM\Column(type: 'integer')]
    private $stazioneErettaProlungata;

    #[ORM\Column(type: 'integer')]
    private $romberg;

    #[ORM\Column(type: 'integer')]
    private $rombergSensibilizzato;

    #[ORM\Column(type: 'integer')]
    private $girarsi1;

    #[ORM\Column(type: 'integer')]
    private $girarsi2;

    #[ORM\Column(type: 'integer')]
    private $sedersi;

    #[ORM\Column(type: 'integer')]
    private $totaleEquilibrio;

    #[ORM\Column(type: 'integer')]
    private $inizioDeambulazione;

    #[ORM\Column(type: 'integer')]
    private $piedeDx;

    #[ORM\Column(type: 'integer')]
    private $piedeDx2;

    #[ORM\Column(type: 'integer')]
    private $piedeSx;

    #[ORM\Column(type: 'integer')]
    private $piedeSx2;

    #[ORM\Column(type: 'integer')]
    private $simmetriaPasso;

    #[ORM\Column(type: 'integer')]
    private $continuitaPasso;

    #[ORM\Column(type: 'integer')]
    private $traiettoria;

    #[ORM\Column(type: 'integer')]
    private $tronco;

    #[ORM\Column(type: 'integer')]
    private $cammino;

    #[ORM\Column(type: 'integer')]
    private $totaleAndatura;

    #[ORM\Column(type: 'integer')]
    private $totale;

    #[ORM\Column(type: 'text')]
    private $firmaOperatore;

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

    public function getFirmaOperatore(): ?string
    {
        return $this->firmaOperatore;
    }

    public function setFirmaOperatore(string $firmaOperatore): self
    {
        $this->firmaOperatore = $firmaOperatore;

        return $this;
    }
}
