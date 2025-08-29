<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Touch;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\Touch;
use Modules\Core\Http\Services\TouchService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Illuminate\Contracts\Translation\Translator;

class TouchApiController extends Controller
{

    protected $translator,$touchService;

    public function __construct(Translator $translator,TouchService $touchService)
    {
        $this->touchService = $touchService;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->successStatus = Constants::successStatus;
        $this->translator = $translator;
    }

    public function addItemTouchCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'type_id' => 'required',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        $msg = implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()));

        if ($validator->fails()) {
            return responseMsgApi($msg, $this->badRequestStatusCode);
        }

        $response = $this->touchService->addItemTouchCountFromApi($request);
        if(isset($response['error'])) {
            if(isset($response['status'])){
                return responseMsgApi($response['error'], $response['status']);
            }else{
                return responseMsgApi($response['error'], $this->badRequestStatusCode);
            }

        }else if(isset($response['success'])) {
            if(isset($response['status'])){
                return responseMsgApi($response['success'], $response['status'], $this->successStatus);
            }else{
                return responseMsgApi($response['success'], $this->okStatusCode, $this->successStatus);
            }

        }
    }

}
