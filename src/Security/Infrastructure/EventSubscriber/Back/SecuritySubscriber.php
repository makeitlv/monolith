<?php

declare(strict_types=1);

namespace App\Security\Infrastructure\EventSubscriber\Back;

use App\Common\Domain\Translation\TranslatableMessage;
use App\Security\Infrastructure\Provider\Back\AdminIdentity;
use EasyCorp\Bundle\EasyAdminBundle\Config\Option\EA;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\Session;

final class SecuritySubscriber implements EventSubscriberInterface
{
    public function __construct(private Security $security)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => "onKernelController",
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->attributes->get(EA::CONTEXT_REQUEST_ATTRIBUTE) === null) {
            return;
        }

        if ($request->getMethod() !== "GET") {
            return;
        }

        /** @var AdminIdentity|null $admin */
        $admin = $this->security->getUser();
        if ($admin instanceof AdminIdentity && $admin->isPasswordSecure() === false) {
            /** @var Session $session */
            $session = $request->getSession();

            $session
                ->getFlashBag()
                ->add("warning", new TranslatableMessage("Please, change your password!", [], "security"));
        }
    }
}
