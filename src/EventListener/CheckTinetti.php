<?php

namespace App\EventListener;

use Doctrine\ORM\Events;
use App\Entity\EntityPAI\Tinetti;
use App\Service\SetterTotaliTinettiService;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class CheckTinetti implements EventSubscriberInterface
{
    private $setterTotaliTinettiService;

    public function __construct( SetterTotaliTinettiService $setterTotaliTinettiService)
    {
       $this->setterTotaliTinettiService = $setterTotaliTinettiService;
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
        if (!$o instanceof Tinetti) {
            return;
        }
        $this->setterTotaliTinettiService->settaTotali($o);
        
    }
    public function postPersist(LifecycleEventArgs $args): void
    {
        $o = $args->getObject();
        

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$o instanceof Tinetti) {
            return;
        }
        $this->setterTotaliTinettiService->settaTotali($o);
    }
    
    
   
}