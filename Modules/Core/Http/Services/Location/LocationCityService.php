<?php

namespace Modules\Core\Http\Services\Location;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Http\Facades\PsCache;
use App\Config\Cache\LocationCityCache;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Imports\LocationCityImport;
use Modules\Core\Entities\Location\LocationCityInfo;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use App\Http\Contracts\Location\LocationCityInfoServiceInterface;

class LocationCityService extends PsService implements LocationCityServiceInterface
{
    public function __construct(
        protected BackendSettingServiceInterface $backendSettingService,
        protected LocationCityInfoServiceInterface $locationCityInfoServiceInterface
    ) {}

    public function save($locationCityData, $relationalData)
    {
        DB::beginTransaction();

        try {
            // save in location city table
            $city = $this->saveCity($locationCityData);

            // save in location_city_info table
            $this->locationCityInfoServiceInterface->save($city->id, $relationalData);

            DB::commit();

            PsCache::clear(LocationCityCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $locationCityData, $relationalData)
    {

        DB::beginTransaction();

        try {
            // update in location_cities table
            $city = $this->updateCity($id, $locationCityData);

            // update in location_city_info table
            $this->locationCityInfoServiceInterface->update($city->id, $relationalData);

            DB::commit();

            PsCache::clear(LocationCityCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            //delete in location_cities table
            $city = $this->deleteCity($id);

            //delete in location_city_infos table
            $cityRelations = $this->locationCityInfoServiceInterface->getAll(null, $id, null, Constants::yes, null);

            $this->locationCityInfoServiceInterface->deleteAll($cityRelations);

            PsCache::clear(LocationCityCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $city]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id, $relation = null)
    {
        $param = [$id, $relation];

        return PsCache::remember(
            [LocationCityCache::BASE],
            LocationCityCache::GET_EXPIRY,
            $param,
            function () use ($id, $relation) {
                return LocationCity::where(LocationCity::id, $id)
                    ->when($relation, function ($q, $relation) {
                        $q->with($relation);
                    })->first();
            }
        );
    }

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null, $condsIn = null)
    {
        $sql = $sql = $this->locationCityInfoServiceInterface->getSqlForCustomField();
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $param = [$relation, $status, $limit, $offset, $conds, $noPagination, $pagPerPage, $condsIn];

        return PsCache::remember(
            [LocationCityCache::BASE],
            LocationCityCache::GET_ALL_EXPIRY,
            $param,
            function () use ($relation, $status, $limit, $offset, $conds, $noPagination, $pagPerPage, $condsIn, $sql, $sort) {
                $cities = LocationCity::select(LocationCity::tableName . '.*')
                    ->when($sql, function ($query, $sql) {
                        $query->selectRaw($sql);
                        $query->leftJoin(LocationCityInfo::tableName, LocationCity::tableName . '.' . LocationCity::id, '=', LocationCityInfo::tableName . '.' . LocationCityInfo::locationCityId);
                        $query->leftJoin(CustomFieldAttribute::tableName, LocationCityInfo::tableName . '.' . LocationCityInfo::value, '=', CustomFieldAttribute::tableName . '.' . CustomFieldAttribute::id);
                    })
                    ->groupBy(LocationCity::tableName . '.' . LocationCity::id)
                    ->when($relation, function ($q, $relation) {
                        $q->with($relation);
                    })
                    ->when($status, function ($q, $status) {
                        $q->where(LocationCity::status, $status);
                    })
                    ->when($limit, function ($query, $limit) {
                        $query->limit($limit);
                    })->when($conds, function ($query, $conds) {
                        $query = $this->searching($query, $conds);
                    })->when($condsIn, function ($query, $condsIn) {
                        if (isset($condsIn['ids'])) {
                            $query->whereIn(LocationCity::tableName . '.' . LocationCity::id, $condsIn['ids']);
                        }

                        if (isset($condsIn['added_user_ids'])) {
                            $query->whereIn(LocationCity::tableName . '.' . LocationCity::addedUserId, $condsIn['added_user_ids']);
                        }
                    })
                    ->when($offset, function ($query, $offset) {
                        $query->offset($offset);
                    })
                    ->when(empty($sort), function ($query, $conds) {
                        $query->orderBy('added_date', 'desc')->orderBy(LocationCity::tableName . '.' . LocationCity::status, 'desc')->orderBy(LocationCity::tableName . '.' . LocationCity::name, 'asc');
                    });
                if ($pagPerPage) {
                    return $cities->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } else {
                    return $cities->get();
                }
            }
        );
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStatusData($status);

            $city = $this->updateCity($id, $status);

            PsCache::clear(LocationCityCache::BASE);

            return $city;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function importCSVFile($locationCityData)
    {
        try {
            $import = new LocationCityImport();
            $import->import($locationCityData);

            PsCache::clear(LocationCityCache::BASE);
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareUpdateStatusData($status)
    {
        return ['status' => $status];
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveCity($locationCityData)
    {
        $city = new LocationCity();
        $city->fill($locationCityData);
        $city->added_user_id = Auth::user()->id;
        $city->save();

        return $city;
    }

    private function updateCity($id, $locationCityData)
    {
        $city = $this->get($id);
        $city->updated_user_id = Auth::user()->id;
        $city->update($locationCityData);

        return $city;
    }

    private function deleteCity($id)
    {
        $city = $this->get($id);
        $name = $city->name;
        $city->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(LocationCity::tableName . '.' . LocationCity::name, 'like', '%' . $search . '%')
                    ->orWhere(LocationCity::tableName . '.' . LocationCity::name, 'like', '%' . $search . '%');
            });
        }
        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where(LocationCity::tableName . '.' . LocationCity::addedUserId, $conds['added_user_id']);
        }

        if (isset($conds['lat']) && $conds['lat'] != '' && isset($conds['lng']) && $conds['lng'] != '') {
            $query->selectRaw('*,
            ( 6371 * acos( cos( radians(' . $conds['lat'] . ') ) *
            cos( radians(lat) ) *
            cos( radians(lng) - radians(' . $conds['lng'] . ') ) +
            sin( radians(' . $conds['lat'] . ') ) *
            sin( radians(lat) ) ) )
            AS distance');

            if (isset($conds['miles'])) {
                if ($conds['miles'] == '') {
                    $conds['miles'] = 0;
                }
                $query->having('distance', '<', $conds['miles']);
            }
        }

        if (isset($conds['city_relation']) && ! empty($conds['city_relation'])) {
            $customizeUis = CustomField::where(CustomField::moduleName, 'loc')->latest()->get();
            foreach ($conds['city_relation'] as $key => $value) {

                if (! empty($value['value'])) {
                    foreach ($customizeUis as $CustomFieldAttribute) {
                        if ($value['core_keys_id'] == $CustomFieldAttribute->core_keys_id) {
                            if ($CustomFieldAttribute->ui_type_id == Constants::dropDownUi || $CustomFieldAttribute->ui_type_id == Constants::radioUi || $CustomFieldAttribute->ui_type_id == Constants::multiSelectUi) {
                                $detail = CustomFieldAttribute::find($value['value']);
                                if ($detail) {
                                    $query->having($value['core_keys_id'] . '@@name', $detail->name);
                                }
                            } else {
                                $detail = CustomFieldAttribute::find($value['value']);
                                if ($detail) {
                                    $query->having($value['core_keys_id'], $detail->name);
                                }
                            }
                        }
                    }
                }
            }
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(LocationCity::tableName . '.id', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------

}
