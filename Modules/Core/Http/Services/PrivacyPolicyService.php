<?php

namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Information\CorePrivacyPolicy;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Entities\CoreImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Exception;
use Modules\Core\Transformers\Backend\Model\Information\PrivacyPolicyWithKeyResource;
/**
 * @deprecated
 */
class PrivacyPolicyService extends PsService
{
    protected $upload_path = 'public/uploads/';
    protected $thumb1x_path = 'public/thumbnail/';
    protected $thumb2x_path = 'public/thumbnail2x/';
    protected $thumb3x_path = 'public/thumbnail3x/';

    protected $storage_upload_path = '/storage/uploads/';
    protected $storage_thumb1x_path = '/storage/thumbnail/';
    protected $storage_thumb2x_path = '/storage/thumbnail2x/';
    protected $storage_thumb3x_path = '/storage/thumbnail3x/';

    protected $privacyPolicyIdCol, $ImageService, $show, $hide, $delete, $unDelete, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $code, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreFieldFilterModuleNameCol, $customUiIsDelCol;

    public function __construct(ImageService $imageService)
    {
        $this->privacyPolicyIdCol = CorePrivacyPolicy::id;
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
        $this->code = Constants::privacyPolicy;
        $this->coverImgType = 'privacy_policy';

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
            $privacy_policy = new CorePrivacyPolicy();
            $privacy_policy->content = $request->content;
            $privacy_policy->privacy_url = $request->privacy_url;
            $privacy_policy->added_user_id = Auth::user()->id;
            $privacy_policy->save();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $privacy_policy;
    }


    public function updateOrCreateImage($request, $fileKey, $imgParentId, $imgType)
    {
        if ($request->file($fileKey)) {
            $conds[$this->coreImageImgParentIdCol] = $imgParentId;
            $conds[$this->coreImageImgTypeCol] = $imgType;
            // $image = $this->getImage($conds);

            $file = $request->file($fileKey);
            $data[$this->coreImageImgParentIdCol] = $imgParentId;
            $data[$this->coreImageImgTypeCol] = $imgType;

            $url = $this->insertImage($file, $data);
            return $url;
        }
    }

    public function insertImage($file, $data, $image = false)
    {
        try {

            $isCreate = true;

            $fileName = newFileName($file);
            $extension = $file->getClientOriginalExtension();

            if ($extension == 'ico') {
                Storage::putFileAs(Constants::uploadPathForDel, $file, $fileName);
                // Storage::putFileAs(Constants::thumb1xPathForDel, $file, $fileName);
                // Storage::putFileAs(Constants::thumb2xPathForDel, $file, $fileName);
                // Storage::putFileAs(Constants::thumb3xPathForDel, $file, $fileName);
                $org_image = getimagesize($file);

                $width = $org_image[0];
                $height = $org_image[1];
            } else {
                // save original image to 'uploads'
                $org_image = $this->uploadOrgImage($file, $fileName);

                // save thumb 1x image to 'thumbnail'
                $width = $org_image->width();
                $height = $org_image->height();
            }


            $image = new CoreImage();


            $image->img_parent_id = $data['img_parent_id'];
            $image->img_type = $data['img_type'];
            $image->img_path = $fileName;
            $image->img_width = $width;
            $image->img_height = $height;
            $image->ordering = isset($data['ordering']) ? $data['ordering'] : 1;


            if (array_key_exists("img_desc", $data)) {
                $image->img_desc = $data['img_desc'];
            }


            $image->added_user_id = Auth::user()->id;
            $image->save();
            $url = asset('storage/uploads') . '/' . $image->img_path;
            return $url;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }



    public function getImage($conds)
    {
        $image = $this->imageService->getImage($conds);
        return $image;
    }

    public function uploadOrgImage($file, $fileName)
    {
        try {
            $org_image = Image::make($file);

            if (!File::isDirectory(public_path() . $this->storage_upload_path)) {
                File::makeDirectory(public_path() . $this->storage_upload_path, 0777, true, true);
            }
            $org_image->save(public_path() . $this->storage_upload_path . $fileName, 50);
            return $org_image;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function update($id, $request)
    {
        $html = $request->content;
        preg_match_all('/<img[^>]+\/?>/i', $html, $imageTags);
        $allImageTags = join(',', $imageTags[0]);
        preg_match_all('#([^/\'"=]*?[.](?:gif|jpeg|jpg|png))\b#i', $allImageTags, $imageTags);
        $imgSrcArray = array_pop($imageTags);

        $imgParentId = 0;

        $images = $this->imageService->getImages($imgParentId, $this->coverImgType);


        foreach ($images as $image) {
            if (!in_array($image->img_path, $imgSrcArray)) {
                $image = $this->imageService->destroy($image->id);
            }
        }

        DB::beginTransaction();
        try {
            $privacy_policy = $this->getPrivacyPolicy($id);
            $privacy_policy->content = $request->content;
            $privacy_policy->updated_user_id = Auth::user()->id;
            $privacy_policy->privacy_url = $request->privacy_url;
            $privacy_policy->update();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }




        return $privacy_policy;
    }

    public function getPrivacyPolicy($id = null)
    {
        $privacy_policy = CorePrivacyPolicy::when($id, function ($q, $id) {
            $q->where($this->privacyPolicyIdCol, $id);
        })->first();
        return $privacy_policy;
    }

    public function privacycontent()
    {

        $privacy_policy = new PrivacyPolicyWithKeyResource($this->getPrivacyPolicy());

        $dataArr = [
            'privacy_policy' => $privacy_policy,
        ];
        return $dataArr;
    }

    public function index()
    {
        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, CorePrivacyPolicy::class, "admin.index");

        $privacy_policy = new PrivacyPolicyWithKeyResource($this->getPrivacyPolicy());

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showPrivacyPolicyCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        $dataArr = [
            'checkPermission' => $checkPermission,
            'privacy_policy' => $privacy_policy,
            'showPrivacyPolicyCols' => $showPrivacyPolicyCols,
            'showCoreAndCustomFieldArr' => $columnProps,
            'hideShowFieldForFilterArr' => $columnFilterOptionProps,
        ];
        return $dataArr;
    }

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;
        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
            $hideShowCoreAndCustomFieldObj->action = 'Action';
        }

        return $hideShowCoreAndCustomFieldObj;
    }

    public function takingForColumnAndFilterOption()
    {

        // for table
        $hideShowCoreFieldForColumnArr = [];
        $hideShowCustomFieldForColumnArr = [];

        $showProductCols = [];

        // for eye
        $hideShowFieldForColumnFilterArr = [];

        // for control
        $controlFieldArr = [];
        $controlFieldObj = $this->takingForColumnProps(__('core__be_action'), "action", "Action", false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        $code = $this->code;
        $hiddenForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->hide, $code);
        $shownForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->show, $code);
        $hideShowForCoreAndCustomFields = $shownForCoreAndCustomField->merge($hiddenForCoreAndCustomField);

        foreach ($hideShowForCoreAndCustomFields as $showFields) {
            if ($showFields->coreField !== null) {

                $label = $showFields->coreField->label_name;
                $field = $showFields->coreField->field_name;
                $colName = $showFields->coreField->field_name;
                $type = $showFields->coreField->data_type;
                $isShowSorting = $showFields->coreField->is_show_sorting;
                $ordering = $showFields->coreField->ordering;

                $cols = $colName;
                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->coreField->module_name;
                $keyId = $showFields->coreField->id;

                $coreFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $coreFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $coreFieldObjForColumnFilter);
                array_push($hideShowCoreFieldForColumnArr, $coreFieldObjForColumnsProps);
                array_push($showProductCols, $cols);
            }
            if ($showFields->customizeField !== null) {

                $label = $showFields->customizeField->name;
                $uiHaveAttribute = [$this->dropDownUi, $this->radioUi];
                if (in_array($showFields->customizeField->ui_type_id, $uiHaveAttribute)) {
                    $field = $showFields->customizeField->core_keys_id . "@@name";
                } else {
                    $field = $showFields->customizeField->core_keys_id;
                }
                $type = $showFields->customizeField->data_type;
                $isShowSorting = $showFields->customizeField->is_show_sorting;
                $ordering = $showFields->customizeField->ordering;

                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->customizeField->module_name;
                $keyId = $showFields->customizeField->core_keys_id;

                $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
                array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
            }
        }

        // for columns props
        $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
        $sortedColumnArr = columnOrdering("ordering", $showCoreAndCustomFieldArr);

        // for eye
        $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

        $dataArr = [
            "arrForColumnProps" => $sortedColumnArr,
            "arrForColumnFilterProps" => $hideShowCoreAndCustomFieldArr,
            "showCoreField" => $showProductCols,
        ];
        return $dataArr;
    }

    public function hiddenShownForCoreAndCustomField($isShown, $code)
    {
        $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
            $q->where($this->customUiIsDelCol, $this->unDelete);
        }, 'coreField' => function ($q) {
            $q->where($this->coreFieldFilterModuleNameCol, $this->code);
        }])
            ->where($this->coreFieldFilterModuleNameCol, $code)
            ->where($this->screenDisplayUiIsShowCol, $isShown)
            ->get();
        return $hiddenShownForFields;
    }

    public function takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->id = $id;
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->key = $field;
        $hideShowCoreAndCustomFieldObj->hidden = $hidden;
        $hideShowCoreAndCustomFieldObj->module_name = $moduleName;
        $hideShowCoreAndCustomFieldObj->key_id = $keyId;

        return $hideShowCoreAndCustomFieldObj;
    }
}
