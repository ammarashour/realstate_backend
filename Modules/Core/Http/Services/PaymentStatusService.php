<?php
namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\PaymentStatus;
use Illuminate\Support\Facades\Auth;

class PaymentStatusService extends PsService
{
    protected $paymentStatusIdCol;

    public function __construct()
    {
        $this->paymentStatusIdCol = PaymentStatus::id;
    }

    public function store($request)
    {

        DB::beginTransaction();

        try {
            $payment_status = new PaymentStatus();
            $payment_status->title = $request->title;
            $payment_status->added_user_id = Auth::user()->id;
            $payment_status->save();

            DB::commit();
        } catch (\Throwable $e){
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $payment_status;
    }

    public function update($id, $request)
    {

        DB::beginTransaction();

        try {
            $payment_status = $this->getPaymentStatus($id);
            $payment_status->title = $request->title;
            $payment_status->updated_user_id = Auth::user()->id;
            $payment_status->update();

            DB::commit();
        } catch (\Throwable $e){
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $payment_status;
    }

    public function getPaymentStatuses(){
        $paymentStatuses = PaymentStatus::latest()->get();
        return $paymentStatuses;
    }

    public function getPaymentStatus($id){
        $paymentStatus = PaymentStatus::when($id, function ($q, $id){
                                                $q->where($this->paymentStatusIdCol, $id);
                                            })->first();
        return $paymentStatus;
    }

    // ------------------------

    public function index(){
        $paymentStatuses = $this->getPaymentStatuses();
        $dataArr = [
            'payment_statuses' => $paymentStatuses,
        ];
        return $dataArr;
    }

    public function edit($id){
        $paymentStatus = $this->getPaymentStatus($id);
        $dataArr = [
            'payment_status' => $paymentStatus
        ];
        return $dataArr;
    }

    public function destroy($id){
        $paymentStatus = $this->getPaymentStatus($id);
        $name = $paymentStatus->title;
        $paymentStatus->delete();

        $status = [
            // 'msg' => 'The '.$name.' row has been deleted successfully.',
            'msg'=>__('core__be_delete_success',['attribute'=>$name]),
            'flag' => 'danger',
        ];
        return $status;
    }

}
