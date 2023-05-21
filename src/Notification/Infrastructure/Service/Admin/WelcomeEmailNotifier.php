<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Service\Admin;

use App\Notification\Application\Service\Admin\WelcomeNotifierInterface;
use App\Notification\Domain\Event\Admin\AdminCreatedEvent;
use App\Common\Presentation\Translation\Notice\TranslatableMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

readonly final class WelcomeEmailNotifier implements WelcomeNotifierInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function notify(AdminCreatedEvent $event): void
    {
        $subject = new TranslatableMessage("Welcome to the team!");

        $email = (new TemplatedEmail())
            ->to($event->email)
            ->subject((string) $subject)
            ->context([
                "subject" => $subject,
                "user" => $event->email,
                "name" => $event->name,
                "password" => $event->plainPassword,
                "confirmationToken" => $event->confirmationToken,
            ])
            ->htmlTemplate("mail/admin/welcome/index.html.twig");

        $this->mailer->send($email);
    }
}
