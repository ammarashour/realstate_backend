<?php

namespace App\Enums\Language;

enum JsonGenerationOption: string
{
    case NO_GENERATE = 'no_generate';
    case TARGET_FILE_ONLY = 'target_file_only';
    case ALL_LANGUAGE_FILES = 'all_language_files';

    /**
     * Get all the values of the enum.
     *
     * @return array
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get a human-readable description for each option.
     *
     * @return string
     */
    public function description(): string
    {
        return match ($this) {
            self::NO_GENERATE => 'No JSON files will be generated.',
            self::TARGET_FILE_ONLY => 'Only the target JSON file will be generated.',
            self::ALL_LANGUAGE_FILES => 'All language JSON files will be generated.',
        };
    }
}