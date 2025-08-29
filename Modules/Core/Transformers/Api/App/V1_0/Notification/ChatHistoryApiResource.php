<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Notification;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Item;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;

class ChatHistoryApiResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => (string) checkAndGetValue($this, 'id'),
            'item_id' => (string) checkAndGetValue($this, 'item_id'),
            'buyer_user_id' => (string) checkAndGetValue($this, 'buyer_user_id'),
            'seller_user_id' => (string) checkAndGetValue($this, 'seller_user_id'),
            'nego_price' => (string) checkAndGetValue($this, 'nego_price'),
            'buyer_unread_count' => (string) checkAndGetValue($this, 'buyer_unread_count'),
            'seller_unread_count' => (string) checkAndGetValue($this, 'seller_unread_count'),
            'latest_chat_message' => (string) checkAndGetValue($this, 'latest_chat_message'),
            'is_accept' => (string) checkAndGetValue($this, 'is_accept'),
            'offer_status' => (string) checkAndGetValue($this, 'offer_status'),
            "is_offer" => (string) $this->getOfferStatus(),
            'added_date' => (string) $this->getChatDate(),
            'offer_amount' => (string) checkAndGetValue($this, 'nego_price'),
            "item" => new ProductApiResource($this->item ?? []),
            "buyer" => new UserApiResource($this->buyer ?? []),
            "seller" => new UserApiResource($this->seller ?? []),
            "added_date_str" => $this->getChatDateStr(),
            "is_empty_object" => $this->when(!isset($this->id),'1'),
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getChatDate()
    {
        $chat_date = '';
        if(isset($this->updated_date)){
            $chat_date = $this->updated_date;
        }else if(isset($this->added_date) && empty($this->updated_date)){
            $chat_date = $this->added_date;
        }
        return $chat_date;
    }

    private function getChatDateStr()
    {
        $chatDateStr = "";
        if(!empty($this->getChatDate())){
            $chatDateStr = $this->getChatDate()->diffForHumans();
        }

        return $chatDateStr;
    }

    private function getOfferStatus()
    {
        $is_sold_out = 0;
        if(isset($this->id)){
            $item = Item::find($this->item_id);
            if($item){
                $is_sold_out = $item->is_sold_out;
            }
        }else if(isset($this->chatItemId)){
            $item = Item::find($this->chatItemId);
            if($item){
                $is_sold_out = $item->is_sold_out;
            }
            $this->item_id = $this->chatItemId;
            $this->item = $item;
        }

        if(empty($this->offer_status)){
            return 0;
        }

        if($this->offer_status == 2 || $is_sold_out == 1){
            return 1;
        } else {
            return 0;
        }
    }
}
