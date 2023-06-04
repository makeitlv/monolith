<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Event;

use App\Common\Domain\Bus\Event\Event;
use App\Common\Domain\Bus\Event\EventBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Serializer\SerializerInterface;
use RuntimeException;
use Throwable;

class AsyncEventBus implements EventBus
{
    public function __construct(
        private ParameterBagInterface $params,
        private SerializerInterface $serializer,
        private MessageBusInterface $eventBus
    ) {
    }

    public function publish(Event $event): void
    {
        try {
            $this->eventBus->dispatch($event);

            $this->dispatchExternalMessages($event);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                /** @var Throwable $e */
                $e = $e->getPrevious();
            }

            throw $e;
        }
    }

    private function dispatchExternalMessages(Event $message): void
    {
        $messageDataOnJson = json_encode($message);

        foreach ($this->getExternalMessages($message) as $messageClass) {
            /** @var Event $externalMessage */
            $externalMessage = $this->serializer->deserialize($messageDataOnJson, $messageClass, "json");

            $this->eventBus->dispatch($externalMessage);
        }
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
