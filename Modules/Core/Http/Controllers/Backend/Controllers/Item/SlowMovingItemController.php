<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\ItemService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Item\SlowMovingItemService;

class SlowMovingItemController extends PsController
{
    const parentPath = "slow_moving_items/slow_moving_item/";
    const indexPath = self::parentPath . "Index";
    const createPath = self::parentPath . "Create";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "slow_moving_item.index";
    const createRoute = "slow_moving_item.create";
    const editRoute = "slow_moving_item.edit";

    protected $slowMovingItemService, $successFlag, $dangerFlag, $csvFile, $itemService, $code, $coreFieldFilterSettingService;

    public function __construct(SlowMovingItemService $slowMovingItemService, ItemService $itemService, CoreFieldFilterSettingService $coreFieldFilterSettingService)
    {
        parent::__construct();

        $this->slowMovingItemService = $slowMovingItemService;
        $this->itemService = $itemService;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->csvFile = Constants::csvFile;
        $this->coreFieldFilterSettingService = $coreFieldFilterSettingService;
        $this->code = Constants::item;
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::slowMovingItemModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->slowMovingItemService->index($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::slowMovingItemModule, ps_constant::updatePermission, Auth::id());

        $dataArr = $this->slowMovingItemService->edit($id);
        return renderView(self::editPath, $dataArr);
    }

    public function update(Request $request)
    {

        //prepare for validation
        $errors = validateForCustomField($this->code, $request->product_relation);

        $coreFieldsIds = [];
        $errors = [];

        $cond['module_name'] = $this->code;
        $cond['mandatory'] = 1;
        $cond['is_core_field'] = 1;

        $coreFields = $this->coreFieldFilterSettingService->getCoreFieldsWithConds($cond);

        // $coreFields = CoreField::where('module_name',)->where('mandatory',1)->where('is_core_field',1)->get();
        foreach ($coreFields as $key => $value) {
            if (str_contains($value->field_name, "@@")) {
                $originFieldName = strstr($value->field_name, "@@", true);
            } else {
                $originFieldName = $value->field_name;
            }
            array_push($coreFieldsIds, $originFieldName);
        }

        $validationArr = [];

        if (in_array('title', $coreFieldsIds)) {
            $validationArr['title'] = 'required|min:3|unique:psx_items,title,' . $request->id;
        }
        if (in_array('description', $coreFieldsIds)) {
            $validationArr['description'] = 'required|min:10';
        }
        if (in_array('category_id', $coreFieldsIds)) {
            $validationArr['category_id'] = 'required|exists:psx_categories,id';
        }
        if (in_array('subcategory_id', $coreFieldsIds)) {
            $validationArr['subcategory_id'] = 'required|exists:psx_subcategories,id';
        }
        if (in_array('location_city_id', $coreFieldsIds)) {
            $validationArr['location_city_id'] = 'required|exists:psx_location_cities,id';
        }
        if (in_array('location_township_id', $coreFieldsIds)) {
            $validationArr['location_township_id'] = 'required|exists:psx_location_townships,id';
        }
        if (in_array('currency_id', $coreFieldsIds)) {
            $validationArr['currency_id'] = 'required|exists:psx_currencies,id';
        }
        // if(in_array('price',$coreFieldsIds)){
        //     $validationArr['price'] = 'required';
        // }
        if (in_array('original_price', $coreFieldsIds)) {
            $validationArr['original_price'] = 'required';
        }
        if (in_array('percent', $coreFieldsIds)) {
            $validationArr['percent'] = 'required';
        }
        if (in_array('lat', $coreFieldsIds)) {
            $validationArr['lat'] = 'required';
        }
        if (in_array('lng', $coreFieldsIds)) {
            $validationArr['lng'] = 'required';
        }
        if (in_array('shop_id', $coreFieldsIds)) {
            $validationArr['shop_id'] = 'required';
        }
        if (in_array('search_tag', $coreFieldsIds)) {
            $validationArr['search_tag'] = 'required';
        }
        if (in_array('ordering', $coreFieldsIds)) {
            $validationArr['ordering'] = 'required';
        }
        if (in_array('is_discount', $coreFieldsIds)) {
            $validationArr['is_discount'] = 'required';
        }
        if (in_array('phone', $coreFieldsIds)) {
            $validationArr['phone'] = 'required';
        }
        if (in_array('phone', $coreFieldsIds)) {
            $validationArr['phone'] = 'required';
        }

        $validationArr['cover'] = 'nullable|sometimes|image';
        $validationArr['video_icon'] = 'nullable|sometimes|image';
        $validationArr['video'] = 'nullable|sometimes|image';


        //prepare validation for core filed
        $validator = Validator::make(
            $request->all(),
            $validationArr
            //  [
            // 'cover' => 'nullable|file|mimes:jpg,png,jpeg',
            // 'video_icon' => 'nullable|file|mimes:jpg,png,jpeg',
            // 'video' => 'nullable|file|mimes:mp4|max:1500'
            // ]
        );

        if ($validator->fails()) {
            // dd("here");
            return redirect()->route('slow_moving_item.edit', $request->id)->with('product_relation_errors', $errors)
                ->withErrors($validator)
                ->withInput();
        } else {

            if (collect($errors)->isNotEmpty()) {
                return redirect()->route('slow_moving_item.edit', $request->id)->with('product_relation_errors', $errors);
            }
        }

        // dd("here");
        $product = $this->itemService->update($request);

        if (isset($product['error'])) {
            // go back to index
            $msg = $product['error'];
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::slowMovingItemModule, ps_constant::deletePermission, Auth::id());

        $dataArr = $this->slowMovingItemService->destroy($id);
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }
}
