<?php

namespace App\Helpers;
use Illuminate\Support\Facades\File;

class PsLanguageJsonHelper
{    
    public static function generateJsonFile($fileName, $langStringList)
    {
        $filePath = base_path('lang/' . $fileName.'.json');
        
        if (! File::exists($filePath)) {
            File::put($filePath, '');
        }

        $jsonData = File::get($filePath);
        $languageString = json_decode($jsonData, true);

        foreach ($langStringList as $str) {
            $languageString[trim($str['key'])] = trim($str['value']);
        }

        $file['data'] = json_encode($languageString);
        File::put($filePath, $file);
    }

    public static function deleteLanguageStringByKeys($fileName, $keys)
    {
        $filePath = base_path('lang/' . $fileName.'.json');
        if (File::exists($filePath)) {
            $jsonData = File::get($filePath);
            $languageString = json_decode($jsonData, true);
                                  
            foreach ($keys as $key) {
                if (array_key_exists($key, $languageString)) {
                    unset($languageString[$key]);
                }
            }

            $file['data'] = json_encode($languageString);
            File::put($filePath, $file);
        }
    }

    public static function deleteJsonFile($fileName)
    {
        $filePath = base_path('lang/' . $fileName.'.json');
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    
}