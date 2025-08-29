<?php

namespace Modules\Core\Transformers\Backend\NoModel\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\User\Rating;

class DashboardBuyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request) + ["rating_count" => isset($this->id) ? (string) (Rating::where(['to_user_id' => $this->id])->count()) : ''];
    }
}
