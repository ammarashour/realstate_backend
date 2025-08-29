<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Vendor;

use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Vendor\VendorBranchServiceInterface;
use App\Http\Controllers\PsApiController;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Vendor\VendorServiceInterface;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorApiResource;
use Modules\Core\Entities\Vendor\Vendor;

class VendorApiController extends PsApiController
{
    public function __construct(protected VendorServiceInterface $vendorService,
        protected VendorBranchServiceInterface $vendorBranchService,
        protected MobileSettingServiceInterface $mobileSettingService)
    {
        parent::__construct();
    }

    public function getVendors(Request $request)
    {
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);
        $loginUserId = $request->query('login_user_id');

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);

        $vendors = $this->vendorService->getAll(
            ownerId: $loginUserId,
            status: $this->getVendorStatus($request),
            relation: null,
            pagPerPage: null,
            conds: $conds,
            limit: $limit,
            offset: $offset,
            ids: null
        );

        return VendorApiResource::collection($vendors);
    }

    public function getVendorById(Request $request)
    {
        $vendor = $this->vendorService->get($request->id);

        return new VendorApiResource($vendor);
    }

    public function getVendorBranches(Request $request)
    {

        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);

        $vendorBranches = $this->vendorBranchService->getAll($limit, $offset, 1, null, $conds);

        return $vendorBranches;

    }

    public function search(Request $request)
    {
        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);
        $vendors = $this->vendorService->getAll(null, Constants::vendorAcceptStatus, null, null, $conds, $limit, $offset);

        $data = VendorApiResource::collection($vendors);

        return $this->handleNoDataResponse($request->offset, $data);

    }

    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->offset;
        $limit = $request->limit ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    private function getDefaultLimit()
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;

        return $defaultLimit ?: 9;
    }

    private function getFilterConditions($request)
    {
        return [
            'searchterm' => $request->searchterm,
            'order_by' => $request->order_by,
            'order_type' => $request->order_type,
        ];
    }

    private function getVendorStatus(Request $request)
    {
        return $request->status ?? null;
    }
}
