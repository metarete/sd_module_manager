<?php

namespace App\Entity\EntityPAI;

use DateTime;
use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SchedaPAIRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SchedaPAIRepository::class)]
#[ORM\Table(name: 'SCHEDA_PAI')]

class SchedaPAI
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'principaleSchedaPai')]
    private $idOperatorePrincipale;

    #[ORM\ManyToMany(inversedBy: 'infSchedaPai', targetEntity: User::class)]
    #[ORM\JoinTable(name: 'scheda_pai_user_inf')]
    #[ORM\JoinColumn(name: 'user_inf_id', referencedColumnName: 'id', nullable: true)]
    #[ORM\InverseJoinColumn(name: 'scheda_pai_inf_id', referencedColumnName: 'id')]
    private $idOperatoreSecondarioInf;

    #[ORM\ManyToMany(inversedBy: 'tdrSchedaPai', targetEntity: User::class)]
    #[ORM\JoinTable(name: 'scheda_pai_user_tdr')]
    #[ORM\JoinColumn(name: 'user_tdr_id', referencedColumnName: 'id', nullable: true)]
    #[ORM\InverseJoinColumn(name: 'scheda_pai_tdr_id', referencedColumnName: 'id')]
    private $idOperatoreSecondarioTdr;

    #[ORM\ManyToMany(inversedBy: 'logSchedaPai', targetEntity: User::class)]
    #[ORM\JoinTable(name: 'scheda_pai_user_log')]
    #[ORM\JoinColumn(name: 'user_log_id', referencedColumnName: 'id', nullable: true)]
    #[ORM\InverseJoinColumn(name: 'scheda_pai_log_id', referencedColumnName: 'id')]
    private $idOperatoreSecondarioLog;

    #[ORM\ManyToMany(inversedBy: 'asaSchedaPai', targetEntity: User::class)]
    #[ORM\JoinTable(name: 'scheda_pai_user_asa')]
    #[ORM\JoinColumn(name: 'user_asa_id', referencedColumnName: 'id', nullable: true)]
    #[ORM\InverseJoinColumn(name: 'scheda_pai_asa_id', referencedColumnName: 'id')]
    private $idOperatoreSecondarioAsa;

    #[ORM\ManyToMany(inversedBy: 'ossSchedaPai', targetEntity: User::class)]
    #[ORM\JoinTable(name: 'scheda_pai_user_oss')]
    #[ORM\JoinColumn(name: 'user_oss_id', referencedColumnName: 'id', nullable: true)]
    #[ORM\InverseJoinColumn(name: 'scheda_pai_oss_id', referencedColumnName: 'id')]
    private $idOperatoreSecondarioOss;

    #[ORM\Column(type: 'integer')]
    private $idAssistito;

    #[ORM\Column(type: 'string', nullable: true)]
    private $nomeAssistito;

    #[ORM\Column(type: 'string', nullable: true)]
    private $cognomeAssistito;

    #[ORM\Column(type: 'string')]
    private $idConsole;

    #[ORM\Column(type: 'integer')]
    private $idProgetto;

    #[ORM\OneToOne(targetEntity: ValutazioneGenerale::class, inversedBy: 'schedaPAI', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $idValutazioneGenerale;

    #[ORM\OneToMany(mappedBy: 'schedaPAI', targetEntity: ValutazioneFiguraProfessionale::class, cascade: ['persist', 'remove'])]
    private $idValutazioneFiguraProfessionale;

    #[ORM\OneToOne(targetEntity: ParereMMG::class, inversedBy: 'schedaPAI', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $idParereMmg;

    #[ORM\OneToOne(targetEntity: ChiusuraServizio::class, inversedBy: 'schedaPAI', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private $idChiusuraServizio;

    #[ORM\OneToMany(mappedBy: 'schedaPAI', targetEntity: Barthel::class, cascade: ['persist', 'remove'])]
    private $idBarthel;

    #[ORM\OneToMany(mappedBy: 'schedaPAI', targetEntity: Braden::class, cascade: ['persist', 'remove'])]
    private $idBraden;

    #[ORM\OneToMany(mappedBy: 'schedaPAI', targetEntity: SPMSQ::class, cascade: ['persist', 'remove'])]
    private $idSpmsq;

    #[ORM\OneToMany(mappedBy: 'schedaPAI', targetEntity: Tinetti::class, cascade: ['persist', 'remove'])]
    private $idTinetti;

    #[ORM\OneToMany(mappedBy: 'schedaPAI', targetEntity: Vas::class, cascade: ['persist', 'remove'])]
    private $idVas;

    #[ORM\OneToMany(mappedBy: 'schedaPAI', targetEntity: Lesioni::class, cascade: ['persist', 'remove'])]
    private Collection $idLesioni;

    #[ORM\Column(type: 'string')]
    private $currentPlace = 'nuova';

    #[ORM\Column(type: 'date')]
    #[Assert\Type(\DateTime::class)]
    private $dataInizio;

    #[ORM\Column(type: 'date')]
    #[Assert\Type(DateTime::class)]
    private $dataFine;

    #[ORM\Column(type: 'boolean',)]
    private $abilitaBarthel = false;

    #[ORM\Column(type: 'boolean')]
    private $abilitaBraden = false;

    #[ORM\Column(type: 'boolean')]
    private $abilitaSpmsq = false;

    #[ORM\Column(type: 'boolean')]
    private $abilitaTinetti = false;

    #[ORM\Column(type: 'boolean')]
    private $abilitaVas = false;

    #[ORM\Column(type: 'boolean')]
    private $abilitaLesioni = false;

    #[ORM\Column(type: 'integer')]
    private $numeroBarthelCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroBradenCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroSpmsqCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroTinettiCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroVasCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroLesioniCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $frequenzaBarthel = 0;

    #[ORM\Column(type: 'integer')]
    private $frequenzaBraden = 0;

    #[ORM\Column(type: 'integer')]
    private $frequenzaSpmsq = 0;

    #[ORM\Column(type: 'integer')]
    private $frequenzaTinetti = 0;

    #[ORM\Column(type: 'integer')]
    private $frequenzaVas = 0;

    #[ORM\Column(type: 'integer')]
    private $frequenzaLesioni = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroBarthelAdOggi = 0; 

    #[ORM\Column(type: 'integer')]
    private $numeroBradenAdOggi = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroSpmsqAdOggi = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroTinettiAdOggi = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroVasAdOggi = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroLesioniAdOggi = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroBarthelAdOggiCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroBradenAdOggiCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroSpmsqAdOggiCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroTinettiAdOggiCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroVasAdOggiCorretto = 0;

    #[ORM\Column(type: 'integer')]
    private $numeroLesioniAdOggiCorretto = 0;




    public function __construct()
    {
        $this->idOperatoreSecondarioInf = new ArrayCollection();
        $this->idOperatoreSecondarioTdr = new ArrayCollection();
        $this->idOperatoreSecondarioLog = new ArrayCollection();
        $this->idOperatoreSecondarioAsa = new ArrayCollection();
        $this->idOperatoreSecondarioOss = new ArrayCollection();
        $this->idValutazioneFiguraProfessionale = new ArrayCollection();
        $this->idBarthel = new ArrayCollection();
        $this->idBraden = new ArrayCollection();
        $this->idSpmsq = new ArrayCollection();
        $this->idTinetti = new ArrayCollection();
        $this->idVas = new ArrayCollection();
        $this->idLesioni = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDataInizio(): ?\DateTimeInterface
    {
        return $this->dataInizio;
    }

    public function setDataInizio(\DateTimeInterface $dataInizio): self
    {
        $this->dataInizio = $dataInizio;

        return $this;
    }

    public function getDataFine(): ?\DateTimeInterface
    {
        return $this->dataFine;
    }

    public function setDataFine(\DateTimeInterface $dataFine): self
    {
        $this->dataFine = $dataFine;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getidOperatoreSecondarioInf(): Collection
    {
        return $this->idOperatoreSecondarioInf;
    }

    public function addidOperatoreSecondarioInf(User $idOperatoreSecondarioInf): self
    {
        if (!$this->idOperatoreSecondarioInf->contains($idOperatoreSecondarioInf)) {
            $this->idOperatoreSecondarioInf[] = $idOperatoreSecondarioInf;
        }

        return $this;
    }

    public function removeidOperatoreSecondarioInf(User $idOperatoreSecondarioInf): self
    {
        if ($this->idOperatoreSecondarioInf->removeElement($idOperatoreSecondarioInf)) {
            // set the owning side to null (unless already changed)

        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getidOperatoreSecondarioTdr(): Collection
    {
        return $this->idOperatoreSecondarioTdr;
    }

    public function addidOperatoreSecondarioTdr(User $idOperatoreSecondarioTdr): self
    {
        if (!$this->idOperatoreSecondarioTdr->contains($idOperatoreSecondarioTdr)) {
            $this->idOperatoreSecondarioTdr[] = $idOperatoreSecondarioTdr;
        }

        return $this;
    }

    public function removeidOperatoreSecondarioTdr(User $idOperatoreSecondarioTdr): self
    {
        if ($this->idOperatoreSecondarioTdr->removeElement($idOperatoreSecondarioTdr)) {
            // set the owning side to null (unless already changed)

        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getidOperatoreSecondarioLog(): Collection
    {
        return $this->idOperatoreSecondarioLog;
    }

    public function addidOperatoreSecondarioLog(User $idOperatoreSecondarioLog): self
    {
        if (!$this->idOperatoreSecondarioLog->contains($idOperatoreSecondarioLog)) {
            $this->idOperatoreSecondarioLog[] = $idOperatoreSecondarioLog;
        }

        return $this;
    }

    public function removeidOperatoreSecondarioLog(User $idOperatoreSecondarioLog): self
    {
        if ($this->idOperatoreSecondarioLog->removeElement($idOperatoreSecondarioLog)) {
            // set the owning side to null (unless already changed)

        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getidOperatoreSecondarioAsa(): Collection
    {
        return $this->idOperatoreSecondarioAsa;
    }

    public function addidOperatoreSecondarioAsa(User $idOperatoreSecondarioAsa): self
    {
        if (!$this->idOperatoreSecondarioAsa->contains($idOperatoreSecondarioAsa)) {
            $this->idOperatoreSecondarioAsa[] = $idOperatoreSecondarioAsa;
        }

        return $this;
    }

    public function removeidOperatoreSecondarioAsa(User $idOperatoreSecondarioAsa): self
    {
        if ($this->idOperatoreSecondarioAsa->removeElement($idOperatoreSecondarioAsa)) {
            // set the owning side to null (unless already changed)

        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getidOperatoreSecondarioOss(): Collection
    {
        return $this->idOperatoreSecondarioOss;
    }

    public function addidOperatoreSecondarioOss(User $idOperatoreSecondarioOss): self
    {
        if (!$this->idOperatoreSecondarioOss->contains($idOperatoreSecondarioOss)) {
            $this->idOperatoreSecondarioOss[] = $idOperatoreSecondarioOss;
        }

        return $this;
    }

    public function removeidOperatoreSecondarioOss(User $idOperatoreSecondarioOss): self
    {
        if ($this->idOperatoreSecondarioOss->removeElement($idOperatoreSecondarioOss)) {
            // set the owning side to null (unless already changed)

        }

        return $this;
    }

    public function getIdAssistito(): ?int
    {
        return $this->idAssistito;
    }

    public function setIdAssistito(?int $idAssistito): self
    {
        $this->idAssistito = $idAssistito;

        return $this;
    }
    public function getNomeAssistito(): ?string
    {
        return $this->nomeAssistito;
    }

    public function setNomeAssistito(?string $nomeAssistito): self
    {
        $this->nomeAssistito = $nomeAssistito;

        return $this;
    }
    public function getCognomeAssistito(): ?string
    {
        return $this->cognomeAssistito;
    }

    public function setCognomeAssistito(?string $cognomeAssistito): self
    {
        $this->cognomeAssistito = $cognomeAssistito;

        return $this;
    }

    public function getIdConsole(): ?string
    {
        return $this->idConsole;
    }

    public function setIdConsole(?string $idConsole): self
    {
        $this->idConsole = $idConsole;

        return $this;
    }

    public function getIdProgetto(): ?int
    {
        return $this->idProgetto;
    }

    public function setIdProgetto(?int $idProgetto): self
    {
        $this->idProgetto = $idProgetto;

        return $this;
    }

    public function getIdValutazioneGenerale(): ?ValutazioneGenerale
    {
        return $this->idValutazioneGenerale;
    }

    public function setIdValutazioneGenerale(?ValutazioneGenerale $idValutazioneGenerale): self
    {
        $this->idValutazioneGenerale = $idValutazioneGenerale;

        return $this;
    }

    /**
     * @return Collection<int, ValutazioneFiguraProfessionale>
     */
    public function getIdValutazioneFiguraProfessionale(): Collection
    {
        return $this->idValutazioneFiguraProfessionale;
    }

    public function addIdValutazioneFiguraProfessionale(ValutazioneFiguraProfessionale $idValutazioneFiguraProfessionale): self
    {
        if (!$this->idValutazioneFiguraProfessionale->contains($idValutazioneFiguraProfessionale)) {
            $this->idValutazioneFiguraProfessionale[] = $idValutazioneFiguraProfessionale;
            $idValutazioneFiguraProfessionale->setSchedaPAI($this);
        }

        return $this;
    }

    public function removeIdValutazioneFiguraProfessionale(ValutazioneFiguraProfessionale $idValutazioneFiguraProfessionale): self
    {
        if ($this->idValutazioneFiguraProfessionale->removeElement($idValutazioneFiguraProfessionale)) {
            // set the owning side to null (unless already changed)
            if ($idValutazioneFiguraProfessionale->getSchedaPAI() === $this) {
                $idValutazioneFiguraProfessionale->setSchedaPAI(null);
            }
        }

        return $this;
    }

    public function getIdParereMmg(): ?ParereMMG
    {
        return $this->idParereMmg;
    }

    public function setIdParereMmg(?ParereMMG $idParereMmg): self
    {
        $this->idParereMmg = $idParereMmg;

        return $this;
    }

    public function getIdChiusuraServizio(): ?ChiusuraServizio
    {
        return $this->idChiusuraServizio;
    }

    public function setIdChiusuraServizio(?ChiusuraServizio $idChiusuraServizio): self
    {
        $this->idChiusuraServizio = $idChiusuraServizio;

        return $this;
    }

    /**
     * @return Collection<int, Barthel>
     */
    public function getIdBarthel(): Collection
    {
        return $this->idBarthel;
    }

    public function addIdBarthel(Barthel $idBarthel): self
    {
        if (!$this->idBarthel->contains($idBarthel)) {
            $this->idBarthel[] = $idBarthel;
            $idBarthel->setSchedaPAI($this);
        }

        return $this;
    }

    public function removeIdBarthel(Barthel $idBarthel): self
    {
        if ($this->idBarthel->removeElement($idBarthel)) {
            // set the owning side to null (unless already changed)
            if ($idBarthel->getSchedaPAI() === $this) {
                $idBarthel->setSchedaPAI(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Braden>
     */
    public function getIdBraden(): Collection
    {
        return $this->idBraden;
    }

    public function addIdBraden(Braden $idBraden): self
    {
        if (!$this->idBraden->contains($idBraden)) {
            $this->idBraden[] = $idBraden;
            $idBraden->setSchedaPAI($this);
        }

        return $this;
    }

    public function removeIdBraden(Braden $idBraden): self
    {
        if ($this->idBraden->removeElement($idBraden)) {
            // set the owning side to null (unless already changed)
            if ($idBraden->getSchedaPAI() === $this) {
                $idBraden->setSchedaPAI(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SPMSQ>
     */
    public function getIdSpmsq(): Collection
    {
        return $this->idSpmsq;
    }

    public function addIdSpmsq(SPMSQ $idSpmsq): self
    {
        if (!$this->idSpmsq->contains($idSpmsq)) {
            $this->idSpmsq[] = $idSpmsq;
            $idSpmsq->setSchedaPAI($this);
        }

        return $this;
    }

    public function removeIdSpmsq(SPMSQ $idSpmsq): self
    {
        if ($this->idSpmsq->removeElement($idSpmsq)) {
            // set the owning side to null (unless already changed)
            if ($idSpmsq->getSchedaPAI() === $this) {
                $idSpmsq->setSchedaPAI(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tinetti>
     */
    public function getIdTinetti(): Collection
    {
        return $this->idTinetti;
    }

    public function addIdTinetti(Tinetti $idTinetti): self
    {
        if (!$this->idTinetti->contains($idTinetti)) {
            $this->idTinetti[] = $idTinetti;
            $idTinetti->setSchedaPAI($this);
        }

        return $this;
    }

    public function removeIdTinetti(Tinetti $idTinetti): self
    {
        if ($this->idTinetti->removeElement($idTinetti)) {
            // set the owning side to null (unless already changed)
            if ($idTinetti->getSchedaPAI() === $this) {
                $idTinetti->setSchedaPAI(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vas>
     */
    public function getIdVas(): Collection
    {
        return $this->idVas;
    }

    public function addIdVas(Vas $idVas): self
    {
        if (!$this->idVas->contains($idVas)) {
            $this->idVas[] = $idVas;
            $idVas->setSchedaPAI($this);
        }

        return $this;
    }

    public function removeIdVas(Vas $idVas): self
    {
        if ($this->idVas->removeElement($idVas)) {
            // set the owning side to null (unless already changed)
            if ($idVas->getSchedaPAI() === $this) {
                $idVas->setSchedaPAI(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Lesioni>
     */
    public function getIdLesioni(): Collection
    {
        return $this->idLesioni;
    }

    public function addIdLesioni(Lesioni $idLesioni): self
    {
        if (!$this->idLesioni->contains($idLesioni)) {
            $this->idLesioni->add($idLesioni);
            $idLesioni->setSchedaPAI($this);
        }

        return $this;
    }

    public function removeIdLesioni(Lesioni $idLesioni): self
    {
        if ($this->idLesioni->removeElement($idLesioni)) {
            // set the owning side to null (unless already changed)
            if ($idLesioni->getSchedaPAI() === $this) {
                $idLesioni->setSchedaPAI(null);
            }
        }

        return $this;
    }

    public function getCurrentPlace()
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace($currentPlace, $context = [])
    {
        $this->currentPlace = $currentPlace;
    }

    public function getIdOperatorePrincipale(): ?User
    {
        return $this->idOperatorePrincipale;
    }

    public function setIdOperatorePrincipale(?User $idOperatorePrincipale): self
    {
        $this->idOperatorePrincipale = $idOperatorePrincipale;

        return $this;
    }

    public function isAbilitaBarthel(): ?bool
    {
        return $this->abilitaBarthel;
    }

    public function setAbilitaBarthel(bool $abilitaBarthel): self
    {
        $this->abilitaBarthel = $abilitaBarthel;

        return $this;
    }
    public function isAbilitaBraden(): ?bool
    {
        return $this->abilitaBraden;
    }

    public function setAbilitaBraden(bool $abilitaBraden): self
    {
        $this->abilitaBraden = $abilitaBraden;

        return $this;
    }
    public function isAbilitaSpmsq(): ?bool
    {
        return $this->abilitaSpmsq;
    }

    public function setAbilitaSpmsq(bool $abilitaSpmsq): self
    {
        $this->abilitaSpmsq = $abilitaSpmsq;

        return $this;
    }
    public function isAbilitaTinetti(): ?bool
    {
        return $this->abilitaTinetti;
    }

    public function setAbilitaTinetti(bool $abilitaTinetti): self
    {
        $this->abilitaTinetti = $abilitaTinetti;

        return $this;
    }
    public function isAbilitaVas(): ?bool
    {
        return $this->abilitaVas;
    }

    public function setAbilitaVas(bool $abilitaVas): self
    {
        $this->abilitaVas = $abilitaVas;

        return $this;
    }
    public function isAbilitaLesioni(): ?bool
    {
        return $this->abilitaLesioni;
    }

    public function setAbilitaLesioni(bool $abilitaLesioni): self
    {
        $this->abilitaLesioni = $abilitaLesioni;

        return $this;
    }

    public function getNumeroBarthelCorretto(): ?int
    {
        return $this->numeroBarthelCorretto;
    }

    public function setNumeroBarthelCorretto(int $numeroBarthelCorretto): self
    {
        $this->numeroBarthelCorretto = $numeroBarthelCorretto;
        return $this;
    }

    public function getNumeroBradenCorretto(): ?int
    {
        return $this->numeroBradenCorretto;
    }

    public function setNumeroBradenCorretto(int $numeroBradenCorretto): self
    {
        $this->numeroBradenCorretto = $numeroBradenCorretto;
        return $this;
    }

    public function getNumeroSpmsqCorretto(): ?int
    {
        return $this->numeroSpmsqCorretto;
    }

    public function setNumeroSpmsqCorretto(int $numeroSpmsqCorretto): self
    {
        $this->numeroSpmsqCorretto = $numeroSpmsqCorretto;
        return $this;
    }

    public function getNumeroTinettiCorretto(): ?int
    {
        return $this->numeroTinettiCorretto;
    }

    public function setNumeroTinettiCorretto(int $numeroTinettiCorretto): self
    {
        $this->numeroTinettiCorretto = $numeroTinettiCorretto;
        return $this;
    }

    public function getNumeroVasCorretto(): ?int
    {
        return $this->numeroVasCorretto;
    }

    public function setNumeroVasCorretto(int $numeroVasCorretto): self
    {
        $this->numeroVasCorretto = $numeroVasCorretto;
        return $this;
    }

    public function getNumeroLesioniCorretto(): ?int
    {
        return $this->numeroLesioniCorretto;
    }

    public function setNumeroLesioniCorretto(int $numeroLesioniCorretto): self
    {
        $this->numeroLesioniCorretto = $numeroLesioniCorretto;
        return $this;
    }

    public function getFrequenzaBarthel(): ?int
    {
        return $this->frequenzaBarthel;
    }

    public function setFrequenzaBarthel(int $frequenzaBarthel): self
    {
        $this->frequenzaBarthel = $frequenzaBarthel;

        return $this;
    }

    public function getFrequenzaBraden(): ?int
    {
        return $this->frequenzaBraden;
    }

    public function setFrequenzaBraden(int $frequenzaBraden): self
    {
        $this->frequenzaBraden = $frequenzaBraden;

        return $this;
    }

    public function getFrequenzaSpmsq(): ?int
    {
        return $this->frequenzaSpmsq;
    }

    public function setFrequenzaSpmsq(int $frequenzaSpmsq): self
    {
        $this->frequenzaSpmsq = $frequenzaSpmsq;

        return $this;
    }

    public function getFrequenzaTinetti(): ?int
    {
        return $this->frequenzaTinetti;
    }

    public function setFrequenzaTinetti(int $frequenzaTinetti): self
    {
        $this->frequenzaTinetti = $frequenzaTinetti;

        return $this;
    }

    public function getFrequenzaVas(): ?int
    {
        return $this->frequenzaVas;
    }

    public function setFrequenzaVas(int $frequenzaVas): self
    {
        $this->frequenzaVas = $frequenzaVas;

        return $this;
    }

    public function getFrequenzaLesioni(): ?int
    {
        return $this->frequenzaLesioni;
    }

    public function setFrequenzaLesioni(int $frequenzaLesioni): self
    {
        $this->frequenzaLesioni = $frequenzaLesioni;

        return $this;
    }

    public function getNumeroBarthelAdOggiCorretto(): ?int
    {
        return $this->numeroBarthelAdOggiCorretto;
    }

    public function setNumeroBarthelAdOggiCorretto($numeroBarthelAdOggiCorretto)
    {
        $this->numeroBarthelAdOggiCorretto = $numeroBarthelAdOggiCorretto;
    }

    public function getNumeroBradenAdOggiCorretto(): ?int
    {
        return $this->numeroBradenAdOggiCorretto;
    }

    public function setNumeroBradenAdOggiCorretto($numeroBradenAdOggiCorretto)
    {
        $this->numeroBradenAdOggiCorretto = $numeroBradenAdOggiCorretto;
    }

    public function getNumeroSpmsqAdOggiCorretto(): ?int
    {
        return $this->numeroSpmsqAdOggiCorretto;
    }

    public function setNumeroSpmsqAdOggiCorretto($numeroSpmsqAdOggiCorretto)
    {
        $this->numeroSpmsqAdOggiCorretto = $numeroSpmsqAdOggiCorretto;
    }

    public function getNumeroTinettiAdOggiCorretto(): ?int
    {
        return $this->numeroTinettiAdOggiCorretto;
    }

    public function setNumeroTinettiAdOggiCorretto($numeroTinettiAdOggiCorretto)
    {
        $this->numeroTinettiAdOggiCorretto = $numeroTinettiAdOggiCorretto;
    }

    public function getNumeroVasAdOggiCorretto(): ?int
    {
        return $this->numeroVasAdOggiCorretto;
    }

    public function setNumeroVasAdOggiCorretto($numeroVasAdOggiCorretto)
    {
        $this->numeroVasAdOggiCorretto = $numeroVasAdOggiCorretto;
    }

    public function getNumeroLesioniAdOggiCorretto(): ?int
    {
        return $this->numeroLesioniAdOggiCorretto;
    }

    public function setNumeroLesioniAdOggiCorretto($numeroLesioniAdOggiCorretto)
    {
        $this->numeroLesioniAdOggiCorretto = $numeroLesioniAdOggiCorretto;
    }
    public function getNumeroBarthelAdOggi(): ?int
    {
        return $this->numeroBarthelAdOggi;
    }

    public function setNumeroBarthelAdOggi($numeroBarthelAdOggi)
    {
        $this->numeroBarthelAdOggi = $numeroBarthelAdOggi;
    }

    public function getNumeroBradenAdOggi(): ?int
    {
        return $this->numeroBradenAdOggi;
    }

    public function setNumeroBradenAdOggi($numeroBradenAdOggi)
    {
        $this->numeroBradenAdOggi = $numeroBradenAdOggi;
    }

    public function getNumeroSpmsqAdOggi(): ?int
    {
        return $this->numeroSpmsqAdOggi;
    }

    public function setNumeroSpmsqAdOggi($numeroSpmsqAdOggi)
    {
        $this->numeroSpmsqAdOggi = $numeroSpmsqAdOggi;
    }

    public function getNumeroTinettiAdOggi(): ?int
    {
        return $this->numeroTinettiAdOggi;
    }

    public function setNumeroTinettiAdOggi($numeroTinettiAdOggi)
    {
        $this->numeroTinettiAdOggi = $numeroTinettiAdOggi;
    }

    public function getNumeroVasAdOggi(): ?int
    {
        return $this->numeroVasAdOggi;
    }

    public function setNumeroVasAdOggi($numeroVasAdOggi)
    {
        $this->numeroVasAdOggi = $numeroVasAdOggi;
    }

    public function getNumeroLesioniAdOggi(): ?int
    {
        return $this->numeroLesioniAdOggi;
    }

    public function setNumeroLesioniAdOggi($numeroLesioniAdOggi)
    {
        $this->numeroLesioniAdOggi = $numeroLesioniAdOggi;
    }
}
