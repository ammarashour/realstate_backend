<?php

namespace Modules\Core\Transformers\Api\App\V1_0\HomePageSearch;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Transformers\Api\App\V1_0\Category\CategoryApiResource;
use Modules\Core\Transformers\Api\App\V1_0\Item\ItemApiResource;
use Modules\Core\Transformers\Api\App\V1_0\User\UserApiResource;

class HomePageSearchApiResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            "items" => ItemApiResource::collection($this['items']),
            "categories" => CategoryApiResource::collection($this['categories']),
            "users" => UserApiResource::collection($this['users']),
        ];
    }
}
