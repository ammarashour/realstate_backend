<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Item;

use App\Config\ps_constant;
use App\Http\Contracts\Utilities\DynamicLinkServiceInterface;
use Carbon\Carbon;
use Modules\Core\Entities\Favourite;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item\PaidItemHistory;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Category\CategoryApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Financial\ItemCurrencyApiResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductInfoApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Category\SubcategoryApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Location\LocationCityApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Location\LocationTownshipApiResource;

class ItemApiResource extends JsonResource
{
    protected DynamicLinkServiceInterface $dynamicLinkService;
    
    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->dynamicLinkService = app(DynamicLinkServiceInterface::class);
    }

    public function toArray($request)
    {

        return [
            'id' => checkAndGetValue($this, 'id'),
            'title' => checkAndGetValue($this, 'title'),
            'category_id' => checkAndGetValue($this, 'category_id'),
            'subcategory_id' => checkAndGetValue($this, 'subcategory_id'),
            'currency_id' => checkAndGetValue($this, 'currency_id'),
            'location_city_id' => checkAndGetValue($this, 'location_city_id'),
            'location_township_id' => checkAndGetValue($this, 'location_township_id'),
            'shop_id' => checkAndGetValue($this, 'shop_id'),
            'phone' => checkAndGetValue($this, 'phone'),
            'percent' => checkAndGetValue($this, 'percent'),
            'price' => checkAndGetValue($this, 'price'),
            'original_price' => checkAndGetValue($this, 'original_price'),
            'description' => checkAndGetValue($this, 'description'),
            'search_tag' => checkAndGetValue($this, 'search_tag'),
            'dynamic_link' => $this->getDeeplink(),
            'lat' => checkAndGetValue($this, 'lat'),
            'lng' => checkAndGetValue($this, 'lng'),
            'status' => checkAndGetValue($this, 'status'),
            'is_paid' => checkAndGetValue($this, 'is_paid'),
            'is_sold_out' => checkAndGetValue($this, 'is_sold_out'),
            'ordering' => checkAndGetValue($this, 'ordering'),
            'is_available' => checkAndGetValue($this, 'is_available'),
            'is_discount' => checkAndGetValue($this, 'is_discount'),
            'item_touch_count' => checkAndGetValue($this, 'item_touch_count'),
            'favourite_count' => checkAndGetValue($this, 'favourite_count'),
            'added_date' => checkAndGetValue($this, 'added_date'),
            'added_user_id' => checkAndGetValue($this, 'added_user_id'),
            'overall_rating' => checkAndGetValue($this, 'overall_rating'),
            "is_favourited" => (string) $this->isFavourite($request->login_user_id),
            "is_owner" => (string) $this->checkIsOwner($request->login_user_id),
            'ad_type' => (string) $this->getAdType(),
            'paid_status' => (string) $this->getPaidStatus(),
            "photo_count" => (string) $this->getPhotoCount(),
            "video_count" => (string) $this->getVideoCount(),
            "productRelation" => ItemInfoApiResource::collection($this->itemRelation ?? []),
            "default_photo" => new CoreImageApiResource($this->cover[0] ?? []),
            "default_video" => new CoreImageApiResource($this->video[0] ?? []),
            "default_video_icon" => new CoreImageApiResource($this->icon[0] ?? []),
            "category" => new CategoryApiResource($this->category ?? []),
            "sub_category" => new SubcategoryApiResource($this->subcategory ?? []),
            "item_currency" => new ItemCurrencyApiResource($this->currency ?? []),
            "item_location" => new LocationCityApiResource($this->city ?? []),
            "item_location_township" => new LocationTownshipApiResource($this->township ?? []),
            "user" => new UserApiResource($this->owner ?? []),
            "vendor" => new VendorApiResource($this->vendor ?? []),
            "added_date_str" => $this->getAddedDateStr(),
            "is_empty_object" => $this->when(!isset($this->id),"1"),
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getAddedDateStr()
    {

        $date = checkAndGetValue($this, 'added_date');

        if ($date === '') {
            return '';
        }

        return $this->added_date->diffForHumans();
    }

    private function checkIsOwner($loginUserId)
    {
       return isset($this->id) ? ((string)$this->added_user_id == $loginUserId ? '1': '0') : '';
    }

    private function getAdType()
    {
        if(empty($this->id) && empty($this->ad_type)){
            return ;
        }

        $today = Carbon::now();

        if (isset($this->ad_type)) {
            return $this->ad_type;
        }

        $paid_data = PaidItemHistory::where('item_id', $this->id)->get();

        foreach ($paid_data as $item) {
            $start_date = Carbon::createFromTimestamp($item->start_timestamp);
            $end_date = Carbon::createFromTimestamp($item->end_timestamp);

            if ($start_date->lte($today) && $end_date->gte($today)) {
                return Constants::paidAd;
            }
        }

        return Constants::normalAd;
    }

    private function getPaidStatus()
    {
        if(empty($this->id)){
            return ;
        }

        $paid_conds['item_id'] = $this->id;
        $paid_histories = PaidItemHistory::where($paid_conds)->get();

        if (count($paid_histories) == 1) {
            $start_timestamp = $paid_histories[0]->start_timestamp;
            $end_timestamp = $paid_histories[0]->end_timestamp;

            if ($this->is_paid == 1) {
                $paid_status = getPaidStatus($start_timestamp, $end_timestamp);
            } else {
                if ($this->is_paid == 0) {
                    $paid_status = Constants::paidItemWaitingForApproval;
                } else {
                    $paid_status = Constants::paidItemRejected;
                }
            }
        } else if (count($paid_histories) > 1) {
            if(isset($this->paid_item_id)){
                $paid_conds['id'] = $this->paid_item_id;
                $paid_history = PaidItemHistory::select("start_timestamp", "end_timestamp")->where($paid_conds)->first();
                $start_timestamp = $paid_history->start_timestamp;
                $end_timestamp = $paid_history->end_timestamp;
                $paid_status = getPaidStatus($start_timestamp, $end_timestamp);
            }else{
                foreach($paid_histories as $paid_history){
                    $start_timestamp = $paid_history->start_timestamp;
                    $end_timestamp = $paid_history->end_timestamp;
                    $paid_status = getPaidStatus($start_timestamp, $end_timestamp);
                    if($paid_status==Constants::paidItemProgressStatus){
                        break;
                    }
                }
            }
            if ($this->is_paid == 1) {
                $paid_status = getPaidStatus($start_timestamp, $end_timestamp);
            } else {
                if ($this->is_paid == 0) {
                    $paid_status = Constants::paidItemWaitingForApproval;
                } else {
                    $paid_status = Constants::paidItemRejected;
                }
            }
        } else {
            $paid_status = Constants::paidItemNotAvailable;
        }

        return $paid_status;
    }

    private function getPhotoCount()
    {
        return (string)(!empty($this->cover) ? $this->cover->count() : 0);
    }

    private function getVideoCount()
    {
        return (string)(!empty($this->video) ? $this->video->count() : 0);
    }

    private function isFavourite($loginUserId)
    {
        if(empty($this->id)){
            return "0";
        }
        $conds['user_id'] = $loginUserId;
        $conds['item_id'] = $this->id;

        $favourite = Favourite::where($conds)->count();

        return $favourite == "1" ? "1" : "0";
    }

    private function getDeeplink()
    {
        $provider = $this->dynamicLinkService->getDeepLinkServiceProvider();
        if($provider == ps_constant::FIREBASE) {
            return $this->dynamic_link ?? '';
        } elseif($provider == ps_constant::PSX_DYNAMIC_LINK) {
            return $this->dynamic_link ? route('shortcode', ['shortCode' => $this->dynamic_link]) : '';
        }
    }
}
