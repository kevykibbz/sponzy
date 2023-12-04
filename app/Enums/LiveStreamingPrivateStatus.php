<?php

namespace App\Enums;

enum LiveStreamingPrivateStatus: int
{
    case PENDING = 0;
    case ACCEPTED = 1;
    case REJECTED = 2;
    case EXPIRED = 3;

    public function locale(): string
    {
        return match ($this) {
            self::PENDING => __('general.pending'),
            self::ACCEPTED => __('general.accepted'),
            self::REJECTED => __('general.rejected'),
            self::EXPIRED => __('general.expired'),
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::ACCEPTED => 'success',
            self::REJECTED => 'danger',
            self::EXPIRED => 'danger',
        };
    }
}
