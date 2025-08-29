<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Category\CategoryService;
use Modules\Core\Http\Services\Category\SubcategoryService;
use Modules\Core\Http\Services\Item\PaidItemService;

class OfflinePaidItemController extends PsController
{
    const parentPath = "item_promotion/offline_paid_item/";
    const indexPath = self::parentPath . "Index";
    const editPath = self::parentPath . "Edit";
    const indexRoute = "offline_paid_item.index";
    const editRoute = "offline_paid_item.edit";

    protected $paidItemService, $successFlag, $dangerFlag, $offlinePaymentMethod, $categoryService, $subcategoryService, $publish;

    public function __construct(PaidItemService $paidItemService, CategoryService $categoryService, SubcategoryService $subcategoryService)
    {
        parent::__construct();

        $this->paidItemService = $paidItemService;
        $this->categoryService = $categoryService;
        $this->subcategoryService = $subcategoryService;

        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->offlinePaymentMethod = Constants::offlinePaymentMethod;
        $this->publish = Constants::publish;
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::offlinePaidItemModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->paidItemService->offlinePaidItemIndex($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::offlinePaidItemModule, ps_constant::updatePermission, Auth::id());

        $dataArr = $this->paidItemService->offlinePaidItemEdit($id);
        return renderView(self::editPath, $dataArr);
    }

    public function store(Request $request)
    {
        $paid_item = $this->paidItemService->store($request);

        // if have error
        if ($paid_item) {
            $msg = $paid_item;
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $paid_item = $this->paidItemService->update($id, $request);

        // if have error
        if ($paid_item) {
            $msg = $paid_item;
            return redirectView(self::editRoute, $msg, $this->dangerFlag, $id);
        }

        return redirect()->back();
    }

    public function destroy($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::offlinePaidItemModule, ps_constant::deletePermission, Auth::id());

        $dataArr = $this->paidItemService->destroy($id);
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }

}
