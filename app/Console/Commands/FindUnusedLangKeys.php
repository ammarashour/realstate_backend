<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

//sample command
// php artisan lang:unused {limit} {offset}
// php artisan lang:unused 50 1
class FindUnusedLangKeys extends Command
{
    protected $signature = 'lang:unused {total_number} {index}';
    protected $description = 'Find unused language keys in the project';

    public function handle()
    {
        // Get parameters
        $totalNumber = $this->argument('total_number');
        $index = $this->argument('index');

        $langFiles = File::allFiles(base_path('lang')); // Change to your language directory
        $allKeys = [];

        // Collect all language keys
        foreach ($langFiles as $file) {
            if ($file->getFilename() === 'en.json') {
                $content = File::get($file->getRealPath());
                $keys = array_keys(json_decode($content, true) ?? []);
                $allKeys = array_merge($allKeys, $keys);
            }
        }
        // Total number of keys in the en.json file
        $totalKeys = count($allKeys);

        // Calculate the start and end index for the current batch of keys
        $startIndex = ($index - 1) * $totalNumber;
        $endIndex = min($startIndex + $totalNumber, $totalKeys); // Ensure we do not exceed the total number of keys

        // Slice the keys array for the current batch
        $keysToCheck = array_slice($allKeys, $startIndex, $totalNumber);

        $unusedKeys = [];

        foreach ($keysToCheck as $key) {
            $searchResult = shell_exec("grep -r --exclude-dir=storage --exclude-dir=lang --exclude-dir=node_modules --exclude-dir=vendor \"$key\" .");

            if (empty(trim($searchResult))) {
                $unusedKeys[] = $key;
            }
        }

        // Output total keys and filter range
        $this->info("Total Key: $totalKeys");
        $this->info("Filter Range: $startIndex to $endIndex");

        if (count($unusedKeys) > 0) {
            $this->warn("Unused Language Keys:");
            foreach ($unusedKeys as $key) {
                $this->line("- $key");
            }
        } else {
            $this->info("No unused keys found.");
        }
    }
}
