<?php

namespace App\Enums;

enum CustomerStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ON_HOLD = 'on_hold';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function getOptions(): array
    {
        return [
            self::ACTIVE->value => 'Aktif',
            self::INACTIVE->value => 'Nonaktif',
            self::ON_HOLD->value => 'Ditahan'
        ];
    }

    public function getLabel(): string
    {
        return match($this) {
            self::ACTIVE => 'Aktif',
            self::INACTIVE => 'Nonaktif',
            self::ON_HOLD => 'Ditahan'
        };
    }

    public function getBadgeClass(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'danger',
            self::ON_HOLD => 'warning'
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }

    public function isOnHold(): bool
    {
        return $this === self::ON_HOLD;
    }
} 