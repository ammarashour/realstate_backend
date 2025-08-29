<?php

namespace Modules\Core\Transformers\Backend\Model\User;

use Illuminate\Http\Resources\Json\JsonResource;

use Modules\Core\Entities\Notification\ChatHistory;

use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;

class UserBoughtWithKeyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $offer_amount = 0;
        $conds[ChatHistory::itemId] = $this->item_id;
        $conds[ChatHistory::sellerUserId] = $this->seller_user_id;
        $conds[ChatHistory::buyerUserId] = $this->buyer_user_id;
        $chat = ChatHistory::where($conds)->first();
        if ($chat) {
            $offer_amount = $chat->nego_price;
        }
        return [
            'id' => $this->id,
            'item_id' => $this->item_id,
            'buyer_user_id' => $this->buyer_user_id,
            'seller_user_id' => $this->seller_user_id,
            'added_date' => $this->added_date,
            'offer_amount' => $offer_amount,
            "item" => new ProductApiResource($this->whenLoaded('item')),
            "buyer" => new UserApiResource($this->whenLoaded('buyer')),
            "seller" => new UserApiResource($this->whenLoaded('seller')),
        ];
    }
}
