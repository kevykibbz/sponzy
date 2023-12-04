<?php

namespace App\Enums;

enum AdvertisingStatus: int
{
    case DISABLED = 0;
    case ENABLED = 1;
    case EXPIRED = 2;

    public function locale(): string
    {
        return match ($this) {
            self::DISABLED => __('general.disabled'),
            self::ENABLED => __('general.enabled'),
            self::EXPIRED => __('general.expired'),
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::DISABLED => 'secondary',
            self::ENABLED => 'success',
            self::EXPIRED => 'danger',
        };
    }
}
