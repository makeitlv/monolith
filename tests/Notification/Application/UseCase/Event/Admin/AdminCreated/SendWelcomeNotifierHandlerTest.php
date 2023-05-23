<?php

declare(strict_types=1);

namespace App\Tests\Notification\Application\UseCase\Event\Admin\AdminCreated;

use App\Admin\Domain\Event\AdminCreatedEvent;
use App\Common\Domain\Bus\Event\EventBus;
use App\Tests\FunctionalTestCase;
use Zenstruck\Mailer\Test\InteractsWithMailer;
use Zenstruck\Messenger\Test\InteractsWithMessenger;

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
                ($firstname = "Admin"),
                ($lastname = "Admin")
            )
        );

        $this->transport("async")
            ->queue()
            ->assertNotEmpty();
        $this->transport("async")
            ->queue()
            ->assertCount(1);
        $this->transport("async")
            ->queue()
            ->assertContains(AdminCreatedEvent::class);

        $this->mailer()->assertNoEmailSent();

        $this->transport("async")->process();

        $this->mailer()->assertSentEmailCount(1);
        $this->mailer()->assertEmailSentTo($email, "Welcome to the team!");
    }
}
