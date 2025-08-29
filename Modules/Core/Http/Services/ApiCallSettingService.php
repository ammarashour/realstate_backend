<?php

namespace Modules\Core\Http\Services;

use Carbon\Carbon;
use App\Http\Services\PsService;
use Modules\Core\Entities\ApiCallSetting;

class ApiCallSettingService extends PsService
{
    public function __construct() {
        //
    }

    public function index() {
        $apiCallSetting = ApiCallSetting::first();
        $apiCallSetting->added_date = $this->dateDiff() ? Carbon::now() : $apiCallSetting->added_date;
        $apiCallSetting->updated_date = Carbon::now();
        $apiCallSetting->update();
    }

    public function update($date) {
        $apiCallSetting = ApiCallSetting::first();
        $apiCallSetting->added_date = $date;
        $apiCallSetting->update();
    }

    public function dateDiff() {
        $apiCallSetting = ApiCallSetting::first();
        $duration = date_diff(Carbon::now(), $apiCallSetting->added_date);

        if($duration->d == 0){
            if($duration->h >= $apiCallSetting->delay){
                return $status = true;
            }else{
                return $status = false;
            }
        }else{
            return $status = true;
        }
    }
}
