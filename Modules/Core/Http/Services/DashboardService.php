<?php

namespace Modules\Core\Http\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Config\ps_url;
use Carbon\CarbonPeriod;
use App\Config\ps_constant;
use Modules\Core\Entities\Item\Item;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\App;
use Modules\Core\Entities\Category\Category;
use Illuminate\Support\Facades\Http;
use Modules\Core\Entities\User\UserBought;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\SpecificUser;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Core\Entities\Utilities\ActivatedFileName;
use Modules\Core\Entities\Financial\TransactionDetail;
use Modules\Core\Entities\Financial\TransactionHeader;
use Modules\Core\Entities\DashboardCoreImage;
use Illuminate\Validation\ValidationException;
use Modules\Core\Entities\Item\ComplaintItem;
use Modules\Core\Entities\DashboardHighestRatedBuyer;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\DashboardLatestRefreshDate;
use Modules\Core\Entities\DashboardHighestRatedSeller;
use Modules\Core\Entities\DashboardRevenueFromPackage;
use Modules\Core\Entities\DashboardTotalAndPercentage;
use Modules\Core\Entities\DashboardMostEngagingProduct;
use Modules\Core\Entities\DashboardMostPurchasedPackage;
use Modules\Core\Entities\DashboardRecentRegisteredUser;
use Modules\Core\Entities\DashboardRevenueFromPromotion;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Core\Http\Services\Item\ComplaintItemService;
use Modules\Core\Transformers\Backend\NoModel\Dashboard\DashboardBuyerResource;
use Modules\Core\Transformers\Backend\NoModel\Dashboard\DashboardSellerResource;
use Modules\Core\Transformers\Backend\NoModel\User\UserReportWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\User\BuyerReportWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\User\SellerReportWithKeyResource;
use Modules\Core\Transformers\Backend\NoModel\Dashboard\DashboardRecentRegisteredUserResource;
use Modules\Core\Entities\Utilities\BuilderAppInfoCache;
use Modules\Core\Http\Services\Category\CategoryService;
use Modules\Core\Http\Services\Configuration\BackendSettingService;
use Modules\Core\Http\Services\Item\PackageBoughtTransactionService;
use Modules\Core\Http\Services\Item\PaidItemService;

class DashboardService extends PsService
{
    protected $packageInAppPurchasePaymentId, $paymentSettingService, $packageBoughtTransactionService, $paidItemService, $categoryService, $userBoughtTableName, $userBoughtIdCol, $userBoughtAddedDateCol, $itemIsSoldOutCol, $itemService, $complaintItemService, $userService, $custom, $last60Days, $last30Days, $last14Days, $last7Days, $yesterday, $today, $unAvailable, $itemIsAvailableCol, $itemAddedDateCol, $transactionHeaderIdCol, $transactionDetailTransactionHeaderIdCol, $transactionDetailAddedDateCol, $transactionDetailItemIdCol, $transactionDetailTableName, $itemIdCol, $publish, $unBan, $userIsBannedCol, $userStatusCol, $complaintItemAddedDateCol, $itemAddedUserIdCol, $itemTableName, $userAddedDateCol, $userOverallRatingCol, $transactionHeaderAddedDateCol, $transactionHeaderTransactionStatusIdCol, $transactionHeaderUserIdCol, $transactionHeaderTableName, $userIdCol, $catAddedDateCol, $userTableName, $itemFavouriteCountCol, $descending, $backendSettingService, $isSoldOut, $tableService;

    public function __construct(BackendSettingService $backendSettingService, UserService $userService, ItemService $itemService, ComplaintItemService $complaintItemService, CategoryService $categoryService, PaidItemService $paidItemService, PackageBoughtTransactionService $packageBoughtTransactionService, PaymentSettingService $paymentSettingService, TableService $tableService)
    {
        $this->descending = Constants::descending;
        $this->publish = Constants::publish;
        $this->unBan = Constants::unBan;
        $this->unAvailable = Constants::unAvailable;
        $this->today = Constants::today;
        $this->yesterday = Constants::yesterday;
        $this->last7Days = Constants::last7Days;
        $this->last14Days = Constants::last14Days;
        $this->last30Days = Constants::last30Days;
        $this->last60Days = Constants::last60Days;
        $this->custom = Constants::custom;
        $this->isSoldOut = Constants::isSoldOut;
        $this->packageInAppPurchasePaymentId = Constants::packageInAppPurchasePaymentId;

        $this->itemFavouriteCountCol = Item::favouriteCount;
        $this->itemTableName = Item::tableName;
        $this->itemAddedUserIdCol = Item::addedUserId;
        $this->itemIdCol = Item::id;
        $this->itemAddedDateCol = Item::addedDate;
        $this->itemIsAvailableCol = Item::isAvailable;
        $this->itemIsSoldOutCol = Item::isSoldOut;

        $this->userTableName = User::tableName;
        $this->userIdCol = User::id;
        $this->userOverallRatingCol = User::overallRating;
        $this->userAddedDateCol = User::addedDate;
        $this->userStatusCol = User::status;
        $this->userIsBannedCol = User::isBanned;

        $this->catAddedDateCol = Category::addedDate;

        $this->complaintItemAddedDateCol = ComplaintItem::addedDate;

        $this->userBoughtTableName = UserBought::tableName;
        $this->userBoughtIdCol = UserBought::id;
        $this->userBoughtAddedDateCol = UserBought::addedDate;

        $this->transactionHeaderTableName = TransactionHeader::tableName;
        $this->transactionHeaderIdCol = TransactionHeader::id;
        $this->transactionHeaderUserIdCol = TransactionHeader::userId;
        $this->transactionHeaderTransactionStatusIdCol = TransactionHeader::transactionStatusId;
        $this->transactionHeaderAddedDateCol = TransactionHeader::addedDate;

        $this->transactionDetailTableName = TransactionDetail::tableName;
        $this->transactionDetailItemIdCol = TransactionDetail::itemId;
        $this->transactionDetailAddedDateCol = TransactionDetail::addedDate;
        $this->transactionDetailTransactionHeaderIdCol = TransactionDetail::transactionHeaderId;

        $this->backendSettingService = $backendSettingService;
        $this->userService = $userService;
        $this->userService = $userService;
        $this->itemService = $itemService;
        $this->complaintItemService = $complaintItemService;
        $this->categoryService = $categoryService;
        $this->paidItemService = $paidItemService;
        $this->packageBoughtTransactionService = $packageBoughtTransactionService;
        $this->paymentSettingService = $paymentSettingService;
        $this->tableService = $tableService;

        // configuration For Dashboard
        $this->totalUserType = 'total_user';
        $this->totalSellerType = 'total_seller';
        $this->totalBuyerType = 'total_buyer';
        $this->successfulDealCountType = 'successful_deal_count';
        $this->totalSoldOutItemType = 'total_sold_out_item';
        $this->totalSlowMovingItemType = 'total_slow_moving_item';
        $this->userRoundedCircleImgCount = 4;
        $this->dateFormatForLastestRefresh = "d-m";
        $this->mostEngagingProductCount = 5;
        $this->packageCount = 5;
        $this->mostPurchasedPackageCount = 5;
        $this->revenueFromPromotionCount = 7;
        $this->revenueFromPackageCount = 7;
        $this->highestRatedSellerCount = 5;
        $this->highestRatedBuyerCount = 5;
        $this->recentRegisteredUserCount = 5;
        $this->subDayFromNowCount = 6;
        $this->subDayFromSubDayForPercentCount = 14;
    }

    public function searching($query, $conds)
    {

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy('psx_items.id', $conds['order_type']);
            } elseif ($conds['order_by'] == 'purchased_item_count') {
                $query->orderBy('purchased_item_count', $conds['order_type']);
            } elseif ($conds['order_by'] == 'subcategory_id@@name') {
                $query->orderBy('sub_cat_name', $conds['order_type']);
            } elseif ($conds['order_by'] == 'currency_id@@currency_short_form') {
                $query->orderBy('curr_short_form', $conds['order_type']);
            } elseif ($conds['order_by'] == 'location_city_id@@name') {
                $query->orderBy('city_name', $conds['order_type']);
            } elseif ($conds['order_by'] == 'location_township_id@@name') {
                $query->orderBy('township_name', $conds['order_type']);
            } elseif ($conds['order_by'] == 'added_user_id@@name') {
                $query->orderBy('owner_name', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        } else {
            // sold_item_count_order_by
            if (isset($conds['purchased_item_count_order_by']) && isset($conds['order_type'])) {
                $query->orderBy('purchased_item_count', $conds['order_type']);
            } elseif (isset($conds['sold_item_count_order_by']) && isset($conds['order_type'])) {
                $query->orderBy('sold_item_count', $conds['order_type']);
            }
        }

        return $query;
    }

    public function dashboardTotalAndPercentage($total, $percentage, $type)
    {
        $result = DashboardTotalAndPercentage::create([
            'total' => $total,
            'percentage' => $percentage,
            'type' => $type,
        ]);

        return $result;
    }


    public function handleRevenue($dateArrFromDb, $valueArrFromDb)
    {
        $formatedDateRangeArr = getBetweenTwoDateRangeArr(Carbon::now()->subDays($this->subDayFromNowCount), Carbon::now());

        $totalZeroDateArr = array_diff($formatedDateRangeArr, $dateArrFromDb);

        $valueRevenueFromPromotion = [];
        foreach ($valueArrFromDb as $revenuePromotion) {
            $valueRevenueFromPromotion[] = [
                'total' => $revenuePromotion->total,
                'date' => $revenuePromotion->date
            ];
        }

        $noValueRevenueFromPromotion = [];
        foreach ($totalZeroDateArr as $totalZeroDate) {
            $noValueRevenueFromPromotion[] = [
                'total' => 0,
                'date' => $totalZeroDate
            ];
        }
        $value = array_merge($valueRevenueFromPromotion, $noValueRevenueFromPromotion);
        return $value;
    }

    public function refresh($request)
    {

        $row = $request->input('row') ?? Constants::dashboardDataTableDefaultRow;

        $defaultDate = [Carbon::now()->subDays($this->subDayFromNowCount), Carbon::now()];
        $lastDate = [Carbon::now()->subDays($this->subDayFromSubDayForPercentCount), Carbon::now()->subDays($this->subDayFromNowCount)];
        $itemRelation = [
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
        $userRelation = [
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation',
            'blue_mark',
            'role'
        ];
        $userConds['is_banned'] = 0;
        $userConds['date_range'] = $defaultDate;
        $itemConds['added_date_range'] = $defaultDate;

        $mostEngagingProducts = [];
        $revenueFromPromotion = [];
        $revenueFromPacakge = [];
        $most_purchased_packages = [];
        $mostEngagingCategories = [];

        // Revenue Promotion
        $revenueFromPromotionArr = $this->paidItemService->getRevenueFromPromotion(null, null, $this->revenueFromPromotionCount, null, $itemConds, true);
        $revenueFromPromotionDateArr = $revenueFromPromotionArr->pluck('date')->toArray();
        $revenueFromPromotion = $this->handleRevenue($revenueFromPromotionDateArr, $revenueFromPromotionArr);

        // Revenue Package
        $revenueFromPackageArr = $this->packageBoughtTransactionService->getRevenueFromPackage(null, null, $this->revenueFromPackageCount, null, $itemConds);
        $revenueFromPackageDateArr = $revenueFromPackageArr->pluck('date')->toArray();
        $revenueFromPacakge = $this->handleRevenue($revenueFromPackageDateArr, $revenueFromPackageArr);

        // for all category data by conds with limit 5
        $categoryRelation = [];
        $categoryConds['date_range'] = $defaultDate;
        $mostEngagingCategories = $this->categoryService->getAll($categoryRelation, true, null, 5, null, $categoryConds, true, null, true);

        // for all item data by conds with limit 5
        $mostEngagingProducts = $this->itemService->getItems($itemRelation, true, null, $this->mostEngagingProductCount, null, $itemConds);
        $packageConds['added_date_range'] = $defaultDate;
        $most_purchased_packages = $this->packageBoughtTransactionService->getMostPurchsedPackages(null, null, $this->mostPurchasedPackageCount, null, $packageConds);
        $pkgConds['payment_id'] = $this->packageInAppPurchasePaymentId;
        $packages = $this->paymentSettingService->getPaymentInfos(null, null, null, $pkgConds, 1);

        $filter = [
            'filter' => $this->last7Days,
            'date' => ''
        ];

        // for table data
        // for all user from table
        $allUserConds['is_banned'] = 0;
        $totalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $allUserConds, null, null, null, true));

        $totalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $allUserConds, null, null, null, true));
        $onlyFourBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $allUserConds, $this->userRoundedCircleImgCount, null, null, true));

        $totalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $allUserConds, null, null, null, true));
        $onlyFourSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $allUserConds, $this->userRoundedCircleImgCount, null, null, true));

        // for table data with paginate according to date filter
        // $userConds['is_banned'] = 0;
        $highestRatedBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, 1, false, null, $this->highestRatedBuyerCount, 0, null, true));
        $highestRatedSeller = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, 1, false, null, $this->highestRatedSellerCount, 0, null, true));
        $userConds['date_range'] = $defaultDate;
        $recentRegisteredUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, 1, false, $userConds, $this->recentRegisteredUserCount, 0, null, true));
        // $highestRatedBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false,  $this->highestRatedBuyerCount ));
        // $highestRatedSeller = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation,  true, false, $userConds, null, null, null, false,  $this->highestRatedSellerCount ));
        // $userConds['date_range'] = $defaultDate;
        // $recentRegisteredUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $this->recentRegisteredUserCount ));

        // for all user by date conds (in last 7 day data)
        $userConds['is_banned'] = 0;
        $userConds['date_range'] = $defaultDate;
        $newTotalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
        $newTotalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
        $newTotalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();

        // for all user by date conds (previous in last 7 day data)
        $userConds['is_banned'] = 0;
        $userConds['date_range'] = $lastDate;
        $lastTotalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
        $lastTotalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
        $lastTotalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();

        // for all item by date conds
        $totalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, true)->count();
        $totalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, true)->count();
        $successfulDealCountRelation = [
            'category',
            'subcategory',
            'city',
            'township',
            'currency',
            'itemRelation.uiType',
            'itemRelation.customizeUi',
            'owner',
            'itemRelation',
            'buyer',
            'seller',
        ];
        $successfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, true)->count();

        // for all item by date conds (in last 14 day data)
        $itemConds['added_date_range'] = $defaultDate;
        $newTotalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, true, null, null, null, $itemConds)->count();
        $newTotalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, true, null, null, null, $itemConds)->count();
        $newSuccessfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, true, null, null, null, $itemConds)->count();

        // for all item by date conds (previous in last 14 day data)
        $itemConds['added_date_range'] = $lastDate;
        $lastTotalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, false, $row, null, null, $itemConds)->count();
        $lastTotalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, false, $row, null, null, $itemConds)->count();
        $lastSuccessfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, false, $row, null, null, $itemConds)->count();

        // Percent Calculation
        if ($lastTotalBuyers == $newTotalBuyers) {
            $buyerPercentage = 0;
        } else if ($lastTotalBuyers == 0) {
            $buyerPercentage = 100;
        } else {
            $buyerPercentage = number_format(($lastTotalBuyers - $newTotalBuyers) / ($lastTotalBuyers * 100), 2);
        }

        if ($lastTotalSellers == $newTotalSellers) {
            $sellerPercentage = 0;
        } else if ($lastTotalSellers == 0) {
            $sellerPercentage = 100;
        } else {
            $sellerPercentage = number_format(($lastTotalSellers - $newTotalSellers) / ($lastTotalSellers * 100), 2);
        }

        if ($lastTotalUsers == $newTotalUsers) {
            $userPercentage = 0;
        } else if ($lastTotalUsers == 0) {
            $userPercentage = 100;
        } else {
            $userPercentage = number_format(($lastTotalUsers - $newTotalUsers) / ($lastTotalUsers * 100), 2);
        }

        if ($lastTotalSoldOutItems == $newTotalSoldOutItems) {
            $soldOutItemPercentage = 0;
        } else if ($lastTotalSoldOutItems == 0) {
            $soldOutItemPercentage = 100;
        } else {
            $soldOutItemPercentage = number_format(($lastTotalSoldOutItems - $newTotalSoldOutItems) / ($lastTotalSoldOutItems * 100), 2);
        }

        if ($lastTotalSlowMovingItems == $newTotalSlowMovingItems) {
            $slowMovingItemPercentage = 0;
        }
        if ($lastTotalSlowMovingItems == 0) {
            $slowMovingItemPercentage = 100;
        } else {
            $slowMovingItemPercentage = number_format(($lastTotalSlowMovingItems - $newTotalSlowMovingItems) / ($lastTotalSlowMovingItems * 100), 2);
        }

        if ($lastSuccessfulDealCount == $newSuccessfulDealCount) {
            $successfulDealCountItemPercentage = 0;
        }
        if ($lastSuccessfulDealCount == 0) {
            $successfulDealCountItemPercentage = 100;
        } else {
            $successfulDealCountItemPercentage = number_format(($lastSuccessfulDealCount - $newSuccessfulDealCount) / ($lastSuccessfulDealCount * 100), 2);
        }

        /**
         * Delete Data Before Prepare
         */
        DashboardLatestRefreshDate::truncate();
        DashboardCoreImage::truncate();
        DashboardTotalAndPercentage::truncate();
        DashboardMostEngagingProduct::truncate();
        DashboardMostPurchasedPackage::truncate();
        DashboardRevenueFromPromotion::truncate();
        DashboardRevenueFromPackage::truncate();
        DashboardHighestRatedSeller::truncate();
        DashboardHighestRatedBuyer::truncate();
        DashboardRecentRegisteredUser::truncate();

        /**
         *  Prepare data For Dashboard
         */
        // for latest data refreshed date
        $defaultDate = [Carbon::now()->subDays($this->subDayFromNowCount), Carbon::now()];
        $from = Carbon::now()->subDays($this->subDayFromNowCount);
        $to = Carbon::now();
        DashboardLatestRefreshDate::create([
            'from' => $from,
            'to' => $to
        ]);

        // for total user card
        $this->dashboardTotalAndPercentage($totalUsers->count(), $userPercentage, $this->totalUserType);

        // for total seller card
        $this->dashboardTotalAndPercentage($totalSellers->count(), $sellerPercentage, $this->totalSellerType);

        $onlyFourSellerImages = [];
        foreach ($onlyFourSellers as $seller) {
            $onlyFourSellerImages[] = [
                'img_path' => $seller->user_cover_photo,
                'img_type' => $this->totalSellerType
            ];
        }
        DashboardCoreImage::upsert(
            $onlyFourSellerImages,
            ['id'],
            ['img_path']
        );

        // for total buyer card
        $this->dashboardTotalAndPercentage($totalBuyers->count(), $buyerPercentage, $this->totalBuyerType);

        $onlyFourBuyerImages = [];
        foreach ($onlyFourBuyers as $buyer) {
            $onlyFourBuyerImages[] = [
                'img_path' => $buyer->user_cover_photo,
                'img_type' => $this->totalBuyerType
            ];
        }
        DashboardCoreImage::upsert(
            $onlyFourBuyerImages,
            ['id'],
            ['img_path']
        );

        // for successful deal count card
        $this->dashboardTotalAndPercentage($successfulDealCount, $successfulDealCountItemPercentage, $this->successfulDealCountType);

        // for total sold out item card
        $this->dashboardTotalAndPercentage($totalSoldOutItems, $soldOutItemPercentage, $this->totalSoldOutItemType);

        // for total slow moving item card
        $this->dashboardTotalAndPercentage($totalSlowMovingItems, $slowMovingItemPercentage, $this->totalSlowMovingItemType);

        // for most engaging products card
        if (!empty($mostEngagingProducts)) {
            $mostEngagingProductArr = [];
            foreach ($mostEngagingProducts as $mostEngagingProduct) {
                $mostEngagingProductArr[] = [
                    'title' => $mostEngagingProduct->title,
                    'item_touch_count' => $mostEngagingProduct->item_touch_count
                ];
            }
            DashboardMostEngagingProduct::upsert(
                $mostEngagingProductArr,
                ['id'],
                ['title', 'item_touch_count']
            );
        }

        // for most purchased package card
        if (!empty($most_purchased_packages)) {
            $most_purchased_packageArr = [];
            foreach ($most_purchased_packages as $most_purchased_package) {
                $most_purchased_packageArr[] = [
                    'total' => $most_purchased_package->total,
                    'package_id' => $most_purchased_package->package_id
                ];
            }
            DashboardMostPurchasedPackage::upsert(
                $most_purchased_packageArr,
                ['id'],
                ['total', 'package_id']
            );
        }

        // for Revenue From Promotion card
        if (!empty($revenueFromPromotion)) {
            $revenuePromotionArr = [];
            foreach ($revenueFromPromotion as $revenuePromotion) {
                $revenuePromotionArr[] = [
                    'total' => $revenuePromotion['total'],
                    'date' => $revenuePromotion['date']
                ];
            }
            DashboardRevenueFromPromotion::upsert(
                $revenuePromotionArr,
                ['id'],
                ['total', 'date']
            );
        }

        // for Revenue From Packages card
        if (!empty($revenueFromPacakge)) {
            $revenuePackageArr = [];
            foreach ($revenueFromPacakge as $revenuePackage) {
                $revenuePackageArr[] = [
                    'total' => $revenuePackage['total'],
                    'date' => $revenuePackage['date']
                ];
            }
            DashboardRevenueFromPackage::upsert(
                $revenuePackageArr,
                ['id'],
                ['total', 'date']
            );
        }

        // for highest rated seller card
        if (!empty($highestRatedSeller)) {
            $highestSellerArr = [];
            foreach ($highestRatedSeller as $highestSeller) {
                $highestSellerArr[] = [
                    'id' => $highestSeller->id,
                    'name' => $highestSeller->name,
                    'email' => $highestSeller->email,
                    'user_phone' => $highestSeller->user_phone,
                    'overall_rating' => $highestSeller->overall_rating,
                    'added_date' => $highestSeller->added_date,

                ];
            }
            DashboardHighestRatedSeller::upsert(
                $highestSellerArr,
                ['id'],
                ['name', 'email', 'user_phone', 'overall_rating', 'added_date']
            );
        }

        // for highest rated buyer card
        if (!empty($highestRatedBuyers)) {
            $highestBuyerArr = [];
            foreach ($highestRatedBuyers as $highestBuyer) {
                $highestBuyerArr[] = [
                    'id' => $highestBuyer->id,
                    'name' => $highestBuyer->name,
                    'email' => $highestBuyer->email,
                    'user_phone' => $highestBuyer->user_phone,
                    'overall_rating' => $highestBuyer->overall_rating,
                    'added_date' => $highestBuyer->added_date,

                ];
            }
            DashboardHighestRatedBuyer::upsert(
                $highestBuyerArr,
                ['id'],
                ['name', 'email', 'user_phone', 'overall_rating', 'added_date']
            );
        }

        // for recent registered user card
        if (!empty($recentRegisteredUsers)) {
            $recentRegisteredUserArr = [];
            foreach ($recentRegisteredUsers as $recentRegisteredUser) {
                $recentRegisteredUserArr[] = [
                    'id' => $recentRegisteredUser->id,
                    'name' => $recentRegisteredUser->name,
                    'email' => $recentRegisteredUser->email,
                    'user_phone' => $recentRegisteredUser->user_phone,
                    'overall_rating' => $recentRegisteredUser->overall_rating,
                    'added_date' => $recentRegisteredUser->added_date,

                ];
            }
            DashboardRecentRegisteredUser::upsert(
                $recentRegisteredUserArr,
                ['id'],
                ['name', 'email', 'user_phone', 'overall_rating', 'added_date']
            );
        }

        $dataArr = [
            // for table data
            'highest_rated_seller' => $highestRatedSeller,
            'highest_rated_buyer' => $highestRatedBuyers,
            'recent_registered_users' => $recentRegisteredUsers,
            // total user data
            'totalUsers' => $totalUsers,
            'totalBuyers' => $totalBuyers,
            'totalSellers' => $totalSellers,
            // total item count data
            'successful_deal_count' => $successfulDealCount,
            'total_sold_out_items' => $totalSoldOutItems,
            'total_slow_moving_items' => $totalSlowMovingItems,
            'filter' => $filter,
            // percentage data
            'buyerPercentage' => $buyerPercentage,
            'sellerPercentage' => $sellerPercentage,
            'userPercentage' => $userPercentage,
            'soldOutItemPercentage' => $soldOutItemPercentage,
            'slowMovingItemPercentage' => $slowMovingItemPercentage,
            'successfulDealCountItemPercentage' => $successfulDealCountItemPercentage,
            // chart data
            'most_engaging_products' => $mostEngagingProducts,
            'most_engaging_categories' => $mostEngagingCategories,
            'revenue_from_promotion' => $revenueFromPromotion,
            'revenue_from_packages' => $revenueFromPacakge,
            'most_purchased_packages' => $most_purchased_packages,
            'packages' => $packages,
            // filter
            'filter' => $filter,
        ];

        return $dataArr;
    }

    public function index($request)
    {

        if (collect($request)->isEmpty() || empty($request)) {
            $today = Carbon::now();
            $latestRefreshDate = DashboardLatestRefreshDate::first();
            $dateString = '';

            $latestRefreshedDateRange = '';
            if (!empty($latestRefreshDate)) {
                $latestRefreshedDateRange = getBetweenTwoDateRangeArr($latestRefreshDate->from, $latestRefreshDate->to, "d-M");
                $formatedToday = date($this->dateFormatForLastestRefresh, strtotime($today));
                $formatedLastestRefreshedDate = date($this->dateFormatForLastestRefresh, strtotime($latestRefreshDate->to));
                $formastedLastestRefreshedLast7Date = date($this->dateFormatForLastestRefresh, strtotime($latestRefreshDate->from));

                if ($formatedToday == $formatedLastestRefreshedDate) {
                    $dateString = $this->last7Days;
                } else {
                    $dateString = "$formastedLastestRefreshedLast7Date to $formatedLastestRefreshedDate";
                }
            }

            $filter = [
                'filter' => $dateString,
                'date' => ''
            ];

            $mostEngagingProducts = [];
            $revenueFromPromotion = [];
            $revenueFromPacakge = [];
            $most_purchased_packages = [];
            $mostEngagingCategories = [];

            // Revenue
            $revenueFromPromotion = DashboardRevenueFromPromotion::get();
            $revenueFromPacakge = DashboardRevenueFromPackage::get();

            // for all item data by conds with limit 5
            $mostEngagingProducts = DashboardMostEngagingProduct::get();
            $most_purchased_packages = DashboardMostPurchasedPackage::get();

            $pkgConds['payment_id'] = $this->packageInAppPurchasePaymentId;
            $packages = $this->paymentSettingService->getPaymentInfos(null, $this->packageCount, null, $pkgConds, 1);

            // total and percentage card
            $dashboardTotalUser = DashboardTotalAndPercentage::where('type', $this->totalUserType)->first();
            $totalUsers = $dashboardTotalUser ? $dashboardTotalUser->total : 0;
            $userPercentage = $dashboardTotalUser ? $dashboardTotalUser->percentage : 0;

            $dashboardTotalBuyer = DashboardTotalAndPercentage::where('type', $this->totalBuyerType)->first();
            $totalBuyers = $dashboardTotalBuyer ? $dashboardTotalBuyer->total : 0;
            $buyerPercentage = $dashboardTotalBuyer ? $dashboardTotalBuyer->percentage : 0;
            $onlyfourBuyerImages = DashboardCoreImage::where('img_type', $this->totalBuyerType)->get();

            $dashboardTotalSeller = DashboardTotalAndPercentage::where('type', $this->totalSellerType)->first();
            $totalSellers = $dashboardTotalSeller ? $dashboardTotalSeller->total : 0;
            $sellerPercentage = $dashboardTotalSeller ? $dashboardTotalSeller->percentage : 0;
            $onlyfourSellerImages = DashboardCoreImage::where('img_type', $this->totalSellerType)->get();


            $dashboardTotalSoldOutItem = DashboardTotalAndPercentage::where('type', $this->totalSoldOutItemType)->first();
            $totalSoldOutItems = $dashboardTotalSoldOutItem ? $dashboardTotalSoldOutItem->total : 0;
            $soldOutItemPercentage = $dashboardTotalSoldOutItem ? $dashboardTotalSoldOutItem->percentage : 0;

            $dashboardTotalSlowMovingItem = DashboardTotalAndPercentage::where('type', $this->totalSlowMovingItemType)->first();
            $totalSlowMovingItems = $dashboardTotalSlowMovingItem ? $dashboardTotalSlowMovingItem->total : 0;
            $slowMovingItemPercentage = $dashboardTotalSlowMovingItem ? $dashboardTotalSlowMovingItem->percentage : 0;

            $dashboardSuccessfulDealCount = DashboardTotalAndPercentage::where('type', $this->successfulDealCountType)->first();
            $successfulDealCount = $dashboardSuccessfulDealCount ? $dashboardSuccessfulDealCount->total : 0;
            $successfulDealCountItemPercentage = $dashboardSuccessfulDealCount ? $dashboardSuccessfulDealCount->percentage : 0;

            // for table data
            //            $highestRatedBuyers = DashboardHighestRatedBuyer::get();
            //            $highestRatedSeller = DashboardHighestRatedSeller::get();
            //            $recentRegisteredUsers = DashboardRecentRegisteredUser::get();

            $highestRatedBuyers = DashboardBuyerResource::collection(DashboardHighestRatedBuyer::paginate(5));
            $highestRatedSeller = DashboardSellerResource::collection(DashboardHighestRatedSeller::paginate(5));
            $recentRegisteredUsers = DashboardRecentRegisteredUserResource::collection(DashboardRecentRegisteredUser::paginate(5));
        } else {
            $row = $request->input('row') ?? Constants::dashboardDataTableDefaultRow;

            $defaultDate = [Carbon::now()->subDays(7), Carbon::now()];
            $lastDate = [Carbon::now()->subDays(14), Carbon::now()->subDays(7)];
            $itemRelation = [
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
            $userRelation = [
                'userRelation.uiType',
                'userRelation.customizeUi',
                'userRelation',
                'blue_mark',
                'role'
            ];
            $userConds['is_banned'] = 0;
            $userConds['date_range'] = $defaultDate;
            $itemConds['added_date_range'] = $defaultDate;

            $latestRefreshedDateRange = [];
            $mostEngagingProducts = [];
            $revenueFromPromotion = [];
            $revenueFromPacakge = [];
            $most_purchased_packages = [];
            $mostEngagingCategories = [];

            // Revenue
            $revenueFromPromotion = $this->paidItemService->getRevenueFromPromotion(null, null, $this->revenueFromPromotionCount, null, $itemConds, true);
            $revenueFromPacakge = $this->packageBoughtTransactionService->getRevenueFromPackage(null, null, $this->revenueFromPackageCount, null, $itemConds);

            // for all category data by conds with limit 5
            $categoryRelation = [];
            $categoryConds['date_range'] = $defaultDate;
            $mostEngagingCategories = $this->categoryService->getAll($categoryRelation, true, null, 5, null, $categoryConds, true, null, true);

            // for all item data by conds with limit 5
            $mostEngagingProducts = $this->itemService->getItems($itemRelation, true, null, $this->mostEngagingProductCount, null, $itemConds);
            $packageConds['added_date_range'] = $defaultDate;
            $most_purchased_packages = $this->packageBoughtTransactionService->getMostPurchsedPackages(null, null, $this->mostPurchasedPackageCount, null, $packageConds);

            $pkgConds['payment_id'] = $this->packageInAppPurchasePaymentId;
            $packages = $this->paymentSettingService->getPaymentInfos(null, null, null, $pkgConds, 1);

            $filter = [
                'filter' => $this->last7Days,
                'date' => ''
            ];

            // for table data
            // for all user from table
            $allUserConds['is_banned'] = 0;
            //        $totalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $allUserConds, null, null, null, true ));
            $totalUsers = SpecificUser::first() ? SpecificUser::first()->total : 0;
            $totalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $allUserConds, null, null, null, true));
            $totalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $allUserConds, null, null, null, true));

            // for table data with paginate according to date filter
            $userConds['is_banned'] = 0;
            $userConds['date_range'] = $defaultDate;
            $highestRatedBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $this->highestRatedBuyerCount));
            $highestRatedSeller = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $this->highestRatedSellerCount));
            $recentRegisteredUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $this->recentRegisteredUserCount));

            // for all user by date conds (in last 7 day data)
            $userConds['is_banned'] = 0;
            $userConds['date_range'] = $defaultDate;
            $newTotalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
            $newTotalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
            $newTotalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();

            // for all user by date conds (previous in last 7 day data)
            $userConds['is_banned'] = 0;
            $userConds['date_range'] = $lastDate;
            $lastTotalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
            $lastTotalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
            $lastTotalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();

            // for all item by date conds
            $totalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, true)->count();
            $totalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, true)->count();
            $successfulDealCountRelation = [
                'category',
                'subcategory',
                'city',
                'township',
                'currency',
                'itemRelation.uiType',
                'itemRelation.customizeUi',
                'owner',
                'itemRelation',
                'buyer',
                'seller',
            ];
            $successfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, true)->count();

            // for all item by date conds (in last 14 day data)
            $itemConds['added_date_range'] = $defaultDate;
            $newTotalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, true, null, null, null, $itemConds)->count();
            $newTotalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, true, null, null, null, $itemConds)->count();
            $newSuccessfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, true, null, null, null, $itemConds)->count();

            // for all item by date conds (previous in last 14 day data)
            $itemConds['added_date_range'] = $lastDate;
            $lastTotalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, false, $row, null, null, $itemConds)->count();
            $lastTotalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, false, $row, null, null, $itemConds)->count();
            $lastSuccessfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, false, $row, null, null, $itemConds)->count();

            // Percent Calculation
            if ($lastTotalBuyers == $newTotalBuyers) {
                $buyerPercentage = 0;
            } else if ($lastTotalBuyers == 0) {
                $buyerPercentage = 100;
            } else {
                $buyerPercentage = number_format(($lastTotalBuyers - $newTotalBuyers) / ($lastTotalBuyers * 100), 2);
            }

            if ($lastTotalSellers == $newTotalSellers) {
                $sellerPercentage = 0;
            } else if ($lastTotalSellers == 0) {
                $sellerPercentage = 100;
            } else {
                $sellerPercentage = number_format(($lastTotalSellers - $newTotalSellers) / ($lastTotalSellers * 100), 2);
            }

            if ($lastTotalUsers == $newTotalUsers) {
                $userPercentage = 0;
            } else if ($lastTotalUsers == 0) {
                $userPercentage = 100;
            } else {
                $userPercentage = number_format(($lastTotalUsers - $newTotalUsers) / ($lastTotalUsers * 100), 2);
            }

            if ($lastTotalSoldOutItems == $newTotalSoldOutItems) {
                $soldOutItemPercentage = 0;
            } else if ($lastTotalSoldOutItems == 0) {
                $soldOutItemPercentage = 100;
            } else {
                $soldOutItemPercentage = number_format(($lastTotalSoldOutItems - $newTotalSoldOutItems) / ($lastTotalSoldOutItems * 100), 2);
            }

            if ($lastTotalSlowMovingItems == $newTotalSlowMovingItems) {
                $slowMovingItemPercentage = 0;
            }
            if ($lastTotalSlowMovingItems == 0) {
                $slowMovingItemPercentage = 100;
            } else {
                $slowMovingItemPercentage = number_format(($lastTotalSlowMovingItems - $newTotalSlowMovingItems) / ($lastTotalSlowMovingItems * 100), 2);
            }

            if ($lastSuccessfulDealCount == $newSuccessfulDealCount) {
                $successfulDealCountItemPercentage = 0;
            }
            if ($lastSuccessfulDealCount == 0) {
                $successfulDealCountItemPercentage = 100;
            } else {
                $successfulDealCountItemPercentage = number_format(($lastSuccessfulDealCount - $newSuccessfulDealCount) / ($lastSuccessfulDealCount * 100), 2);
            }
        }




        $dataArr = [
            'latest_refreshed_date_range' => $latestRefreshedDateRange,
            // for table data
            'highest_rated_seller' => $highestRatedSeller,
            'highest_rated_buyer' => $highestRatedBuyers,
            'recent_registered_users' => $recentRegisteredUsers,
            // total user data
            'totalUsers' => $totalUsers,
            'totalBuyers' => $totalBuyers,
            'onlyFourBuyerImages' => $onlyfourBuyerImages,
            'totalSellers' => $totalSellers,
            'onlyFourSellerImages' => $onlyfourSellerImages,
            // total item count data
            'successful_deal_count' => $successfulDealCount,
            'total_sold_out_items' => $totalSoldOutItems,
            'total_slow_moving_items' => $totalSlowMovingItems,
            'filter' => $filter,
            'endDate' => $latestRefreshDate->to,
            // percentage data
            'buyerPercentage' => $buyerPercentage,
            'sellerPercentage' => $sellerPercentage,
            'userPercentage' => $userPercentage,
            'soldOutItemPercentage' => $soldOutItemPercentage,
            'slowMovingItemPercentage' => $slowMovingItemPercentage,
            'successfulDealCountItemPercentage' => $successfulDealCountItemPercentage,
            // chart data
            'most_engaging_products' => $mostEngagingProducts,
            'most_engaging_categories' => $mostEngagingCategories,
            'revenue_from_promotion' => $revenueFromPromotion,
            'revenue_from_packages' => $revenueFromPacakge,
            'most_purchased_packages' => $most_purchased_packages,
            'packages' => $packages,
            // filter
            'filter' => $filter,
        ];

        return $dataArr;
    }


    public function search($request)
    {
        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dashboardDataTableDefaultRow;

        $itemRelation = [
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

        $userRelation = [
            'userRelation.uiType',
            'userRelation.customizeUi',
            'userRelation',
            'blue_mark',
            'role'
        ];
        $userConds['is_banned'] = 0;

        $revenueFromPromotion = [];
        $revenueFromPacakge = [];

        if ($request->highest_rated_buyer_sort_field) {
            $conds['order_by'] = $request->highest_rated_buyer_sort_field;
            $conds['order_type'] = $request->highest_rated_buyer_sort_order;
        } elseif ($request->highest_rated_seller_sort_field) {
            $conds['order_by'] = $request->highest_rated_seller_sort_field;
            $conds['order_type'] = $request->highest_rated_seller_sort_order;
        } elseif ($request->recent_registered_user_sort_field) {
            $conds['order_by'] = $request->recent_registered_user_sort_field;
            $conds['order_type'] = $request->recent_registered_user_sort_order;
        }

        if (!empty($request->form)) {
            $filter = $request->form['filter'];
            if (!empty($request->form['date'])) {
                $dateFromReq = $request->form['date'];
            } else {
                $dateFromReq = '';
            }
        } else {
            $filter = $request->filter;
            $dateFromReq = $request->date;
        }

        // previous last 7 days
        $defaultDate = [Carbon::now()->subDays(7), Carbon::now()];
        $lastDate = [Carbon::now()->subDays(14), Carbon::now()->subDays(7)];
        $itemConds['added_date_range'] = $defaultDate;

        // Revenue
        $revenueFromPromotion = $this->paidItemService->getRevenueFromPromotion(null, null, $this->revenueFromPromotionCount, null, $itemConds, true);
        $revenueFromPacakge = $this->packageBoughtTransactionService->getRevenueFromPackage(null, null, $this->revenueFromPackageCount, null, $itemConds);

        // for all user from table
        $allUserConds['is_banned'] = 0;
        $totalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $allUserConds, null, null, null, true));
        $totalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $allUserConds, null, null, null, true));
        $totalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $allUserConds, null, null, null, true));

        // for all user by date conds (in last 7 day data)
        $userConds['is_banned'] = 0;
        $userConds['date_range'] = $defaultDate;
        $newTotalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
        $newTotalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
        $newTotalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();

        // for all user by date conds (previous in last 7 day data)
        $userConds['is_banned'] = 0;
        $userConds['date_range'] = $lastDate;
        $lastTotalBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
        $lastTotalSellers = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();
        $lastTotalUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $row))->count();

        // for all item by date conds
        $totalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, true)->count();
        $totalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, true)->count();
        $successfulDealCountRelation = [
            'category',
            'subcategory',
            'city',
            'township',
            'currency',
            'itemRelation.uiType',
            'itemRelation.customizeUi',
            'owner',
            'itemRelation',
            'buyer',
            'seller',
        ];
        $successfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, true)->count();

        // for all item by date conds (in last 14 day data)
        $itemConds['added_date_range'] = $defaultDate;
        $newTotalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, true, null, null, null, $itemConds)->count();
        $newTotalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, true, null, null, null, $itemConds)->count();
        $newSuccessfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, true, null, null, null, $itemConds)->count();

        // for all item by date conds (previous in last 14 day data)
        $itemConds['added_date_range'] = $lastDate;
        $lastTotalSoldOutItems = $this->itemService->getSoldOutItems($itemRelation, false, $row, null, null, $itemConds)->count();
        $lastTotalSlowMovingItems = $this->itemService->getSlowMovingItems($itemRelation, false, $row, null, null, $itemConds)->count();
        $lastSuccessfulDealCount = $this->itemService->getSuccessfulDealItems($successfulDealCountRelation, false, $row, null, null, $itemConds)->count();

        // Percent Calculation
        if ($lastTotalBuyers == $newTotalBuyers) {
            $buyerPercentage = 0;
        } else if ($lastTotalBuyers == 0) {
            $buyerPercentage = 100;
        } else {
            $buyerPercentage = number_format(($lastTotalBuyers - $newTotalBuyers) / ($lastTotalBuyers * 100), 2);
        }

        if ($lastTotalSellers == $newTotalSellers) {
            $sellerPercentage = 0;
        } else if ($lastTotalSellers == 0) {
            $sellerPercentage = 100;
        } else {
            $sellerPercentage = number_format(($lastTotalSellers - $newTotalSellers) / ($lastTotalSellers * 100), 2);
        }

        if ($lastTotalUsers == $newTotalUsers) {
            $userPercentage = 0;
        } else if ($lastTotalUsers == 0) {
            $userPercentage = 100;
        } else {
            $userPercentage = number_format(($lastTotalUsers - $newTotalUsers) / ($lastTotalUsers * 100), 2);
        }

        if ($lastTotalSoldOutItems == $newTotalSoldOutItems) {
            $soldOutItemPercentage = 0;
        } else if ($lastTotalSoldOutItems == 0) {
            $soldOutItemPercentage = 100;
        } else {
            $soldOutItemPercentage = number_format(($lastTotalSoldOutItems - $newTotalSoldOutItems) / ($lastTotalSoldOutItems * 100), 2);
        }

        if ($lastTotalSlowMovingItems == $newTotalSlowMovingItems) {
            $slowMovingItemPercentage = 0;
        }
        if ($lastTotalSlowMovingItems == 0) {
            $slowMovingItemPercentage = 100;
        } else {
            $slowMovingItemPercentage = number_format(($lastTotalSlowMovingItems - $newTotalSlowMovingItems) / ($lastTotalSlowMovingItems * 100), 2);
        }

        if ($lastSuccessfulDealCount == $newSuccessfulDealCount) {
            $successfulDealCountItemPercentage = 0;
        }
        if ($lastSuccessfulDealCount == 0) {
            $successfulDealCountItemPercentage = 100;
        } else {
            $successfulDealCountItemPercentage = number_format(($lastSuccessfulDealCount - $newSuccessfulDealCount) / ($lastSuccessfulDealCount * 100), 2);
        }

        $pkgConds['payment_id'] = $this->packageInAppPurchasePaymentId;
        $packages = $this->paymentSettingService->getPaymentInfos(null, null, null, $pkgConds, 1);

        $date = Carbon::now();
        $start_date = Carbon::now();
        $end_date = Carbon::now();
        if ($filter == $this->yesterday) {
            $date = Carbon::yesterday();
        } else if ($filter == $this->last7Days) {
            $start_date = Carbon::now()->subDays(7);
        } else if ($filter == $this->last14Days) {
            $start_date = Carbon::now()->subDays(14);
        } else if ($filter == $this->last30Days) {
            $start_date = Carbon::now()->subDays(30);
        } else if ($filter == $this->last60Days) {
            $start_date = Carbon::now()->subDays(60);
        } else if ($filter == $this->custom) {
            $date = $dateFromReq;

            if ($date) {
                $start_date = $date[0];
                if ($date[1]) {
                    $end_date = $date[1];
                }
            } else {
                $start_date = Carbon::now()->subDays(7);
                $end_date = Carbon::now();
            }
        }

        if ($filter == $this->today || $filter == $this->yesterday) {

            // for all category data by conds with limit 5
            $categoryRelation = [];
            $categoryConds['added_date'] = $date;
            $mostEngagingCategories = $this->categoryService->getAll($categoryRelation, true, null, 5, null, $categoryConds, true, null, true);

            $itemConds['added_date'] = $date;
            $mostEngagingProducts = $this->itemService->getItems($itemRelation, true, null, $this->mostEngagingProductCount, null, $itemConds);
            $packageConds['added_date'] = $date;
            $most_purchased_packages = $this->packageBoughtTransactionService->getMostPurchsedPackages(null, null, $this->mostPurchasedPackageCount, null, $packageConds);

            // for table data with paginate
            $userConds['is_banned'] = 0;
            $userConds['added_date'] = $date;
            $highestRatedBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $this->highestRatedBuyerCount));
            $highestRatedSeller = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $this->highestRatedSellerCount));
            $recentRegisteredUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $this->recentRegisteredUserCount));
        } else {
            $startAndEndDate = [$start_date, $end_date];

            // for all category data by conds with limit 5
            $categoryRelation = [];
            $categoryConds['date_range'] = $startAndEndDate;
            $mostEngagingCategories = $this->categoryService->getAll($categoryRelation, true, null, 5, null, $categoryConds, true, null, true);

            $itemConds['added_date_range'] = $startAndEndDate;
            $mostEngagingProducts = $this->itemService->getItems($itemRelation, true, null, $this->mostEngagingProductCount, null, $itemConds);
            $packageConds['added_date_range'] = $startAndEndDate;
            $most_purchased_packages = $this->packageBoughtTransactionService->getMostPurchsedPackages(null, null, $this->mostPurchasedPackageCount, null, $packageConds);

            $userConds['is_banned'] = 0;
            $userConds['date_range'] = $startAndEndDate;
            $highestRatedBuyers = BuyerReportWithKeyResource::collection($this->userService->getBuyers($userRelation, true, false, $userConds, null, null, null, false, $this->highestRatedBuyerCount));
            $highestRatedSeller = SellerReportWithKeyResource::collection($this->userService->getSellers($userRelation, true, false, $userConds, null, null, null, false, $this->highestRatedSellerCount));
            $recentRegisteredUsers = UserReportWithKeyResource::collection($this->userService->getUsers($userRelation, true, false, $userConds, null, null, null, false, $this->recentRegisteredUserCount));
        }

        $filter = [
            'filter' => $filter,
            'date' => $dateFromReq
        ];

        if ($conds['order_by']) {
            $dataArr = [
                // order by
                'highest_rated_buyer_sort_field' => $conds['order_by'],
                'highest_rated_buyer_sort_order' => $request->highest_rated_buyer_sort_order,
                'highest_rated_seller_sort_field' => $conds['order_by'],
                'highest_rated_seller_sort_order' => $request->highest_rated_seller_sort_order,
                'recent_registered_user_sort_field' => $conds['order_by'],
                'recent_registered_user_sort_order' => $request->recent_registered_user_sort_order,

                // for table data
                'highest_rated_seller' => $highestRatedSeller,
                'highest_rated_buyer' => $highestRatedBuyers,
                'recent_registered_users' => $recentRegisteredUsers,
                // total user data
                'totalUsers' => $totalUsers,
                'totalBuyers' => $totalBuyers,
                'totalSellers' => $totalSellers,
                // total item count data
                'successful_deal_count' => $successfulDealCount,
                'total_sold_out_items' => $totalSoldOutItems,
                'total_slow_moving_items' => $totalSlowMovingItems,
                'filter' => $filter,
                // percentage data
                'buyerPercentage' => $buyerPercentage,
                'sellerPercentage' => $sellerPercentage,
                'userPercentage' => $userPercentage,
                'soldOutItemPercentage' => $soldOutItemPercentage,
                'slowMovingItemPercentage' => $slowMovingItemPercentage,
                'successfulDealCountItemPercentage' => $successfulDealCountItemPercentage,
                // chart data
                'most_engaging_products' => $mostEngagingProducts,
                'most_engaging_categories' => $mostEngagingCategories,
                'revenue_from_promotion' => $revenueFromPromotion,
                'revenue_from_packages' => $revenueFromPacakge,
                'most_purchased_packages' => $most_purchased_packages,
                'packages' => $packages,
                // filter
                'filter' => $filter,
            ];
        } else {
            $dataArr = [
                // for table data
                'highest_rated_seller' => $highestRatedSeller,
                'highest_rated_buyer' => $highestRatedBuyers,
                'recent_registered_users' => $recentRegisteredUsers,
                // total user data
                'totalUsers' => $totalUsers,
                'totalBuyers' => $totalBuyers,
                'totalSellers' => $totalSellers,
                // total item count data
                'successful_deal_count' => $successfulDealCount,
                'total_sold_out_items' => $totalSoldOutItems,
                'total_slow_moving_items' => $totalSlowMovingItems,
                'filter' => $filter,
                // percentage data
                'buyerPercentage' => $buyerPercentage,
                'sellerPercentage' => $sellerPercentage,
                'userPercentage' => $userPercentage,
                'soldOutItemPercentage' => $soldOutItemPercentage,
                'slowMovingItemPercentage' => $slowMovingItemPercentage,
                'successfulDealCountItemPercentage' => $successfulDealCountItemPercentage,
                // chart data
                'most_engaging_products' => $mostEngagingProducts,
                'most_engaging_categories' => $mostEngagingCategories,
                'revenue_from_promotion' => $revenueFromPromotion,
                'revenue_from_packages' => $revenueFromPacakge,
                'most_purchased_packages' => $most_purchased_packages,
                'packages' => $packages,
                // filter
                'filter' => $filter,
            ];
        }


        return $dataArr;
    }


    public function updateLicenseForController($request)
    {
        return $this->updateLicense($request);
    }

    public function activateLicenseForController($request)
    {
        return $this->activateLicense($request);
    }

    public function checkConnection($request)
    {

        try {
            $token = PersonalAccessToken::findToken($request->token);

            if ($token->exists) {
                $connection = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::checkBuilderConnection);
                if ($connection->status == "error") {
                    throw ValidationException::withMessages(["error" => "Connection Fail"]);
                }
            } else {
                throw ValidationException::withMessages(["error" => "Token Not Valid"]);
            }
        } catch (\Throwable $e) {
            return $e->getMessage();
        }
    }

    public function setupAutoSync($request)
    {

        try {

            $project = Project::first();
            $project->first_time_sync = 1;
            $project->update();

            $dataArr = [
                "autoSyncStatus" => 'success',
                "autoSyncMessage" => __('auto_sync_success')
            ];
        } catch (\Throwable $e) {
            $dataArr = [
                "autoSyncStatus" => 'error',
                "autoSyncMessage" => $e->getMessage()
            ];
        }


        return $dataArr;
    }

    public function activateLicenseWithBuilderConnection($request)
    {

        try {

            $response = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getProject, "&api_key=" . getApiKey());

            if ($response && $response->project) {
                $dataArr = $this->activateLicenseFromBuilder(json_decode($response->project));
                // Update Builder App Info Cache
                $ishaveCachRow =  $builderAppInfoCache = BuilderAppInfoCache::first();
                if ($ishaveCachRow) {
                    $buildAppInfoCache = new \stdClass();
                    $buildAppInfoCache->isValid = 1;
                    updateBuilderAppInfoCache($buildAppInfoCache);
                }
            } else {
                $baseProjectObj = new \stdClass();
                $baseProjectObj->status = "danger";
                $baseProjectObj->message = __('get_project_api_error');
                $logMessages[] = $baseProjectObj;
                $dataArr = [
                    "logMessages" => $logMessages,
                    "hasError" => 1,
                    "activateStatus" => 'false'
                    // "zipFileName" => $fileName,
                ];
            }
        } catch (\Throwable $e) {
            $baseProjectObj = new \stdClass();
            $baseProjectObj->status = "danger";
            $baseProjectObj->message = $e->getMessage();
            $logMessages[] = $baseProjectObj;
            $dataArr = [
                "logMessages" => $logMessages,
                "hasError" => 1,
                "activateStatus" => 'false'
            ];
        }

        return $dataArr;
    }

    public function downloadAndImportProject()
    {
        try {

            $sync_project = getHttpWithApiKey(ps_constant::base_url, getApiKey(), ps_url::getNextProjectJson, "&api_key=" . getApiKey());

            $tableService = $this->tableService->handleProjectNotSameBuilderSync($sync_project);

            if (empty($tableService['success']) || $sync_project == null) {
                $downloadObj = new \stdClass();
                $downloadObj->status = "error";
                $downloadObj->message = __('error_import');
                $downloadStatus[] = $downloadObj;

                $dataArr = [
                    "downloadStatus" => $downloadObj,
                ];
            } else {
                $downloadObj = new \stdClass();
                $downloadObj->status = "success";
                $downloadObj->message = __('download_success');
                $downloadStatus[] = $downloadObj;

                $dataArr = [
                    "downloadStatus" => $downloadObj,
                ];
            }
        } catch (\Throwable $e) {
            $downloadObj = new \stdClass();
            $downloadObj->status = "error";
            $downloadObj->message = $e->getMessage();
            $downloadStatus[] = $downloadObj;

            $dataArr = [
                "downloadStatus" => $downloadObj,
            ];
        }
        return $dataArr;
    }
}
