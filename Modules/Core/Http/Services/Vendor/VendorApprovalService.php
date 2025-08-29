<?php

namespace Modules\Core\Http\Services\Vendor;

use App\Config\Cache\VendorCache;
use App\Http\Services\PsService;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\VendorPaymentService;
use Modules\StoreFront\VendorPanel\Http\Services\VendorOrderStatusService;
use Modules\StoreFront\VendorPanel\Http\Services\VendorPaymentStatusService;
use App\Http\Contracts\Vendor\VendorServiceInterface;
use App\Http\Contracts\Vendor\VendorApprovalServiceInterface;
use App\Http\Contracts\Vendor\VendorInfoServiceInterface;
use App\Http\Contracts\Vendor\VendorUserPermissionServiceInterface;
use Modules\Core\Http\Facades\PsCache;

class VendorApprovalService extends PsService implements VendorApprovalServiceInterface
{
    public function __construct(
        protected VendorServiceInterface $vendorService,
        protected VendorPaymentStatusService $vendorPaymentStatusService,
        protected VendorPaymentService $vendorPaymentService,
        protected VendorOrderStatusService $vendorOrderStatusService,
        protected VendorInfoServiceInterface $vendorInfoService,
        protected VendorUserPermissionServiceInterface $vendorUserPermissionService
    ) {}

    public function setStatus($id, $vendorApprovalData)
    {
        try {
            $vendor = $this->vendorService->get($id);

            if ($vendorApprovalData == 'accept') {
                $this->acceptVendor($vendor);
                PsCache::clear(VendorCache::BASE);
                return $this->createResponse(__('core__be_item_accepted'), Constants::success);
            } elseif ($vendorApprovalData == 'reject') {
                $this->rejectVendor($vendor);
                PsCache::clear(VendorCache::BASE);
                return $this->createResponse(__('core__be_item_rejected'), Constants::danger);
            } else {
                $this->disableVendor($vendor);
                PsCache::clear(VendorCache::BASE);
                return $this->createResponse(__('core__be_item_disabled'), Constants::danger);
            }

        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getLine(), $e->getFile());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            //delete in vendor table
            $vendor = $this->vendorService->get($id);

            //delete in vendor_infos table
            $productRelations = $this->vendorInfoService->getAll('', $id, null, 1);

            $title = $vendor->name;
            $this->vendorService->delete($id);

            $this->vendorInfoService->deleteAll($productRelations);

            PsCache::clear(VendorCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $title]),
                'flag' => Constants::danger,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }


    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function acceptVendor($vendor)
    {
        $vendor->status = Constants::vendorAcceptStatus;
        $this->grantVendorPermissions($vendor);
        $this->processVendorPaymentsAndStatuses($vendor->id);
        $vendor->update();
    }

    private function rejectVendor($vendor)
    {
        $vendor->status = Constants::vendorRejectStatus;
        $vendor->update();
    }

    private function disableVendor($vendor)
    {
        $vendor->status = Constants::vendorPendingStatus;
        $vendor->update();
    }

    private function grantVendorPermissions($vendor)
    {
        $vendorUserPermission = $this->vendorUserPermissionService->get($vendor->owner_user_id);

        $vendorRoleObj = json_decode($vendorUserPermission->vendor_and_role ?? '{}');

        $vendorRoleObj->{$vendor->id} = isset($vendorRoleObj->{$vendor->id})
                ? $this->addOwnerRole($vendorRoleObj->{$vendor->id})
                : Constants::vendorOwnerRole;

        if(!empty($vendorUserPermission)){

            $vendorUserPermission->vendor_and_role = json_encode($vendorRoleObj);
            $vendorUserPermission->update();

        } else {
            $vendorUserPermissionData['user_id'] = $vendor->owner_user_id;
            $vendorUserPermissionData['vendor_and_role'] = json_encode($vendorRoleObj);
            $this->vendorUserPermissionService->save($vendorUserPermissionData);
        }

    }

    private function addOwnerRole($currentRole)
    {
        return str_contains($currentRole, Constants::vendorOwnerRole)
            ? $currentRole
            : $currentRole . ',' . Constants::vendorOwnerRole;
    }

    private function processVendorPaymentsAndStatuses($vendorId)
    {
        $data = new \stdClass();
        $data->vendor_id = $vendorId;
        $this->vendorPaymentService->setVendorPayments($data);
        $this->vendorPaymentStatusService->setPaymentStatuses($vendorId);
        $this->vendorOrderStatusService->setOrderStatuses($vendorId);
    }

    private function createResponse($msg, $flag)
    {
        return [
            'msg' => $msg,
            'flag' => $flag,
        ];
    }
}
