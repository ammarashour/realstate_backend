<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Favourite;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\Favourite;
use Modules\Core\Http\Services\FavouriteService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;
use Illuminate\Contracts\Translation\Translator;

class FavouriteApiController extends Controller
{

    protected $translator,$favouriteService;

    public function __construct(Translator $translator,FavouriteService $favouriteService)
    {
        $this->favouriteService = $favouriteService;
        $this->successStatus = Constants::successStatus;
        $this->createdStatusCode = Constants::createdStatusCode;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->translator = $translator;
    }

    public function favouriteItem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'item_id' => 'required',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        $msg = implode("\n", Arr::flatten($validator->getMessageBag()->getMessages()));

        if ($validator->fails()) {
            return responseMsgApi($msg, $this->badRequestStatusCode);
        }

        $response = $this->favouriteService->favouriteItemFromApi($request);
        if(isset($response['error'])) {
            if(isset($response['status'])){
                return responseMsgApi($response['error'], $response['status']);
            }else{
                return responseMsgApi($response['error'], $this->badRequestStatusCode);
            }
            
        } else if(isset($response['success'])) {
            if(isset($response['status'])){
                return responseMsgApi($response['success'], $response['status'], $this->successStatus);
            }else{
                return responseMsgApi($response['success'], $this->okStatusCode, $this->successStatus);
            }
        }else  {

            $item = new ProductApiResource($response);
            return responseDataApi($item);
        }
    }

    public function getAllFavouriteItem(Request $request)
    {
        $response = $this->favouriteService->indexFromApi($request);
        if(isset($response['error'])) {
            if(isset($response['status'])){
                return responseMsgApi($response['error'], $response['status']);
            }else{
                return responseMsgApi($response['error'], $this->badRequestStatusCode);
            }
            
        } else  {

            $data = ProductApiResource::collection($response);

            $hasError = $this->favouriteService->noDataError( $request->offset, $data);

            if($hasError)
                return $hasError;
            else {
                return $data;
            }
        }
    }

}
