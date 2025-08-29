<?php

namespace Modules\Core\Http\Services\Location;

use App\Http\Contracts\Core\PsInfoServiceInterface;
use App\Http\Contracts\Location\LocationCityInfoServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Services\PsService;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Location\LocationCityInfo;

class LocationCityInfoService extends PsService implements LocationCityInfoServiceInterface
{
    public function __construct(
        protected PsInfoServiceInterface $psInfoServiceInterface,
        protected CustomFieldServiceInterface $customFieldServiceInterface
    ) {}

    public function save($parentId, $customFieldValues)
    {
        $this->psInfoServiceInterface->save(Constants::locationCity, $customFieldValues, $parentId, LocationCityInfo::class, "location_city_id");
    }

    public function update($parentId, $customFieldValues)
    {
        // $coreKeysIds = array_keys($customFieldValues);
        // $getOldInfoValues = $this->getAll($coreKeysIds, $parentId, null, Constants::yes);

        $this->psInfoServiceInterface->update(Constants::locationCity, $customFieldValues, $parentId, LocationCityInfo::class, "location_city_id");
        // $this->deleteAll($getOldInfoValues);
    }

    public function deleteAll($customFieldValues)
    {
        $this->psInfoServiceInterface->deleteAll($customFieldValues);
    }

    public function get($id = null, $relation = null)
    {
        return LocationCityInfo::when($id, function ($q, $id) {
            $q->where(LocationCityInfo::id, $id);
        })
            ->when($relation, function ($q, $relation) {
                $q->with($relation);
            })->first();
    }

    public function getAll($coreKeysIds = null, $locationCityId = null, $relation = null, $noPagination = null, $pagPerPage = null)
    {
        $locationCityInfos = LocationCityInfo::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when($coreKeysIds, function ($q, $coreKeysIds) {
                $q->whereIn(LocationCityInfo::coreKeysId, $coreKeysIds);
            })
            ->when($locationCityId, function ($q, $locationCityId) {
                $q->where(LocationCityInfo::locationCityId, $locationCityId);
            });
        if ($pagPerPage) {
            return $locationCityInfos->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            return $locationCityInfos->get();
        }
    }

    public function getSqlForCustomField()
    {
        $sql = "";
        $customizeUis = $this->customFieldServiceInterface->getAll(moduleName: Constants::locationCity, withNoPag: Constants::yes);

        // $CustomizeUiDetails = CustomFieldAttribute::latest()->get();
        $customizeuideatil_array = [];
        // $customizeuideatil_sql = "";

        foreach ($customizeUis as $CustomFieldAttribute) {
            if ($CustomFieldAttribute->ui_type_id == Constants::dropDownUi || $CustomFieldAttribute->ui_type_id == Constants::radioUi || $CustomFieldAttribute->ui_type_id == Constants::multiSelectUi) {
                $customizeuideatil_array[$CustomFieldAttribute->core_keys_id . '@@name'] = $CustomFieldAttribute->core_keys_id;
            }
        }

        foreach (array_unique($customizeuideatil_array) as $key => $customizeuideatil) {
            $sql .= "max(case when psx_location_city_infos.core_keys_id = '$customizeuideatil' then psx_customize_ui_details.name end) as '$key',";
        }
        foreach ($customizeUis as $key => $customizeUi) {
            if ($key + 1 == count($customizeUis)) {
                $sql .= "max(case when psx_location_city_infos.core_keys_id = '$customizeUi->core_keys_id' then psx_location_city_infos.value end) as '$customizeUi->core_keys_id'";
            } else {
                $sql .= "max(case when psx_location_city_infos.core_keys_id = '$customizeUi->core_keys_id' then psx_location_city_infos.value end) as '$customizeUi->core_keys_id' ,";
            }
        }
        return $sql;
    }
}
