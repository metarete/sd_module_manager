<?php

namespace App\EventListener;

use Doctrine\ORM\Events;
use App\Entity\EntityPAI\Painad;
use App\Service\SetterTotaliPainadService;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class CheckPainad implements EventSubscriberInterface
{
    private $setterTotaliPainadService;

    public function __construct( SetterTotaliPainadService $setterTotaliPainadService)
    {
       $this->setterTotaliPainadService = $setterTotaliPainadService;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate,
            Events::postPersist,
            
        ];
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $o = $args->getObject();
        
        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$o instanceof Painad) {
            return;
        }

        $this->setterTotaliPainadService->settaTotali($o);
        
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $o = $args->getObject();
        
        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$o instanceof Painad) {
            return;
        }

        $this->setterTotaliPainadService->settaTotali($o);
    }
    
}