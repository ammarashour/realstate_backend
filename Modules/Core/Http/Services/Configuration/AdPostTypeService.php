<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Http\Contracts\Configuration\AdPostTypeServiceInterface;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\AdPostType;
use Modules\Core\Entities\CoreKeyCounter;

class AdPostTypeService extends PsService implements AdPostTypeServiceInterface
{
    public function get($id = null, $conds = null)
    {
        return AdPostType::when($id, function($query, $id){
            $query->where(AdPostType::id, $id);
        })
        ->when($conds, function($query, $conds){
            $query->where($conds);
        })
        ->first();
    }

    public function getAll($id = null, $conds = null)
    {
        return AdPostType::when($id, function($query, $id){
            $query->where(AdPostType::id, $id);
        })
        ->when($conds, function($query, $conds){
            $query->where($conds);
        })
        ->get();
    }

    /////////////////////////////////////////////////////
    //// Private Functions
    /////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------


    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------


    //------------------------------------------------------------------
    // Others
    //------------------------------------------------------------------
}
