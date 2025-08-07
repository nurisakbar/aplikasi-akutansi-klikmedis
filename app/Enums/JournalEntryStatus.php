<?php

namespace App\Enums;

enum JournalEntryStatus: string
{
    case DRAFT = 'draft';
    case POSTED = 'posted';

    /**
     * Get all status values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the label for the status.
     */
    public function getLabel(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::POSTED => 'Posted',
        };
    }

    /**
     * Get the badge class for the status.
     */
    public function getBadgeClass(): string
    {
        return match($this) {
            self::DRAFT => 'secondary',
            self::POSTED => 'success',
        };
    }

    /**
     * Check if status is draft.
     */
    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    /**
     * Check if status is posted.
     */
    public function isPosted(): bool
    {
        return $this === self::POSTED;
    }

    /**
     * Get status options for select dropdown.
     */
    public static function getOptions(): array
    {
        $options = [];
        foreach (self::cases() as $status) {
            $options[$status->value] = $status->getLabel();
        }
        return $options;
    }
} 