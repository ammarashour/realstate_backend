<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Notification;

use App\Config\ps_constant;
use App\Http\Contracts\Notification\ChatServiceInterface;
use App\Http\Controllers\PsApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Modules\Core\Constants\Constants;
use Illuminate\Contracts\Translation\Translator;
use Modules\Core\Http\Requests\Notification\ChatImageDeleteChatApiRequest;
use Modules\Core\Http\Requests\Notification\ChatImageUploadChatApiRequest;
use Modules\Core\Http\Requests\Notification\GetBuyerSellerListChatApiRequest;
use Modules\Core\Http\Requests\Notification\GetChatHistoryChatApiRequest;
use Modules\Core\Http\Requests\Notification\GetOfferListChatApiRequest;
use Modules\Core\Http\Requests\Notification\IsUserBoughtChatApiRequest;
use Modules\Core\Http\Requests\Notification\ItemSoldOutChatApiRequest;
use Modules\Core\Http\Requests\Notification\ResetCountChatApiRequest;
use Modules\Core\Http\Requests\Notification\StoreChatApiRequest;
use Modules\Core\Http\Requests\Notification\UnreadCountChatApiRequest;
use Modules\Core\Http\Requests\Notification\UpdateAcceptChatApiRequest;
use Modules\Core\Http\Requests\Notification\UpdatePriceChatApiRequest;

class ChatApiController extends PsApiController
{

    public function __construct(
        protected Translator $translator,
        protected ChatServiceInterface $chatService
    )
    {
        parent::__construct();
    }

    public function store(StoreChatApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');

        return $this->chatService->storeFromApi($validatedData, $langSymbol, $loginUserId);
    }

    public function updatePrice(UpdatePriceChatApiRequest $request)
    {

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->chatService->updatePriceFromApi($validatedData, $langSymbol, $loginUserId);
    }

    public function show(GetChatHistoryChatApiRequest $request)
    {

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->chatService->getChatHistoryFromApi($validatedData, $langSymbol, $loginUserId);
    }

    public function chatImageUpload(ChatImageUploadChatApiRequest $request){

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $file = $request->file('file');

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        $chat = $this->chatService->chatImageUploadFromApi($validatedData, $langSymbol, $loginUserId, $file);
        return $chat;
    }

    public function chatImageDelete(ChatImageDeleteChatApiRequest $request)
    {

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->chatService->chatImageDeleteFromApi($validatedData, $langSymbol, $loginUserId);
    }

    public function resetCount(ResetCountChatApiRequest $request){

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->chatService->resetCountFromApi($validatedData, $langSymbol, $loginUserId);
    }

    public function unreadCount(UnreadCountChatApiRequest $request)
    {

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->chatService->unreadCountFromApi($validatedData, $langSymbol, $loginUserId);
    }

    function getOfferList(GetOfferListChatApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $limit = $request->query('limit');
        $offset = $request->query('offset');

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->chatService->getAcceptOfferFromApi($validatedData, $langSymbol, $loginUserId, $limit, $offset);
    }

    function isUserBought(IsUserBoughtChatApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');

        return $this->chatService->isUserBoughtFromApi($validatedData, $langSymbol, $loginUserId);
    }

    // accept offer
    function updateAccept(UpdateAcceptChatApiRequest $request)
    {

        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');

        return $this->chatService->acceptOfferFromApi($validatedData, $langSymbol, $loginUserId);
    }

    function itemSoldOut(ItemSoldOutChatApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->chatService->itemSoldOutFromApi($validatedData, $langSymbol, $loginUserId);
    }


    function getBuyerSellerList(GetBuyerSellerListChatApiRequest $request)
    {
        $validatedData = $request->validated();
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        $limit = $request->query('limit');
        $offset = $request->query('offset');

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        return $this->chatService->getBuyerSellerListFromApi($validatedData, $langSymbol, $loginUserId, $limit, $offset);
    }
}
