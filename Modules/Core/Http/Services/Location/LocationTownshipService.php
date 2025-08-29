<?php

namespace Modules\Core\Http\Services\Location;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Facades\PsCache;
use App\Config\Cache\LocationTownshipCache;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Imports\LocationTownshipImport;
use Modules\Core\Entities\Location\LocationTownship;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use App\Http\Contracts\Location\LocationTownshipServiceInterface;

class LocationTownshipService extends PsService implements LocationTownshipServiceInterface
{
    public function __construct(
        protected LocationCityServiceInterface $locationCityService) {}

    public function save($townshipData)
    {
        DB::beginTransaction();

        try {
            // save in location city table
            $this->saveTownship($townshipData);

            DB::commit();

            PsCache::clear(LocationTownshipCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $townshipData)
    {
        DB::beginTransaction();

        try {
            // update in location_townships table
            $this->updateTownship($id, $townshipData);

            DB::commit();

            PsCache::clear(LocationTownshipCache::BASE);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function get($id = null, $status = null)
    {
        $param = [$id, $status];

        return PsCache::remember([LocationTownshipCache::BASE], LocationTownshipCache::GET_EXPIRY, $param,
            function() use($id, $status) {
                return LocationTownship::when($id, function ($q, $id) {
                        $q->where(LocationTownship::id, $id);
                    })
                    ->when($status, function ($q, $status) {
                        $q->where(LocationTownship::status, $status);
                    })
                    ->first();
            });
    }

    public function getAll($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $param = [$relation, $status, $limit, $offset, $conds, $noPagination, $pagPerPage];

        return PsCache::remember([LocationTownshipCache::BASE], LocationTownshipCache::GET_ALL_EXPIRY, $param,
            function() use($relation, $status, $limit, $offset, $conds, $noPagination, $pagPerPage, $sort) {
                $townships = LocationTownship::select(LocationTownship::tableName.'.*')
                    ->when(isset($conds['order_by']) && $conds['order_by'] == 'location_city_id@@name', function ($q) {
                        $q->join(LocationCity::tableName, LocationCity::tableName.'.'.LocationCity::id, '=', LocationTownship::location_city_id)
                        ->select(LocationCity::tableName.'.'.LocationCity::name.' as location_city_name', LocationTownship::tableName.'.*');
                    })
                    ->when($relation, function ($q, $relation) {
                        $q->with($relation);
                    })
                    ->when($limit, function ($query, $limit) {
                        $query->limit($limit);
                    })
                    ->when($offset, function ($query, $offset) {
                        $query->offset($offset);
                    })
                    ->when($status, function ($q, $status) {
                        $q->where(LocationTownship::status, $status);
                    })
                    ->when($conds, function ($query, $conds) {
                        $query = $this->searching($query, $conds);
                    })
                    ->when(empty($sort), function ($query) {
                        $query->orderBy('added_date', 'desc')
                            ->orderBy(LocationTownship::tableName.'.'.LocationTownship::status, 'desc')
                            ->orderBy(LocationTownship::tableName.'.'.LocationTownship::name, 'asc');
                    });

                if ($pagPerPage) {
                    $townships = $townships->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } else {
                    $townships = $townships->get();
                }

                return $townships;
            });
    }


    public function delete($id)
    {
        try {
            //delete in location_cities table
            $city = $this->deleteTownship($id);

            PsCache::clear(LocationTownshipCache::BASE);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $city]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function setStatus($id, $status)
    {
        try {
            $status = $this->prepareUpdateStatusData($status);

            $township = $this->updateTownship($id, $status);

            PsCache::clear(LocationTownshipCache::BASE);

            return $township;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function importCSVFile($townshipData)
    {
        try {
            $import = new LocationTownshipImport();
            $import->import($townshipData);

            PsCache::clear(LocationTownshipCache::BASE);
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
    //------------------------------------------------------------------

    private function saveTownship($locationTownshipData)
    {
        $township = new LocationTownship();
        $township->fill($locationTownshipData);
        $township->added_user_id = Auth::user()->id;
        $township->save();

        return $township;
    }

    private function updateTownship($id, $locationTownshipData)
    {
        $township = $this->get($id);
        $township->updated_user_id = Auth::user()->id;
        $township->update($locationTownshipData);

        return $township;
    }

    private function deleteTownship($id)
    {
        $township = $this->get($id);
        $name = $township->name;
        $township->delete();

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
                $query->where(LocationTownship::tableName.'.'.LocationTownship::name, 'like', '%'.$search.'%');
            });
        }

        if (isset($conds['location_city_id']) && $conds['location_city_id']) {
            $location_city_filter = $conds['location_city_id'];
            $query->where(LocationTownship::location_city_id, $location_city_filter);
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(LocationCity::tableName.'.'.LocationCity::id, $conds['order_type']);
            } elseif ($conds['order_by'] == 'location_city_id@@name') {

                $query->orderBy('location_city_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }

        }

        return $query;
    }
}
