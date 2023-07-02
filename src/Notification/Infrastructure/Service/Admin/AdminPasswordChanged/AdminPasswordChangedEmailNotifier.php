<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Service\Admin\AdminPasswordChanged;

use App\Common\Domain\Bus\Event\Event;
use App\Common\Domain\Translation\TranslatableMessage;
use App\Notification\Application\Service\NotifierInterface;
use App\Notification\Domain\Event\External\Admin\AdminPasswordChangedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

readonly final class AdminPasswordChangedEmailNotifier implements NotifierInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function notify(Event $event): void
    {
        /** @var AdminPasswordChangedEvent $event */
        $subject = new TranslatableMessage("Your password changed!", [], "notice");

        $email = (new TemplatedEmail())
            ->to($event->email)
            ->subject((string) $subject)
            ->context([
                "subject" => $subject,
                "user" => $event->email,
                "name" => $event->name,
                "password" => $event->plainPassword,
            ])
            ->htmlTemplate("mail/admin/password/index.html.twig");

        $this->mailer->send($email);
    }
}
