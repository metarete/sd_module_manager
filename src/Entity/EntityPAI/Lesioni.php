<?php

namespace App\Entity\EntityPAI;

use App\Entity\BordiLesione;
use App\Entity\CondizioneLesione;
use App\Entity\Copertura;
use App\Entity\CutePerilesionale;
use App\Entity\Medicazione;
use App\Entity\User;
use App\Entity\EntityPAI\SchedaPAI;
use App\Repository\LesioniRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Nette\Utils\Json;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LesioniRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI_lesioni')]
class Lesioni
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\Type(\DateTime::class)]
    private ?\DateTimeInterface $dataRivalutazioniSettimanali = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $lesione = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $tipologiaLesione = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $numeroSedeLesione = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $gradoLesione = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank]
    private $dimensioneLesione = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $disinfezione = null;  
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specificheDisinfezione = null;  

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $noteSullaLesione = null;

    #[ORM\ManyToOne(inversedBy: 'idLesioni', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'CASCADE')]
    private ?SchedaPAI $schedaPAI = null;

    #[ORM\ManyToOne(targetEntity: User:: class, inversedBy: 'idLesioni', cascade:['persist'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $autoreLesioni;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specificheMedicazione = null;

    #[ORM\ManyToMany(targetEntity: CondizioneLesione::class, inversedBy: 'lesioni', cascade:['persist'])]
    private Collection $condizioneLesione;

    #[ORM\ManyToMany(targetEntity: BordiLesione::class, inversedBy: 'lesioni', cascade:['persist'])]
    private Collection $bordiLesione;

    #[ORM\ManyToMany(targetEntity: CutePerilesionale::class, inversedBy: 'lesioni', cascade:['persist'])]
    private Collection $cutePerilesionale;

    #[ORM\ManyToMany(targetEntity: Medicazione::class, inversedBy: 'lesioni', cascade:['persist'])]
    private Collection $medicazione;

    #[ORM\ManyToMany(targetEntity: Copertura::class, inversedBy: 'lesioni', cascade:['persist'])]
    private Collection $copertura;

    public function __construct()
    {
        $this->condizioneLesione = new ArrayCollection();
        $this->bordiLesione = new ArrayCollection();
        $this->cutePerilesionale = new ArrayCollection();
        $this->medicazione = new ArrayCollection();
        $this->copertura = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataRivalutazioniSettimanali(): ?\DateTimeInterface
    {
        return $this->dataRivalutazioniSettimanali;
    }

    public function setDataRivalutazioniSettimanali(\DateTimeInterface $dataRivalutazioniSettimanali): self
    {
        $this->dataRivalutazioniSettimanali = $dataRivalutazioniSettimanali;

        return $this;
    }

    public function getLesione(): ?string
    {
        return $this->lesione;
    }

    public function setLesione(string $lesione): self
    {
        $this->lesione = $lesione;

        return $this;
    }

    public function getTipologiaLesione(): ?string
    {
        return $this->tipologiaLesione;
    }

    public function setTipologiaLesione(string $tipologiaLesione): self
    {
        $this->tipologiaLesione = $tipologiaLesione;

        return $this;
    }

    public function getNumeroSedeLesione(): ?int
    {
        return $this->numeroSedeLesione;
    }

    public function setNumeroSedeLesione(int $numeroSedeLesione): self
    {
        $this->numeroSedeLesione = $numeroSedeLesione;

        return $this;
    }

    public function getGradoLesione(): ?string
    {
        return $this->gradoLesione;
    }

    public function setGradoLesione(string $gradoLesione): self
    {
        $this->gradoLesione = $gradoLesione;

        return $this;
    }

    public function getDimensioneLesione(): ?int
    {
        return $this->dimensioneLesione;
    }

    public function setDimensioneLesione(int $dimensioneLesione): self
    {
        $this->dimensioneLesione = $dimensioneLesione;

        return $this;
    }

    public function getDisinfezione(): ?string
    {
        return $this->disinfezione;
    }

    public function setDisinfezione(string $disinfezione): self
    {
        $this->disinfezione = $disinfezione;

        return $this;
    }

    public function getSpecificheDisinfezione(): ?string
    {
        return $this->specificheDisinfezione;
    }

    public function setSpecificheDisinfezione(?string $specificheDisinfezione): self
    {
        $this->specificheDisinfezione = $specificheDisinfezione;

        return $this;
    }

    public function getNoteSullaLesione(): ?string
    {
        return $this->noteSullaLesione;
    }

    public function setNoteSullaLesione(?string $noteSullaLesione): self
    {
        $this->noteSullaLesione = $noteSullaLesione;

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
        return $this->autoreLesioni;
    }

    public function setOperatore(?User $autoreLesioni): self
    {
        $this->autoreLesioni = $autoreLesioni;

        return $this;
    }

    public function getAutoreLesioni(): ?User
    {
        return $this->autoreLesioni;
    }

    public function setAutoreLesioni(?User $autoreLesioni): self
    {
        $this->autoreLesioni = $autoreLesioni;

        return $this;
    }

    public function getSpecificheMedicazione(): ?string
    {
        return $this->specificheMedicazione;
    }

    public function setSpecificheMedicazione(?string $specificheMedicazione): self
    {
        $this->specificheMedicazione = $specificheMedicazione;

        return $this;
    }

    /**
     * @return Collection<int, CondizioneLesione>
     */
    public function getCondizioneLesione(): Collection
    {
        return $this->condizioneLesione;
    }

    public function addCondizioneLesione(CondizioneLesione $condizioneLesione): self
    {
        if (!$this->condizioneLesione->contains($condizioneLesione)) {
            $this->condizioneLesione->add($condizioneLesione);
        }

        return $this;
    }

    public function removeCondizioneLesione(CondizioneLesione $condizioneLesione): self
    {
        $this->condizioneLesione->removeElement($condizioneLesione);

        return $this;
    }

    /**
     * @return Collection<int, BordiLesione>
     */
    public function getBordiLesione(): Collection
    {
        return $this->bordiLesione;
    }

    public function addBordiLesione(BordiLesione $bordiLesione): self
    {
        if (!$this->bordiLesione->contains($bordiLesione)) {
            $this->bordiLesione->add($bordiLesione);
        }

        return $this;
    }

    public function removeBordiLesione(BordiLesione $bordiLesione): self
    {
        $this->bordiLesione->removeElement($bordiLesione);

        return $this;
    }

    /**
     * @return Collection<int, CutePerilesionale>
     */
    public function getCutePerilesionale(): Collection
    {
        return $this->cutePerilesionale;
    }

    public function addCutePerilesionale(CutePerilesionale $cutePerilesionale): self
    {
        if (!$this->cutePerilesionale->contains($cutePerilesionale)) {
            $this->cutePerilesionale->add($cutePerilesionale);
        }

        return $this;
    }

    public function removeCutePerilesionale(CutePerilesionale $cutePerilesionale): self
    {
        $this->cutePerilesionale->removeElement($cutePerilesionale);

        return $this;
    }

    /**
     * @return Collection<int, Medicazione>
     */
    public function getMedicazione(): Collection
    {
        return $this->medicazione;
    }

    public function addMedicazione(Medicazione $medicazione): self
    {
        if (!$this->medicazione->contains($medicazione)) {
            $this->medicazione->add($medicazione);
        }

        return $this;
    }

    public function removeMedicazione(Medicazione $medicazione): self
    {
        $this->medicazione->removeElement($medicazione);

        return $this;
    }

    /**
     * @return Collection<int, Copertura>
     */
    public function getCopertura(): Collection
    {
        return $this->copertura;
    }

    public function addCopertura(Copertura $copertura): self
    {
        if (!$this->copertura->contains($copertura)) {
            $this->copertura->add($copertura);
        }

        return $this;
    }

    public function removeCopertura(Copertura $copertura): self
    {
        $this->copertura->removeElement($copertura);

        return $this;
    }

}
