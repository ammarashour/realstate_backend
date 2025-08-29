<?php

namespace Modules\Core\Transformers\Api\App\V1_0\SearchHistory;

use Illuminate\Http\Resources\Json\JsonResource;

class SearchHistoryApiResource extends JsonResource
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
            'keyword' => isset($this->keyword)?(string)$this->keyword:'',
            'user_id' => isset($this->user_id)?(string)$this->user_id:'',
            'type' => isset($this->type)?(string)$this->type:'',
            'is_home_page_search' => isset($this->is_home_page_search)?(string)$this->is_home_page_search:'',
            'added_date' => isset($this->added_date)?(string)$this->added_date:'',
            'added_user_id' => isset($this->added_user_id)?(string)$this->added_user_id:'',
            'updated_date' => isset($this->updated_date)?(string)$this->updated_date:'',
            'updated_user_id' => isset($this->updated_user_id)?(string)$this->updated_user_id:'',
            'updated_flag' => isset($this->updated_flag)?(string)$this->updated_flag:'',
            "added_date_str" => isset($this->added_date)?(string)$this->added_date->diffForHumans():'',
            "is_empty_object" => $this->when(!isset($this->id),1),
        ];
    }
}
