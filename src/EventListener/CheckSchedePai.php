<?php

namespace App\EventListener;

use App\Entity\EntityPAI\SchedaPAI;
use App\Service\SetterDatiSchedaPaiService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CheckSchedePai implements EventSubscriberInterface
{
    private $setterDatiSchedePaiService;

    public function __construct( SetterDatiSchedaPaiService $setterDatiSchedaPAiService)
    {
       $this->setterDatiSchedePaiService = $setterDatiSchedaPAiService;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate,
            Events::postPersist,
            Events::postLoad,
        ];
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof SchedaPAI) {
            return;
        }

        $this->setterDatiSchedePaiService->settaDatiAssistito($entity);
        $this->setterDatiSchedePaiService->settaDatiCompilazioneSchede($entity);
        
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof SchedaPAI) {
            return;
        }

        $this->setterDatiSchedePaiService->settaDatiAssistito($entity);
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        
        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof SchedaPAI) {
            return;
        }
        
        $this->setterDatiSchedePaiService->settaDatiCompilazioneSchede($entity);
    }

}
