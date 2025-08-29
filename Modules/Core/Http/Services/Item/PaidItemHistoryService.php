<?php
namespace Modules\Core\Http\Services\Item;

use App\Http\Contracts\Item\PaidItemHistoryServiceInterface;
use stdClass;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Item\CartItem;
use Modules\Core\Entities\Item\PaidItemHistory;

class PaidItemHistoryService extends PsService implements PaidItemHistoryServiceInterface
{

    public function __construct()
    {}

    public function get($id = null, $itemId = null)
    {
        $paidItem = PaidItemHistory::when($id, function($q, $id){
            $q->where(PaidItemHistory::id, $id);
        })
        ->when($itemId, function($q, $itemId){
            $q->where(PaidItemHistory::itemId, $itemId);
        })
        ->first();

        return $paidItem;
    }

    public function getAll($id = null, $itemId = null, $status = null, $startTimeStamp = null, $endTimestamp = null)
    {
        $paidItem = PaidItemHistory::when($id, function($q, $id){
            $q->where(PaidItemHistory::id, $id);
        })
        ->when($itemId, function($q, $itemId){
            $q->where(PaidItemHistory::itemId, $itemId);
        })
        ->when($status, function($q, $status){
            $q->where(PaidItemHistory::status, $status);
        })
        ->when($startTimeStamp, function($q, $startTimeStamp){
            $q->where(PaidItemHistory::startTimestamp, '<=', $startTimeStamp);
        })
        ->when($endTimestamp, function($q, $endTimestamp){
            $q->where(PaidItemHistory::endTimestamp, '>=', $endTimestamp);
        })
        ->get();

        return $paidItem;
    }

    public function update($id, $paidItemHistoryData)
    {

        DB::beginTransaction();

        try {
            $this->updatePaidItemHistory($id, $paidItemHistoryData);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            throw $e;
        }
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function updatePaidItemHistory($id, $paidItemHistoryData)
    {
        $paidItemHistory = $this->get($id);
        $paidItemHistory->updated_user_id = Auth::id();
        $paidItemHistory->update($paidItemHistoryData);

        return $paidItemHistory;
    }


}
