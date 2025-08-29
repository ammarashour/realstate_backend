<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Product;

use App\Config\ps_constant;
use App\Http\Contracts\Utilities\DynamicLinkServiceInterface;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Item\ComplaintItem;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Entities\Location\LocationTownship;
use Modules\Core\Entities\Category\Subcategory;
use Modules\Core\Transformers\Api\App\V1_0\Category\CategoryApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductInfoApiResource;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Financial\ItemCurrencyApiResource;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;
use Modules\Core\Entities\Favourite;
use Modules\Core\Entities\Item\PaidItemHistory;
use Modules\Core\Transformers\Api\App\V1_0\Category\SubcategoryApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Location\LocationCityApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Location\LocationTownshipApiResource;

/**
 * @deprecated
 */
class ProductApiResource extends JsonResource
{
    protected DynamicLinkServiceInterface $dynamicLinkService;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->dynamicLinkService = app(DynamicLinkServiceInterface::class);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        // dd($this);
        $normalAd = Constants::normalAd;
        $paidAd = Constants::paidAd;
        $paidItemRejected = Constants::paidItemRejected;
        $paidItemProgressStatus = Constants::paidItemProgressStatus;
        $paidItemCompletedStatus = Constants::paidItemCompletedStatus;
        $paidItemNotYetStartStatus = Constants::paidItemNotYetStartStatus;
        $paidItemWaitingForApproval = Constants::paidItemWaitingForApproval;
        $paidItemNotAvailable = Constants::paidItemNotAvailable;

        if (isset($this->ad_type)) {
            $ad_type = $this->ad_type;
        }

        if (isset($this->id) && !empty($this->id)) {
            $conds['user_id'] = $request->login_user_id;
            $conds['item_id'] = $this->id;

            $favourite = Favourite::where($conds)->count();

            if ($favourite == "1") {
                $is_favourited = 1;
            } else {
                $is_favourited = 0;
            }

            // ad type
            $today = Carbon::now();
            if (!isset($this->ad_type)) {
                $ad_type = $normalAd;

                $ad_conds['item_id'] = $this->id;

                $paid_data = PaidItemHistory::where($ad_conds)->get();

                foreach ($paid_data as $item) {
                    $start_date = date('Y-m-d H:i:s', $item->start_timestamp);
                    $end_date = date('Y-m-d H:i:s', $item->end_timestamp);
                    if ($start_date <= $today && $end_date >= $today) {
                        $ad_type = $paidAd;
                        break;
                    }
                }
            } else {
                $ad_type = $this->ad_type;
            }
            // paid status
            $paid_conds['item_id'] = $this->id;
            $paid_histories = PaidItemHistory::where($paid_conds)->get();
            // dd($paid_histories);

            if (count($paid_histories) == 1) {
                $start_timestamp = $paid_histories[0]->start_timestamp;
                $end_timestamp = $paid_histories[0]->end_timestamp;

                if ($this->is_paid == 1) {
                    $paid_status = getPaidStatus($start_timestamp, $end_timestamp);
                } else {
                    if ($this->is_paid == 0) {
                        $paid_status = $paidItemWaitingForApproval;
                    } else {
                        $paid_status = $paidItemRejected;
                    }
                }
            } else if (count($paid_histories) > 1) {
                if (isset($this->paid_item_id)) {
                    $paid_conds['id'] = $this->paid_item_id;
                    $paid_history = PaidItemHistory::select("start_timestamp", "end_timestamp")->where($paid_conds)->first();
                    $start_timestamp = $paid_history->start_timestamp;
                    $end_timestamp = $paid_history->end_timestamp;
                    $paid_status = getPaidStatus($start_timestamp, $end_timestamp);
                } else {
                    foreach ($paid_histories as $paid_history) {
                        $start_timestamp = $paid_history->start_timestamp;
                        $end_timestamp = $paid_history->end_timestamp;
                        $paid_status = getPaidStatus($start_timestamp, $end_timestamp);
                        if ($paid_status == Constants::paidItemProgressStatus) {
                            break;
                        }
                    }
                }
                if ($this->is_paid == 1) {
                    $paid_status = getPaidStatus($start_timestamp, $end_timestamp);
                } else {
                    if ($this->is_paid == 0) {
                        $paid_status = $paidItemWaitingForApproval;
                    } else {
                        $paid_status = $paidItemRejected;
                    }
                }
            } else {
                $paid_status = $paidItemNotAvailable;
            }
        }


        return [
            'id' => isset($this->id) ? (string)$this->id : '',
            'title' => isset($this->title) ? (string)$this->title : '',
            'category_id' => isset($this->category_id) ? (string)$this->category_id : '',
            'subcategory_id' => isset($this->subcategory_id) ? (string)$this->subcategory_id : '',
            'currency_id' => isset($this->currency_id) ? (string)$this->currency_id : '',
            'location_city_id' => isset($this->location_city_id) ? (string)$this->location_city_id : '',
            'location_township_id' => isset($this->location_township_id) ? (string)$this->location_township_id : '',
            'shop_id' => isset($this->shop_id) ? (string)$this->shop_id : '',
            'phone' => isset($this->phone) ? (string)$this->phone : '',
            'percent' => isset($this->percent) ? (string)$this->percent : '',
            'price' => isset($this->price) ? (string)$this->price : '',
            'original_price' => isset($this->price) ? (string)$this->original_price : '',
            'description' => isset($this->description) ? (string)$this->description : '',
            'search_tag' => isset($this->search_tag) ? (string)$this->search_tag : '',
            'dynamic_link' => $this->getDeeplink(),
            'lat' => isset($this->lat) ? (string)$this->lat : '',
            'lng' => isset($this->lng) ? (string)$this->lng : '',
            'status' => isset($this->status) ? (string)$this->status : '',
            'is_paid' => isset($this->is_paid) ? (string)$this->is_paid : '',
            'is_sold_out' => isset($this->is_sold_out) ? (string)$this->is_sold_out : '',
            'ordering' => isset($this->ordering) ? (string)$this->ordering : '',
            'is_available' => isset($this->is_available) ? (string)$this->is_available : '',
            'is_discount' => isset($this->is_discount) ? (string)$this->is_discount : '',
            'item_touch_count' => isset($this->item_touch_count) ? (string)$this->item_touch_count : '',
            'favourite_count' => isset($this->favourite_count) ? (string)$this->favourite_count : '',
            'added_date' => isset($this->added_date) ? (string)$this->added_date : '',
            'added_user_id' => isset($this->added_user_id) ? (string)$this->added_user_id : '',
            'overall_rating' => isset($this->overall_rating) ? (string)$this->overall_rating : '',
            "is_favourited" => isset($is_favourited) ? (string)$is_favourited : '',
            "is_owner" => isset($this->id) ? ((string)$this->added_user_id == $request->login_user_id ? '1' : '0') : '',
            'ad_type' => isset($ad_type) ? (string)$ad_type : '',
            'paid_status' => isset($paid_status) ? (string)$paid_status : '',
            "photo_count" => !empty($this->cover) ? (string)$this->cover->count() : "0",
            "video_count" => !empty($this->video) ? (string)$this->video->count() : "0",
            "productRelation" => ProductInfoApiResource::collection(isset($this->itemRelation) && count($this->itemRelation) > 0 ? $this->whenLoaded('itemRelation') : ['xxx']),
            "default_photo" => new CoreImageApiResource(isset($this->cover[0]) && $this->cover[0] ? $this->cover[0] : []),
            "default_video" => new CoreImageApiResource(isset($this->video[0]) && $this->video[0] ? $this->video[0] : []),
            "default_video_icon" => new CoreImageApiResource(isset($this->icon[0]) && $this->icon[0] ? $this->icon[0] : []),
            "category" => new CategoryApiResource(isset($this->category) && $this->category ? $this->whenLoaded('category') : []),
            "sub_category" => new SubcategoryApiResource(isset($this->subcategory) && $this->subcategory ? $this->whenLoaded('subcategory') : []),
            "item_currency" => new ItemCurrencyApiResource(isset($this->currency) && $this->currency ? $this->whenLoaded('currency') : []),
            "item_location" => new LocationCityApiResource(isset($this->city) && $this->city ? $this->whenLoaded('city') : []),
            "item_location_township" => new LocationTownshipApiResource($this->township ?? []),
            // "shop" => $this->whenLoaded('shop'),
            "user" => new UserApiResource(isset($this->owner) && $this->owner ? $this->whenLoaded('owner') : []),
            "vendor" => new VendorApiResource(isset($this->vendor) && $this->vendor ? $this->whenLoaded('vendor') : []),
            "added_date_str" => isset($this->added_date) ? (string)$this->added_date->diffForHumans() : '',
            "is_empty_object" => $this->when(!isset($this->id), '1'),
        ];
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
