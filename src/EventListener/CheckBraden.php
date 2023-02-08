<?php

namespace App\EventListener;

use App\Entity\EntityPAI\Braden;
use App\Service\SetterTotaleBradenService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CheckBraden implements EventSubscriberInterface
{
    private $setterTotaleBradenService;

    public function __construct( SetterTotaleBradenService $setterTotaleBradenService)
    {
       $this->setterTotaleBradenService = $setterTotaleBradenService;
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
        if (!$o instanceof Braden) {
            return;
        }
        $this->setterTotaleBradenService->settaTotale($o);
        
    }
    public function postPersist(LifecycleEventArgs $args): void
    {
        $o = $args->getObject();
        

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$o instanceof Braden) {
            return;
        }
        $this->setterTotaleBradenService->settaTotale($o);
        
    }
    
    
   
}