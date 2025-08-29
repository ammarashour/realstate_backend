<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Item;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Modules\Core\Http\Services\Item\ComplaintItemService;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;
use Illuminate\Contracts\Translation\Translator;

class ComplaintItemApiController extends Controller
{
    protected $translator,$complaintItemService, $badRequestStatusCode;

    public function __construct(Translator $translator,ComplaintItemService $complaintItemService)
    {
        $this->complaintItemService = $complaintItemService;

        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->successStatus = Constants::successStatus;
        $this->translator = $translator;
    }

    public function index(Request $request)
    {
        $complaintItem = $this->complaintItemService->indexFromApi($request);
        $data = ProductApiResource::collection($complaintItem);

        $hasError = $this->complaintItemService->noDataError( $request->offset, $data);

        if($hasError)
            return $hasError;
        else {
            return $data;
        }
    }

    public function store(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:psx_items,id',
            'reported_user_id' => 'required|exists:users,id',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        $msg = implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()));

        if ($validator->fails()) {
            return responseMsgApi($msg, $this->badRequestStatusCode);
        }
        /// validation end

        $response = $this->complaintItemService->storeFromApi($request);
        if(isset($response['error'])) {
            if(isset($response['status'])){
                return responseMsgApi($response['error'], $response['status']);
            }else{
                return responseMsgApi($response['error'], $this->badRequestStatusCode);
            }

        }else{
            if(isset($response['status'])){
                return responseMsgApi($response['success'], $response['status'], $this->successStatus);
            }else{
                return responseMsgApi($response['success'], $this->okStatusCode, $this->successStatus);
            }

        }
    }

}
