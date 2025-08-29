<?php

use Modules\Core\Entities\Configuration\FrontendSetting;
use Modules\Core\Entities\Configuration\Color;
use Illuminate\Support\Facades\File;

if ( !function_exists("rgb2hex2rgb")){
    function rgb2hex2rgb($color){
        if(!$color) return false;
        $color = trim($color);
        $result = false;
    if(preg_match("/^[0-9ABCDEFabcdef\#]+$/i", $color)){
        $hex = str_replace('#','', $color);
        if(!$hex) return false;
        if(strlen($hex) == 3):
            $result['r'] = hexdec(substr($hex,0,1).substr($hex,0,1));
            $result['g'] = hexdec(substr($hex,1,1).substr($hex,1,1));
            $result['b'] = hexdec(substr($hex,2,1).substr($hex,2,1));
        else:
            $result['r'] = hexdec(substr($hex,0,2));
            $result['g'] = hexdec(substr($hex,2,2));
            $result['b'] = hexdec(substr($hex,4,2));
        endif;
        }elseif (preg_match("/^[0-9]+(,| |.)+[0-9]+(,| |.)+[0-9]+$/i", $color)){
        $rgbstr = str_replace(array(',',' ','.'), ':', $color);
        $rgbarr = explode(":", $rgbstr);
        $result = '#';
        $result .= str_pad(dechex($rgbarr[0]), 2, "0", STR_PAD_LEFT);
        $result .= str_pad(dechex($rgbarr[1]), 2, "0", STR_PAD_LEFT);
        $result .= str_pad(dechex($rgbarr[2]), 2, "0", STR_PAD_LEFT);
        $result = strtoupper($result);
        }else{
        $result = false;
        }

        return $result;
    }
}

if ( !function_exists("syncFrontendColors")){
    function syncFrontendColors(){

        $colorList = Color::where('fe_color','1')->get();

        $style =
"@tailwind base;\n
@tailwind components;\n
@tailwind utilities;\n

@layer base {\n
  :root {\n";

        foreach($colorList as $color){
            $response = rgb2hex2rgb($color->value);
            $colorCode = $response['r']." ".$response['g']." ".$response['b'];

                $style = $style .
"--color-".$color->key.": ".$colorCode.";\n";

        }
        $style = $style ."}\n}\n";
        
        // List of name of files inside 
        $files = glob('css/*');  
        
        // Deleting all the files in the list 
        foreach($files as $file) { 
            if(is_file($file))  
                unlink($file);  
        } 
        
        $code = FrontendSetting::first()->color_changed_code;
        $file = 'css/custom_color_'.$code.'.css';

        // $filePath = storage_path($file); // Get the full path to the storage directory

        $filePath = public_path($file);

        // Ensure the directory exists
        if (!File::exists(dirname($filePath))) {
            File::makeDirectory(dirname($filePath), 0755, true);
        }

        File::put($filePath, $style);
        
        // if(is_file($file)) {
        //     file_put_contents($file,$style);
        // }
    }
}
