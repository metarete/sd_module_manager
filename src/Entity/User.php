<?php

namespace App\Entity;

use App\Entity\EntityPAI\Barthel;
use App\Entity\EntityPAI\Braden;
use App\Entity\EntityPAI\Cdr;
use App\Entity\EntityPAI\ChiusuraServizio;
use App\Entity\EntityPAI\Lesioni;
use App\Entity\EntityPAI\Painad;
use App\Entity\EntityPAI\ParereMMG;
use App\Entity\EntityPAI\SchedaPAI;
use App\Entity\EntityPAI\SPMSQ;
use App\Entity\EntityPAI\Tinetti;
use App\Entity\EntityPAI\ValutazioneFiguraProfessionale;
use App\Entity\EntityPAI\ValutazioneGenerale;
use App\Entity\EntityPAI\Vas;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'user')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 180)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private $email;

    #[ORM\Column(type: 'string', length: 180, nullable: true)]
    private $username = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private $name;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private $surname;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    private $cf;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string', nullable: true)]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $stato;

    #[ORM\OneToMany(mappedBy: 'idOperatorePrincipale', targetEntity: SchedaPAI::class, cascade:['persist'])]
    private $principaleSchedaPai;

    #[ORM\ManyToMany(mappedBy: 'idOperatoreSecondarioInf', targetEntity: SchedaPai::class, cascade:['persist'])]
    private $infSchedaPai;

    #[ORM\ManyToMany(mappedBy: 'idOperatoreSecondarioTdr', targetEntity: SchedaPai::class, cascade:['persist'])]
    private $tdrSchedaPai;

    #[ORM\ManyToMany(mappedBy: 'idOperatoreSecondarioLog', targetEntity: SchedaPai::class, cascade:['persist'])]
    private $logSchedaPai;

    #[ORM\ManyToMany(mappedBy: 'idOperatoreSecondarioAsa', targetEntity: SchedaPai::class, cascade:['persist'])]
    private $asaSchedaPai;
   
    #[ORM\ManyToMany(mappedBy: 'idOperatoreSecondarioOss', targetEntity: SchedaPai::class, cascade:['persist'])]
    private $ossSchedaPai;

    #[ORM\Column(type: 'boolean')]
    private $sdManagerOperatore = false;

    #[ORM\OneToMany(mappedBy: 'autoreBarthel', targetEntity: Barthel::class, cascade:['persist'])]
    private $idBarthel;

    #[ORM\OneToMany(mappedBy: 'autoreBraden', targetEntity: Braden::class, cascade:['persist'])]
    private $idBraden;

    #[ORM\OneToMany(mappedBy: 'autoreChiusuraServizio', targetEntity: ChiusuraServizio::class, cascade:['persist'])]
    private $idChiusuraServizio;

    #[ORM\OneToMany(mappedBy: 'autoreLesioni', targetEntity: Lesioni::class, cascade:['persist'])]
    private $idLesioni;

    #[ORM\OneToMany(mappedBy: 'autoreParereMmg', targetEntity: ParereMMG::class, cascade:['persist'])]
    private $idParereMmg;

    #[ORM\OneToMany(mappedBy: 'autoreSpmsq', targetEntity: SPMSQ::class, cascade:['persist'])]
    private $idSpmsq;

    #[ORM\OneToMany(mappedBy: 'autoreTinetti', targetEntity: Tinetti::class, cascade:['persist'])]
    private $idTinetti;

    #[ORM\OneToMany(mappedBy: 'autorePainad', targetEntity: Painad::class, cascade:['persist'])]
    private $idPainad;

    #[ORM\OneToMany(mappedBy: 'autoreValutazioneProfessionale', targetEntity: ValutazioneFiguraProfessionale::class, cascade:['persist'])]
    private $idValutazioneFiguraProfessionale;

    #[ORM\OneToMany(targetEntity: ValutazioneGenerale::class, mappedBy: 'autoreValutazioneGenerale', cascade:['persist'])]
    private $idValutazioneGenerale;

    #[ORM\OneToMany(mappedBy: 'autoreVas', targetEntity: Vas::class, cascade:['persist'])]
    private $idVas;

    //#[ORM\Column(type: 'string', nullable: true)]
    private $plainPassword2;

    #[ORM\OneToMany(mappedBy: 'autoreCdr', targetEntity: Cdr::class)]
    private Collection $cdrs;

    public function __construct()
    {
        $this->principaleSchedaPai = new ArrayCollection();
        $this->infSchedaPai = new ArrayCollection();
        $this->tdrSchedaPai = new ArrayCollection();
        $this->logSchedaPai = new ArrayCollection();
        $this->asaSchedaPai = new ArrayCollection();
        $this->ossSchedaPai = new ArrayCollection();
        $this->cdrs = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function setCf(string $cf): self
    {
        $this->cf = $cf;

        return $this;
    }

    public function getCf(): ?string
    {
        return $this->cf;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainpassword2(): string
    {
        return $this->plainPassword2;
    }

    public function setPlainpassword2(string $plainPassword2): self
    {
        $this->plainPassword2 = $plainPassword2;

        return $this;
    }

    public function isStato(): ?bool
    {
        return $this->stato;
    }

    public function setStato(bool $stato): self
    {
        $this->stato = $stato;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, SchedaPAI>
     */
    public function getInfSchedaPai(): Collection
    {
        return $this->infSchedaPai;
    }

    public function addInfSchedaPai(SchedaPAI $infSchedaPai): self
    {
        if (!$this->infSchedaPai->contains($infSchedaPai)) {
            $this->infSchedaPai->add($infSchedaPai);
            $infSchedaPai->addIdOperatoreSecondarioInf($this);
        }

        return $this;
    }

    public function removeInfSchedaPai(SchedaPAI $infSchedaPai): self
    {
        if ($this->infSchedaPai->removeElement($infSchedaPai)) {
            $infSchedaPai->removeIdOperatoreSecondarioInf($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SchedaPAI>
     */
    public function getTdrSchedaPai(): Collection
    {
        return $this->tdrSchedaPai;
    }

    public function addTdrSchedaPai(SchedaPAI $tdrSchedaPai): self
    {
        if (!$this->tdrSchedaPai->contains($tdrSchedaPai)) {
            $this->tdrSchedaPai->add($tdrSchedaPai);
            $tdrSchedaPai->addIdOperatoreSecondarioTdr($this);
        }

        return $this;
    }

    public function removeTdrSchedaPai(SchedaPAI $tdrSchedaPai): self
    {
        if ($this->tdrSchedaPai->removeElement($tdrSchedaPai)) {
            $tdrSchedaPai->removeIdOperatoreSecondarioTdr($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SchedaPAI>
     */
    public function getLogSchedaPai(): Collection
    {
        return $this->logSchedaPai;
    }

    public function addLogSchedaPai(SchedaPAI $logSchedaPai): self
    {
        if (!$this->logSchedaPai->contains($logSchedaPai)) {
            $this->logSchedaPai->add($logSchedaPai);
            $logSchedaPai->addIdOperatoreSecondarioLog($this);
        }

        return $this;
    }

    public function removeLogSchedaPai(SchedaPAI $logSchedaPai): self
    {
        if ($this->logSchedaPai->removeElement($logSchedaPai)) {
            $logSchedaPai->removeIdOperatoreSecondarioLog($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SchedaPAI>
     */
    public function getAsaSchedaPai(): Collection
    {
        return $this->asaSchedaPai;
    }

    public function addAsaSchedaPai(SchedaPAI $asaSchedaPai): self
    {
        if (!$this->asaSchedaPai->contains($asaSchedaPai)) {
            $this->asaSchedaPai->add($asaSchedaPai);
            $asaSchedaPai->addIdOperatoreSecondarioAsa($this);
        }

        return $this;
    }

    public function removeAsaSchedaPai(SchedaPAI $asaSchedaPai): self
    {
        if ($this->asaSchedaPai->removeElement($asaSchedaPai)) {
            $asaSchedaPai->removeIdOperatoreSecondarioAsa($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SchedaPAI>
     */
    public function getOssSchedaPai(): Collection
    {
        return $this->ossSchedaPai;
    }

    public function addOssSchedaPai(SchedaPAI $ossSchedaPai): self
    {
        if (!$this->ossSchedaPai->contains($ossSchedaPai)) {
            $this->ossSchedaPai->add($ossSchedaPai);
            $ossSchedaPai->addIdOperatoreSecondarioOss($this);
        }

        return $this;
    }

    public function removeOssSchedaPai(SchedaPAI $ossSchedaPai): self
    {
        if ($this->ossSchedaPai->removeElement($ossSchedaPai)) {
            $ossSchedaPai->removeIdOperatoreSecondarioOss($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, SchedaPAI>
     */
    public function getPrincipaleSchedaPai(): Collection
    {
        return $this->principaleSchedaPai;
    }

    public function addPrincipaleSchedaPai(SchedaPAI $principaleSchedaPai): self
    {
        if (!$this->principaleSchedaPai->contains($principaleSchedaPai)) {
            $this->principaleSchedaPai->add($principaleSchedaPai);
            $principaleSchedaPai->setIdOperatorePrincipale($this);
        }

        return $this;
    }

    public function removePrincipaleSchedaPai(SchedaPAI $principaleSchedaPai): self
    {
        if ($this->principaleSchedaPai->removeElement($principaleSchedaPai)) {
            // set the owning side to null (unless already changed)
            if ($principaleSchedaPai->getIdOperatorePrincipale() === $this) {
                $principaleSchedaPai->setIdOperatorePrincipale(null);
            }
        }

        return $this;
    }

    public function isSdManagerOperatore(): ?bool
    {
        return $this->sdManagerOperatore;
    }

    public function setSdManagerOperatore(bool $sdManagerOperatore): self
    {
        $this->sdManagerOperatore = $sdManagerOperatore;

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
            
        }

        return $this;
    }

      /**
     * @return Collection<int, ChiusuraServizio>
     */
    public function getIdChiusuraServizio(): Collection
    {
        return $this->idChiusuraServizio;
    }

    public function addIdChiusuraServizio(ChiusuraServizio $idChiusuraServizio): self
    {
        if (!$this->idChiusuraServizio->contains($idChiusuraServizio)) {
            $this->idChiusuraServizio[] = $idChiusuraServizio;
            
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
            $this->idLesioni[] = $idLesioni;
            
        }

        return $this;
    }
      /**
     * @return Collection<int, ParereMmg>
     */
    public function getIdParereMmg(): Collection
    {
        return $this->idParereMmg;
    }

    public function addIdParereMmg(ParereMMG $idParereMmg): self
    {
        if (!$this->idParereMmg->contains($idParereMmg)) {
            $this->idParereMmg[] = $idParereMmg;
            
        }

        return $this;
    }
      /**
     * @return Collection<int, Spmsq>
     */
    public function getIdSpmsq(): Collection
    {
        return $this->idSpmsq;
    }

    public function addIdSpmsq(SPMSQ $idSpmsq): self
    {
        if (!$this->idSpmsq->contains($idSpmsq)) {
            $this->idSpmsq[] = $idSpmsq;
            
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
            
        }

        return $this;
    }

      /**
     * @return Collection<int, Painad>
     */
    public function getIdPainad(): Collection
    {
        return $this->idPainad;
    }

    public function addIdPainad(Painad $idPainad): self
    {
        if (!$this->idPainad->contains($idPainad)) {
            $this->idPainad[] = $idPainad;
            
        }

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
            
        }

        return $this;
    }
      /**
     * @return Collection<int, ValutazioneGenerale>
     */
    public function getIdValutazioneGenerale(): Collection
    {
        return $this->idValutazioneGenerale;
    }

    public function addIdValutazioneGenerale(ValutazioneGenerale $idValutazioneGenerale): self
    {
        if (!$this->idValutazioneGenerale->contains($idValutazioneGenerale)) {
            $this->idValutazioneGenerale[] = $idValutazioneGenerale;
            
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
            
        }

        return $this;
    }

    /**
     * @return Collection<int, Cdr>
     */
    public function getCdrs(): Collection
    {
        return $this->cdrs;
    }

    public function addCdr(Cdr $cdr): self
    {
        if (!$this->cdrs->contains($cdr)) {
            $this->cdrs->add($cdr);
            $cdr->setAutoreCdr($this);
        }

        return $this;
    }

    public function removeCdr(Cdr $cdr): self
    {
        if ($this->cdrs->removeElement($cdr)) {
            // set the owning side to null (unless already changed)
            if ($cdr->getAutoreCdr() === $this) {
                $cdr->setAutoreCdr(null);
            }
        }

        return $this;
    }

}
