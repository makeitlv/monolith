<?php

declare(strict_types=1);

namespace App\Security\Presentation\EventSubscriber\Back;

use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\Session;

final class SecuritySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => "onKernelController",
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        if (null === $request->attributes->get(EA::CONTEXT_REQUEST_ATTRIBUTE)) {
            return;
        }

        if (true) {
            /** @var Session $session */
            $session = $request->getSession();

            $session->getFlashBag()->add("warning", "Please, change your password!");
        }
    }
}
