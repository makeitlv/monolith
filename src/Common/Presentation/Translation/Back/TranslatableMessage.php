<?php

declare(strict_types=1);

namespace App\Common\Presentation\Translation\Back;

use App\Common\Domain\Translation\TranslatableMessage as TranslationTranslatableMessage;

final class TranslatableMessage extends TranslationTranslatableMessage
{
    public const DOMAIN = "back";

    public function __construct(private string $message, private array $parameters = [])
    {
        parent::__construct($message, $parameters);
    }
}
