<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Item;

use App\Config\ps_constant;
use App\Exceptions\PsApiException;
use App\Http\Contracts\Authorization\PermissionServiceInterface;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Configuration\AdPostTypeServiceInterface;
use App\Http\Contracts\Configuration\BackendSettingServiceInterface;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use App\Http\Contracts\Configuration\SystemConfigServiceInterface;
use App\Http\Contracts\Image\ImageServiceInterface;
use App\Http\Contracts\Item\ItemServiceInterface;
use App\Http\Contracts\Item\PaidItemHistoryServiceInterface;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use App\Http\Contracts\User\BlockUserServiceInterface;
use App\Http\Contracts\User\UserInfoServiceInterface;
use App\Http\Contracts\User\UserServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Utilities\UiTypeServiceInterface;
use App\Http\Contracts\Utilities\VideoServiceInterface;
use App\Http\Contracts\Vendor\VendorServiceInterface;
use App\Http\Controllers\PsApiController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\CoreImage\CoreImageApiResource;
use Modules\Core\Transformers\Api\App\V1_0\HomePageSearch\HomePageSearchApiResource;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Core\Http\Services\Item\ComplaintItemService;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\Item\Item;
use Modules\Core\Entities\Item\PaidItemHistory;
use Modules\Core\Entities\User\BlockUser;
use Modules\Core\Transformers\Api\App\V1_0\Vendor\VendorForItemEntryApiResource;

use Modules\Core\Http\Requests\Item\CoverUploadItemApiRequest;
use Modules\Core\Http\Requests\Item\CreateItemApiRequest;
use Modules\Core\Http\Requests\Item\DeleteItemApiRequest;
use Modules\Core\Http\Requests\Item\DestroyImageItemApiRequest;
use Modules\Core\Http\Requests\Item\DestroyVideoItemApiRequest;
use Modules\Core\Http\Requests\Item\GetGalleryListItemApiRequest;
use Modules\Core\Http\Requests\Item\GetItemByIdItemApiRequest;
use Modules\Core\Http\Requests\Item\GetRelatedTrendingItemApiRequest;
use Modules\Core\Http\Requests\Item\IconUploadItemApiRequest;
use Modules\Core\Http\Requests\Item\ReorderImagesItemApiRequest;
use Modules\Core\Http\Requests\Item\SoldoutFromDetailItemApiRequest;
use Modules\Core\Http\Requests\Item\StatusChangeItemApiRequest;
use Modules\Core\Http\Requests\Item\StoreItemApiRequest;
use Modules\Core\Http\Requests\Item\UploadMultiItemRequest;
use Modules\Core\Http\Requests\Item\VideoUploadItemApiRequest;
use Modules\Core\Http\Requests\Item\VideoUploadItemApiRequestV2;
use Modules\Core\Http\Services\SearchHistoryService;
use Modules\Core\Transformers\Api\App\V1_0\Item\ItemApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Utilities\CoreFieldApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Utilities\CustomFieldApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Utilities\CustomFieldAttributeApiResource;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ItemApiController extends PsApiController
{
    protected $itemApiRelation;

    public function __construct(
        protected VendorServiceInterface $vendorService,
        protected PaidItemHistoryServiceInterface $paidItemHistoryService,
        protected Translator $translator,
        protected ItemServiceInterface $itemService,
        protected SystemConfigServiceInterface $systemConfigService,
        protected UserInfoServiceInterface $userInfoService,
        protected UserServiceInterface $userService,
        protected MobileSettingServiceInterface $mobileSettingService,
        protected CoreFieldServiceInterface $coreFieldService,
        protected CustomFieldServiceInterface $customFieldService,
        protected ImageServiceInterface $imageService,
        protected LanguageServiceInterface $languageService,
        protected CustomFieldAttributeServiceInterface $customFieldAttributeService,
        protected UiTypeServiceInterface $uiTypeService,
        protected BlockUserServiceInterface $blockUserService,
        protected ComplaintItemService $complaintItemService,
        protected AdPostTypeServiceInterface $adPostTypeService,
        protected SearchHistoryService $searchHistoryService,
        protected BackendSettingServiceInterface $backendSettingService,
        protected CategoryServiceInterface $categoryService,
        protected PermissionServiceInterface $permissionService,
        protected VideoServiceInterface $videoService
    ) {
        parent::__construct();
        $this->itemApiRelation = ['vendor', 'category', 'subcategory', 'city', 'township', 'currency', 'owner', 'itemRelation', 'cover', 'video', 'icon'];
    }

    public function index(Request $request)
    {
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $data = ItemApiResource::collection($this->itemService->getAll(
            relations: $this->itemApiRelation,
            limit: $limit,
            offset: $offset
        ));

        return $this->handleNoDataResponse($offset, $data);
    }

    public function uploadMulti(UploadMultiItemRequest $request)
    {
        try {
            $validData = $request->validated();
            $file = $request->file('file');
            return $this->itemService->updateMultiImage($validData, $file);
        } catch (Throwable $e) {
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }
    }

    public function create(CreateItemApiRequest $request)
    {
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');

        $dataArr = $this->prepareCreateData($validatedData, $limit, $offset, $loginUserId, $langSymbol);

        return responseDataApi($dataArr);
    }

    public function getGalleryList(GetGalleryListItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $imgType = $validatedData[CoreImage::imgType] ?? 'item_related';
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $conds['order_by'] = 'ordering';
        $conds['order_type'] = 'asc';

        $data = CoreImageApiResource::collection(
            $this->imageService->getAll(
                imgParentId: $validatedData[CoreImage::imgParentId],
                imgType: $imgType,
                limit: $limit,
                offset: $offset,
                conds: $conds
            )
        );

        return $this->handleNoDataResponse($offset, $data);
    }

    public function store(StoreItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $relationalData = $this->prepareDataCustomFields($request);
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $userInfo = $this->userInfoService->get(null, null, $loginUserId, Constants::usrRemainingPost);
        $systemConfig = $this->systemConfigService->get();
        $user = $this->userService->get(conds: ['id' => $loginUserId]);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end
        
        // check vendor permission
        if (!empty($validatedData['vendor_id']) 
            && !$this->permissionService->vendorPermissionControl(Constants::vendorItemModule, ps_constant::createPermission, $validatedData['vendor_id'], $loginUserId)) {
            $msg = __('core__api_update_no_permission_for_vendor', [], $langSymbol);
            throw new PsApiException($msg, Constants::forbiddenStatusCode);
        }

        // check user have upload perimission depend on setting
        $this->checkUserUploadPermission($user, $loginUserId, $langSymbol);

        // if IsPaidApp on, will check available post count to upload
        $this->checkIsPostCountEnough($systemConfig, $user, $langSymbol, $userInfo);

        $id = $validatedData['id'];
        if (empty($id)) {

            try {
                $isReduceRemainPostCount = $this->handlePostCountReduceConds($systemConfig, $user, $validatedData['vendor_id']);
                $item = $this->itemService->saveFromApi($validatedData, $relationalData, $userInfo, $isReduceRemainPostCount);

                $data = new ItemApiResource($this->itemService->get($item->id, $this->itemApiRelation));
                return responseDataApi($data);
            } catch (Throwable $e) {
                throw new PsApiException($e->getMessage() . $e->getFile() . $e->getLine(), Constants::internalServerErrorStatusCode);
            }
        } else {
            $itemObj = $this->itemService->get($id);

            // check ownership permission start
            $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
            // check ownership permission end

            // if vendor item, will check
            $this->checkVendorCurrency($itemObj, $langSymbol);

            try {

                $item = $this->itemService->updateFromApi($itemObj, $validatedData, $relationalData);

                $data = new ItemApiResource($this->itemService->get($item->id, $this->itemApiRelation));

                return responseDataApi($data);
            } catch (\Throwable $e) {
                throw new PsApiException($e->getMessage() . $e->getFile() . $e->getLine(), Constants::internalServerErrorStatusCode);
            }
        }
    }

    public function deleteItem(DeleteItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        try {
            $this->itemService->delete($validatedData['id']);
            return responseMsgApi(
                __('core__api_item_delete_success', [], $langSymbol),
                Constants::okStatusCode,
                Constants::successStatus
            );
        } catch (Throwable $e) {
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }
    }

    public function destroyImage(DestroyImageItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $imageObj = $this->imageService->get(['id' => $validatedData['img_id']]);
        $itemObj = $this->itemService->get($imageObj->img_parent_id);

        // check permission start
        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
        // check permission end

        try {
            // delete File
            $this->imageService->delete($imageObj->img_path);
            $imageObj->delete();

            return responseMsgApi(
                __('core__api_delete_image_success', [], $langSymbol),
                Constants::okStatusCode,
                Constants::successStatus
            );
        } catch (\Throwable $e) {
            throw new PsApiException(__('core__api_db_error', [], $langSymbol), Constants::internalServerErrorStatusCode);
        }
    }

    public function destroyVideo(DestroyVideoItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $imageObj = $this->imageService->get(['id' => $validatedData['img_id']]);
        $itemObj = $this->itemService->get($imageObj->img_parent_id);

        // check permission start
        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
        // check permission end

        try {
            // delete File
            $this->imageService->delete($imageObj->img_path);
            $imageObj->delete();

            return responseMsgApi(
                __('core__api_delete_video_success', [], $langSymbol),
                Constants::okStatusCode,
                Constants::successStatus
            );
        } catch (\Throwable $e) {
            throw new PsApiException(__('core__api_db_error', [], $langSymbol), Constants::internalServerErrorStatusCode);
        }
    }

    public function getItemById(GetItemByIdItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $langSymbol = $request->query('language_symbol');

        $activeLanguage = $this->languageService->get(
            conds: ['symbol' => $langSymbol ?? 'en']
        );
        $itemApiRelation = ['vendor', 'category.categoryLanguageString', 'subcategory.subCategoryLanguageString', 'city', 'township', 'currency', 'owner', 'itemRelation' => ['customizeUi'], 'cover', 'video', 'icon'];

        $item = $this->itemService->get($validatedData['id'], $itemApiRelation);

        return responseDataApi(new ItemApiResource($item));
    }

    public function customizeDetails(Request $request, $core_keys_id)
    {
        $langSymbol = $request->query('language_symbol');
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $customFieldAttributes = $this->customFieldAttributeService->getAll(
            coreKeysId: $core_keys_id,
            limit: $limit,
            offset: $offset,
            isLatest: Constants::yes,
            noPagination: Constants::yes
        );

        if ($customFieldAttributes->isEmpty()) {
            $msg =  __('core__api_record_not_found', [], $langSymbol);
            throw new PsApiException($msg, Constants::notFoundStatusCode);
        }

        $data = CustomFieldAttributeApiResource::collection($customFieldAttributes);

        return $this->handleNoDataResponse($offset, $data);
    }

    public function coverUpload(CoverUploadItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $itemId = $validatedData['item_id'];
        $imgId = $validatedData['img_id'];
        $file = $request->file('cover');
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $itemObj = $this->itemService->get($itemId);

        // check permission start
        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
        // check permission end

        $this->checkMaxImageUpload($itemId, $langSymbol);

        DB::beginTransaction();
        try {

            $imgData = $this->prepareImageData($itemId, Constants::itemCoverImgType, $validatedData['ordering']);
            if (empty($imgId)) {
                $this->imageService->save($file, $imgData);
            } else {
                $this->imageService->update($imgId, $file, $imgData);
            }

            $this->itemService->generateDeeplink($itemId);

            $data = new CoreImageApiResource($this->imageService->get($imgData));

            DB::commit();

            return responseDataApi($data);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new PsApiException(__('core__api_db_error', [], $langSymbol), Constants::internalServerErrorStatusCode);
        }
    }

    public function iconUpload(IconUploadItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $itemId = $validatedData['item_id'];
        $imgId = $validatedData['img_id'];
        $file = $request->file('video_icon');
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $itemObj = $this->itemService->get($itemId);

        // check permission start
        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
        // check permission end

        $this->checkMaxImageUpload($itemId, $langSymbol);

        DB::beginTransaction();
        try {

            $imgData = $this->prepareImageData($itemId, Constants::itemVideoIconImgType);
            if (empty($imgId)) {
                $this->imageService->save($file, $imgData);
            } else {
                $this->imageService->update($imgId, $file, $imgData);
            }

            $data = new CoreImageApiResource($this->imageService->get($imgData));

            DB::commit();

            return responseDataApi($data);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new PsApiException(__('core__api_db_error', [], $langSymbol), Constants::internalServerErrorStatusCode);
        }
    }

    public function videoUploadV2(VideoUploadItemApiRequestV2 $request)
    {
        $meta = [];
        $validatedData = $request->validated();

        $file = $request->file('video');
        $itemId = $validatedData['item_id'];
        $loginUserId = $request->query('login_user_id');
        $meta['name'] = $request->input('name');
        $meta['total_chunks'] = $request->input('total_chunks');
        $meta['file_size'] = $request->input('file_size');
        $meta['chunk_no'] = $request->input('chunk_no');
        $meta['img_id'] = $request->input('img_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $itemObj = $this->itemService->get($itemId);

        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);

        $imgData = $this->prepareImageData($itemId, Constants::itemVideoImgType);

        $this->videoService->uploadVideo($loginUserId, $file, $meta, $imgData);

        return responseDataApi(['message' => 'Chunk uploaded']);

    }

    public function videoUpload(VideoUploadItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $itemId = $validatedData['item_id'];
        $imgId = $validatedData['img_id'];
        $file = $request->file('video');
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $itemObj = $this->itemService->get($itemId);

        // check permission start
        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
        // check permission end

        $this->checkMaxImageUpload($itemId, $langSymbol);

        DB::beginTransaction();
        try{

            $imgData = $this->prepareImageData($itemId, Constants::itemVideoImgType);
            if(empty($imgId)){
                $this->imageService->saveVideo($file, $imgData);
            } else {
                $this->imageService->updateVideo($imgId, $file, $imgData);
            }

            $data = new CoreImageApiResource($this->imageService->get($imgData));

            DB::commit();

            return responseDataApi($data);


        } catch (\Throwable $e) {
            DB::rollBack();
            throw new PsApiException(__('core__api_db_error', [], $langSymbol), Constants::internalServerErrorStatusCode);
        }
    }




    public function videoUpload_Original(VideoUploadItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $itemId = $validatedData['item_id'];
        $imgId = $validatedData['img_id'];
        $file = $request->file('video');
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $itemObj = $this->itemService->get($itemId);

        // check permission start
        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
        // check permission end

        $this->checkMaxImageUpload($itemId, $langSymbol);

        DB::beginTransaction();
        try {

            $imgData = $this->prepareImageData($itemId, Constants::itemVideoImgType);
            if (empty($imgId)) {
                $this->imageService->saveVideo($file, $imgData);
            } else {
                $this->imageService->updateVideo($imgId, $file, $imgData);
            }

            $data = new CoreImageApiResource($this->imageService->get($imgData));

            DB::commit();

            return responseDataApi($data);
        } catch (\Throwable $e) {
            DB::rollBack();
            throw new PsApiException(__('core__api_db_error', [], $langSymbol), Constants::internalServerErrorStatusCode);
        }
    }

    public function reorderImages(ReorderImagesItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        foreach ($validatedData as $validatedObj) {
            $image = $this->imageService->get([CoreImage::id => $validatedObj['img_id']]);
            $itemId = $image->img_parent_id;
            $ownerId = $this->itemService->get($itemId)->added_user_id;

            // check permission start
            $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $ownerId);
            // check permission end

            $image->ordering = $validatedObj[CoreImage::ordering];
            $image->updated_user_id = $loginUserId;
            $image->update();
        }

        $this->itemService->generateDeeplink($itemId);
        $msg = __('core__api_success_image_reorder', [], $langSymbol);
        return responseMsgApi($msg, Constants::createdStatusCode, Constants::successStatus);
    }

    // public function search(Request $request)
    // {
    //     [$limit, $offset] = $this->getLimitOffsetFromSetting($request);
    //     $loginUserId = $request->query('login_user_id');
    //     $langSymbol = $request->query('language_symbol');

    //     $data = $this->prepareSearchData($request ,$loginUserId, $langSymbol, $limit, $offset);
    //     return $this->handleNoDataResponse($offset, $data);
    // }

    public function search(Request $request)
    {
        $systemConfig = $this->systemConfigService->get();
        $ad_post_type = $this->getAdPostType($request, $systemConfig);
        $interval = $systemConfig->promo_cell_interval_no;

        [$relation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn] = $this->prepareItemSearchData($request);

        $handlers = [
            Constants::onlyPaidItemAdType => function () use ($relation, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn) {
                return $this->itemService->getAll($relation, $paidItemFilters, $sorting, $limit, $offset, Constants::yes, $filtersNotIn);
            },

            Constants::normalAdsOnlyAdType => function () use ($relation, $filters, $sorting, $limit, $offset, $normalItemFiltersNotIn) {
                return $this->itemService->getAll($relation, $filters, $sorting, $limit, $offset, Constants::yes, $normalItemFiltersNotIn);
            },

            Constants::paidItemFirstAdType => function () use ($relation, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $filters, $normalItemFiltersNotIn) {
                $paidItems = $this->itemService->getAll($relation, $paidItemFilters, $sorting, $limit, $offset, Constants::yes, $filtersNotIn);
                $limit = $this->prepareSponsoredFirstData($relation, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $paidItems->count());
                $normalItems = $this->itemService->getAll($relation, $filters, $sorting, $limit, $offset, Constants::yes, $normalItemFiltersNotIn);
                return $paidItems->count() > 0 ? $paidItems->merge($normalItems) : $normalItems;
            },

            Constants::googleAdsBetweenAdType => function () use ($relation, $filters, $sorting, $limit, $offset, $normalItemFiltersNotIn, $interval) {
                return $this->prepareGoogleAdBetweenNormalAdData($relation, $filters, $sorting, $limit, $offset, $normalItemFiltersNotIn, $interval);
            },

            Constants::bumpsUpsBetweenAdType => function () use($relation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval) {   
                return $this->prepareSponsoredAdBetweenNormalAdData($relation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval);
            },

            Constants::bumbsAndGoogleAdsBetweenAdType => function () use ($relation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval) {
                return $this->prepareSponsoredAndGoogleAdBetweenNormalAdAltData($relation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval);
            },

            Constants::paidItemFirstWithGoogleAdType => function () use ($relation, $paidItemFilters, $filters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval) {
                $paidItems = $this->itemService->getAll($relation, $paidItemFilters, $sorting, $limit, $offset, Constants::yes, $filtersNotIn);
                $limit = $this->prepareSponsoredFirstData($relation, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $paidItems->count());
                $googleAdItems = $this->prepareGoogleAdBetweenNormalAdData($relation, $filters, $sorting, $limit, $offset, $normalItemFiltersNotIn, $interval);
                return collect($paidItems)->merge($googleAdItems);
            },
        ];

        $items = $handlers[$ad_post_type]() ?? collect();

        $data = ItemApiResource::collection($items);

        return $this->handleNoDataResponse($offset, $data);
    }


    public function getRelatedTrending(GetRelatedTrendingItemApiRequest $request)
    {
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');

        $data = $this->prepareGetRelatedTrendingData($validatedData, $loginUserId, $limit, $offset);

        return $this->handleNoDataResponse($offset, $data);
    }

    public function soldOutFromItemDetail(SoldoutFromDetailItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $itemId = $validatedData['item_id'];
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $itemObj = $this->itemService->get($itemId);

        // check permission start
        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
        // check permission end

        try {
            $this->itemService->decreaseItemQuantity($itemId, Constants::yes);
        } catch (Throwable $e) {
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }

        $data = new ItemApiResource($this->itemService->get($itemId, $this->itemApiRelation));
        return responseDataApi($data);
    }

    public function allSearch(Request $request)
    {
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');

        $data = $this->prepareAllSearchData($request, $loginUserId, $langSymbol, $limit, $offset);
        return responseDataApi(new HomePageSearchApiResource(collect($data)));
    }

    public function statusChangeFromApi(StatusChangeItemApiRequest $request)
    {
        $validatedData = $request->validated();
        $itemId = $validatedData['item_id'];
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        $systemConfig = $this->systemConfigService->get();
        $itemObj = $this->itemService->get($itemId);
        $status = $this->prepareStatusData($validatedData['status'], $systemConfig->is_approved_enable);

        // check permission start
        $this->checkApiPermissionAndOwnerShip($loginUserId, $headerToken, $langSymbol, $itemObj->added_user_id);
        // check permission end

        try {

            if ($validatedData['status'] == 'disable') {
                $paidItemHistory = $this->paidItemHistoryService->get(itemId: $itemId);
                $paidItemHistoryData = $this->preparePaidItemHistoryStatusData();
                $this->paidItemHistoryService->update($paidItemHistory->id, $paidItemHistoryData);
            }

            $itemObj->status = $status;
            $itemObj->update();
            $this->itemService->sendApprovalNoti($itemObj->id);
        } catch (Throwable $e) {
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }

        $data = new ItemApiResource($this->itemService->get($itemId, $this->itemApiRelation));
        return responseDataApi($data);
    }

    public function customizeHeadersForCustomizeDetails(Request $request)
    {
        $coreKeyIds = [Constants::dropDownUi, Constants::radioUi, Constants::multiSelectUi];
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        $data = CustomFieldAttributeApiResource::collection($this->uiTypeService->getAll($coreKeyIds, $limit, $offset));

        return $this->handleNoDataResponse($offset, $data);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparations
    //-------------------------------------------------------------------

    private function prepareItemSearchData($request) {
        $loginUserId = Auth::id() ?? "nologinuser";

        $productRelation = [];
        if($request->has('product_relation')) {
            foreach ($request->product_relation as $relation) {
                $productRelation[$relation['core_keys_id']] = $relation['value'];
            }
        }

        $filter = [
            'login_user_id' => $loginUserId,
            'category_id' => $request->cat_id,
            'subcategory_id' => $request->sub_cat_id,
            'is_sold_out' => $request->is_sold_out,
            'is_discount' => $request->is_discount,
            'status' => $request->status,
            'keyword' => $request->searchterm,
            'infos_filter' => $productRelation,
            'currency_id' => $request->item_currency_id,
            'location_city_id' => $request->item_location_id,
            'location_township_id' => $request->item_location_township_id,
            'max_pirce' => $request->max_price,
            'min_price' => $request->min_price,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'miles' => $request->miles,
            'added_user_id' => $request->added_user_id,
            'vendor_id' => $request->vendor_id
        ];

        $systemConfig = $this->systemConfigService->get();

        $sort = [];
        if($request->has('order_by') && $request->order_by) {
            $sort = [
                $request->order_by => $request->order_type ?? Constants::descending
            ];
        }

        $block_ids = [];
        if ($systemConfig->is_block_user == 1) {
            $blockUserConds['to_block_user_id'] = $loginUserId;
            $block_ids = $this->blockUserService->getAll(relation: null, conds: $blockUserConds)->pluck('from_block_user_id')->toArray();
        }

        $complaintItems = $this->complaintItemService->getComplaintItems(reportedUserId: $loginUserId)->pluck('item_id')->toArray();

        $filterNotIn = [
            'blockUserIds_not_in' => $block_ids,
            'complaintItemIds_not_in' => $complaintItems,
        ];

        $paidItemFilter = $this->prepareGetPaidItemFilterData($filter);
        $normalItemFilterNotIn = $this->prepareGetNormalItemNotFilterData($filterNotIn);

        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);
        $itemApiRelation = ['vendor', 'category.categoryLanguageString', 'subcategory', 'city', 'township', 'currency', 'owner', 'itemRelation', 'cover', 'video', 'icon'];

        return [$itemApiRelation, $filter, $paidItemFilter, $sort, $limit, $offset, $filterNotIn, $normalItemFilterNotIn];
    }

    private function prepareDataCustomFields($request)
    {
        // Retrieve the 'relation' input as an array of strings
        $relationsInput = $request->input('product_relation', []);

        // Retrieve the 'relation' files as an array of files
        $relationsFiles = !empty($request->allFiles()['product_relation']) ? $request->allFiles()['product_relation'] : [];

        // Merge the input and files arrays, preserving keys
        return array_merge($relationsInput, $relationsFiles);
    }

    private function prepareAllSearchData($request, $loginUserId, $langSymbol, $limit, $offset)
    {
        $keyword = $request->input('keyword');
        $type = $request->input('type');
        $backendSetting = $this->backendSettingService->get();

        $limits = $this->getLimitAllSearch($limit, $backendSetting);

        $conds = ['keyword' => $keyword];
        $itemConds = ['keyword' => $keyword, 'status' => 1];

        $activeLanguage = $this->getActiveLanguage($langSymbol);

        $users = $this->userService->getAll(
            status: Constants::yes,
            conds: $conds,
            limit: $limits['user'],
            offset: $offset,
            noPagination: Constants::yes
        );

        $itemApiRelation = $this->itemApiRelation;
        $items = $this->itemService->getAll(
            relations: $itemApiRelation,
            filters: $itemConds,
            limit: $limits['item'],
            offset: $offset,
            noPagination: Constants::yes
        );

        $categoryApiRelation = ['defaultPhoto', 'defaultIcon'];
        $categories = $this->categoryService->getAll(
            relation: $categoryApiRelation,
            status: Constants::publish,
            languageId: $activeLanguage->id,
            limit: $limits['category'],
            offset: $offset,
            conds: $conds,
            noPagination: Constants::yes
        );

        if ($type == Constants::categoryType) {

            $type = Constants::searchHistoryCategoryType;

            $data = [
                'items' => [],
                'categories' => $categories,
                'users' => [],
            ];
        } else if ($type == Constants::userType) {

            $type = Constants::searchHistoryUserType;

            $data = [
                'items' => [],
                'categories' => [],
                'users' => $users,
            ];
        } else if ($type == Constants::itemType) {

            $type = Constants::searchHistoryItemType;

            $data = [
                'items' => $items,
                'categories' => [],
                'users' => [],
            ];
        } else if ($type == Constants::allType) {

            $type = Constants::searchHistoryAllType;

            $data = [
                'items' => $items,
                'categories' => $categories,
                'users' => $users,
            ];
        }

        if (!empty($keyword) && !empty($loginUserId)) {
            $searchdata = $this->prepareSaveSearchHistoryData($loginUserId, $keyword, $type, Constants::fromHomePageSearch);
            $this->searchHistoryService->store($searchdata);
        }

        return $data;
    }

    private function prepareSaveSearchHistoryData($loginUserId, $keyword, $type, $isFromHomePageSearch)
    {
        $searchdata = new \stdClass;
        $searchdata->user_id = $loginUserId;
        $searchdata->keyword = $keyword;
        $searchdata->type = $type;
        $searchdata->is_home_page_search = $isFromHomePageSearch;
        $searchdata->added_user_id = $loginUserId;

        return $searchdata;
    }

    private function prepareSearchData($request, $loginUserId, $langSymbol, $limit, $offset)
    {
        $systemConfig = $this->systemConfigService->get();
        $interval = $systemConfig->promo_cell_interval_no;

        if ($systemConfig->is_block_user == 1) {
            $blockUserids = $this->blockUserService->getAll(
                conds: [BlockUser::fromBlockUserId => $loginUserId]
            )->pluck(BlockUser::toBlockUserId)->toArray();
        }

        $complaintItemIds = $this->complaintItemService->getComplaintItems(
            reportedUserId: $loginUserId,
            noPagination: Constants::yes
        )->pluck('item_id')->toArray();

        $activeLanguage = $this->getActiveLanguage($langSymbol);

        $itemApiRelation = ['vendor', 'category.categoryLanguageString', 'subcategory', 'city', 'township', 'currency', 'owner', 'itemRelation', 'cover', 'video', 'icon'];

        $ad_post_type = $this->getAdPostType($request, $systemConfig);

        // Sorting
        $sorting = [];
        if (!empty($request->input('order_by')) && !empty($request->input('order_type'))) {
            $sorting = [
                $request->input('order_by') => $request->input('order_type')
            ];
        }

        // Filters
        $filters = [
            'keyword' => $request->input('searchterm'),
            'category_id' => $request->input('cat_id'),
            'subcategory_id' => $request->input('sub_cat_id'),
            'currency_id' => $request->input('item_currency_id'),
            'location_city_id' => $request->input('item_location_id'),
            'location_township_id' => $request->input('item_location_township_id'),
            'max_price' => $request->input('max_price'),
            'min_price' => $request->input('min_price'),
            'lat' => $request->input('lat'),
            'lng' => $request->input('lng'),
            'miles' => $request->input('miles'),
            'added_user_id' => $request->input('added_user_id'),
            'status' => $request->input('status'),
            'is_sold_out' => $request->input('is_sold_out'),
            'is_discount' => $request->input('is_discount'),
            'vendor_id' => $request->input('vendor_id'),
            'product_relation' => $request->input('product_relation'),
        ];

        $filtersNotIn = [
            'blockUserIds_not_in' => $blockUserids ?? [],
            'complaintItemIds_not_in' => $complaintItemIds
        ];

        $paidItemFilters = $this->prepareGetPaidItemFilterData($filters);
        $normalItemFiltersNotIn = $this->prepareGetNormalItemNotFilterData($filtersNotIn);

        if ($ad_post_type == Constants::onlyPaidItemAdType) {

            $items = $this->itemService->getAll($itemApiRelation, $paidItemFilters, $sorting, $limit, $offset, Constants::yes, $filtersNotIn);
        } else if ($ad_post_type == Constants::normalAdsOnlyAdType) {

            $items = $this->itemService->getAll($itemApiRelation, $filters, $sorting, $limit, $offset, Constants::yes, $normalItemFiltersNotIn);
        } else if ($ad_post_type == Constants::paidItemFirstAdType) {
            $paidItems = $this->itemService->getAll($itemApiRelation, $paidItemFilters, $sorting, $limit, $offset, Constants::yes, $filtersNotIn);
            $limit = $this->prepareSponsoredFirstData($itemApiRelation, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $paidItems->count());

            $normalItems = $this->itemService->getAll($itemApiRelation, $filters, $sorting, $limit, $offset, Constants::yes, $normalItemFiltersNotIn);

            if ($paidItems->count() > 0) {
                $items = $paidItems->merge($normalItems);
            } else {
                $items = $normalItems;
            }
        } else if ($ad_post_type == Constants::googleAdsBetweenAdType) {
            $items = $this->prepareGoogleAdBetweenNormalAdData($itemApiRelation, $filters, $sorting, $limit, $offset, $normalItemFiltersNotIn, $interval);
        } else if ($ad_post_type == Constants::bumpsUpsBetweenAdType) {

            $items = $this->prepareSponsoredAdBetweenNormalAdData($itemApiRelation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval);
        } else if ($ad_post_type == Constants::bumbsAndGoogleAdsBetweenAdType) {
            $items = $this->prepareSponsoredAndGoogleAdBetweenNormalAdAltData($itemApiRelation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval);
        } else if ($ad_post_type == Constants::paidItemFirstWithGoogleAdType) {
            $paidItems = $this->itemService->getAll($itemApiRelation, $paidItemFilters, $sorting, $limit, $offset, Constants::yes, $filtersNotIn);

            $limit = $this->prepareSponsoredFirstData($itemApiRelation, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $paidItems->count());

            $items = array();
            foreach ($paidItems as $paidItem) {
                array_push($items, $paidItem);
            }

            $googleAdBetweenNormalAdItems = $this->prepareGoogleAdBetweenNormalAdData($itemApiRelation, $filters, $sorting, $limit, $offset, $normalItemFiltersNotIn, $interval);

            $items = array_merge($items, $googleAdBetweenNormalAdItems);
        }

        // save search history
        if (!empty($request->input('searchterm'))) {
            $searchdata = new \stdClass;
            $searchdata->user_id = $loginUserId;
            $searchdata->keyword = $request->input('searchterm');
            $searchdata->type = Constants::searchHistoryItemType;
            $searchdata->is_home_page_search = Constants::notFromHomePageSearch;
            $searchdata->added_user_id = $loginUserId;
            $this->searchHistoryService->store($searchdata);
        }

        return ItemApiResource::collection($items);
    }

    private function prepareSponsoredFirstData($itemApiRelation, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $paidItemCount)
    {

        if (!empty($limit) && !empty($offset)) {
            if ($paidItemCount < $limit) {
                $limit = $limit - $paidItemCount;
                $paid = $this->itemService->getAll($itemApiRelation, $paidItemFilters, $sorting, $limit, null, Constants::yes, $filtersNotIn);

                $offset = max(0, $offset - $paid->count());
            }
        } else if (!empty($limit)) {
            $limit = $limit - $paidItemCount;
        }

        return $limit;
    }

    private function prepareGoogleAdBetweenNormalAdData($itemApiRelation, $filters, $sorting, $limit, $offset, $normalItemFiltersNotIn, $interval)
    {
        $items = array();
        $dataLimit = $this->getLimitForAdPostType($limit, $offset, $interval, Constants::paidItemFirstWithGoogleAdType);
        $normalLimit = $dataLimit['normalLimit'];
        $normalOffset = $dataLimit['normalOffset'];

        $googleItem = new \stdClass;
        $googleItem->ad_type = Constants::googleAd;

        $normalItems = $this->itemService->getAll($itemApiRelation, $filters, $sorting, $normalLimit, $normalOffset, Constants::yes, $normalItemFiltersNotIn);

        $normalItemsIndex = 0;


        $total = $normalItems->count() ? $normalItems->count() : 0;
        if ($total != 0) {
            for ($x = 0; $x < count($dataLimit['exampleOutput']); $x++) {
                if ($dataLimit['exampleOutput'][$x] == 'one' && $normalItemsIndex < $normalItems->count() && $normalItems->count() > 0) {
                    array_push($items, $normalItems[$normalItemsIndex]);
                    if (($normalItemsIndex + 1) >= $total) {
                        if (isset($dataLimit['exampleOutput'][$x + 1])) {
                            if ($dataLimit['exampleOutput'][$x + 1] != 'zero') {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                    $normalItemsIndex = $normalItemsIndex + 1;
                } else {
                    array_push($items, $googleItem);
                    if ($normalItemsIndex >= $total) {
                        break;
                    }
                }
            }
        }
        return $items;
    }

    private function prepareSponsoredAdBetweenNormalAdData($itemApiRelation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval)
    {
        $dataLimit = $this->getLimitForAdPostType($limit, $offset, $interval, Constants::bumpsUpsBetweenAdType);
        $normalLimit = $dataLimit['normalLimit'];
        $paidLimit = $dataLimit['paidLimit'];
        $normalOffset = $dataLimit['normalOffset'];
        $paidOffset = $dataLimit['paidOffset'];

        $paidItems = $this->itemService->getAll($itemApiRelation, $paidItemFilters, $sorting, $paidLimit, $paidOffset, Constants::yes, $filtersNotIn);

        if ($paidItems->count() == 0) {
            $normalLimit = $normalLimit + $paidLimit;
            $normalOffset = $normalOffset + $paidOffset;

            $tempPaidItems = $this->itemService->getAll($itemApiRelation, $paidItemFilters, $sorting, $offset, 0, Constants::yes, $filtersNotIn);

            $exampleArray = $dataLimit['exampleArray'];
            $exampleArray_count = array_count_values($exampleArray);
            $dataArr['normalOffset'] = ($exampleArray_count['zero'] - $tempPaidItems->count()) + $normalOffset;
        } else if ($paidItems->count() < $paidLimit) {
            $paid = $this->itemService->getAll($itemApiRelation, $paidItemFilters, $sorting, $paidLimit, null, Constants::yes, $filtersNotIn);

            $normalLimit = $normalLimit + ($paidLimit - $paidItems->count());
            if ($paidItems->count() < $paidOffset) {
                $normalOffset = $normalOffset + ($paidOffset - $paid->count());
            }
        }
        $normalItems = $this->itemService->getAll($itemApiRelation, $filters, $sorting, $normalLimit, $normalOffset, Constants::yes, $normalItemFiltersNotIn);

        $normalItemsIndex = 0;
        $paidIndex = 0;
        $items = array();
        for ($x = 0; $x < count($dataLimit['exampleOutput']); $x++) {

            if (($dataLimit['exampleOutput'][$x] == 'one' || $paidIndex + 1 > $paidItems->count()) && $normalItemsIndex < $normalItems->count() && $normalItems->count() > 0) {
                array_push($items, $normalItems[$normalItemsIndex]);
                $normalItemsIndex = $normalItemsIndex + 1;
            } else if ($paidItems->count() != 0 && $paidIndex < $paidItems->count()) {
                array_push($items, $paidItems[$paidIndex]);
                $paidIndex = $paidIndex + 1;
            } else if ($normalItemsIndex < $normalItems->count() && $normalItems->count() > 0) {
                array_push($items, $normalItems[$normalItemsIndex]);
                $normalItemsIndex = $normalItemsIndex + 1;
            }
        }
        return $items;
    }

    private function prepareSponsoredAndGoogleAdBetweenNormalAdAltData($itemApiRelation, $filters, $paidItemFilters, $sorting, $limit, $offset, $filtersNotIn, $normalItemFiltersNotIn, $interval)
    {
        $dataLimit = $this->getLimitForAdPostType($limit, $offset, $interval, Constants::bumbsAndGoogleAdsBetweenAdType);
        $normalLimit = $dataLimit['normalLimit'];
        $paidLimit = $dataLimit['paidLimit'] / 2;
        $normalOffset = $dataLimit['normalOffset'];
        $paidOffset = $dataLimit['paidOffset'];

        $paidItems = $this->itemService->getAll($itemApiRelation, $paidItemFilters, $sorting, $paidLimit, $paidOffset, Constants::yes, $filtersNotIn);
        $normalItems = $this->itemService->getAll($itemApiRelation, $filters, $sorting, $normalLimit, $normalOffset, Constants::yes, $normalItemFiltersNotIn);

        $googleItem = new \stdClass;
        $googleItem->ad_type = Constants::googleAd;

        $total = $normalItems->count() ? $normalItems->count() : 0;
        $total = $paidItems->count() ? $paidItems->count() + $total : $total;

        $items = array();

        $havepaid = $paidItems->count() > 0 ? 1 : 0;

        $normalItemsIndex = 0;
        $paidIndex = 0;
        $showGoogle = false;
        if ($total != 0) {
            for ($x = 0; $x < count($dataLimit['exampleOutput']); $x++) {
                if ($dataLimit['exampleOutput'][$x] == 'one'  && $normalItemsIndex < $normalItems->count() && $normalItems->count() > 0) {
                    array_push($items, $normalItems[$normalItemsIndex]);

                    $normalItemsIndex = $normalItemsIndex + 1;
                    if (($normalItemsIndex + $paidIndex + $havepaid) >= $total) {
                        if (isset($dataLimit['exampleOutput'][$x + 1])) {
                            if ($dataLimit['exampleOutput'][$x + 1] != 'zero') {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                } else if ($showGoogle == false && $paidIndex < $paidItems->count() && $paidItems->count() > 0) {
                    array_push($items, $paidItems[$paidIndex]);
                    $paidIndex = $paidIndex + 1;
                    $showGoogle = !$showGoogle;
                    if ($normalItemsIndex + $paidIndex >= $total) {
                        break;
                    }
                } else {
                    array_push($items, $googleItem);
                    $showGoogle = !$showGoogle;
                    if ($normalItemsIndex + $paidIndex >= $total) {
                        break;
                    }
                }
            }
        }
        return $items;
    }

    private function prepareGetRelatedTrendingData($validatedData, $loginUserId, $limit, $offset)
    {
        $systemConfig = $this->systemConfigService->get();
        $blockUserids = $this->getBlockUserIds($systemConfig, $loginUserId);
        $paidItemIds = $this->getPaidItemHistoryIds();

        // Orders
        $orders = [
            Item::touchCount => 'desc',
        ];

        // Filter
        $filters = [
            'category_id' => $validatedData['cat_id'],
            'status' => Constants::publishItem,
        ];

        $filtersNotIn = [
            'blockUserIds_not_in' => $blockUserids,
            'id' => array_merge([$validatedData['id']], $paidItemIds)
        ];

        return ItemApiResource::collection($this->itemService->getAll($this->itemApiRelation, $filters, $orders, $limit, $offset, Constants::yes, $filtersNotIn));
    }

    private function preparePaidItemHistoryStatusData()
    {
        return [PaidItemHistory::status => Constants::unPublish];
    }

    private function prepareCreateData($data, $limit, $offset, $loginUserId, $langSymbol)
    {
        $coreFieldData = [];

        //for vendor create access
        /** @todo */
        $createAsVendor = haveVendorAndCreateAccess($loginUserId);
        if (!empty($createAsVendor)) {
            $createAsVendor = VendorForItemEntryApiResource::collection($this->vendorService->getAll(
                relation: ['vendorCurrency'],
                ids: $createAsVendor
            ));
        } else {
            $createAsVendor = [];
        }

        $customizeUiRelation = ['uiTypeId'];
        $customFields = $this->customFieldService->getAll(
            code: Constants::item,
            relation: $customizeUiRelation,
            isDelete: Constants::unDelete,
            // limit: $limit,
            // offset: $offset,
            categoryId: $data['category_id'] ?? null,
            withNoPag: Constants::yes
        );

        $coreFields = $this->coreFieldService->getAll(
            code: Constants::item,
            // limit: $limit,
            // offset: $offset,
            isDel: Constants::unDelete,
            withNoPag: Constants::yes
        );
        $coreFieldData = $this->getAllCoreFieldData($coreFields, $langSymbol);

        $coreFieldData = CoreFieldApiResource::collection($coreFieldData);
        $customFieldData = CustomFieldApiResource::collection($customFields);
        $createAsVendorData = VendorForItemEntryApiResource::collection($createAsVendor);

        return [
            "custom" => $customFieldData,
            "core" => $coreFieldData,
            'vendor_list' => $createAsVendorData
        ];
    }

    private function prepareImageData($ImgParentid, $imgType, $ordering = 1)
    {
        return [
            'img_parent_id' => $ImgParentid,
            'img_type' => $imgType,
            'ordering' => $ordering
        ];
    }

    private function prepareStatusData($status, $isApprovedEnable)
    {
        if ($isApprovedEnable == 1) {
            $statusDependOnSetting = Constants::pendingItem;
        } else {
            $statusDependOnSetting = Constants::publishItem;
        }

        if ($status == 'accept' || $status == 'apply') {
            $status = $statusDependOnSetting;
        } elseif ($status == 'reject') {
            $status = Constants::rejectItem;
        }
        if ($status == 'disable') {
            $status = Constants::disableItem;
        }

        return $status;
    }

    private function prepareGetNormalItemNotFilterData($filtersNotIn)
    {
        $paidItemIds = $this->getPaidItemHistoryIds();

        $filtersNotIn['id'] = $paidItemIds;

        return $filtersNotIn;
    }

    private function prepareGetPaidItemFilterData($filters)
    {
        $filters['is_paid'] = Constants::yes;
        $filters['paid_item_histories_timestamp'] = $this->getTodayDateTimeStamp();
        $filters['paid_item_histories_deleted_at'] = null;

        return $filters;
    }

    //-------------------------------------------------------------------
    // Database
    //-------------------------------------------------------------------

    private function getLimitAllSearch($limit, $backendSetting)
    {
        return [
            'category' => $limit ?: $backendSetting->search_category_limit,
            'item' => $limit ?: $backendSetting->search_item_limit,
            'user' => $limit ?: $backendSetting->search_user_limit,
        ];
    }

    private function checkMaxImageUpload($itemId, $langSymbol)
    {
        $images = $this->imageService->getAll($itemId, Constants::itemCoverImgType);
        $systemConfig = $this->systemConfigService->get();
        if ($systemConfig->max_img_upload_of_item < $images->count()) {
            throw new PsApiException(__('core__api_err_max_img_upload', [], $langSymbol), Constants::badRequestStatusCode);
        }
    }

    private function getAdPostType($request, $systemConfig)
    {
        $adPostTypes = $this->adPostTypeService->getAll()->pluck("key")->toArray();
        $adPostType = $this->adPostTypeService->get($systemConfig->ad_type)->key;
        $requestAdPostType = $request->input('ad_post_type');

        if (empty($requestAdPostType)) {
            return $adPostType;
        }

        if (in_array($requestAdPostType, $adPostTypes)) {
            return $requestAdPostType;
        }

        if ($requestAdPostType == Constants::onlyPaidItemAdType || $requestAdPostType == Constants::paidItemFirstWithGoogleAdType) {
            return $requestAdPostType;
        }
    }

    private function getActiveLanguage($langSymbol)
    {
        $langConds = ['symbol' => $langSymbol ?? 'en'];
        return $this->languageService->get(null, $langConds);
    }

    private function getPaidItemHistoryIds()
    {
        return $this->paidItemHistoryService->getAll(
            status: Constants::publish,
            startTimeStamp: $this->getTodayDateTimeStamp(),
            endTimestamp: $this->getTodayDateTimeStamp()
        )->pluck(PaidItemHistory::itemId)->toArray();
    }

    private function getBlockUserIds($systemConfig, $loginUserId)
    {
        if ($systemConfig->is_block_user == 1) {
            $blockUserids = $this->blockUserService->getAll(
                conds: [BlockUser::fromBlockUserId => $loginUserId]
            )->pluck(BlockUser::toBlockUserId)->toArray();
        }
        return $blockUserids ?? [];
    }

    //-------------------------------------------------------------------
    // Other
    //-------------------------------------------------------------------
    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->query('offset');
        $limit = $request->query('limit') ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    private function getDefaultLimit()
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;

        return $defaultLimit ?: 9;
    }

    private function getTodayDateTimeStamp()
    {
        // $today = Carbon::now();
        $today = Carbon::now()->minute((int) (Carbon::now()->minute / 5) * 5)->second(0);

        return strtotime($today->toDateTimeString());
    }

    private function getLimitForAdPostType($limit = null, $offset = 0, $interval = null, $ad_post_type = null)
    {

        $total_limit = $limit + $offset;
        $exampleArray = [];
        $tempInterval = $interval;
        for ($i = 1; $i <= $total_limit; $i++) {
            if ($i > $tempInterval) {
                array_push($exampleArray, 'zero');
                $tempInterval = $i + $interval;
            } else {
                array_push($exampleArray, 'one');
            }
        }
        $example_output = array_slice($exampleArray, $offset);
        $exampleArray_count = array_count_values($exampleArray);
        $example_count = array_count_values($example_output);

        $dataArr['normalOffset'] = $exampleArray_count['one'] - $example_count['one'];
        $dataArr['paidOffset'] = $exampleArray_count['zero'] - $example_count['zero'];

        $dataArr['normalLimit'] = $example_count['one'];
        $dataArr['paidLimit'] = $example_count['zero'];
        $dataArr['exampleOutput'] = $example_output;
        $dataArr['exampleArray'] = $exampleArray;

        return $dataArr;
    }

    private function isUploadNotAllowed($uploadSetting, $roleId, $isVerifyBlueMark)
    {
        if ($uploadSetting == 'admin-bluemark') {
            return $roleId != 1 && $isVerifyBlueMark != 1;
        }

        if ($uploadSetting == 'admin') {
            return $roleId != 1;
        }

        return false; // Default to allow upload if no conditions match
    }

    private function handlePostCountReduceConds($systemConfig, $user, $vendorId)
    {
        return empty($vendorId) && ($systemConfig->is_paid_app == 1 && $user->role_id != Constants::superAdminRoleId);
    }

    private function getOriginalFieldName($coreField)
    {
        if (str_contains($coreField->field_name, "@@")) {
            $originFieldName = strstr($coreField->field_name, "@@", true);
        } else {
            $originFieldName = $coreField->field_name;
        }
        return $originFieldName;
    }

    private function getAllCoreFieldData($coreFields, $langSymbol)
    {
        $coreFieldData = [];
        $coreFieldTableColumns = Schema::getColumnListing(Item::tableName);
        foreach ($coreFields as $coreField) {
            $originFieldName = $this->getOriginalFieldName($coreField);

            if (in_array($originFieldName, $coreFieldTableColumns)) {

                $coreField->placeholder = __($coreField->placeholder, [], $langSymbol);
                $coreField->label_name = __($coreField->label_name, [], $langSymbol);

                if ($this->mobileSettingService->get()->is_show_subcategory == '1' || $coreField->field_name != 'subcategory_id@@name') {
                    array_push($coreFieldData, $coreField);
                }
            }
        }
        return $coreFieldData;
    }

    private function checkUserUploadPermission($user, $loginUserId, $langSymbol)
    {
        $uploadSetting = $this->backendSettingService->get()->upload_setting;

        $isVerifyBlueMark = $this->userInfoService->get(
            parentId: $user->id,
            coreKeysId: Constants::usrIsVerifyBlueMark
        )?->value;

        if ($this->isUploadNotAllowed($uploadSetting, $user->role_id, $isVerifyBlueMark)) {
            throw new PsApiException(__('core__api_item_upload_not_allow', [], $langSymbol), Constants::forbiddenStatusCode);
        }
    }

    private function checkIsPostCountEnough($systemConfig, $user, $langSymbol, $userInfo)
    {
        if ($systemConfig->is_paid_app == 1 && $user->role_id != Constants::superAdminRoleId) {
            if (empty($userInfo) || $userInfo->value == 0) {
                throw new PsApiException(__('core__api_not_enought_to_post', [], $langSymbol), Constants::badRequestStatusCode);
            }
        }
    }

    private function checkVendorCurrency($itemObj, $langSymbol)
    {
        if (!empty($itemObj->vendor_id)) {
            $vendor = $this->vendorService->get($itemObj->vendor_id);
            if ($vendor->currency_id == null) {
                throw new PsApiException(__('core__api_vendor_currency_error', [], $langSymbol), Constants::badRequestStatusCode);
            }
        }
    }
}
