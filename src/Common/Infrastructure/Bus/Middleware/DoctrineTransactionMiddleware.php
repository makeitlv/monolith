<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Bus\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use InvalidArgumentException;
use RuntimeException;
use Throwable;

class DoctrineTransactionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ValidatorInterface $validator,
        private ManagerRegistry $managerRegistry,
        private ?string $entityManagerName = null
    ) {
    }

    final public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            /** @var EntityManagerInterface $entityManager */
            $entityManager = $this->managerRegistry->getManager($this->entityManagerName);
        } catch (InvalidArgumentException $e) {
            throw new UnrecoverableMessageHandlingException($e->getMessage(), 0, $e);
        }

        return $this->handleForManager($entityManager, $envelope, $stack);
    }

    private function handleForManager(
        EntityManagerInterface $entityManager,
        Envelope $envelope,
        StackInterface $stack
    ): Envelope {
        $entityManager->getConnection()->beginTransaction();
        try {
            $envelope = $stack->next()->handle($envelope, $stack);

            $objects = $entityManager->getUnitOfWork()->getScheduledEntityInsertions();

            foreach ($objects as $object) {
                /** @var ClassMetadata $metadata */
                $metadata = $this->validator->getMetadataFor($object);
                if (!count($metadata->getConstrainedProperties())) {
                    throw new RuntimeException(sprintf("Validation rules is not set for class %s", $object::class));
                }

                $violations = $this->validator->validate($object);
                if (count($violations)) {
                    throw new ValidationFailedException($object, $violations);
                }
            }

            $entityManager->flush();
            $entityManager->getConnection()->commit();

            return $envelope;
        } catch (Throwable $exception) {
            $entityManager->getConnection()->rollBack();

            if ($exception instanceof HandlerFailedException) {
                // Remove all HandledStamp from the envelope so the retry will execute all handlers again.
                // When a handler fails, the queries of allegedly successful previous handlers just got rolled back.
                throw new HandlerFailedException(
                    $exception->getEnvelope()->withoutAll(HandledStamp::class),
                    $exception->getNestedExceptions()
                );
            }

            throw $exception;
        }
    }
}
