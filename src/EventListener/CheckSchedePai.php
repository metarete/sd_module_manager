<?php

namespace App\EventListener;

use App\Service\DateCompilazioneSchedeService;
use App\Entity\EntityPAI\SchedaPAI;
use App\Service\SetterDatiSchedaPaiService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CheckSchedePai implements EventSubscriberInterface
{
    private $dateCompilazioneSchede;
    private $setterDatiSchedePaiService;

    public function __construct(DateCompilazioneSchedeService $dateCompilazioneSchede, SetterDatiSchedaPaiService $setterDatiSchedaPAiService)
    {
       $this->dateCompilazioneSchede = $dateCompilazioneSchede;
       $this->setterDatiSchedePaiService = $setterDatiSchedaPAiService;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postUpdate,
            Events::postPersist,
            Events::postRemove,
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

        $this->dateCompilazioneSchede->settaScadenzarioSchede($entity);
        $this->setterDatiSchedePaiService->settaDati($entity);
        
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof SchedaPAI) {
            return;
        }
        
        $this->dateCompilazioneSchede->settaScadenzarioSchede($entity);
        $this->setterDatiSchedePaiService->settaDati($entity);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof SchedaPAI) {
            return;
        }
        
        $this->dateCompilazioneSchede->settaScadenzarioSchede($entity);
        
    }

}
