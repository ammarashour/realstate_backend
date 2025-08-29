<?php

namespace App\Http\Contracts\Image;

use App\Http\Contracts\Core\PsInterface;

interface ImageProcessingServiceInterface extends PsInterface
{
    public function createImageFiles($file, $fileName, $imageType, array $resolutions = ['original']);

    public function createImageFile($file, $fileName, $imageType, $resolution = 'original');

    public function createIcoFile($file, $fileName);

    public function deleteImageFile($imgPath);
}
