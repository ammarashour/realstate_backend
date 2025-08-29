<?php

namespace Modules\Core\Transformers\Backend\NoModel\Dashboard;

use Illuminate\Http\Resources\Json\JsonResource;

class DashboardRecentRegisteredUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
