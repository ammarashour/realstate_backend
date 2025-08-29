<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\User;

use App\Config\ps_constant;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Exceptions\PsApiException;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsApiController;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Http\Services\ItemService;
use Illuminate\Contracts\Translation\Translator;
use App\Http\Contracts\User\UserServiceInterface;
use Modules\Core\Http\Services\User\FollowUserService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Http\Requests\User\StoreFollowUserRequest;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;

class FollowUserApiController extends PsApiController
{
    protected $followUserApiRelation;

    public function __construct(
        protected FollowUserService $followUserService,
        protected UserServiceInterface $userService,
        protected ItemService $itemService,
        protected MobileSettingServiceInterface $mobileSettingService)
    {
        parent::__construct();
        $this->followUserApiRelation = ['userRelation'];
    }

     /**
     * To Follow and unfollow
     */
    public function followUser(StoreFollowUserRequest $request)
    {

        $loginUserId = $request->query('login_user_id');
        Auth::loginUsingId($loginUserId);
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        try{
            $validateData = $request->validated();

            //follow user
            $followedUser = $this->followUserService->save($validateData);

            return responseDataApi(new UserApiResource($followedUser));
        }catch(\Exception $e){
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }
    }

    //follow list
    public function getFollower(Request $request)
    {
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);

        $data = UserApiResource::collection($this->followUserService->getAll(
            userId: $request->input('login_user_id'),
            relation: $this->followUserApiRelation,
            conds: $conds,
            limit: $limit,
            offset: $offset));

        // Prepare and Check No Data Return
        return $this->handleNoDataResponse($request->offset, $data);
    }

    public function searchFollower(Request $request)
    {
        if (empty($request->input('id'))) {
            // Get Limit and Offset
            [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

            // Prepare Filter Conditions
            $conds = $this->getFilterConditions($request);

            $data = UserApiResource::collection($this->followUserService->getAll(
                userId: $request->input('login_user_id'),
                relation: $this->followUserApiRelation,
                conds: $conds,
                limit: $limit,
                offset: $offset));

            return $this->handleNoDataResponse($request->offset, $data);

        } else {
            $data = new UserApiResource($this->userService->get($request->input('id')));

            return responseDataApi($data);
        }

        // Prepare and Check No Data Return

    }

    //item list from follower
    public function itemListFromFollower(Request $request)
    {
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = [
            'return_types' => Constants::followingReturnType
        ];
        $followedUserIds = $this->followUserService->getAll($request->login_user_id, null, $conds)->pluck('id')->toArray();

        // Prepare Filter Conditions
        $itemApiRelation = ['category', 'subcategory', 'city', 'township', 'currency', 'owner','itemRelation'];
        $dataConds = [
            'status' => Constants::publish
        ];
        $conds_in = [
            'added_user_ids' => $followedUserIds
        ];

        $data = ProductApiResource::collection($this->itemService->getItems(
            $itemApiRelation,
            true,
            null,
            $limit,
            $offset,
            $dataConds,
            $conds_in));

        // Prepare and Check No Data Return
        return $this->handleNoDataResponse($request->offset, $data);
    }

    /////////////////////////////////////////////////////////////
    /// Private Functions
    /////////////////////////////////////////////////////////////
    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->offset;
        $limit = $request->limit ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    private function getDefaultLimit()
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;

        return $defaultLimit ?: 9;
    }

    private function getFilterConditions($request)
    {
        return [
            'user_name' => $request->input('user_name'),
            'overall_rating' => $request->input('overall_rating'),
            'return_types' => $request->input('return_types'),
            // 'id' => $request->input('id'),
        ];
    }
}
