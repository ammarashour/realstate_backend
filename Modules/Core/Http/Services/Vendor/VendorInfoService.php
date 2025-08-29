<?php

namespace Modules\Core\Http\Services\Vendor;

use App\Http\Contracts\Core\PsInfoServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Vendor\VendorInfoServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Vendor\VendorInfo;
use Modules\Core\Http\Services\UserAccessApiTokenService;

class VendorInfoService extends PsService implements VendorInfoServiceInterface
{
    public function __construct(protected UserAccessApiTokenService $userAccessApiTokenService,
        protected PsInfoServiceInterface $psInfoServiceInterface,
        protected CustomFieldServiceInterface $customFieldServiceInterface) {}

    public function save($parentId, $customFieldValues)
    {
        $this->psInfoServiceInterface->save(Constants::vendor, $customFieldValues, $parentId, VendorInfo::class, 'vendor_id');
    }

    public function update($parentId, $customFieldValues)
    {
        // $coreKeysIds = array_keys($customFieldValues);
        // $getOldInfoValues = $this->getAll($coreKeysIds, $parentId, null, Constants::yes);
        $this->psInfoServiceInterface->update(Constants::vendor, $customFieldValues, $parentId, VendorInfo::class, 'location_city_id');
        // $this->deleteAll($getOldInfoValues);
    }

    public function deleteAll($customFieldValues)
    {
        $this->psInfoServiceInterface->deleteAll($customFieldValues);
    }

    public function get($id = null, $relation = null)
    {
        return VendorInfo::when($id, function ($q, $id) {
            $q->where(VendorInfo::id, $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->first();
    }

    public function getAll($coreKeysIds = null, $vendorId = null, $relation = null, $noPagination = null, $pagPerPage = null)
    {
        $vendorInfos = VendorInfo::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($coreKeysIds, function ($q, $coreKeysIds) {
                $q->whereIn(VendorInfo::coreKeyId, $coreKeysIds);
            })
            ->when($vendorId, function ($q, $vendorId) {
                $q->where(VendorInfo::vendorId, $vendorId);
            });
        if ($pagPerPage) {
            return $vendorInfos->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $vendorInfos->get();
        }
    }
}
