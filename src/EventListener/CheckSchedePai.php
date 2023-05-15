<?php

namespace App\EventListener;

use App\Entity\EntityPAI\SchedaPAI;
use App\Service\SetterDatiSchedaPaiService;
use App\Service\SetterValoriNonMappatiScaleSchedaPaiService;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CheckSchedePai implements EventSubscriberInterface
{
    private $setterDatiSchedePaiService;
    private $setterValoriNonMappatiScaleSchedaPaiService;

    public function __construct( SetterDatiSchedaPaiService $setterDatiSchedaPAiService, SetterValoriNonMappatiScaleSchedaPaiService $setterValoriNonMappatiScaleSchedaPaiService)
    {
       $this->setterDatiSchedePaiService = $setterDatiSchedaPAiService;
       $this->setterValoriNonMappatiScaleSchedaPaiService = $setterValoriNonMappatiScaleSchedaPaiService;
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
        dump('postUpdate');
        dump($entity);

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof SchedaPAI) {
            return;
        }
        $this->setterDatiSchedePaiService->settaDati($entity);

        $this->setterValoriNonMappatiScaleSchedaPaiService->settaValoriScale($entity);
        
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof SchedaPAI) {
            return;
        }
        $this->setterDatiSchedePaiService->settaDati($entity);
    }

    public function postLoad(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();
        
        // if this subscriber only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if (!$entity instanceof SchedaPAI) {
            return;
        }
        $this->setterValoriNonMappatiScaleSchedaPaiService->settaValoriScale($entity);
    }

}
