<?php

namespace App\Helpers;

class PsArtisanHelper
{
    public static function runArtisanCommandWithPhpVersion($phpBinary, $artisanCommand)
    {
        // Check if PHP binary file exists
        if (!file_exists($phpBinary)) {
            $phpBinary = 'php';
        }

        // Open process to execute Artisan command with specified PHP version
        $descriptors = [
            0 => ['pipe', 'r'], // stdin
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ];

        $process = proc_open("$phpBinary $artisanCommand", $descriptors, $pipes, base_path());

        // Check if process opened successfully
        if (!is_resource($process)) {
            return "Failed to execute Artisan command with PHP binary";
        }

        // Read output from stdout
        $output = stream_get_contents($pipes[1]);

        // Close pipes and process
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        return $output;
    }
}

