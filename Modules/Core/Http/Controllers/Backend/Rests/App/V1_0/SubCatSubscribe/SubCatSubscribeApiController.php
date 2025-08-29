<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\SubCatSubscribe;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Services\SubCatSubscribeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Illuminate\Contracts\Translation\Translator;

class SubCatSubscribeApiController extends Controller
{

    protected $translator,$subCatSubscribeService;

    public function __construct(Translator $translator,SubCatSubscribeService $subCatSubscribeService)
    {
        $this->subCatSubscribeService = $subCatSubscribeService;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->successStatus = Constants::successStatus;
        $this->translator = $translator;
    }

    public function subCategorySubscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'cat_id' => 'required'
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        $msg = implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()));

        if ($validator->fails()) {
            return responseMsgApi($msg, $this->badRequestStatusCode);
        }

        $response = $this->subCatSubscribeService->subCategorySubscribeFromApi($request);
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

    public function isUserSubscribed(Request $request)
    {
        $response = $this->subCatSubscribeService->checkIsUserSubscribed($request);

        if(isset($response['error'])) {
            return responseMsgApi($response['error'], $response['status']);
        }else{
            return responseMsgApi($response['success'], $response['status'], $this->successStatus);

        }
    }

}
