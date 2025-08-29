<?php

namespace Modules\Core\Http\Services;

use Carbon\Carbon;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\PsxLandingPage;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;


class LandingPageService extends PsService
{
    protected $upload_path = 'public/uploads/';
    protected $thumb1x_path = 'public/thumbnail/';
    protected $thumb2x_path = 'public/thumbnail2x/';
    protected $thumb3x_path = 'public/thumbnail3x/';

    protected $storage_upload_path = '/storage/uploads/';
    protected $storage_thumb1x_path = '/storage/thumbnail/';
    protected $storage_thumb2x_path = '/storage/thumbnail2x/';
    protected $storage_thumb3x_path = '/storage/thumbnail3x/';

    protected $landingPageIdCol, $ImageService, $show, $hide, $delete, $unDelete, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $code, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreFieldFilterModuleNameCol, $customUiIsDelCol;

    public function __construct(ImageService $imageService)
    {
        $this->landingPageIdCol = PsxLandingPage::id;
        $this->imageService = $imageService;
        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;
        $this->coreImageImgParentIdCol = CoreImage::imgParentId;
        $this->coreImageImgTypeCol = CoreImage::imgType;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;
        $this->code = Constants::landingPage;
        $this->logoImgType = 'landing-logo';
        $this->coverImgType = 'landing-cover';

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;

        $this->customUiIsDelCol = CustomField::isDelete;
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $landing_page = new PsxLandingPage();
            $landing_page->title = $request->title;
            $landing_page->description = $request->description;
            $landing_page->default_landing_page_color = $request->default_landing_page_color;
            $landing_page->gps_link = $request->gps_link;
            $landing_page->ios_link = $request->ios_link;
            $landing_page->yt_link = $request->yt_link;
            $landing_page->fb_link = $request->fb_link;
            $landing_page->tw_link = $request->tw_link;
            $landing_page->added_user_id = Auth::user()->id;
            $landing_page->save();

            // save category cover photo
            $this->updateOrCreateImage($request, "logo", $landing_page->id, $this->logoImgType);

            // save category icon
            $this->updateOrCreateImage($request, "cover", $landing_page->id, $this->coverImgType);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $landing_page;
    }


    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $landing_page = $this->getLandingPage($id);
            $landing_page->title = $request->title;
            $landing_page->description = $request->description;
            $landing_page->default_landing_page_color = $request->default_landing_page_color;
            $landing_page->gps_link = $request->gps_link;
            $landing_page->ios_link = $request->ios_link;
            $landing_page->yt_link = $request->yt_link;
            $landing_page->fb_link = $request->fb_link;
            $landing_page->tw_link = $request->tw_link;
            $landing_page->update();

            // save category cover photo
            $this->updateOrCreateImage($request, "logo", $landing_page->id, $this->logoImgType);

            // save category icon
            $this->updateOrCreateImage($request, "cover", $landing_page->id, $this->coverImgType);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
        return $landing_page;
    }

    public function getImage($conds)
    {
        $image = $this->imageService->getImage($conds);
        return $image;
    }

    public function updateOrCreateImage($request, $fileKey, $imgParentId, $imgType)
    {

        if ($request->file($fileKey)) {

            $conds[$this->coreImageImgParentIdCol] = $imgParentId;
            $conds[$this->coreImageImgTypeCol] = $imgType;
            $image = $this->getImage($conds);

            $file = $request->file($fileKey);
            $data[$this->coreImageImgParentIdCol] = $imgParentId;
            $data[$this->coreImageImgTypeCol] = $imgType;

            // if image, delete existing file and update
            if (!empty($image)) {
                // delete image from storage folder
                $this->imageService->deleteImage($image->img_path);
                $this->imageService->update(null, null, $file, $data, $image);
            } else {
                $this->imageService->update(null, null, $file, $data);
            }
        }
    }

    public function getLandingPage($id = null)
    {
        $landing_page = PsxLandingPage::with(['landing_logo', 'landing_cover'])
            ->first();
        return $landing_page;
    }

    public function index()
    {
        // check permission
        // $checkPermission = $this->checkPermission($this->viewAnyAbility, PsxLandingPage::class, "admin.index");

        $landing_page = $this->getLandingPage();

        $code = $this->code;
        $coreFieldFilterSettings = $this->getCoreFieldFilteredLists($code);

        $conds = [
            'module_name' => Constants::landingPage,
            'enable' => 1,
            'mandatory' => 1,
            'is_core_field' => 1,
        ];

        $core_headers = CoreField::where($conds)->get();

        $validation = [];

        foreach ($core_headers as $core_header) {
            if ($core_header->field_name == 'landing-cover') {
                array_push($validation, 'cover');
            }
            if ($core_header->field_name == 'landing-icon') {
                array_push($validation, 'logo');
            }
        }



        $dataArr = [
            'validation' => $validation,
            'landing_page' => $landing_page,
            'coreFieldFilterSettings' => $coreFieldFilterSettings

        ];
        return $dataArr;
    }

    public function getCoreFieldFilteredLists($code)
    {
        return CoreField::where($this->coreFieldFilterModuleNameCol, $code)->get();
    }
}
