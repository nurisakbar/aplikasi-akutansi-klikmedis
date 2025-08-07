<?php

namespace App\Enums;

enum CashBankTransactionType: string
{
    case IN = 'in';
    case OUT = 'out';
    case TRANSFER = 'transfer';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function getLabel(): string
    {
        return match($this) {
            self::IN => 'Masuk',
            self::OUT => 'Keluar',
            self::TRANSFER => 'Transfer',
        };
    }

    public function getBadgeClass(): string
    {
        return match($this) {
            self::IN => 'success',
            self::OUT => 'danger',
            self::TRANSFER => 'info',
        };
    }

    public function isIn(): bool
    {
        return $this === self::IN;
    }

    public function isOut(): bool
    {
        return $this === self::OUT;
    }

    public function isTransfer(): bool
    {
        return $this === self::TRANSFER;
    }

    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $type) {
            $options[$type->value] = $type->getLabel();
        }
        return $options;
    }
} 