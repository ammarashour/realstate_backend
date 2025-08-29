<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Image;

use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Image\ThumbnailGeneratorService;

class ThumbnailGeneratorController extends Controller
{
    const parentPath = "image_generation_for_thumbnail/thumbnail_generator/";
    const indexPath = self::parentPath . "Index";
    const indexRoute = "thumbnail_generator.index";

    const imageListParentPath = "image_generation_for_thumbnail/image_list/";
    const imageListIndexPath = self::imageListParentPath . "Index";
    const imageListIndexRoute = "image_lists.index";

    protected $thumbnailGeneratorService, $dangerFlag;

    public function __construct(ThumbnailGeneratorService $thumbnailGeneratorService)
    {
        $this->thumbnailGeneratorService = $thumbnailGeneratorService;
        $this->dangerFlag = Constants::danger;
    }

    public function index()
    {
        return renderView(self::indexPath);
    }

    public function thumbnail()
    {
        $dataArr = $this->thumbnailGeneratorService->thumbnail();
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);

    }

    public function imageListIndex()
    {
        $dataArr = $this->thumbnailGeneratorService->imageListIndex();
        return renderView(self::imageListIndexPath, $dataArr);
    }

    public function imageListUpdate($id)
    {
        $dataArr = $this->thumbnailGeneratorService->imageListUpdate($id);
        return redirectView(self::imageListIndexRoute, $dataArr['msg'], $dataArr['flag']);

    }
}
