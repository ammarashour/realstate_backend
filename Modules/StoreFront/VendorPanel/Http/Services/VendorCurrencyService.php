<?php

namespace Modules\StoreFront\VendorPanel\Http\Services;

use App\Config\Cache\VendorCache;
use App\Config\ps_constant;
use App\Http\Contracts\Financial\ItemCurrencyServiceInterface;
use Modules\Core\Entities\Item;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Http\Services\VendorService;
use Modules\Core\Transformers\Backend\Model\Financial\ItemCurrencyWithKeyResource;

class VendorCurrencyService extends PsService
{
    const parentPath = "Pages/vendor/views/currency/";
    const indexPath = self::parentPath."Index";
    const indexRoute = "vendor_currency.index";

    protected $vendorService;

    public function __construct(protected ItemCurrencyServiceInterface $itemCurrencyService, VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    public function index($request)
    {
        $vendorId = getVendorIdFromSession();

        // search filter
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['order_by']= null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if($request->sort_field)
        {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $currencies = ItemCurrencyWithKeyResource::collection($this->itemCurrencyService->getAll(null,null, null,null,false,  $row, $conds));
        $vendor = $this->vendorService->getVendor($vendorId);
        $vendorCurrencyId = $vendor->currency_id;

        if($conds['order_by'])
        {
            $dataArr = [
                'currencies' => $currencies,
                'defaultCurrencyId' => $vendorCurrencyId,
                'sort_field' =>$conds['order_by'],
                'sort_order'=>$request->sort_order,
                'search'=>$conds['searchterm'] ,
            ];
        }
        else
        {
            $dataArr = [
                'currencies' => $currencies,
                'defaultCurrencyId' => $vendorCurrencyId,
                'search'=>$conds['searchterm'] ,
            ];
        }

        return renderView(self::indexPath, $dataArr);
    }

    public function defaultChange($id)
    {
        $vendorId = getVendorIdFromSession();
        $vendor = $this->vendorService->getVendor($vendorId);
        $vendorCurrencyId = $vendor->currency_id;
        $currency = $this->itemCurrencyService->get($id);
        $items = Item::where(Item::vendorId, $vendorId)->get();

        if($vendorCurrencyId == $id){
            $dataArr = [
                'msg' => ' Sorry, the '.$currency->currency_short_form.' row cannot be changed to default status because it is default.',
                'flag' => constants::warning,
            ];

            PsCache::clear(VendorCache::BASE);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        }else{
            if($items){
                foreach($items as $item){
                    $item->currency_id = $id;
                    $item->updated_user_id = Auth::user()->id;
                    $item->update();
                }
            }
            $vendor->currency_id = $id;
            $vendor->updated_user_id = Auth::user()->id;
            $vendor->update();

            $dataArr = [
                'msg' =>  __('core__be_default_currency',['attribute'=>$currency->currency_short_form]),
                'flag' => constants::success,
            ];

            PsCache::clear(VendorCache::BASE);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        }
    }
}
