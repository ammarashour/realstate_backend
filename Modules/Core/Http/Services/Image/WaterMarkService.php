<?php

namespace Modules\Core\Http\Services\Image;

use App\Http\Services\PsService;
use Modules\Core\Entities\CoreImage;
use Intervention\Image\Facades\Image;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Image\WaterMarkServiceInterface;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;

class WaterMarkService extends PsService implements WaterMarkServiceInterface
{
    public function __construct() {}

    public function applyWatermark($image = null)
    {

        if (empty($image)) {
            throw new FileNotFoundException("Image can't be null.");
        }

        $backendSettingService = app()->make(BackendSettingServiceInterface::class);
        $backendSetting = $backendSettingService->get();
        if ($backendSetting->is_watermask != 1) {
            return $image;
        }

        // Get Water Mark Size
        $waterMarkSize = $this->getWaterMarkImageSize(
            $backendSetting->watermask_image_size,
            $image->width(),
            $image->height()
        );

        // Get Water Mark Image
        $waterMarkImage = $this->getWatermarkImage();
        $waterMarkImageFullPath = public_path().Constants::storageOriginalPath.$waterMarkImage->img_path;

        if (! file_exists($waterMarkImageFullPath)) {
            return $image;
        }

        // Prepare Water Mark Image
        $watermark = Image::make($waterMarkImageFullPath)
            ->resize($waterMarkSize, $waterMarkSize, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->opacity($backendSetting->opacity)
            ->rotate($backendSetting->watermask_angle);

        // Prepare Positions
        $position = $this->getPosition($backendSetting);
        if ($position == null) {
            return $image;
        }

        // Insert WaterMark
        return $this->insertWaterMark($image, $watermark, $position);

    }

    // not sure we will need size param ?
    public function getWatermarkImage()
    {
        return CoreImage::where([CoreImage::imgType => 'backend-water-mask-image'])->first();
    }

    public function isRequireWatermark($uploadType)
    {
        $typesRequiringWatermask = ['item', 'preview', 'background', 'chatApi', 'itemMulti', 'water-mask-background-original'];

        return in_array($uploadType, $typesRequiringWatermask);
    }
    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getWaterMarkImageSize($userProvidedSize, $imgWidth, $imgHeight)
    {

        if (validateWithOperator([$userProvidedSize, $imgWidth, $imgHeight], '<=', 0)) {
            return 0;
        }

        $userProvidedSize = $this->convertSize($userProvidedSize);

        $defaultSize = 0;
        if ($imgWidth > $imgHeight) {
            $defaultSize = $imgWidth / 10;
        } else {
            $defaultSize = $imgHeight / 10;
        }

        return $defaultSize * $userProvidedSize;

    }

    /**
     * Currently user can put the watermark size as
     * any value they want. (Current default setup is 1000)
     * This is old setting value.
     *
     * So, we need to convert the size to between 0 and 9.
     * This function will convert and return the size.
     */
    private function convertSize($userProvidedSize)
    {
        $userProvidedSize = abs($userProvidedSize); // Ensure the number is positive

        if ($userProvidedSize > 9) {
            $userProvidedSize = (int) ($userProvidedSize / pow(10,
                floor(log10($userProvidedSize))));
        }

        return $userProvidedSize;
    }

    private function getPosition($backendSetting)
    {
        $positions = [
            'bottom-right' => ['bottom-right', $backendSetting->padding, $backendSetting->padding],
            'bottom' => ['bottom', 0, $backendSetting->padding],
            'bottom-left' => ['bottom-left', $backendSetting->padding, $backendSetting->padding],
            'top-right' => ['top-right', $backendSetting->padding, $backendSetting->padding],
            'top' => ['top', 0, $backendSetting->padding],
            'top-left' => ['top-left', $backendSetting->padding, $backendSetting->padding],
            'left' => ['left', $backendSetting->padding, $backendSetting->padding],
            'center' => ['center', 0, 0],
            'right' => ['right', $backendSetting->padding, $backendSetting->padding],
        ];

        return $positions[$backendSetting->position] ?? null;
    }

    private function insertWaterMark($image, $watermark, $position)
    {

        if (count($position) >= 3) {
            $image = $image->insert($watermark, $position[0], $position[1], $position[2]);
        }

        return $image;
    }
}
