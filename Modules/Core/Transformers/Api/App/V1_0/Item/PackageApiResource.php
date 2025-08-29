<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Item;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Financial\ItemCurrency;
use Modules\Core\Transformers\Api\App\V1_0\Financial\ItemCurrencyApiResource;

class PackageApiResource extends JsonResource
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
            'id' => isset($this->id)?(string)$this->id:'',
            'title' => isset($this->title)?(string)$this->title:'',
            'price' => isset($this->price)?(string)$this->price:'',
            'currency_id' => isset($this->currency_id)?(string)$this->currency_id:'',
            'post_count' => isset($this->post_count)?(string)$this->post_count:'',
            'status' => isset($this->status)?(string)$this->status:'',
            "currency" => new ItemCurrencyApiResource(isset($this->currency) && $this->currency ? $this->whenLoaded('currency'): ItemCurrency::where('id', 0)->get()),
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
