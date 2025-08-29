<?php

namespace Modules\Core\Entities\Item;

use App\Models\PsModel;
use App\Models\User;
use App\Policies\PsPolicy;
use App\Policies\PsVendorPolicy;
use App\Traits\VendorAuthorizationTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Entities\User\UserBought;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Category\Category;
use Modules\Core\Entities\Category\Subcategory;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Favourite;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\Core\Entities\ItemInfo;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Entities\Location\LocationTownship;
use Modules\Core\Entities\Touch;
use Modules\Core\Entities\Financial\TransactionCount;
use Modules\Core\Entities\Vendor\Vendor;

class Item extends PsModel
{
    use HasFactory, VendorAuthorizationTrait;

    protected $fillable = [
        'id',
        'title',
        'category_id',
        'subcategory_id',
        'currency_id',
        'location_city_id',
        'location_township_id',
        'shop_id',
        'price',
        'phone',
        'original_price',
        'description',
        'search_tag',
        'dynamic_link',
        'lat',
        'lng',
        'status',
        'ordering',
        'is_available',
        'is_discount',
        'touch_count',
        'favourite_count',
        'overall_rating',
        'item_touch_count',
        'is_paid',
        'is_sold_out',
        'vendor_id',
        'added_date',
        'added_user_id',
        'updated_date',
        'updated_user_id',
        'updated_flag',
    ];

    protected $table = 'psx_items';

    const CREATED_AT = 'added_date';

    const UPDATED_AT = 'updated_date';

    const tableName = 'psx_items';

    const id = 'id';

    const title = 'title';

    const categoryId = 'category_id';

    const subCategoryId = 'subcategory_id';

    const itemCurrencyId = 'currency_id';

    const itemLocationId = 'location_city_id';

    const itemLocationTownshipId = 'location_township_id';

    const shopId = 'shop_id';

    const price = 'price';

    const description = 'description';

    const searchterm = 'search_tag';

    const status = 'status';

    const lat = 'lat';

    const lng = 'lng';

    const isAvailable = 'is_available';

    const isPaid = 'is_paid';

    const isSoldOut = 'is_sold_out';

    const isDiscount = 'is_discount';

    const favouriteCount = 'favourite_count';

    const touchCount = 'item_touch_count';

    const overallRating = 'overall_rating';

    const addedUserId = 'added_user_id';

    const addedDate = 'added_date';

    const updatedDate = 'updated_date';

    const percent = 'percent';

    const originalPrice = 'original_price';

    const userId = 'added_user_id';

    const vendorId = 'vendor_id';

    const dynamicLink = 'dynamic_link';

    public function __construct()
    {
        $this->vendorModule = Constants::vendorItemModule;
    }

    protected static function newFactory()
    {
        return \Modules\Core\Database\factories\ItemFactory::new();
    }

    public static function t($key)
    {
        return Item::tableName . '.' . $key;
    }

    public function city()
    {
        return $this->belongsTo(LocationCity::class, 'location_city_id');
    }

    public function township()
    {
        return $this->belongsTo(LocationTownship::class, 'location_township_id');
    }

    public function currency()
    {
        return $this->belongsTo(ItemCurrency::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'added_user_id');
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_user_id');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
        // ->select('id', 'name', 'ordering'); // For next Update
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id', 'id')->where('psx_vendors.status', Constants::vendorAcceptStatus)->with('logo');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id', 'id');
        // ->select('id', 'name', 'category_id', 'ordering'); // For Next Update
    }

    public function item_touch()
    {
        return $this->hasMany(Touch::class, 'type_id')->where('type_name', 'Item');
    }

    public function category_touch()
    {
        return $this->hasMany(Touch::class, 'type_id', 'category_id')->where('type_name', 'Category');
    }

    public function subcategory_touch()
    {
        return $this->hasMany(Touch::class, 'type_id', 'subcategory_id')->where('type_name', 'Subcategory');
    }

    public function cover()
    {
        return $this->hasMany(CoreImage::class, 'img_parent_id')->where('img_type', 'item')->orderBy('ordering', 'asc');
    }

    public function video()
    {
        return $this->hasMany(CoreImage::class, 'img_parent_id')->where('img_type', 'item-video');
    }

    public function icon()
    {
        return $this->hasMany(CoreImage::class, 'img_parent_id')->where('img_type', 'item-video-icon');
    }

    public function favourite()
    {
        return $this->hasMany(Favourite::class);
    }

    public function transaction()
    {
        return $this->hasMany(TransactionCount::class);
    }

    public function itemRelation()
    {
        return $this->hasMany(ItemInfo::class, 'item_id', 'id');
        // ->select('id', 'item_id', 'core_keys_id', 'ui_type_id', 'value'); // For next update
    }

    public function user_boughts()
    {
        return $this->hasMany(UserBought::class, 'item_id')->with('buyer');
    }


    ////////////////////////////////////////////////////////////////////
    /// Scopes
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Search & Filters
    //-------------------------------------------------------------------

    public function scopeSearchKeyword($query, $filterMap)
    {
        $key = 'keyword';
        if (array_key_exists($key, $filterMap)) {
            $keyword = $filterMap[$key] ?? '';

            $query->where(function ($query) use ($keyword) {
                $query->where(Item::t(Item::searchterm), 'like', "%$keyword%")
                    ->orWhere(Item::t(Item::title), 'like', "%$keyword%")
                    ->orWhere(Item::t(Item::description), 'like', "%$keyword%");
            });
        }

        return $query;
    }

    public function scopeSearchBuyerSellerByName($query, $filterMap)
    {
        $key = 'seller_buyer_name';
        if (array_key_exists($key, $filterMap)) {
            $name = $filterMap[$key] ?? '';

            $query->join(UserBought::tableName, UserBought::t(UserBought::itemId), '=', Item::t(Item::id))
                ->join(User::tableName . ' as buyers', 'buyers.' . User::id, '=', UserBought::tableName . '.' . UserBought::buyerUserId)
                ->join(User::tableName . ' as sellers', 'sellers.' . User::id, '=', UserBought::tableName . '.' . UserBought::sellerUserId)
                ->addSelect('sellers.name as seller_name', 'buyers.name as buyer_name')
                ->where(function ($query) use ($name) {
                    $query->where('sellers.name', 'like', "%$name%")
                        ->orWhere('buyers.name', 'like', "%$name%");
                });
        }

        return $query;
    }

    public function scopePaidItemTimeStampFilter($query, $filterMap)
    {
        $key = 'paid_item_histories_timestamp';
        if (array_key_exists($key, $filterMap)) {
            $timeStamp = $filterMap[$key] ?? '';

            $query->where(function ($query) use ($timeStamp) {
                $query->where(PaidItemHistory::t(PaidItemHistory::startTimestamp), '<=', $timeStamp)
                    ->where(PaidItemHistory::t(PaidItemHistory::endTimestamp), '>=', $timeStamp);
            });
        }

        return $query;
    }

    public function scopePaidItemFilterByDeletedAt($query, $filterMap)
    {
        $key = 'paid_item_histories_deleted_at';
        if (array_key_exists($key, $filterMap)) {
            $value = $filterMap[$key] ?? null;

            $query->where(function ($query) use ($value) {
                $query->where(PaidItemHistory::t(PaidItemHistory::deletedAt), $value);
            });
        }

        return $query;
    }

    public function scopeFilterByFields($query, $filterMap)
    {
        if (! empty($filterMap) && count($filterMap) > 0) {

            foreach ($filterMap as $key => $value) {

                if ($this->isValidColumn($key)) {
                    $value = $value ?? '';
                    if ($value || $value == 0) {
                        $query->where(Item::t($key), $value);
                    }
                }
            }
        }

        return $query;
    }

    public function scopeMinPriceFilter($query, $filterMap)
    {
        $key = 'min_price';
        if (array_key_exists($key, $filterMap)) {
            $minPrice = $filterMap[$key] ?? '';

            if ($minPrice) {
                $query->where(Item::t(Item::price), '>=', $minPrice);
            }
        }

        return $query;
    }

    public function scopeMaxPriceFilter($query, $filterMap)
    {
        $key = 'max_price';
        if (! empty($key) && array_key_exists($key, $filterMap)) {
            $maxPrice = $filterMap[$key] ?? '';

            if ($maxPrice) {
                $query->where(Item::t(Item::price), '<=', $maxPrice);
            }
        }

        return $query;
    }

    public function scopeMinAddedDateFilter($query, $filterMap)
    {
        $key = 'min_added_date';
        if (array_key_exists($key, $filterMap)) {
            $minAddedDate = $filterMap[$key] ?? '';

            if ($minAddedDate) {
                $query->where(Item::t(Item::addedDate), '>=', $minAddedDate);
            }
        }

        return $query;
    }

    public function scopeMaxAddedDateFilter($query, $filterMap)
    {
        $key = 'max_added_date';
        if (array_key_exists($key, $filterMap)) {
            $maxAddedDate = $filterMap[$key] ?? '';

            if ($maxAddedDate) {
                $query->where(Item::t(Item::addedDate), '<=', $maxAddedDate);
            }
        }

        return $query;
    }

    public function scopeMinUpdatedDateFilter($query, $filterMap)
    {
        $key = 'min_updated_date';
        if (array_key_exists($key, $filterMap)) {
            $minUpdatedDate = $filterMap[$key] ?? '';

            if ($minUpdatedDate) {
                $query->where(Item::t(Item::updatedDate), '>=', $minUpdatedDate);
            }
        }

        return $query;
    }

    public function scopeMaxUpdatedDateFilter($query, $filterMap)
    {
        $key = 'max_updated_date';
        if (array_key_exists($key, $filterMap)) {
            $maxUpdatedDate = $filterMap[$key] ?? '';

            $query = $query->whereHas($maxUpdatedDate, function ($query) use ($maxUpdatedDate) {
                $query->where(Item::t(Item::updatedDate), '<=', $maxUpdatedDate);
            });
        }

        return $query;
    }

    public function scopeStatusInFilter($query, $filterMap)
    {
        $key = 'status_in';
        if (array_key_exists($key, $filterMap)) {
            $status_in = (array) $filterMap[$key] ?? [];

            $query->whereIn(Item::t(Item::status), (array) $status_in);
        }

        return $query;
    }

    public function scopeBlockUserNotInFilter($query, $filterMap)
    {
        $key = 'blockUserIds_not_in';
        if (array_key_exists($key, $filterMap)) {
            $blockUserIds_not_in = (array) $filterMap[$key] ?? [];

            $query->whereNotIn(Item::t(Item::addedUserId), (array) $blockUserIds_not_in);
        }

        return $query;
    }

    public function scopeComplaintItemNotInFilter($query, $filterMap)
    {
        $key = 'complaintItemIds_not_in';
        if (array_key_exists($key, $filterMap)) {
            $complaintItemIds_not_in = (array) $filterMap[$key] ?? [];

            $query->whereNotIn(Item::t(Item::id), (array) $complaintItemIds_not_in);
        }

        return $query;
    }

    public function scopeNotInFilterByFields($query, $filterMap)
    {
        if (! empty($filterMap) && count($filterMap) > 0) {

            foreach ($filterMap as $key => $value) {
                if ($this->isValidColumn($key)) {
                    $value = $value ?? [];
                    if ($value) {
                        $query->whereNotIn(Item::t($key), $value);
                    }
                }
            }
        }

        return $query;
    }

    public function scopeLocationFilterWithLatLng($query, $filterMap)
    {
        $keyLat = 'lat';
        $keyLng = 'lng';
        $keyMiles = 'miles';

        if (
            array_key_exists($keyLat, $filterMap)
            && array_key_exists($keyLng, $filterMap)
            && array_key_exists($keyMiles, $filterMap)
        ) {
            $lat = $filterMap[$keyLat];
            $lng = $filterMap[$keyLng];
            $miles = $filterMap[$keyMiles];

            if ($lat !== null && $lng !== null) {
                $query->selectRaw('
                    (3959 * acos(cos(radians(?)) *
                    cos(radians(lat)) *
                    cos(radians(lng) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(lat)))) AS distance', [$lat, $lng, $lat])
                    ->when($miles, function ($query, $miles) {
                        $query->having('distance', '<', $miles ?: 0);
                    });
            }
        }

        return $query;
    }

    public function scopeInfosFilter($query, $filterMap)
    {
        $key = 'infos_filter';
        if (array_key_exists($key, $filterMap)) {
            $filter = $filterMap[$key] ?? [];

            foreach ($filter as $key => $value) {
                $uiTypeId = ItemInfo::where(ItemInfo::t(ItemInfo::coreKeysId), $key)
                    ->value(ItemInfo::t(ItemInfo::uiTypeId));

                $query->whereHas('itemRelation', function ($query) use ($key, $value, $uiTypeId) {
                    $query->where('core_keys_id', $key)
                        ->where(function ($query) use ($key, $value, $uiTypeId) {
                            $query->where(ItemInfo::t(ItemInfo::coreKeysId), $key);

                            if (in_array($uiTypeId, [Constants::dropDownUi, Constants::radioUi])) {
                                $query->where(ItemInfo::t(ItemInfo::value), $value);
                            } elseif (in_array($uiTypeId, [Constants::multiSelectUi])) {
                                $query->where(ItemInfo::t(ItemInfo::value), 'like', "%$value%");
                            } elseif (in_array($uiTypeId, [Constants::dateTimeUi, Constants::dateOnlyUi])) {
                                [$startRaw, $endRaw] = array_pad(explode('-', $value, 2), 2, null);

                                $start = $uiTypeId == Constants::dateOnlyUi ?
                                    Carbon::createFromTimestampMs((int) trim($startRaw))->startOfDay() :
                                    Carbon::createFromTimestampMs((int) trim($startRaw));

                                if (!empty($endRaw)) {
                                    $end = $uiTypeId == Constants::dateOnlyUi ?
                                        Carbon::createFromTimestampMs((int) trim($endRaw))->endOfDay() :
                                        Carbon::createFromTimestampMs((int) trim($endRaw));

                                    $query->whereBetween(ItemInfo::t(ItemInfo::value), [
                                        $start->toIso8601String(),
                                        $end->toIso8601String()
                                    ]);
                                } else {
                                    $query->where(ItemInfo::t(ItemInfo::value), '>=', $start->toIso8601String());
                                }
                            } elseif (in_array($uiTypeId, [Constants::timeOnlyUi])) {
                                [$start, $end] = array_pad(explode('-', $value, 2), 2, null);

                                $query->whereBetween(ItemInfo::t(ItemInfo::value), [$start, $end]);
                            } elseif (in_array($uiTypeId, [Constants::numberUi])) {
                                [$start, $end] = array_pad(explode(',', $value, 2), 2, null);

                                if ($start !== null && $start !== '' && $end !== null && $end !== '') {
                                    $query->whereBetween(ItemInfo::t(ItemInfo::value), [$start, $end]);
                                } elseif ($start !== null && $start !== '') {
                                    $query->where(ItemInfo::t(ItemInfo::value), '>=', $start);
                                } elseif ($end !== null && $end !== '') {
                                    $query->where(ItemInfo::t(ItemInfo::value), '<=', $end);
                                }
                            } else {
                                $query->where(ItemInfo::t(ItemInfo::value), 'LIKE', '%' . $value . '%');
                            }
                        });
                });
            }
        }

        return $query;
    }

    public function hasJoin($table, $q)
    {
        $query = $q->getQuery();

        $joins = $query->joins ?? [];

        foreach ($joins as $join) {
            if ($join->table == $table) {
                return true;
            }
        }

        return false;
    }

    //-------------------------------------------------------------------
    // Ordering
    //-------------------------------------------------------------------

    public function scopeOrderByCategoryName($query, $keyList)
    {

        $key = 'category_id@@name';
        if (array_key_exists($key, $keyList)) {
            $type = $keyList[$key] ?? 'desc';

            if (! in_array(strtolower($type), ['asc', 'desc'])) {
                $type = 'desc';
            }

            if (! $this->hasJoin(Category::tableName, $query)) {
                $query->join(Category::tableName, Category::t(Category::id), '=', Item::t(Item::categoryId));
            }

            $query->addSelect(Category::t(Category::name) . ' as cat_name');
            $query->orderBy('cat_name', $type);
        }

        return $query;
    }

    public function scopeOrderBySubCategoryName($query, $keyList)
    {
        $key = 'subcategory_id@@name';
        if (array_key_exists($key, $keyList)) {
            $type = $keyList[$key] ? $keyList[$key] : 'desc';

            if (! $this->hasJoin(Subcategory::tableName, $query)) {
                $query->leftJoin(Subcategory::tableName, Subcategory::t(Subcategory::id), '=', Item::t(Item::subCategoryId));
            }
            $query->addSelect(Subcategory::t(Subcategory::name) . ' as sub_cat_name');
            $query->orderBy('sub_cat_name', $type);
        }

        return $query;
    }

    public function scopeOrderByCityName($query, $keyList)
    {
        $key = 'location_city_id@@name';
        if (array_key_exists($key, $keyList)) {
            $type = $keyList[$key] ? $keyList[$key] : 'desc';

            if (! $this->hasJoin(LocationCity::tableName, $query)) {
                $query->join(LocationCity::tableName, LocationCity::t(LocationCity::id), '=', Item::t(Item::itemLocationId));
            }
            $query->addSelect(LocationCity::t(LocationCity::name) . ' as city_name');
            $query->orderBy('city_name', $type);
        }

        return $query;
    }

    public function scopeOrderByTownshipName($query, $keyList)
    {
        $key = 'location_township_id@@name';
        if (array_key_exists($key, $keyList)) {
            $type = $keyList[$key] ? $keyList[$key] : 'desc';

            if (! $this->hasJoin(LocationTownship::tableName, $query)) {
                $query->leftJoin(LocationTownship::tableName, LocationTownship::t(LocationTownship::id), '=', Item::t(Item::itemLocationTownshipId));
            }

            $query->addSelect(LocationTownship::t(LocationTownship::name) . ' as township_name');
            $query->orderBy('township_name', $type);
        }

        return $query;
    }

    public function scopeOrderByCurrencyName($query, $keyList)
    {
        $key = 'currency_id@@currency_short_form';
        if (array_key_exists($key, $keyList)) {
            $type = $keyList[$key] ? $keyList[$key] : 'desc';

            if (! $this->hasJoin(ItemCurrency::tableName, $query)) {
                $query->join(ItemCurrency::tableName, ItemCurrency::t(ItemCurrency::id), '=', Item::t(Item::itemCurrencyId));
            }
            $query->addSelect(ItemCurrency::t(ItemCurrency::currencyShortForm) . ' as curr_short_form');
            $query->orderBy('curr_short_form', $type);
        }

        return $query;
    }

    public function scopeOrderByOwnerName($query, $keyList)
    {
        $key = 'added_user_id@@name';
        if (array_key_exists($key, $keyList)) {
            $type = $keyList[$key] ? $keyList[$key] : 'desc';

            if (! $this->hasJoin(User::tableName, $query)) {
                $query->join(User::tableName, User::t(User::id), '=', Item::t(Item::userId));
            }
            $query->addSelect(User::t(User::name) . ' as owner_name');
            $query->orderBy('owner_name', $type);
        }

        return $query;
    }

    public function scopeOrderByBuyerName($query, $keyList)
    {
        $key = 'buyer_user_id@@name';
        if (! empty($key) && array_key_exists($key, $keyList)) {
            $type = $keyList[$key] ? $keyList[$key] : 'desc';

            if (! $this->hasJoin(UserBought::tableName, $query)) {
                $query->join(UserBought::tableName, UserBought::t(UserBought::itemId), '=', Item::t(Item::id));
            }

            if (! $this->hasJoin(User::tableName . ' as buyers', $query)) {
                $query->join(User::tableName . ' as buyers', 'buyers.' . User::id, '=', UserBought::tableName . '.' . UserBought::buyerUserId);
            }

            $query->addSelect('buyers.name as buyer_name');

            $query->orderBy('buyer_name', $type);
        }

        return $query;
    }

    public function scopeOrderBySellerName($query, $keyList)
    {
        $key = 'seller_user_id@@name';
        if (! empty($key) && array_key_exists($key, $keyList)) {
            $type = $keyList[$key] ? $keyList[$key] : 'desc';

            if (! $this->hasJoin(UserBought::tableName, $query)) {
                $query->join(UserBought::tableName, UserBought::t(UserBought::itemId), '=', Item::t(Item::id));
            }

            if (! $this->hasJoin(User::tableName . ' as sellers', $query)) {
                $query->join(User::tableName . ' as sellers', 'sellers.' . User::id, '=', UserBought::tableName . '.' . UserBought::sellerUserId);
            }

            $query->addSelect('sellers.name as seller_name');

            $query->orderBy('seller_name', $type);
        }

        return $query;
    }

    public function scopeOrderByFields($query, $keyList)
    {
        if (!empty($keyList) && count($keyList) > 0) {
            $hasIdOrder = false;

            foreach ($keyList as $key => $type) {
                if($key == Item::id) {
                    $hasIdOrder = true;
                }
                if ($this->isValidColumn($key)) {
                    $query->orderBy(Item::t($key), $type);
                } elseif ($this->isColumnSelected($query, $key)) {
                    $query->orderBy($key, $type);
                }
            }

            if (!$hasIdOrder) {
                $query->orderBy(Item::t(Item::id), 'desc');
            }
        } else {

            // Default Sorting
            $query->orderBy(Item::t(Item::title), 'desc')
                ->orderBy(Item::t(Item::addedDate), 'desc')
                ->orderBy(Item::t(Item::status), 'desc');
        }

        return $query;
    }

    public function scopeJoinWithItemInfo($query)
    {
        if ($this->hasJoin(ItemInfo::tableName, $query)) {
            return $query;
        }

        return $query->leftJoin(ItemInfo::tableName, function ($join) {
            $join->on(Item::tableName . '.' . Item::id, '=', ItemInfo::tableName . '.' . ItemInfo::itemId);
        });
    }

    public function scopeJoinWithPaidItemHistory($query)
    {
        if ($this->hasJoin(PaidItemHistory::tableName, $query)) {
            return $query;
        }

        return $query->leftJoin(PaidItemHistory::tableName, function ($join) {
            $join->on(Item::tableName . '.' . Item::id, '=', PaidItemHistory::tableName . '.' . PaidItemHistory::itemId);
        });
    }

    public function scopeSelectCustomField($query)
    {
        return $query->selectRaw((new CustomField())->getCustomizeUiSelectSQL());
    }

    public function scopeJoinWithCustomFieldAttribute($query)
    {
        if ($this->hasJoin(CustomFieldAttribute::tableName, $query)) {
            return $query;
        }

        return $query->leftJoin(CustomFieldAttribute::tableName, ItemInfo::tableName . '.' . ItemInfo::value, '=', CustomFieldAttribute::tableName . '.' . CustomFieldAttribute::id);
    }

    // limit and offset
    public function scopeLimitAndOffset($query, $limit = null, $offset = null)
    {

        if (! empty($limit)) {
            $query->limit($limit);
        }

        if (! empty($offset)) {
            $query->offset($offset);
        }

        return $query;
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////
    private function isColumnSelected($query, $column)
    {
        // Get the underlying query builder
        $baseQuery = $query->getQuery();

        // If no columns are explicitly selected, all columns are selected by default
        if (empty($baseQuery->columns)) {
            return false;
        }

        // Normalize the column name for comparison
        $normalizedColumn = strtolower($column);

        // Check if the column is in the selected columns
        foreach ($baseQuery->columns as $selectedColumn) {
            // Normalize the selected column for comparison
            $normalizedSelectedColumn = strtolower($selectedColumn);

            // Check for exact match or partial match in complex expressions
            if (
                $normalizedColumn === $normalizedSelectedColumn ||
                strpos($normalizedSelectedColumn, $normalizedColumn) !== false
            ) {
                return true;
            }
        }

        return false;
    }

    private function isValidColumn($column)
    {
        if (!in_array($column, [Item::lat, Item::lng])) {
            return in_array($column, $this->getFillable()) || in_array($column, $this->getGuarded());
        }
    }
}
