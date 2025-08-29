<?php

namespace App\Http\Contracts\Notification;

use App\Http\Contracts\Core\PsInterface;

interface ChatServiceInterface extends PsInterface
{
    public function storeFromApi($chatData, $langSymbol, $loginUserId);

    public function updatePriceFromApi($chatData, $langSymbol, $loginUserId);

    public function getChatHistoryFromApi($chatData, $langSymbol, $loginUserId);

    public function chatImageDeleteFromApi($chatData, $langSymbol, $loginUserId);

    public function resetCountFromApi($chatData, $langSymbol, $loginUserId);

    public function unreadCountFromApi($chatData, $langSymbol, $loginUserId);

    public function getAcceptOfferFromApi($chatData, $langSymbol, $loginUserId, $limit, $offset);

    public function getBuyerSellerListFromApi($chatData, $langSymbol, $loginUserId, $limit, $offset);

    public function itemSoldOutFromApi($chatData, $langSymbol, $loginUserId);

    public function isUserBoughtFromApi($chatData, $langSymbol, $loginUserId);

    public function acceptOfferFromApi($chatData, $langSymbol, $loginUserId);

    public function chatImageUploadFromApi($chatData, $langSymbol, $loginUserId, $file);
}
