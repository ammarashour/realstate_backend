<?php

namespace Modules\Core\Http\Services\Configuration;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreKeyCounter;
use App\Http\Contracts\Configuration\CoreKeyCounterServiceInterface;

class CoreKeyCounterService extends PsService implements CoreKeyCounterServiceInterface
{
    public function get($id = null, $conds = null)
    {
        return CoreKeyCounter::when($id, function($query, $id){
            $query->where(CoreKeyCounter::id, $id);
        })
        ->when($conds, function($query, $conds){
            $query->where($conds);
        })
        ->first();
    }

    public function generate($code)
    {
        DB::beginTransaction();
        try{
            $conds['code'] = $code;
            $coreKeyCounter = $this->get(null, $conds);

            $counter = $coreKeyCounter->counter + 1;
            $count = $this->getCoreKeyCount($counter);

            // update core key counter
            $coreKeyData['counter'] = $counter;
            $this->updateCoreKeyCounter($coreKeyCounter->id, $coreKeyData);

            DB::commit();

            return $code.$count;
        }catch(\Throwable $e){
            throw $e;
        }
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


    private function updateCoreKeyCounter($id, $coreKeyData)
    {
        $conds['id'] = $id;
        $coreKeyCounter = $this->get(null, $conds);
        $coreKeyCounter->updated_user_id = Auth::user()->id;
        $coreKeyCounter->update($coreKeyData);

        return $coreKeyCounter;
    }

    //------------------------------------------------------------------
    // Others
    //------------------------------------------------------------------

    private function getCoreKeyCount($counter)
    {
        $middleCoreKeyLength = strlen(Constants::middleCoreKeyCode);
        $counterLength = strlen((string) $counter);

        if ($middleCoreKeyLength <= $counterLength) {
            return $counter;
        } elseif ($middleCoreKeyLength > $counterLength) {
            return substr(Constants::middleCoreKeyCode, 0, ($middleCoreKeyLength - $counterLength) + 1).$counter;
        }
    }
}
