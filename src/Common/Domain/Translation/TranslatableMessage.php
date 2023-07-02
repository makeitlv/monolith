<?php

declare(strict_types=1);

namespace App\Common\Domain\Translation;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use RuntimeException;

class TranslatableMessage implements TranslatableInterface
{
    public function __construct(private string $message, private array $parameters = [], private ?string $domain = null)
    {
        if (!$this->domain) {
            throw new RuntimeException("Important to set translation domain parameter.");
        }
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $translator->trans(
            $this->getMessage(),
            array_map(static function ($parameter) use ($translator, $locale) {
                return $parameter instanceof TranslatableInterface
                    ? $parameter->trans($translator, $locale)
                    : $parameter;
            }, $this->getParameters()),
            $this->getDomain(),
            $locale
        );
    }
}
