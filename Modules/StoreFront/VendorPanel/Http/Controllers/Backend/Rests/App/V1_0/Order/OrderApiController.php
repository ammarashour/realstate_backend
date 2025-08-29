<?php

namespace Modules\StoreFront\VendorPanel\Http\Controllers\Backend\Rests\App\V1_0\Order;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Arr;
use Modules\Core\Constants\Constants;
use Modules\StoreFront\VendorPanel\Http\Services\OrderService;

class OrderApiController extends Controller
{
    protected $translator, $orderService;

    public function __construct(Translator $translator, OrderService $orderService)
    {
        $this->translator = $translator;
        $this->orderService = $orderService;
    }

    public function getOrderSummary(Request $request)
    {
        //prepare validation
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:psx_orders,id',
        ]);

        if($request->language_symbol){
            $this->translator->setLocale($request->language_symbol);
            $validator->setTranslator($this->translator);
        }

        if ($validator->fails()) {
            return responseMsgApi(implode("\n", Arr::flatten($validator->getMessageBag()->getMessages())), Constants::badRequestStatusCode);
        }
        /// validation end

        return $this->orderService->getOrderSummaryFromApi($request);
    }

    public function getOrderHistory(Request $request)
    {
        return $this->orderService->searchFromApi($request);
    }

    public function downloadPDF(Request $request) {
        return $this->orderService->downloadOrderPDF($request);
    }

}
