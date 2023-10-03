<?php

namespace App\Entity;

use App\Repository\AudioPrivacyRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AudioPrivacyRepository::class)]
class AudioPrivacy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $audio = null;

    #[ORM\OneToOne(inversedBy: 'audioPrivacy', cascade: ['persist'])]
    private ?Paziente $assistito = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAudio(): ?array
    {
        return $this->audio;
    }

    public function setAudio(?array $audio): static
    {
        $this->audio = $audio;

        return $this;
    }

    public function getAssistito(): ?Paziente
    {
        return $this->assistito;
    }

    public function setAssistito(?Paziente $assistito): static
    {
        $this->assistito = $assistito;

        return $this;
    }
}
