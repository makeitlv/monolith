<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Middleware;

use App\Common\Domain\Bus\Event\Event;
use App\Common\Domain\Bus\Event\EventBus;
use App\Common\Infrastructure\Bus\Stamp\ExternalEventMessageStamp;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Serializer\SerializerInterface;
use RuntimeException;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

class EventMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ParameterBagInterface $params,
        private SerializerInterface $serializer,
        private EventBus $eventBus
    ) {
    }

    final public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $externalStamp = $envelope->last(ExternalEventMessageStamp::class);
        if ($externalStamp !== null) {
            return $stack->next()->handle($envelope, $stack);
        }

        /** @var Event $message */
        $message = $envelope->getMessage();

        $messageDataOnJson = json_encode($message);

        foreach ($this->getExternalMessages($message) as $messageClass) {
            /** @var Event $externalMessage */
            $externalMessage = $this->serializer->deserialize($messageDataOnJson, $messageClass, "json");

            $this->eventBus->publish(
                (new Envelope($externalMessage))->with(
                    new ExternalEventMessageStamp(),
                    new DispatchAfterCurrentBusStamp()
                )
            );
        }

        return $stack->next()->handle($envelope, $stack);
    }

    /** @return array<string> */
    private function getExternalMessages(Event $event): array
    {
        /** @var string $rootDirectory */
        $rootDirectory = $this->params->get("kernel.project_dir");
        $messageClassName = (new \ReflectionClass($event))->getShortName();

        $directory = sprintf("%s/%s", $rootDirectory, "src/*/Domain/Event/External");

        $finder = new Finder();
        $finder
            ->files()
            ->name("*.php")
            ->contains($messageClassName)
            ->in($directory);

        $messages = [];
        foreach ($finder as $file) {
            $messages[] = $this->getMessageClassName($file, $messageClassName);
        }

        return $messages;
    }

    private function getMessageClassName(SplFileInfo $file, string $messageClassName): string
    {
        if (preg_match("/namespace\s+([^;]+);/", $file->getContents(), $matches)) {
            return sprintf("%s\\%s", $matches[1], $messageClassName);
        }

        throw new RuntimeException("External message namespace not found.");
    }
}
