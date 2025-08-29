<?php

namespace App\Http\Contracts\Image;

use App\Http\Contracts\Core\PsInterface;

interface WaterMarkServiceInterface extends PsInterface
{ 
    public function applyWatermark($image);

    // not sure we will need size param ?
    public function getWatermarkImage(); 

    public function isRequireWatermark($uploadType);

}