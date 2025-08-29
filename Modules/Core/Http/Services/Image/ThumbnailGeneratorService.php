<?php

namespace Modules\Core\Http\Services\Image;

use App\Http\Services\PsService;
use Illuminate\Http\File;
use Illuminate\Support\Facades\File as FacadesFile;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Item;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\ItemService;

class ThumbnailGeneratorService extends PsService
{
    protected $successFlag, $dangerFlag, $itemService, $imageService, $originalPath;

    public function __construct(ItemService $itemService, ImageService $imageService)
    {
        $this->itemService = $itemService;
        $this->imageService = $imageService;
        $this->dangerFlag = Constants::danger;
        $this->successFlag = Constants::success;

        $this->originalPath = Constants::originPath;

        $this->imageImgTypeCol = CoreImage::imgType;
    }

    public function thumbnail()
    {
        try {
            $images = CoreImage::where($this->imageImgTypeCol, '!=', 'fav-icon')->get();
            foreach ($images as $image) {
                $this->createThumbnail($image->img_path);
            }
            $status = [
                'msg' => 'The thumbnails has been generated successfully.',
                'flag' => $this->successFlag,
            ];
            return $status;

        } catch (\Throwable$e) {
            $msg = $e->getMessage();
            $status = [
                'msg' => $msg,
                'flag' => $this->dangerFlag,
            ];
            return $status;
        }
    }

    public function createThumbnail($img_path){
        $this->imageService->createThumbnail1x($this->originalPath . $img_path, $img_path);
        $this->imageService->createThumbnail2x($this->originalPath . $img_path, $img_path);
        $this->imageService->createThumbnail3x($this->originalPath . $img_path, $img_path);
    }

    public function imageListIndex()
    {
        $images = CoreImage::where($this->imageImgTypeCol, '!=', 'fav-icon')->get();
        $dataArr = [
            'images' => $images,
        ];
        return $dataArr;
    }

    public function imageListUpdate($id)
    {
        try {
            $image = CoreImage::find($id);
            $this->createThumbnail($image->img_path);

            $status = [
                'msg' => __('core__be_generate_thumbnail'),
                'flag' => $this->successFlag,
            ];
            return $status;

        } catch (\Throwable$e) {
            $msg = $e->getMessage();
            $status = [
                'msg' => $msg,
                'flag' => $this->dangerFlag,
            ];
            return $status;
        }
    }
}
