<?php

namespace App\EventListener;

use App\Entity\EntityPAI\Barthel;
use App\Service\SetterTotaliBarthelService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CheckBarthel implements EventSubscriberInterface
{
    
    private $setterTotaliBarthelService;

    public function __construct( SetterTotaliBarthelService $setterTotaliBarthelService)
    {
       $this->setterTotaliBarthelService = $setterTotaliBarthelService;
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
        if (!$o instanceof Barthel) {
            return;
        }
        
        
        
        $this->setterTotaliBarthelService->settaTotali($o);
        
    }
    public function postPersist(LifecycleEventArgs $args): void
    {
        $o = $args->getObject();
        

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$o instanceof Barthel) {
            return;
        }
        
        
        
        $this->setterTotaliBarthelService->settaTotali($o);
        
    }
    
    
   
}