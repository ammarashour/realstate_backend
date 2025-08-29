<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Notification;

use Illuminate\Http\Resources\Json\JsonResource;

class UnreadCountApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'buyer_unread_count' => isset($this->buyer_unread_count)?(string)$this->buyer_unread_count:'0',
            'seller_unread_count' => isset($this->seller_unread_count)?(string)$this->seller_unread_count:'0',
            'noti_unread_count' => isset($this->noti_unread_count)?(string)$this->noti_unread_count:'0',
        ];
    }
}
