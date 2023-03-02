<?php

namespace App\EventListener;

use Doctrine\ORM\Events;
use App\Entity\EntityPAI\SPMSQ;
use App\Service\SetterTotaliSpmsqService;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class CheckSpmsq implements EventSubscriberInterface
{
    private $setterTotaliSpmsqService;

    public function __construct( SetterTotaliSpmsqService $setterTotaliSpmsqService)
    {
       $this->setterTotaliSpmsqService = $setterTotaliSpmsqService;
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
        if (!$o instanceof SPMSQ) {
            return;
        }
        $this->setterTotaliSpmsqService->settaTotali($o);
        
    }
    public function postPersist(LifecycleEventArgs $args): void
    {
        $o = $args->getObject();
        

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$o instanceof SPMSQ) {
            return;
        }
        $this->setterTotaliSpmsqService->settaTotali($o);
    }
    
    
   
}