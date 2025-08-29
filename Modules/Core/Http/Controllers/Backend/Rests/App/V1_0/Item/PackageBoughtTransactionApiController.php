<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Item;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Translation\Translator;
use Modules\Core\Http\Services\Item\PackageBoughtTransactionService;

class PackageBoughtTransactionApiController extends Controller
{
    protected $translator,$packageBoughtTransactionService, $successStatus, $badRequestStatusCode, $notFoundStatusCode, $okStatusCode, $packageInAppPurchasePaymentId;

    public function __construct(Translator $translator,PackageBoughtTransactionService $packageBoughtTransactionService)
    {
        $this->packageBoughtTransactionService = $packageBoughtTransactionService;

        $this->successStatus = Constants::success;
        $this->okStatusCode = Constants::okStatusCode;
        $this->notFoundStatusCode = Constants::notFoundStatusCode;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->packageInAppPurchasePaymentId = Constants::packageInAppPurchasePaymentId;
        $this->translator = $translator;
    }

    public function store(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'package_id' => 'required|exists:psx_payment_infos,id',
            'payment_method' => 'required',
            'price' => 'required',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end
        $package = $this->packageBoughtTransactionService->getPaymentInfoByPackageId($request->package_id);
        if($package->payment_id != $this->packageInAppPurchasePaymentId){
            return responseMsgApi('package__pkg_invalid', $this->badRequestStatusCode);
        }
        $packages = $this->packageBoughtTransactionService->storeFromApi($request);
        return $packages;
    }

    public function search(Request $request)
    {
        $transactions = $this->packageBoughtTransactionService->searchFromApi($request);
        return $transactions;
    }

    public function destroy(Request $request)
    {
        $msg = $this->packageBoughtTransactionService->destroyFromApi($request);

        if(isset($msg['error'])) {
            if(isset($msg['status'])){
                return responseMsgApi($msg['error'], $msg['status']);
            }else{
                return responseMsgApi($msg['error'], $this->notFoundStatusCode);
            }
        }
        if(isset($msg['success'])) {
            if(isset($msg['status'])){
                return responseMsgApi($msg['success'], $msg['status'], $this->successStatus);
            }else{
                return responseMsgApi($msg['success'], $this->okStatusCode, $this->successStatus);
            }
        }
    }
}
