<?php

namespace App\Enum;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum CategoryStatusEnum: int implements TranslatableInterface
{
    case ACTIVE = 1;
    case INACTIVE = 0;

    public function trans(TranslatorInterface $translator, string $locale = null): string
    {
        return $this->getDisplayName();
    }

    public function getDisplayName(): string
    {
        return match ($this) {
            self::ACTIVE => 'فعال',
            self::INACTIVE => 'غیر فعال',
        };
    }

    public function getCssClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
        };
    }
}
