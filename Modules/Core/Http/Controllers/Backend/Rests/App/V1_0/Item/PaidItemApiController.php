<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Item;

use App\Rules\CheckExistsForIapProductId;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\ItemService;
use Illuminate\Contracts\Translation\Translator;
use Modules\Core\Http\Services\Item\PaidItemService;

class PaidItemApiController extends Controller
{
    protected $translator,$notFoundStatusCode, $okStatusCode, $successStatus, $paidItemService, $itemService, $badRequestStatusCode, $forbiddenStatusCode;

    public function __construct(Translator $translator,PaidItemService $paidItemService, ItemService $itemService)
    {
        $this->paidItemService = $paidItemService;
        $this->itemService = $itemService;

        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;
        $this->successStatus = Constants::successStatus;
        $this->translator = $translator;
    }

    public function index(Request $request)
    {
        $paidItems = $this->paidItemService->indexFromApi($request);
        return $paidItems;
    }

    public function store(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:psx_items,id',
            'amount' => 'required',
            'payment_method' => 'required',
            'start_date' => 'required',
            'how_many_day' => 'required_without_all:product_id',
            'product_id' => [new CheckExistsForIapProductId($request->payment_method)],
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }
        /// validation end

        $loginUserId = $request->login_user_id;

        $item = $this->itemService->getItem($request->item_id);
        $added_user_id = 0;
        if($item){
            $added_user_id = $item->added_user_id;
        }
        if($loginUserId == $added_user_id){
            $paidItem = $this->paidItemService->storeFromApi($request, $item);
            return $paidItem;
        } else {
            return responseMsgApi(__('itemPromotion__api_not_owner',[],$request->language_symbol), $this->forbiddenStatusCode);
        }
    }

    public function destroy(Request $request)
    {
        $msg = $this->paidItemService->destroyFromApi($request);
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

    public function getPurchasedHistory(Request $request){
        $paidItems = $this->paidItemService->getPurchasedHistoryFromApi($request);
        return $paidItems;
    }

    public function token(Request $request){
        $token = $this->paidItemService->tokenFromApi($request);
        return $token;
    }

    public function verifyTransaction(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required',
            'vendor_id' => isset($request->vendor_id) ? 'exists:psx_vendors,id' : '',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), $this->badRequestStatusCode);
        }

        $response = $this->paidItemService->verifyTransaction($request);

        return response($response, json_decode($response)->status == Constants::successStatus ? Constants::okStatusCode : Constants::badRequestStatusCode);
    }
}
