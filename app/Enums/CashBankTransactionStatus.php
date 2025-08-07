<?php

namespace App\Enums;

enum CashBankTransactionStatus: string
{
    case DRAFT = 'draft';
    case POSTED = 'posted';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::POSTED => 'Posted',
        };
    }

    public function getBadgeClass(): string
    {
        return match($this) {
            self::DRAFT => 'secondary',
            self::POSTED => 'success',
        };
    }

    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    public function isPosted(): bool
    {
        return $this === self::POSTED;
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $status) {
            $options[$status->value] = $status->getLabel();
        }
        return $options;
    }
} 