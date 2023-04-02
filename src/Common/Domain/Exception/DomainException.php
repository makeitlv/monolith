<?php

declare(strict_types=1);

namespace App\Common\Domain\Exception;

use App\Common\Domain\Translation\TranslatableMessage;
use DomainException as GeneralDomainException;

final class DomainException extends GeneralDomainException
{
    public function __construct(private TranslatableMessage $translatableMessage)
    {
        parent::__construct((string) $translatableMessage);
    }

    public function getTranslatableMessage(): TranslatableMessage
    {
        return $this->translatableMessage;
    }

    public function getTranslatableMessageParameters(): array
    {
        return $this->translatableMessage->getParameters();
    }
}
