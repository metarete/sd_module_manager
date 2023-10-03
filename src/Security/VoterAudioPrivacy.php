<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class VoterAudioPrivacy extends Voter
{
    private $params;

    const ATTIVAZIONE_AUDIO_PRIVACY = "attivazione_audio_privacy";

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [
            self::ATTIVAZIONE_AUDIO_PRIVACY,
            
        ])) {

            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /*$user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }*/

        return match ($attribute) {
            self::ATTIVAZIONE_AUDIO_PRIVACY => $this->attivazioneAudioPrivacy(),
            default => throw new \LogicException('This code should not be reached!')
        };
    }

    private function attivazioneAudioPrivacy(): bool
    {
        $attivazione = $this->params->get('app.audio_privacy_abilitati');

        if ($attivazione == "true") {
            return true;
        } else {
            return false;
        }
    }
}