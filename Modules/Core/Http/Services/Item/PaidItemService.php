<?php

namespace Modules\Core\Http\Services\Item;

use App\Config\Cache\ItemCache;
use Carbon\Carbon;
use App\Config\ps_constant;
use Modules\Core\Entities\Item;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Core\Entities\Category\Category;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
// use Modules\Core\Entities\Item\PaidItemHistory;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Category\Subcategory;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Core\Http\Services\ItemService;
use Modules\Core\Entities\AvailableCurrency\AvailableCurrency;

use Modules\Core\Entities\VendorPaymentInfo;
use PHPUnit\TextUI\XmlConfiguration\Constant;
use Modules\Payment\Entities\PaymentAttribute;
use Modules\Core\Http\Services\Category\CategoryService;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Http\Services\Category\SubcategoryService;
use Modules\Core\Entities\Item\PaidItemHistory;
use Modules\Core\Exports\PromotionReportExport;
use Modules\Core\Http\Facades\PsCache;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Transformers\Api\App\V1_0\Item\PaidItemHistoryApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Item\PurchasedItemHistoryApiResource;
use Modules\Core\Transformers\Backend\Model\Item\PaidItemWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\Item\OfflinePaidItemWithKeyResource;

class PaidItemService extends PsService
{
    protected $unDelete, $dropDownUi, $customUiIsDelCol, $radioUi, $coreFieldFilterModuleNameCol, $coreFieldFilterIdCol, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $show, $paidItmItemIdCol, $dangerFlag, $subcategoryService, $categoryService, $hide, $code, $badRequestStatusCode, $okStatusCode, $noContentStatusCode, $successStatus, $paymentSettingService, $createdStatusCode, $internalServerErrorStatusCode, $stripeSecretKey, $paypalPrivateKey, $paypalPublicKey, $paypalMerchantId, $paypalSecretKey, $paypalEnvironment, $paidItmStatusCol, $coverImgType, $itemService, $paidItmIdCol, $coreImageImgParentIdCol, $publish, $unPublish, $coreImageImgTypeCol, $locationCityService, $paidItemApiRelation, $offlinePaymentMethod, $paystackPaymentMethod, $razorPaymentMethod, $paypalPaymentMethod, $stripePaymentMethod, $iapPaymentMethod,  $deviceTokenParaApi, $loginUserIdParaApi, $userAccessApiTokenService, $forbiddenStatusCode;

    public function __construct(CategoryService $categoryService, SubcategoryService $subcategoryService, UserAccessApiTokenService $userAccessApiTokenService, ItemService $itemService, PaymentSettingService $paymentSettingService)
    {
        $this->dropDownUi = Constants::dropDownUi;
        $this->radioUi = Constants::radioUi;
        $this->customUiIsDelCol = CustomField::isDelete;
        $this->itemService = $itemService;
        $this->categoryService = $categoryService;
        $this->subcategoryService = $subcategoryService;
        $this->paymentSettingService = $paymentSettingService;

        $this->paidItmIdCol = PaidItemHistory::id;
        $this->paidItmStatusCol = PaidItemHistory::status;
        $this->paidItmItemIdCol = PaidItemHistory::itemId;
        $this->paidItmTableName = PaidItemHistory::tableName;
        $this->paidItmAmountCol = PaidItemHistory::amount;
        $this->paidItmStartDateCol = PaidItemHistory::startDate;
        $this->paidItmEndDateCol = PaidItemHistory::endDate;
        $this->paidItmStartTimeStamp = PaidItemHistory::startTimestamp;
        $this->paidItmEndTimeStamp = PaidItemHistory::endTimestamp;
        $this->paidItmPaymentMethodCol = PaidItemHistory::paymentMethod;
        $this->paidItmPromotedDaysCol = PaidItemHistory::promotedDays;
        $this->paidItmAddedDateCol = PaidItemHistory::addedDate;
        $this->paidItmAddedUserIdCol = PaidItemHistory::addedUserId;

        $this->catTableName = Category::tableName;
        $this->catIdCol = Category::id;
        $this->catNameCol = Category::name;

        $this->subCatTableName = Subcategory::tableName;
        $this->subCatIdCol = Subcategory::id;
        $this->subCatNameCol = Subcategory::name;

        $this->itemTableName = Item::tableName;
        $this->itemIdCol = Item::id;
        $this->itemCategoryIdCol = Item::categoryId;
        $this->itemSubCategoryIdCol = Item::subCategoryId;
        $this->itemTitleCol = Item::title;

        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->hide = Constants::hide;
        $this->show = Constants::show;
        $this->dangerFlag = Constants::danger;
        $this->unDelete = Constants::unDelete;

        $this->paidItemApiRelation = ['item'];

        $this->createdStatusCode = Constants::createdStatusCode;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;

        $this->paypalPrivateKey = Constants::paypalPrivateKey;
        $this->paypalPublicKey = Constants::paypalPublicKey;
        $this->paypalMerchantId = Constants::paypalMerchantId;
        $this->paypalSecretKey = Constants::paypalSecretKey;
        $this->paypalEnvironment = Constants::paypalEnvironment;

        $this->stripeSecretKey = Constants::stripeSecretKey;

        $this->offlinePaymentMethod = Constants::offlinePaymentMethod;
        $this->paystackPaymentMethod = Constants::paystackPaymentMethod;
        $this->razorPaymentMethod = Constants::razorPaymentMethod;
        $this->paypalPaymentMethod = Constants::paypalPaymentMethod;
        $this->stripePaymentMethod = Constants::stripePaymentMethod;
        $this->iapPaymentMethod = Constants::iapPaymentMethod;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;

        $this->noContentStatusCode = Constants::noContentStatusCode;
        $this->successStatus = Constants::successStatus;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;
        $this->okStatusCode = Constants::okStatusCode;

        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->loginUserIdParaApi = ps_constant::loginUserIdParaFromApi;
        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;

        $this->code = Constants::paidItem;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;
        $this->coreFieldFilterIdCol = CoreField::id;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->csvFileName = 'promotion_report';
    }

    public function store($request)
    {
        // dd($request->all());
        DB::beginTransaction();

        try {
            $paidItem = new PaidItemHistory();

            if (isset($request->item_id) && !empty($request->item_id))
                $paidItem->item_id = $request->item_id;

            if (isset($request->date_range) && !empty($request->date_range)) {
                $date_range = $request->date_range;

                if ($date_range) {
                    $start_date = new Carbon($date_range[0]);
                    // dd($start_date);

                    $paidItem->start_date = $start_date;

                    $paidItem->start_timestamp = strtotime($start_date);

                    if ($date_range[1]) {
                        $end_date = new Carbon($date_range[1]);
                        $paidItem->end_date = $end_date;
                        $paidItem->end_timestamp = strtotime($end_date);
                    } else {
                        $paidItem->end_date = $start_date;
                        $paidItem->end_timestamp = strtotime($start_date);
                    }
                    $paidItem->promoted_days = ($paidItem->start_date)->diffInDays($paidItem->end_date);
                }
            }
            if (isset($request->start_date) && !empty($request->start_date) && isset($request->start_timestamp) && !empty($request->start_timestamp)) {
                // dd($request->payment_method);
                // dd(Carbon::parse($request->start_date));
                // dd("here");
                // $paidItem->start_date = Carbon::parse($request->start_date)->format('Y-m-d h:m:s');
                // $$request->start_date = Carbon::createFromFormat('n-j-Y H:i', $request->start_date)->toDateTimeString();

                $new_date = str_replace('-', '/', $request->start_date);
                $start_date = date('Y-m-d H:i', strtotime($new_date)) . ":00";

                $paidItem->start_date = $start_date;
                $paidItem->start_timestamp = $request->start_timestamp;
            } else if (isset($request->start_date) && !empty($request->start_date)) {
                // dd('here2');
                $paidItem->start_date = $request->start_date;
                $paidItem->start_timestamp = strtotime($request->start_date);
            }

            // dd($days);
            if (isset($request->how_many_day) && !empty($request->how_many_day)) {
                $start_date = date('Y-m-d H:i:s', $paidItem->start_timestamp);
                $end_date = Carbon::create($start_date)->addDays((int)$request->how_many_day);
                $paidItem->end_date = $end_date;
                $paidItem->end_timestamp = strtotime($end_date);
                $paidItem->promoted_days = $request->how_many_day;
            }

            if (isset($request->product_id) && !empty($request->product_id)) {
                $coreKeys = CoreKey::where('name', $request->product_id)->pluck(CoreKey::coreKeysId);
                $paymentAttributes = PaymentAttribute::whereIn(PaymentAttribute::coreKeysId, $coreKeys)->where([
                    PaymentAttribute::paymentId => Constants::promotionInAppPurchasePaymentId,
                    PaymentAttribute::attributeKey => 'days'
                ])->first();
                if ($paymentAttributes) {
                    $start_date = date('Y-m-d H:i:s', $paidItem->start_timestamp);
                    $end_date = Carbon::create($start_date)->addDays((int)$paymentAttributes->attribute_value);
                    $paidItem->end_date = $end_date;
                    $paidItem->end_timestamp = strtotime($end_date);
                    $paidItem->promoted_days = $paymentAttributes->attribute_value;
                } else {
                    return responseMsgApi(__('itemPromotion__api_no_data_product_id', [], $request->language_symbol), $this->noContentStatusCode);
                }
            }
            if (isset($request->how_many_day) && !empty($request->how_many_day) && isset($request->product_id) && !empty($request->product_id)) {
                $coreKeys = CoreKey::where('name', $request->product_id)->pluck(CoreKey::coreKeysId);
                // $paymentInfo = PaymentInfo::where('core_keys_id', $coreKey->core_keys_id)->first();
                $paymentAttributes = PaymentAttribute::whereIn(PaymentAttribute::coreKeysId, $coreKeys)->where([
                    PaymentAttribute::paymentId => Constants::promotionInAppPurchasePaymentId,
                    PaymentAttribute::attributeKey => 'days'
                ])->first();
                if ($paymentAttributes) {
                    $paidItem->promoted_days = $paymentAttributes->attribute_value;
                } else {
                    $paidItem->promoted_days = $request->how_many_day;
                    return responseMsgApi(__('itemPromotion__api_no_data_product_id', [], $request->language_symbol), $this->noContentStatusCode);
                }
            }

            if (!(isset($request->how_many_day)) && isset($request->end_date) && !empty($request->end_date)) {
                $paidItem->end_date = $request->end_date;
                $paidItem->end_timestamp = strtotime($request->end_date);
                $paidItem->promoted_days = $paidItem->start_date - $paidItem->end_date;
            }

            if (isset($request->amount) && !empty($request->amount))
                $paidItem->amount = $request->amount;
            else
                $paidItem->amount = '0.0';

            if (isset($request->payment_method) && !empty($request->payment_method))
                $paidItem->payment_method = $request->payment_method;
            else
                $paidItem->payment_method = 'NA';

            if (isset($request->razor_id) && !empty($request->razor_id))
                $paidItem->razor_id = $request->razor_id;

            if (isset($request->purchased_id) && !empty($request->purchased_id))
                $paidItem->purchased_id = $request->purchased_id;

            if (isset($request->status))
                $paidItem->status = $request->status;
            else
                $paidItem->status = 1; // because of from adminpanel. Therefore, auto approve

            if (isset($request->added_user_id) && !empty($request->added_user_id)) {
                $paidItem->added_user_id = $request->added_user_id;
            } else if (isset($request->login_user_id) && !empty($request->login_user_id)) {
                $paidItem->added_user_id = $request->login_user_id;
            } else {
                $paidItem->added_user_id = $this->itemService->getItem($request->item_id)->added_user_id;
            }

            $paidItem->save();

            // $data = new \stdClass();
            // $data->id = $request->item_id;
            // $data->is_paid = (int)$paidItem->status;
            // $this->itemService->updateCoreFieldValues($data);
            $this->updateItemPaidStatus($request->item_id, (int)$paidItem->status);

            DB::commit();
        } catch (\Throwable $e) {
            // return $e->getMessage();
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $paidItem;
    }

    public function update($id, $request)
    {
        DB::beginTransaction();
        try {
            $paidItem = $this->getPaidItemHistory($id);

            if (isset($request->item_id) && !empty($request->item_id))
                $paidItem->item_id = $request->item_id;

            if (isset($request->date_range) && !empty($request->date_range)) {
                $date_range = $request->date_range;

                if ($date_range) {
                    $start_date = new Carbon($date_range[0]);

                    $paidItem->start_date = $start_date;
                    $paidItem->start_timestamp = $start_date->timestamp;

                    if ($date_range[1]) {
                        $end_date = new Carbon($date_range[1]);
                        $paidItem->end_date = $end_date;
                        $paidItem->end_timestamp = $end_date->timestamp;
                    } else {
                        $paidItem->end_date = $start_date;
                        $paidItem->end_timestamp = $start_date->timestamp;
                    }
                    // $paidItem->promoted_days = $paidItem->start_date - $paidItem->end_date;
                    $paidItem->promoted_days = ($paidItem->start_date)->diffInDays($paidItem->end_date);
                }
            }

            if (isset($request->start_date) && !empty($request->start_date) && isset($request->start_timestamp) && !empty($request->start_timestamp)) {
                $paidItem->start_date = Carbon::parse($request->start_date)->format('Y-m-d h:m:s');
                $paidItem->start_timestamp = $request->start_timestamp;
            } else if (isset($request->start_date) && !empty($request->start_date)) {
                $paidItem->start_date = $request->start_date;
                $paidItem->start_timestamp = strtotime($request->start_date);
            }

            if (isset($request->how_many_day) && !empty($request->how_many_day)) {
                $start_date = date('Y-m-d H:i:s', $paidItem->start_timestamp);
                $end_date = Carbon::create($start_date)->addDays((int)$request->how_many_day);
                $paidItem->end_date = $end_date;
                $paidItem->end_timestamp = strtotime($end_date);
                $paidItem->promoted_days = $request->how_many_day;
            }

            if (isset($request->product_id) && !empty($request->product_id)) {
                $coreKeys = CoreKey::where('name', $request->product_id)->pluck(CoreKey::coreKeysId);
                $paymentAttributes = PaymentAttribute::whereIn(PaymentAttribute::coreKeysId, $coreKeys)->where([
                    PaymentAttribute::paymentId => Constants::promotionInAppPurchasePaymentId,
                    PaymentAttribute::attributeKey => 'days'
                ])->first();
                if ($paymentAttributes) {
                    $start_date = date('Y-m-d H:i:s', $paidItem->start_timestamp);
                    $end_date = Carbon::create($start_date)->addDays((int)$paymentAttributes->attribute_value);
                    $paidItem->end_date = $end_date;
                    $paidItem->end_timestamp = strtotime($end_date);
                    $paidItem->promoted_days = $paymentAttributes->attribute_value;
                } else {
                    return responseMsgApi(__('itemPromotion__api_no_data_product_id', [], $request->language_symbol), $this->noContentStatusCode);
                }
            }
            if (isset($request->how_many_day) && !empty($request->how_many_day) && isset($request->product_id) && !empty($request->product_id)) {
                $coreKeys = CoreKey::where('name', $request->product_id)->pluck(CoreKey::coreKeysId);
                $paymentAttributes = PaymentAttribute::whereIn(PaymentAttribute::coreKeysId, $coreKeys)->where([
                    PaymentAttribute::paymentId => Constants::promotionInAppPurchasePaymentId,
                    PaymentAttribute::attributeKey => 'days'
                ])->first();
                if ($paymentAttributes) {
                    $paidItem->promoted_days = $paymentAttributes->attribute_value;
                } else {
                    $paidItem->promoted_days = $request->how_many_day;
                    return responseMsgApi(__('itemPromotion__api_no_data_product_id', [], $request->language_symbol), $this->noContentStatusCode);
                }
            }

            if (!(isset($request->how_many_day)) && isset($request->end_date) && !empty($request->end_date)) {
                $paidItem->end_date = $request->end_date;
                $paidItem->end_timestamp = strtotime($request->end_date);
                $paidItem->promoted_days = $paidItem->start_date - $paidItem->end_date;
            }

            if (isset($request->amount) && !empty($request->amount))
                $paidItem->amount = $request->amount;
            else
                $paidItem->amount = '0.0';

            if (isset($request->payment_method) && !empty($request->payment_method))
                $paidItem->payment_method = $request->payment_method;
            else
                $paidItem->payment_method = 'NA';

            if (isset($request->razor_id) && !empty($request->razor_id))
                $paidItem->razor_id = $request->razor_id;

            if (isset($request->purchased_id) && !empty($request->purchased_id))
                $paidItem->purchased_id = $request->purchased_id;

            if (isset($request->status))
                $paidItem->status = $request->status;
            else
                $paidItem->status = '0';

            if (isset($request->payment_status) && !empty($request->payment_status))
                $paidItem->status = $request->payment_status;
            else
                $paidItem->status = '0';

            if (isset($request->updated_user_id) && !empty($request->updated_user_id)) {
                $paidItem->updated_user_id = $request->updated_user_id;
            } else if (isset($request->login_user_id) && !empty($request->login_user_id)) {
                $paidItem->added_user_id = $request->login_user_id;
            } else {
                $paidItem->updated_user_id = Auth::user()->id;
            }

            $paidItem->update();

            if (isset($request->payment_status)) {
                $item = $this->itemService->getItem($paidItem->item_id);
                $item->is_paid = (int)$request->payment_status;
                $this->itemService->updateCoreFieldValues($item);
            }

            DB::commit();
            return $paidItem;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function updateItemPaidStatus($id, $status)
    {
        $item = $this->itemService->getItem($id);
        $item->is_paid = $status;
        $item->update();
    }

    public function deleteAllBy($conds = null)
    {
        DB::beginTransaction();
        try {
            PaidItemHistory::when($conds, function ($query, $conds) {
                $query->where($conds);
            })->delete();

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }

    public function searching($query, $conds)
    {

        $query->join($this->itemTableName, $this->itemTableName . '.' . $this->itemIdCol, '=', $this->paidItmTableName . '.' . $this->paidItmItemIdCol);

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];

            $query->where(function ($query) use ($search) {
                $query->where($this->itemTableName . '.' . $this->itemTitleCol, 'like', '%' . $search . '%')
                    ->orWhere($this->paidItmTableName . '.' . $this->paidItmAmountCol, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['category_id']) && $conds['category_id']) {
            $category_filter = $conds['category_id'];
            $query->where($this->itemTableName . '.' . $this->itemCategoryIdCol, $category_filter);
        }

        if (isset($conds['subcategory_id']) && $conds['subcategory_id']) {
            $sub_cat_filter = $conds['subcategory_id'];
            // $query->whereHas('subcategory', function($q) use($sub_cat_filter){
            $query->where($this->itemTableName . '.' . $this->itemSubCategoryIdCol, $sub_cat_filter);
            // });
        }

        if (isset($conds['item_id']) && $conds['item_id']) {
            $query->where($this->paidItmTableName . '.' . $this->paidItmItemIdCol, $conds['item_id']);
        }

        if (isset($conds['start_date']) && $conds['start_date']) {
            $query->where($this->paidItmTableName . '.' . $this->paidItmStartDateCol, $conds['start_date']);
        }

        if (isset($conds['end_date']) && $conds['end_date']) {
            $query->where($this->paidItmTableName . '.' . $this->paidItmEndDateCol, $conds['end_date']);
        }

        if (isset($conds['payment_method']) && $conds['payment_method']) {
            $query->where($this->paidItmTableName . '.' . $this->paidItmPaymentMethodCol, $conds['payment_method']);
        }

        if (isset($conds['amount']) && $conds['amount']) {
            $query->where($this->paidItmTableName . '.' . $this->paidItmAmountCol, $conds['amount']);
        }

        if (isset($conds['promoted_days']) && $conds['promoted_days']) {
            $query->where($this->paidItmTableName . '.' . $this->paidItmPromotedDaysCol, $conds['promoted_days']);
        }

        if (isset($conds['status']) && $conds['status']) {
            $query->where($this->paidItmTableName . '.' . $this->paidItmStatusCol, $conds['status']);
        }

        if (isset($conds['status_filter']) && $conds['status_filter']) {
            $today = Carbon::now();
            if ($conds['status_filter'] == 1) {
                $query->where($this->paidItmStartTimeStamp, '<=', strtotime($today))->where($this->paidItmEndTimeStamp, '>=', strtotime($today));
            }
            if ($conds['status_filter'] == 2) {
                $query->where($this->paidItmStartTimeStamp, '<', strtotime($today))->where($this->paidItmEndTimeStamp, '<', strtotime($today));
            }
            if ($conds['status_filter'] == 3) {
                $query->where($this->paidItmStartTimeStamp, '>', strtotime($today))->where($this->paidItmEndTimeStamp, '>', strtotime($today));
            }
        }

        if (isset($conds['selected_date']) && $conds['selected_date']) {
            $date_filter = $conds['selected_date'];
            $new_date = date('Y-m-d', strtotime($date_filter));
            $query->where($this->paidItmStartTimeStamp, '<=', strtotime($new_date))->where($this->paidItmEndTimeStamp, '>=', strtotime($new_date));
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas('added_date', function ($q) use ($date_filter) {
                $q->where($this->paidItmAddedDateCol, $date_filter);
            });
        }

        if (isset($conds['added_date_range']) && $conds['added_date_range']) {
            $added_date_filter = $conds['added_date_range'];

            if ($added_date_filter[1] == '') {
                $added_date_filter[1] = Carbon::now();
            }
            $query->whereBetween($this->paidItmTableName . '.' . $this->paidItmAddedDateCol, $added_date_filter);
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where($this->paidItmTableName . '.' . $this->paidItmAddedUserIdCol, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy('id', $conds['order_type']);
            } elseif ($conds['order_by'] == 'item_id@@title') {
                $query->orderBy('itm_title', $conds['order_type']);
            } elseif ($conds['order_by'] == 'category') {
                $query->orderBy('cat_name', $conds['order_type']);
            } elseif ($conds['order_by'] == 'subcategory') {
                $query->orderBy('sub_cat_name', $conds['order_type']);
            } else {
                $query->orderBy($this->paidItmTableName . '.' . $conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getPaidItemHistories($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $paidItems = PaidItemHistory::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {

                if ($sort == 'item_id@@title') {
                    $q->join($this->itemTableName . ' as titleItems', 'titleItems.' . $this->itemIdCol, '=', $this->paidItmTableName . '.' . $this->paidItmItemIdCol);
                    $q->select('titleItems.' . $this->itemTitleCol . ' as itm_title', $this->paidItmTableName . '.*');
                } else if ($sort == 'category') {
                    $q->join($this->itemTableName . ' as catItems', 'catItems.' . $this->catIdCol, '=', $this->paidItmTableName . '.' . $this->paidItmItemIdCol);
                    $q->join($this->catTableName, $this->catTableName . '.' . $this->catIdCol, '=', 'catItems.category_id');
                    $q->select($this->catTableName . '.' . $this->catNameCol . ' as cat_name', $this->paidItmTableName . '.*');
                } else if ($sort == 'subcategory') {
                    $q->join($this->itemTableName . ' as subcatItems', 'subcatItems.id', '=', $this->paidItmTableName . '.' . $this->paidItmItemIdCol);
                    $q->join($this->subCatTableName, $this->subCatTableName . '.' . $this->subCatIdCol, '=', 'subcatItems.subcategory_id');
                    $q->select($this->subCatTableName . '.' . $this->subCatNameCol . ' as sub_cat_name', $this->paidItmTableName . '.*');
                } else {
                    $q->select($this->paidItmTableName . '.*');
                }
            })
            ->when(!isset($conds['order_by']), function ($query) {
                $query->select($this->paidItmTableName . '.*');
            })
            ->when($status, function ($q, $status) {
                $q->where($this->paidItmStatusCol, $status);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->when(empty($sort), function ($query, $conds) {
                $query->orderBy($this->paidItmTableName . '.' . $this->paidItmAddedDateCol, 'desc');
            });
        if ($pagPerPage) {
            $paidItems = $paidItems->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } else {
            $paidItems = $paidItems->get();
        }
        return $paidItems;
    }

    public function getRevenueFromPromotion($relation = null, $status = null, $limit = null, $offset = null, $conds = null, $noPagination = null, $pagPerPage = null)
    {
        $sort = '';
        if (isset($conds['order_by'])) {
            $sort = $conds['order_by'];
        }

        $paidItems = PaidItemHistory::when($relation, function ($q, $relation) {
            $q->with($relation);
        })
            ->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
                if ($sort == 'title') {
                    $q->join($this->itemTableName . ' as titleItems', 'titleItems.' . $this->itemIdCol, '=', $this->paidItmTableName . '.' . $this->paidItmItemIdCol);
                    $q->select('titleItems.title as itm_title', $this->paidItmTableName . '.*');
                } else if ($sort == 'category') {
                    $q->join($this->itemTableName . ' as catItems', 'catItems.' . $this->itemIdCol, '=', $this->paidItmTableName . '.' . $this->paidItmItemIdCol);
                    $q->join($this->catTableName, $this->catTableName . '.' . $this->catIdCol, '=', 'catItems.category_id');
                    $q->select($this->catTableName . '.' . $this->catNameCol . ' as cat_name', $this->paidItmTableName . '.*');
                } else if ($sort == 'subcategory') {
                    $q->join($this->itemTableName . ' as subcatItems', 'subcatItems.' . $this->itemIdCol, '=', $this->paidItmTableName . '.' . $this->paidItmItemIdCol);
                    $q->join($this->subCatTableName, $this->subCatTableName . '.' . $this->subCatIdCol, '=', 'subcatItems.subcategory_id');
                    $q->select($this->subCatTableName . '.' . $this->subCatNameCol . ' as sub_cat_name', $this->paidItmTableName . '.*');
                } else {
                    $q->select($this->paidItmTableName . '.*');
                }
            })
            ->when(!isset($conds['order_by']), function ($query) {
                $query->select($this->paidItmTableName . '.*');
            })
            ->selectRaw('SUM(amount) as total, DATE(psx_paid_item_histories.added_date) as date')
            ->groupBy('date')
            ->when($status, function ($q, $status) {
                $q->where($this->paidItmStatusCol, $status);
            })
            ->when($limit, function ($query, $limit) {
                $query->limit($limit);
            })
            ->when($offset, function ($query, $offset) {
                $query->offset($offset);
            })
            ->when($conds, function ($query, $conds) {
                $query = $this->searching($query, $conds);
            })
            ->latest($this->paidItmTableName . '.' . $this->paidItmAddedDateCol);
        if ($pagPerPage) {
            $paidItems = $paidItems->paginate($pagPerPage)->onEachSide(1)->withQueryString();
        } elseif ($noPagination) {
            $paidItems = $paidItems->get();
        } else {
            $paidItems = $paidItems->get();
        }
        return $paidItems;
    }

    public function getPaidItemHistory($id, $relation = null)
    {
        $paidItem = PaidItemHistory::where($this->paidItmIdCol, $id)->when($relation, function ($q, $relation) {
            $q->with($relation);
        })->first();
        return $paidItem;
    }

    public function makePublishOrUnpublish($id)
    {
        $paidItem = PaidItemHistory::find($id);
        if ($paidItem->status == $this->publish) {
            $paidItem->status = $this->unPublish;
        } else {
            $paidItem->status = $this->publish;
        }
        $paidItem->updated_user_id = Auth::user()->id;
        $paidItem->update();
    }

    public function paidItemReportCsvExport()
    {
        $filename = newFileNameForExport($this->csvFileName);
        return (new PromotionReportExport())->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    //--------------
    public function index($request)
    {

        // dd("here");
        $code = $this->code;

        $checkPermission = $this->checkPermission($this->viewAnyAbility, PaidItemHistory::class, "admin.index");

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['status_filter'] = $request->input('status_filter') == 'all' ? null  : $request->status_filter;
        $conds['payment_method'] = $request->input('payment_method') == 'all' ? null  : $request->payment_method;
        $conds['selected_date'] = $request->input('date_filter') == 'all' ? null  : $request->date_filter;

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        if ($request->payment_method_filter == 1) {
            $conds['payment_method'] = $this->paypalPaymentMethod;
        }
        if ($request->payment_method_filter == 2) {
            $conds['payment_method'] = $this->stripePaymentMethod;
        }
        if ($request->payment_method_filter == 3) {
            $conds['payment_method'] = $this->razorPaymentMethod;
        }
        if ($request->payment_method_filter == 4) {
            $conds['payment_method'] = $this->paystackPaymentMethod;
        }
        if ($request->payment_method_filter == 5) {
            $conds['payment_method'] = $this->offlinePaymentMethod;
        }
        if ($request->payment_method_filter == 6) {
            $conds['payment_method'] = Constants::iapPaymentMethod;
        }
        if ($request->payment_method_filter == 7) {
            $conds['payment_method'] = Constants::flutterwavePaymentMethod;
        }

        $relations = ['item'];
        $paidItems = PaidItemWithKeyResource::collection($this->getPaidItemHistories($relations, null, null, null, $conds, false, $row));
        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showProductCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing item arr object with new format
        $changedProductObj = $paidItems;
        $categories = $this->categoryService->getAll(status: Constants::publish);
        $subcategories = $this->subcategoryService->getAll(null, $this->publish);

        $payment_methods = [];

        $paypal = new \stdClass();
        $paypal->id = 1;
        $paypal->name = $this->paypalPaymentMethod;
        array_push($payment_methods, $paypal);

        $stripe = new \stdClass();
        $stripe->id = 2;
        $stripe->name = $this->stripePaymentMethod;
        array_push($payment_methods, $stripe);

        $razor = new \stdClass();
        $razor->id = 3;
        $razor->name = $this->razorPaymentMethod;
        array_push($payment_methods, $razor);

        $paystack = new \stdClass();
        $paystack->id = 4;
        $paystack->name = $this->paystackPaymentMethod;
        array_push($payment_methods, $paystack);

        $offline = new \stdClass();
        $offline->id = 5;
        $offline->name = $this->offlinePaymentMethod;
        array_push($payment_methods, $offline);

        $purchase = new \stdClass();
        $purchase->id = 6;
        $purchase->name = $this->iapPaymentMethod;
        array_push($payment_methods, $purchase);

        $purchase = new \stdClass();
        $purchase->id = 7;
        $purchase->name = Constants::flutterwavePaymentMethod;
        array_push($payment_methods, $purchase);


        $statusList = [];

        $completed = new \stdClass();
        $completed->id = 2;
        $completed->name = __('core__be_compleated_status');
        array_push($statusList, $completed);

        $ongoing = new \stdClass();
        $ongoing->id = 1;
        $ongoing->name = __('core__be_ongoing_status');
        array_push($statusList, $ongoing);

        $notYetStart = new \stdClass();
        $notYetStart->id = 3;
        $notYetStart->name = __('core__be_not_yet_start_status');
        array_push($statusList, $notYetStart);

        if ($conds['order_by']) {
            $dataArr = [
                'checkPermission' => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'paidItems' => $changedProductObj,
                'statusList' => $statusList,
                'payment_methods' => $payment_methods,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedStatus' => $conds['status_filter'],
                'selectedPaymentMethod' => $conds['payment_method'],
                'selectedDate' => $conds['selected_date'],
            ];
        } else {
            $dataArr = [
                'checkPermission' => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'categories' => $categories,
                'subcategories' => $subcategories,
                'paidItems' => $changedProductObj,
                'payment_methods' => $payment_methods,
                'statusList' => $statusList,
                'search' => $conds['searchterm'],
                'selectedStatus' => $conds['status_filter'],
                'selectedPaymentMethod' => $conds['payment_method'],
                'selectedDate' => $conds['selected_date'],
            ];
        }

        return $dataArr;
    }


    public function edit($id)
    {
        $relations = ['item'];
        $checkPermission = $this->checkPermission($this->viewAnyAbility, PaidItemHistory::class, "admin.index");
        $paid_item = $this->getPaidItemHistory($id, $relations);

        $dataArr = [
            'checkPermission' => $checkPermission,
            "paid_item" => $paid_item,
        ];
        return $dataArr;
    }

    public function destroy($id)
    {
        $paidItem = $this->getPaidItemHistory($id, ['item']);
        $title = $paidItem->item->title;
        $paidItem->delete();

        $dataArr = [
            'msg' => __('core__be_delete_success', ['attribute' => $title]),
            'flag' => $this->dangerFlag,
        ];

        return $dataArr;
    }

    public function offlinePaidItemIndex($request)
    {


        $code = $this->code;

        // Search and filter
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['category_id'] = $request->input('category_filter') == 'all' ? null  : $request->category_filter;
        $conds['subcategory_id'] = $request->input('sub_category_filter') == "all" ? null : $request->sub_category_filter;
        $conds['status'] = $request->input('payment_status_filter') == "all" ? null : $request->payment_status_filter;

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $conds['payment_method'] = $this->offlinePaymentMethod;
        $relations = ['item'];
        $paidItems = OfflinePaidItemWithKeyResource::collection($this->getPaidItemHistories($relations, null, null, null, $conds, false, $row));
        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showProductCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // changing item arr object with new format
        $changedProductObj = $paidItems;
        // $categories = $this->categoryService->getCategories(null, $this->publish);
        // $subcategories = $this->subcategoryService->getAll(null, $this->publish);
        $selected_category = $this->categoryService->get($conds['category_id']);
        $selected_Subcategory = Subcategory::where('id', $conds['subcategory_id'])->first();

        if ($conds['order_by']) {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'paidItems' => $changedProductObj,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
                'selectedCategory' => $selected_category ? $selected_category : '',
                'selectedSubcategory' => $selected_Subcategory ? $selected_Subcategory : '',
            ];
        } else {
            $dataArr = [
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'paidItems' => $changedProductObj,
                'search' => $conds['searchterm'],
                'selectedCategory' => $selected_category ? $selected_category : '',
                'selectedSubcategory' => $selected_Subcategory ? $selected_Subcategory : '',
            ];
        }

        return $dataArr;
    }

    public function offlinePaidItemEdit($id)
    {
        $relations = ['item'];
        $paid_item = $this->getPaidItemHistory($id, $relations);

        $types = [
            [
                'id' => '0',
                'name' => __('core__be_waiting_for_approval'),
            ],
            [
                'id' => '1',
                'name' => __('core__be_approved'),
            ],
            [
                'id' => '2',
                'name' => __('core__be_rejected'),
            ]
        ];

        $dataArr = [
            "paid_item" => $paid_item,
            "types" => $types,
        ];
        return $dataArr;
    }

    // for api
    public function indexFromApi($request)
    {
        $offset = $request->offset;
        $limit = $request->limit;

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('itemPromotion__api_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {
            $paidItemApiRelation = $this->paidItemApiRelation;
            $conds['added_user_id'] = $request->login_user_id;
            $paidItems = PaidItemHistoryApiResource::collection($this->getPaidItemHistories($paidItemApiRelation, null, $limit, $offset, $conds, true));

            if ($offset > 0 && $paidItems->isEmpty()) {
                // no paginate data
                $data = [];
                return responseDataApi($data);
            } else if ($paidItems->isEmpty()) {
                // no data db
                return responseMsgApi(__('itemPromotion__api_no_data'), $this->noContentStatusCode, $this->successStatus);
            } else {
                return responseDataApi($paidItems);
            }
        }
    }

    public function storeFromApi($request, $item)
    {
        // dd($request->all());
        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        $checkOwnerShip = checkOwnerShip($item, $request->login_user_id);

        /// check permission end

        if (empty($userAccessApiToken) || !$checkOwnerShip) {
            $msg = __('itemPromotion__api_create_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {

            $paypal_result = 0;
            $stripe_result = 0;
            $razor_result = 0;
            $paystack_result = 0;
            $in_app_purchase_result = 0;
            $offline_result = 0;
            $flutterwave_result = 0;
            $finished_status = 0;

            $currency = AvailableCurrency::where('is_default', '1')->first()->currency_short_form;

            if ($request->payment_method == constants::paypalPaymentMethod) {
                //User using Paypal to submit the transaction
                $payment_method = $this->paypalPaymentMethod;
                $gateway = new \Braintree\Gateway([
                    'environment' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalEnvironment)->value),
                    'merchantId' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalMerchantId)->value),
                    'publicKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalPublicKey)->value),
                    'privateKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalPrivateKey)->value)
                ]);

                $result = $gateway->transaction()->sale([
                    'amount'                => $request->amount,
                    'paymentMethodNonce' => $request->payment_method_nonce,
                    'options' => [
                        'submitForSettlement' => True
                    ]
                ]);

                if ($result->success == 1) {
                    $paypal_result = $result->success;
                } else {
                    return responseMsgApi($result->message, $this->internalServerErrorStatusCode);
                }
            } else if ($request->payment_method == constants::stripePaymentMethod) {
                $payment_method = $this->stripePaymentMethod;
                //User using Stripe to submit the transaction
                $payment_method_nonce = explode('_', $request->payment_method_nonce);

                if ($payment_method_nonce[0] == 'tok') {

                    try {

                        # set stripe test key
                        \Stripe\Stripe::setApiKey(trim($this->paymentSettingService->getPaymentInfo(null, null, $this->stripeSecretKey)->value));

                        $charge = \Stripe\Charge::create(array(
                            "amount"       => $request->amount * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                            "currency"    => trim($currency),
                            "source"      => $request->payment_method_nonce,
                            "description" => __('itemPromotion__api_order_desc', [], $request->language_symbol) . ' '
                        ));

                        if ($charge->status == "succeeded") {
                            $stripe_result = 1;
                        } else {
                            return responseMsgApi($charge, $this->internalServerErrorStatusCode);
                        }
                    } catch (\Throwable $e) {

                        return responseMsgApi($e->getMessage(), $this->internalServerErrorStatusCode);
                    }
                } else if ($payment_method_nonce[0] == 'pm') {
                    try {
                        \Stripe\Stripe::setApiKey(trim($this->paymentSettingService->getPaymentInfo(null, null, $this->stripeSecretKey)->value));

                        $paymentIntent = \Stripe\PaymentIntent::create([
                            'payment_method' => $request->payment_method_nonce,
                            'amount' => $request->amount * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                            'currency' => trim($currency),
                            'confirmation_method' => 'manual',
                            'confirm' => true,
                            'payment_method_types' => ['card']
                        ]);
                        if ($paymentIntent->status == "succeeded") {
                            $stripe_result = 1;
                        } else {
                            return responseMsgApi($paymentIntent, $this->internalServerErrorStatusCode);
                        }
                    } catch (\Throwable $e) {
                        return responseMsgApi($e->getMessage(), $this->internalServerErrorStatusCode);
                    }
                }
            } else if ($request->payment_method == constants::razorPaymentMethod) {

                //User Using Razor
                $payment_method = $this->razorPaymentMethod;
                $razor_result = 1;
            } else if ($request->payment_method == constants::paystackPaymentMethod) {

                //User Using Paystack
                $payment_method = $this->paystackPaymentMethod;
                $paystack_result = 1;
            } else if ($request->payment_method == constants::offlinePaymentMethod) {

                //User Using Offline
                $payment_method = $this->offlinePaymentMethod;
                $offline_result = 1;
            } else if ($request->payment_method == constants::flutterwavePaymentMethod) {

                //User Using Offline
                $payment_method = constants::flutterwavePaymentMethod;
                $flutterwave_result = 1;
            } else if ($request->payment_method == constants::iapPaymentMethod) {

                //User Using COD
                $payment_method = $this->iapPaymentMethod;
                $in_app_purchase_result = 1;
            }
            if ($paypal_result == 1 || $stripe_result == 1  || $razor_result == 1 || $paystack_result == 1 || $flutterwave_result == 1 || $in_app_purchase_result == 1) {

                $conds['item_id'] = $request->item_id;
                $paid_items = $this->getPaidItemHistories(null, null, null, null, $conds, true);

                if (count($paid_items) == 0) {
                    $request->status = 1;
                    $request->is_paid = 1;
                    $paidItem = $this->store($request);
                    // return $paidItem;
                } else {
                    foreach ($paid_items as $paid) {
                        $today = Carbon::now();
                        $start_date = date('Y-m-d H:i:s', $paid->start_timestamp);
                        $end_date = date('Y-m-d H:i:s', $paid->end_timestamp);

                        if ($start_date < $today && $end_date < $today) {
                            $finished_status = 1;
                        }
                    }
                    if ($finished_status == 1) {
                        $request->status = 1;
                        $request->is_paid = 1;
                        $paidItem = $this->store($request);
                    } else {
                        return responseMsgApi(__('itemPromotion__api_err_paid_item_history', [], $request->language_symbol), $this->badRequestStatusCode);
                    }
                }
            } else if ($offline_result == 1) {
                // dd("here");
                $request->status = 0;


                $item_data = new \stdClass();
                $item_data->id = $request->item_id;
                $item_data->status = 1;
                $item_data->is_paid = 0;

                // $this->itemService->updateCoreFieldValues($item_data);

                $conds['item_id'] = $request->item_id;
                $paid_items = $this->getPaidItemHistories(null, null, null, null, $conds, true);

                if (count($paid_items) == 0) {
                    // dd("here 0");
                    // dd($request);
                    $paidItem = $this->store($request);
                    // dd($paidItem);
                } else {

                    // dd($paid_items);
                    foreach ($paid_items as $paid) {

                        $today = Carbon::now();

                        $start_date = date('Y-m-d H:i:s', $paid->start_timestamp);
                        $end_date = date('Y-m-d H:i:s', $paid->end_timestamp);

                        if ($start_date < $today && $end_date < $today) {
                            $finished_status = 1;
                        }
                    }
                    if ($finished_status == 1) {
                        // dd($finished_status);
                        $paidItem = $this->store($request);
                    } else {
                        return responseMsgApi(__('itemPromotion__api_err_paid_item_history', [], $request->language_symbol), $this->badRequestStatusCode);
                    }
                }
            } else {

                return responseMsgApi(__('itemPromotion__api_err_paid_item_history', [], $request->language_symbol), $this->badRequestStatusCode);
            }

            $paidItemApiRelation = $this->paidItemApiRelation;
            $savedPaidItem = new PaidItemHistoryApiResource($this->getPaidItemHistory($paidItem->id, $paidItemApiRelation));

            PsCache::clear(ItemCache::BASE);

            return responseDataApi($savedPaidItem, $this->createdStatusCode, $this->successStatus);
        }
    }

    public function destroyFromApi($request)
    {

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('core__api_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {
            //delete in search_histories table
            $ids = $request->ids;
            PaidItemHistory::destroy($ids);

            if (empty($ids)) {
                return  ['success' =>  __('core__api_record_not_found', [], $request->language_symbol), 'status' =>  $this->noContentStatusCode];
            } else {
                return  ['success' =>  __('core__api_paid_item_delete_success', [], $request->language_symbol), 'status' =>  $this->okStatusCode];
            }
        }
    }

    public function getPurchasedHistoryFromApi($request)
    {
        $offset = $request->offset;
        $limit = $request->limit;

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('itemPromotion__api_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {
            $paidItemApiRelation = $this->paidItemApiRelation;
            $conds['added_user_id'] = $request->login_user_id;
            $paidItems = PurchasedItemHistoryApiResource::collection($this->getPaidItemHistories($paidItemApiRelation, null, $limit, $offset, $conds));

            if ($offset > 0 && $paidItems->isEmpty()) {
                // no paginate data
                $data = [];
                return responseDataApi($data);
            } else if ($paidItems->isEmpty()) {
                // no data db
                return responseMsgApi(__('itemPromotion__api_no_data', [], $request->language_symbol), $this->noContentStatusCode, $this->successStatus);
            } else {
                return responseDataApi($paidItems);
            }
        }
    }


    public function takingForColumnAndFilterOption()
    {

        // for table
        $hideShowCoreFieldForColumnArr = [];
        $hideShowCustomFieldForColumnArr = [];

        $showProductCols = [];

        // for eye
        $hideShowFieldForColumnFilterArr = [];

        // for control
        $controlFieldArr = [];

        $controlFieldObj = $this->takingForColumnProps(__('core__be_action_label'), "action", "Action", false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        $code = $this->code;
        $hiddenForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->hide, $code);
        $shownForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->show, $code);
        $hideShowForCoreAndCustomFields = $shownForCoreAndCustomField->merge($hiddenForCoreAndCustomField);

        foreach ($hideShowForCoreAndCustomFields as $showFields) {
            if ($showFields->coreField !== null) {

                $label = $showFields->coreField->label_name;

                if (str_contains($showFields->coreField->field_name, "@@")) {
                    $afterNeedleField = strstr($showFields->coreField->field_name, "@@");
                    $afterNeedleField = str_replace("@@", "", $afterNeedleField);
                    $beforeNeedleField = strstr($showFields->coreField->field_name, "@@", 'true');
                    $field = $beforeNeedleField . "@@" . $afterNeedleField;
                } else {
                    $field = $showFields->coreField->field_name;
                }
                // $field = $showFields->coreField->field_name;
                $colName = $showFields->coreField->field_name;
                $type = $showFields->coreField->data_type;
                $isShowSorting = $showFields->coreField->is_show_sorting;
                $ordering = $showFields->coreField->ordering;

                $cols = $colName;
                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->coreField->module_name;
                $keyId = $showFields->coreField->id;

                $coreFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $coreFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $coreFieldObjForColumnFilter);
                array_push($hideShowCoreFieldForColumnArr, $coreFieldObjForColumnsProps);
                array_push($showProductCols, $cols);
            }
            if ($showFields->customizeField !== null) {

                $label = $showFields->customizeField->name;
                $uiHaveAttribute = [$this->dropDownUi, $this->radioUi];
                if (in_array($showFields->customizeField->ui_type_id, $uiHaveAttribute)) {
                    $field = $showFields->customizeField->core_keys_id . "@@name";
                } else {
                    $field = $showFields->customizeField->core_keys_id;
                }
                $type = $showFields->customizeField->data_type;
                $isShowSorting = $showFields->customizeField->is_show_sorting;
                $ordering = $showFields->customizeField->ordering;

                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->customizeField->module_name;
                $keyId = $showFields->customizeField->core_keys_id;

                $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
                array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
            }
        }

        // for columns props
        $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
        $sortedColumnArr = columnOrdering("ordering", $showCoreAndCustomFieldArr);

        // for eye
        $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

        $dataArr = [
            "arrForColumnProps" => $sortedColumnArr,
            "arrForColumnFilterProps" => $hideShowCoreAndCustomFieldArr,
            "showCoreField" => $showProductCols,
        ];
        return $dataArr;
    }

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;

        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
            $hideShowCoreAndCustomFieldObj->action = __('core__be_action_label');
        }

        return $hideShowCoreAndCustomFieldObj;
    }

    public function takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->id = $id;
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->key = $field;
        $hideShowCoreAndCustomFieldObj->hidden = $hidden;
        $hideShowCoreAndCustomFieldObj->module_name = $moduleName;
        $hideShowCoreAndCustomFieldObj->key_id = $keyId;

        return $hideShowCoreAndCustomFieldObj;
    }

    public function hiddenShownForCoreAndCustomField($isShown, $code)
    {
        $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
            $q->where($this->customUiIsDelCol, $this->unDelete)->where($this->customUiIsDelCol, $this->unDelete);
        }, 'coreField' => function ($q) {
            $q->where($this->coreFieldFilterModuleNameCol, $this->code);
        }])
            ->where($this->coreFieldFilterModuleNameCol, $code)
            ->where($this->screenDisplayUiIsShowCol, $isShown)
            ->get();

        return $hiddenShownForFields;
    }

    public function tokenFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)) {
            $msg = __('payment__api_no_permission', [], $request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        } else {
            $gateway = new \Braintree\Gateway([
                'environment' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalEnvironment)->value),
                'merchantId' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalMerchantId)->value),
                'publicKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalPublicKey)->value),
                'privateKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalPrivateKey)->value)
            ]);

            $clientToken = $gateway->clientToken()->generate();

            if ($clientToken != '') {
                return responseMsgApi($clientToken, $this->okStatusCode, $this->successStatus);
            } else {
                // no data db
                return responseMsgApi(__('token_not_found', [], $request->language_symbol), $this->noContentStatusCode, $this->successStatus);
            }
        }
    }

    public function verifyTransaction($request)
    {
        $transactionId = $request->transaction_id;
        if (isset($request->vendor_id)) {
            $flutterwaveSecretKey = VendorPaymentInfo::where([VendorPaymentInfo::vendorId => $request->vendor_id, VendorPaymentInfo::coreKeysId => Constants::flutterwaveSecretKey])->first()?->value;
        } else {
            $flutterwaveSecretKey = trim($this->paymentSettingService->getPaymentInfo(null, null, Constants::flutterwaveSecretKey)->value);
        }
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transactionId/verify",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer $flutterwaveSecretKey"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        return $response;
    }
}
