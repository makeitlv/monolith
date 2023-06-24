<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Service\Admin;

use App\Common\Infrastructure\Translation\Notice\TranslatableMessage;
use App\Notification\Application\Service\Admin\PasswordNotifierInterface;
use App\Notification\Domain\Event\External\Admin\AdminPasswordChangedEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

readonly final class PasswordEmailNotifier implements PasswordNotifierInterface
{
    public function __construct(private MailerInterface $mailer)
    {
    }

    public function notify(AdminPasswordChangedEvent $event): void
    {
        $subject = new TranslatableMessage("Your password changed!");

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
