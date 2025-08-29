<?php

namespace App\Enums\Language;

enum InsertionSource: string
{
    case FROM_BUILDER = 'from_builder';
    case DEFAULT = 'from_default'; 
    
    /**
     * Get the description for the insertion source.
     *
     * @return string
     */
    public function description(): string
    {
        return match ($this) {
            self::FROM_BUILDER => 'Language was imported from builder',
            self::DEFAULT => 'Language was imported from other sources',
        };
    }

    public function getValue(): string {
        return match ($this) {
            self::FROM_BUILDER => '1',
            self::DEFAULT => '0',
        };
    }

}