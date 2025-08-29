<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Rests\App\V1_0\Cart;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\CartService;

class CartApiController extends Controller
{
    protected $translator, $cartService;

    public function __construct(Translator $translator, CartService $cartService)
    {
        $this->translator = $translator;
        $this->cartService = $cartService;
    }

    public function addToCart(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:psx_vendors,id',
            'item_id' => 'required|exists:psx_items,id',
            'quantity' => 'required',
            'user_id' => 'required|exists:users,id',
            'is_select' => 'required'
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        return $this->cartService->addToCartFromApi($request);
    }

    public function delItemFromCart(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'cart_item_ids' => 'required|array',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        return $this->cartService->delItemFromCartFromApi($request);
    }

    public function getAllItemFromCart(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'login_user_id' => 'required',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        return $this->cartService->getAllItemFromCartFromApi($request);
    }

}
