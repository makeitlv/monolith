<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use stdClass;

abstract class AbstractDoctrineRepository
{
    /** @var class-string */
    protected const CLASS_NAME = stdClass::class;

    protected ObjectRepository $objectRepository;

    public function __construct(protected EntityManagerInterface $entityManager)
    {
        if (static::CLASS_NAME === stdClass::class) {
            throw new InvalidArgumentException("Constant CLASS_NAME should be set.");
        }

        $this->objectRepository = $this->entityManager->getRepository(static::CLASS_NAME);
    }
}
