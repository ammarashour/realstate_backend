<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\QueryTest;

use Exception;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Utilities\CustomFieldAttribute;
use Modules\Core\Entities\Item;
use Modules\Core\Entities\ItemInfo;
use Modules\Core\Entities\Configuration\Setting;
use Modules\Core\Http\Services\Item\ItemService;

class QueryTestController extends Controller
{
    const parentPath = 'query_test/';

    const indexPath = self::parentPath . 'Index';

    const indexRoute = 'about.index';

    public function __construct(protected ItemService $itemService) {}

    private function execQuery($func)
    {

        $start = microtime(true);
        $data = $this->$func();

        // With arguments:
        //$this->$func($arg1, $arg2);

        $time = microtime(true) - $start;

        $retData = [
            'name' => $func,
            'data' => $data,
            'time' => round($time * 100, 3),
        ];

        return $retData;
    }

    public function index()
    {
        ////////////////////////////////////////////////////////////////////
        // Edit HERE

        DB::enableQueryLog();

        try {
            $list = [
                // $this->execQuery("getItemsWithWhereHas"),
                // $this->execQuery("getItemsWithJoin")

                // $this->execQuery("getItems"),
                // $this->execQuery("getItemsWithScopes")
                $this->execQuery('serviceItemGetAll'),
                // $this->execQuery("getCustomizeUIDetail")
            ];
        } catch (Exception $e) {
            $list = $e->getMessage();

            dd($e);
        }
        // End Edit HERE
        ////////////////////////////////////////////////////////////////////

        $queries = DB::getQueryLog();

        $qq = 'Test';
        // Output the queries
        foreach ($queries as $query) {
            $qq .= 'SQL: ' . $query['query'] . '<br>';
            // echo "Bindings: " . implode(', ', $query['bindings']) . "<br>";
        }

        $result = [
            'list' => $list,
            'qq' => 'qq',
        ];

        return renderView(self::indexPath, $result);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getCustomizeUIDetail()
    {
        $relations = []; //['customizeUiDetail','uiTypeId'];
        $categoryId = null;
        $filters = [

            CustomField::coreKeysId => null,
            CustomField::moduleName => 'itm3323',
            CustomField::isDelete => 0,

        ];
        $query = CustomField::query()->select('name', 'is_delete')
            ->withRelations($relations)
            ->categoryFilter($categoryId)
            ->filterByFields($filters);
        // ->limitAndOffset(2, 2)

        $data = $query->get();

        // if(empty($data))
        //     // echo "empty";
        // else
        //     echo "not empty";
        // dd($data);

        $data = CoreImage::where('id', '=', '1')->orderBy('id', 'desc')->first();

        return $data;
    }

    private function serviceItemGetAll()
    {

        $filters = [
            // "keyword" => "",
            // "vendor_id" => "",
            // "category_id" => 17,
            'subcategory_id' => null,
            'status_in' => [Constants::publishItem, Constants::unpublishItem],
            // "location_city_id" => null,
            // "location_township_id" => null,
            // "added_user_id" => null,
            // "is_sold_out" => null,
            // "min_updated_date" => null,
            // "min_added_date" => null,
            // "min_price" => null,
            // "infos_filter" => []
            // 'keyword' => "Xiaomi"
            // 'seller_buyer_name' => 'u'
            // 'category_id' => 16
            // 'subcategory_id' => 45,
            // 'location_city_id' => 21,
            // 'location_township_id' => 23
            // 'min_price' => 220,
            // 'max_price' => 500
            // 'min_added_date' => '2023-11-18 05:07:03',
            // 'max_added_date' => '2023-11-18 05:13:03'
            // 'min_updated_date' => '2023-11-18 05:07:03',
            // 'min_updated_date' => null,
            // 'max_updated_date' => '2023-11-18 05:13:03'
            // 'lat_lng' => [
            //     'lat' => 21.982952,
            //     'lng' => 96.083908,
            //     'miles' => 1
            // ]
            // itm00029, 75
            // 'infos_filter' => [
            //     'itm00029' => '75',
            //     'ps-itm00009' => 'India'
            // ]
        ];
        // dd($filters);
        $orderCriteria = [
            // "location_city_id@@name" => "desc"
            'itm00006' => 'desc',
            // Item::favouriteCount => "desc",
            // Item::touchCount => "asc",
            // 'category_id@@name' => "asc"
            // 'id' => 'desc'
            // 'added_user_id@@name' => 'asc',
            // 'buyer_user_id@@name' => 'asc',
            // 'seller_user_id@@name' => 'desc',
            // 'item_touch_count' => 'asc'
        ];

        // $relations = [
        //     'vendor',
        //     'category',
        //     'subcategory',
        //     'city',
        //     'township',
        //     'currency',
        //     // 'itemRelation.uiType',
        //     // 'itemRelation.customizeUi',
        //     'owner',
        //     // 'itemRelation',
        //     'itemRelation.customizeUiDetail',
        //     'itemRelation.uiType',
        //     'itemRelation.customizeUi',
        // ];

        $relations = [
            'vendor',
            'category',
            'subcategory',
            'city',
            'township',
            'currency',
            'itemRelation.uiType',
            'itemRelation.customizeUi',
            'owner',
            'itemRelation',
        ];

        return $this->itemService->getAll(
            relations: $relations,
            filters: $filters,
            sorting: $orderCriteria,
            limit: 1
        );
    }

    private function getItemsWithScopes()
    {

        // select
        // join
        // where
        // order

        $query = Item::select(
            Item::t(Item::id),
            Item::t(Item::title),
            Item::t(Item::subCategoryId),
            Item::t(Item::itemLocationId),
            Item::t(Item::itemLocationTownshipId),
            Item::t(Item::price),
            Item::t(Item::addedDate),
            Item::t(Item::UPDATED_AT),
            Item::t(Item::lat),
            Item::t(Item::lng),
            Item::t(Item::favouriteCount),
            Item::t(Item::touchCount)
        );
        $query->with(['infos']);
        // ->joinWithItemInfo();
        // dd("efef");

        $filter = '';
        // // for single level
        // $query = $query->whereHas('infos', function ($query) use ($filter) {
        //     $query->where('core_keys_id', 'ps-itm00009')
        //         ->where('value', 'London');
        // });
        // for multi level
        // $query = $query->whereHas('infos', function ($query) use ($filter) {
        //     $query->where('core_keys_id', 'ps-itm00004')
        //         ->whereHas('customizeUiDetail', function ($query) use ($filter) {
        //             $query->where('name', 'Used');
        //         });
        // });
        $query = $query->whereHas('infos', function ($query) {
            $query->where('core_keys_id', 'ps-itm00002');
        });
        $orderBy = 'added_date';
        // "item_touch_count"; //"currency_id@@currency_short_form";
        $orderType = 'desc';
        // $orderType = "desc";
        // Order By
        // Option 1
        // if(!empty($orderBy)) {
        //     $query->prepareRelationNameForSorting($orderBy);
        // }
        // Option 2
        // if($orderBy == "category_id@@name") {
        //     $query->orderByCategoryName($orderType);
        // }
        // Option 3
        /**
         * todo
         * -----
         * id // Done
         * buyer name
         * seller name
         * added_date // Done
         * item_touch_count // Done
         * direct fields // Done
         * default case // Done
         * item report // Done
         *   if ($extra == 'itemReport') {
         *      $query->orderBy(Item::touchCount, 'desc');
         *      $query->orderBy(Item::favouriteCount, 'desc');
         *   }
         * another default // Done
         */
        // $conds['order_by'] = Item::favouriteCount;// .",".Item::touchCount;
        // $orderArr[] = $conds['order_by'];
        // if(str_contains($conds['order_by'],',')){
        //     $orderArr = explode(',', $conds['order_by']);
        // }

        $orderCriteria = [
            // Item::favouriteCount => "desc",
            // Item::touchCount => "asc",
            'category_id@@name' => 'asc',
        ];

        $query->orderByCategoryName($orderCriteria)
            ->orderBySubCategoryName($orderCriteria)
            ->orderByCityName($orderCriteria)
            ->orderByTownshipName($orderCriteria)
            ->orderByCurrencyName($orderCriteria)
            ->orderByOwnerName($orderCriteria)
            ->orderByFields($orderCriteria);

        // Search & Filter
        $query //->searchBuyerSellerByName('Admin')
            ->categoryFilter(13)
            //->cityFilter(22)
            // ->townshipFilter(10)
            // ->priceFilter(1200, null)
            // ->addedDateRangeFilter('2023-11-17 00:00:00','2023-11-18 00:00:00')
            // ->addedDateRangeFilter('2023-11-17 00:00:00')
            // ->updatedDateRangeFilter('2023-11-17 00:00:00','2023-11-18 00:00:00')
            // ->updatedDateRangeFilter('2023-11-17 00:00:00')
            // "lat": 4.010393, "lng": 101.381836,
            // ->locationFilterWithLatLng(0,0,2)
            // ->locationFilterWithLatLng(4.110393,101.181836,100)
            ->limit(20);

        return $query->get();
    }

    private function getItems()
    {

        $limit = 20;
        $orderBy = 'category_id@@name'; //"category_id@@name";
        $itemRelation = [
            'vendor',
            'category',
            'subcategory',
            'city',
            'township',
            'currency',
            'itemRelation.uiType',
            'itemRelation.customizeUi',
            'owner',
            'itemRelation',
        ];

        $data = Cache::remember('def', 100, function () use ($limit, $orderBy, $itemRelation) {
            $query = Item::select(Item::tableName . '.*');

            if (! empty($orderBy)) {
                $query->prepareRelationNameForSorting($orderBy);
            }

            $orderBy = 'subcategory_id@@name';
            if (! empty($orderBy)) {
                $query->prepareRelationNameForSorting($orderBy);
            }

            $customizeUiSelectSQL = (new CustomField())->getCustomizeUiSelectSQL();

            if (! empty($customizeUiSelectSQL)) {
                $query->selectRaw($customizeUiSelectSQL);
            }

            $query->joinWithItemInfo();
            $query->leftJoin(CustomFieldAttribute::tableName, ItemInfo::tableName . '.' . ItemInfo::value, '=', CustomFieldAttribute::tableName . '.' . CustomFieldAttribute::id);

            $query->when($itemRelation, function ($q, $relation) {
                $q->with($relation);
            });

            $query->limit($limit);

            return $query->get();
        });

        return $data;
    }

    // private static function j($parm1, $parm2) {
    //     return $parm1.".".$parm2;
    // }
    // private function handleOrderBy($query, $conds, $sort)
    // {
    //     $query->when(isset($conds['order_by']) && $conds['order_by'], function ($q) use ($sort) {
    //         if ($sort == 'category_id@@name') {
    //             $q->join('psx_categories as c', "c.id", '=', 'psx_items.category_id');
    //             $q->select("c.name as cat_name", "psx_items.*");
    //         }});
    // }
    private function getVendorSetting()
    {
        return Setting::select('setting')->where('setting_env', Constants::VENDOR_SUBSCRIPTION_CONFIG)->first();
    }

    private function getItemsWithJoin()
    {
        return Item::select('psx_items.*')
            ->join('psx_categories', 'psx_categories.id', '=', 'psx_items.category_id')
            ->where('psx_categories.name', 'Like', '%a%')
            ->get();
    }

    private function getItemsWithWhereHas()
    {
        return Item::whereHas('category', function ($q) {
            $q->where('name', 'Like', '%a%');
        })->get();
    }
}
