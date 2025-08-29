<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\Color;
use Modules\Core\Http\Facades\MobileSettingFacade;
use App\Http\Contracts\Configuration\ColorServiceInterface;

class ColorService extends PsService implements ColorServiceInterface
{

    public function __construct()
    {}

    public function save($colorData)
    {
        DB::beginTransaction();
        try {

            $this->saveColor($colorData);

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function update($id, $colorData)
    {
        DB::beginTransaction();
        try {

            $this->updateColor($id, $colorData);

            $this->updateMobileSettingCode();

            DB::commit();

        } catch (\Throwable $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function delete($id)
    {
        try {

            $name = $this->deleteColor($id);

            return [
                'msg' => __('core__be_delete_success', ['attribute' => $name]),
                'flag' => Constants::success,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function get($id = null, $key = null)
    {
        $language = Color::when($id, function ($query, $id) {
            $query->where(Color::id, $id);
        })
            ->when($key, function ($query, $key) {
                $query->where(Color::key, $key);
            })
            ->first();
        return $language;
    }

    public function getAll($limit = null, $offset = null, $conds = null, $noPagination = null,  $pagPerPage = null)
    {
        $colors = Color::when($limit, function ($query, $limit) {
            $query->limit($limit);
        })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            });
        if ($pagPerPage) {
            $colors = $colors->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $colors = $colors->get();
        } else {
            $colors = $colors->get();
        }
        return $colors;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function saveColor($colorData)
    {
        $color = new Color();
        $color->fill($colorData);
        $color->added_user_id = Auth::user()->id;
        $color->save();
    }

    private function updateColor($id, $colorData)
    {
        $color = $this->get($id);
        $color->updated_user_id = Auth::user()->id;
        $color->update($colorData);
    }

    private function updateMobileSettingCode()
    {
         $mobile_setting = MobileSettingFacade::get();
         $mobile_setting->color_change_code = Carbon::now()->getPreciseTimestamp(3);
         $mobile_setting->update();
    }

    private function deleteColor($id)
    {
        $color = $this->get($id);
        $name = $color->title;
        $color->delete();

        return $name;
    }

    private function searching($query, $conds)
    {
        // search term
        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }

        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where(Color::tableName . '.' . Color::title, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds[Color::key]) && $conds[Color::key]) {
            $query->where(Color::tableName . '.' . Color::key, $conds[Color::key]);
        }

        if (isset($conds[Color::value]) && $conds[Color::value]) {
            $query->where(Color::tableName . '.' . Color::value, $conds[Color::value]);
        }

        if (isset($conds[Color::title]) && $conds[Color::title]) {
            $query->where(Color::tableName . '.' . Color::title, $conds[Color::title]);
        }

        // if(isset($conds[$this->colorIsLightColorCol])){
        //     $query->where(Color::tableName .'.'.$this->colorIsLightColorCol, $conds[$this->colorIsLightColorCol]);
        // }

        if (isset($conds[Color::feColor])) {
            $query->where(Color::tableName . '.' . Color::feColor, $conds[Color::feColor]);
        }

        if (isset($conds[Color::mbColor])) {
            $query->where(Color::tableName . '.' . Color::mbColor, $conds[Color::mbColor]);
        }

        if (isset($conds['selected_date']) && $conds['selected_date']) {
            $date_filter = $conds['selected_date'];
            $new_date = date('Y-m-d', strtotime($date_filter));

            $query->whereDate(Color::tableName . '.added_date', '=', $new_date);
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->where(Color::tableName . '.' . Color::addedDate, $date_filter);
        }

        if (isset($conds['date_range']) && $conds['date_range']) {
            $date_filter = $conds['date_range'];
            if ($date_filter[1] == '') {
                $date_filter[1] = Carbon::now();
            }
            $query->whereBetween(Color::tableName . '.' . Color::addedDate, $date_filter);
        }
        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {

            if ($conds['order_by'] == 'id') {
                $query->orderBy(Color::tableName . '.id', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

}
