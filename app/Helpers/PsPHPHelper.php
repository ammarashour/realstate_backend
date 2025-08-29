<?php

namespace App\Helpers;

class PsPHPHelper
{
    public static function getPhpVersion($path)
    {
        // Check if the path exists
        // if (!is_dir($path)) {
        //     return "Invalid path";
        // }

        // Construct the path to the PHP binary file
        // $phpBinary = $path . DIRECTORY_SEPARATOR . 'php';
        $phpBinary = $path;

        // Check if PHP binary file exists
        if (!file_exists($phpBinary)) {
            // return "PHP binary not found";
            return 'php';
        }

        // Open process to execute "php -v" command
        $descriptors = [
            0 => ['pipe', 'r'], // stdin
            1 => ['pipe', 'w'], // stdout
            2 => ['pipe', 'w'], // stderr
        ];

        $process = proc_open("$phpBinary -v", $descriptors, $pipes);

        // Check if process opened successfully
        if (!is_resource($process)) {
            return "Failed to execute PHP binary";
        }

        // Read output from stdout
        $output = stream_get_contents($pipes[1]);

        // Close pipes and process
        fclose($pipes[0]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);

        // Parse PHP version from the output
        preg_match('/PHP\s*(\d+\.\d+\.\d+)/', $output, $matches);

        // Check if version found
        if (isset($matches[1])) {
            return $matches[1];
        } else {
            return "PHP version not found";
        }
    }

    public static function CheckPhpVersion()
    {
        $phpPathFromEnv = config("app.php_path");

        if (!empty($phpPathFromEnv)) {
            $phpVersion = shell_exec($phpPathFromEnv . ' -r "echo PHP_VERSION;"');

            if (empty($phpVersion)) {
                $dataArr = [
                    "errMsg" => "This php path ($phpPathFromEnv) is wrong. You can find detailed instructions in our guide at",
                ];
                // dd($dataArr);
            }

            return $phpPathFromEnv;
        } else {
            return "php";
        }
    }
}
