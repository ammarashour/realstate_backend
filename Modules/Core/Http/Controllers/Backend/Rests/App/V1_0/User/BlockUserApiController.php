<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\User;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use App\Exceptions\PsApiException;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsApiController;
use Illuminate\Contracts\Translation\Translator;
use App\Http\Contracts\User\BlockUserServiceInterface;
use App\Http\Contracts\User\UserServiceInterface;
use Modules\Core\Entities\User\BlockUser;
use Modules\Core\Http\Requests\User\StoreBlockUserRequest;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;

class BlockUserApiController extends PsApiController
{
    protected $blockUserApiRelation;

    public function __construct(protected Translator $translator,
        protected BlockUserServiceInterface $blockUserService,
        protected UserServiceInterface $userService,
        protected MobileSettingServiceInterface $mobileSettingService)
    {
        parent::__construct();
        $this->blockUserApiRelation = ['userRelation'];
    }

    public function blockUser(StoreBlockUserRequest $request)
    {
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        try{
            $validateData = $request->validated();

            // block user
            $this->blockUserService->save($validateData);

            $data = [
                "msg" => __('blockUser__api_block_success', [], $request->language_symbol),
                "statusCode" => Constants::createdStatusCode,
                "flag" => Constants::success,
            ];

            return responseMsgApi($data['msg'], $data['statusCode'], $data['flag']);
        }catch(\Exception $e){
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }
    }

    public function unblockUser(StoreBlockUserRequest $request)
    {
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        try{
            $validateData = $request->validated();

            // unblock user
            $this->blockUserService->delete($validateData);

            $data = [
                "msg" => __('blockUser___api_unblock_success', [], $request->language_symbol),
                "statusCode" => Constants::createdStatusCode,
                "flag" => Constants::success,
            ];

            return responseMsgApi($data['msg'], $data['statusCode'], $data['flag']);
        }catch(\Exception $e){
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }
    }

    public function getBlockedUser(Request $request)
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

        $blockedUserIds['ids'] = $this->blockUserService->getAll(null, $conds)->pluck(BlockUser::toBlockUserId)->toArray();

        $data = UserApiResource::collection($this->userService->getAll(relation: $this->blockUserApiRelation,
                    status: null, isBanned: null, conds: null,
                    limit: $limit, offset: $offset, condsIn: $blockedUserIds));

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
            'from_block_user_id' => $request->login_user_id,
        ];
    }
}
