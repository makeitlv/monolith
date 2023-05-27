<?php

declare(strict_types=1);

namespace App\Tests\Notification\Application\UseCase\Event\Admin\AdminCreated;

use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Common\Domain\Bus\Event\EventBus;
use App\Tests\FunctionalTestCase;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use Zenstruck\Mailer\Test\InteractsWithMailer;
use Zenstruck\Messenger\Test\InteractsWithMessenger;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class SendWelcomeNotifierHandlerTest extends FunctionalTestCase
{
    use InteractsWithMessenger;
    use InteractsWithMailer;

    public function testEmailSent(): void
    {
        /** @var EventBus $eventBus */
        $eventBus = $this->getContainer()->get(EventBus::class);
        $eventBus->publish(
            new AdminCreatedEvent(
                ($uuid = "de28e1d2-9a57-48b0-af1b-ae54b39e0801"),
                ($email = "admin@admin.com"),
                ($name = "Admin Admin"),
                ($password = "Password")
            )
        );

        $asyncTrasport = $this->transport("async");
        $mailer = $this->mailer();

        $asyncTrasport->queue()->assertNotEmpty();
        $asyncTrasport->queue()->assertCount(1);
        $asyncTrasport->queue()->assertContains(AdminCreatedEvent::class);

        $mailer->assertNoEmailSent();

        $asyncTrasport->process();

        $mailer->assertSentEmailCount(1);
        $mailer->assertEmailSentTo($email, "Welcome to the team!");
    }

    public function testEmailNotSent(): void
    {
        /** @var EventBus $eventBus */
        $eventBus = $this->getContainer()->get(EventBus::class);
        $eventBus->publish(
            new AdminCreatedEvent(
                ($uuid = "de28e1d2-9a57-48b0-af1b-ae54b39e0801"),
                ($email = "adminadmin.com"),
                ($name = "Admin Admin"),
                ($password = "Password")
            )
        );

        $asyncTrasport = $this->transport("async");

        $asyncTrasport->queue()->assertNotEmpty();
        $asyncTrasport->queue()->assertCount(1);
        $asyncTrasport->queue()->assertContains(AdminCreatedEvent::class);

        $asyncTrasport->process();
        $asyncTrasport
            ->rejected()
            ->assertCount(4)
            ->assertContains(AdminCreatedEvent::class);

        $asyncTrasport->queue()->assertEmpty();

        /** @var Connection $connection */
        $connection = $this->getContainer()
            ->get(EntityManagerInterface::class)
            ->getConnection();

        $messageData = $connection
            ->prepare("SELECT * FROM messenger_messages LIMIT 1")
            ->executeQuery()
            ->fetchAssociative();

        /** @var SerializerInterface $serializer */
        $serializer = $this->getContainer()->get(SerializerInterface::class);
        /** @var AdminCreatedEvent $message */
        $message = $serializer->decode($messageData)->getMessage();

        self::assertEquals($uuid, $message->uuid);
        self::assertEquals($email, $message->email);
        self::assertEquals($name, $message->name);
        self::assertEquals($password, $message->plainPassword);
        self::assertNull($message->confirmationToken);
    }
}
