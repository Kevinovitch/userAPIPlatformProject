<?php

namespace App\EventListener;


use App\Entity\User;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;

/**
 * An event listener to write a message in the logs
 * after each update on a User
 */
class UserUpdateListener
{

    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof User) {
            return;
        }
        $message = sprintf('User %s (%d) %s', $entity->getFirstname() . ' ' . $entity->getLastname(), $entity->getId(), 'updated');
        $this->logger->info($message);

    }

}