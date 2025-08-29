<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Item;

use App\Config\ps_constant;
use App\Http\Controllers\PsController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Services\ItemService as OldItemService;
use Modules\Core\Http\Services\Item\ItemService;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;

class PendingController extends PsController
{
    const parentPath = "pending_item/";
    const indexPath = self::parentPath."Index";
    const editPath = self::parentPath."Edit";
    const indexRoute = "pending_item.index";
    const editRoute = "pending_item.edit";

    protected $disableItem, $pendingItem, $oldItemService, $dangerFlag, $approvalNotiFlag, $publishItem, $rejectItem, $disabledItem, $itemService;

    public function __construct(OldItemService $olItemService, ItemService $itemService)
    {
        parent::__construct();

        $this->oldItemService = $olItemService;
        $this->itemService = $itemService;
        $this->dangerFlag = Constants::danger;
        $this->pendingItem = Constants::pendingItem;
        $this->rejectItem = Constants::rejectItem;
        $this->disableItem = Constants::disableItem;
        $this->publishItem = Constants::publishItem;
        $this->pendingItem = Constants::pendingItem;
        $this->approvalNotiFlag = Constants::approvalNotiFlag;

    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::pendingModule, ps_constant::readPermission, Auth::id());

        $dataArr = $this->oldItemService->disableOrPendingOrRejectIndex($this->pendingItem, $request);

        return renderView(self::indexPath, $dataArr);
    }

    public function edit($id)
    {
        $relation = ['category', 'subcategory', 'city', 'township', 'currency', 'owner','itemRelation', 'cover', 'video', 'icon'];
        $dataArr = $this->oldItemService->disableOrPendingOrRejectEdit($relation, $id);

        return renderView(self::editPath, $dataArr);
    }

    public function destroy($id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::pendingModule, ps_constant::deletePermission, Auth::id());

        $dataArr = $this->oldItemService->disableOrPendingOrRejectDestroy($id);
        return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
    }

    public function statusChange(Request $request, $id)
    {
        // check permission
        $this->handlePermissionWithoutModel(Constants::pendingModule, ps_constant::updatePermission, Auth::id());

        $item = $this->oldItemService->disableOrPendingOrRejectStatusChange($id,$request);

        // if have error
        if (isset($item['error'])){
            $msg = $item['error'];
            return redirectView(self::indexRoute, $msg, $this->dangerFlag);
        }

        return redirectView(self::indexRoute, $item['msg'], $item['flag']);
    }



}
