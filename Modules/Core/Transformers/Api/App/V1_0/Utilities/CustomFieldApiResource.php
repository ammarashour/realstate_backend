<?php

namespace Modules\Core\Transformers\Api\App\V1_0\Utilities;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Core\Entities\Utilities\UiType;

class CustomFieldApiResource extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => checkAndGetValue($this, 'id'),
            'name' => $this->getName($request),
            'placeholder' => $this->getPlaceholder($request),
            'core_keys_id' => checkAndGetValue($this, 'core_keys_id'),
            "ui_type" => new UiTypeApiResource(isset($this->uiTypeId) && $this->uiTypeId ? $this->whenLoaded('uiTypeId') : UiType::where('id', 0)->get()),
            'mandatory' => checkAndGetValue($this, 'mandatory'),
            'is_visible' => checkAndGetValue($this, 'enable'),
            'is_delete' => isset($this->is_delete) ? (string)$this->is_delete : '',
            'module_name' => isset($this->module_name) ? (string)$this->module_name : '',
            'data_type' => isset($this->data_type) ? (string)$this->data_type : '',
            'is_core_field' => isset($this->is_core_field) ? (string)$this->is_core_field : '',
            'added_date' => isset($this->added_date) ? (string)$this->added_date : '',
            "added_date_str" => isset($this->added_date) ? (string)$this->added_date->diffForHumans() : '',
            "is_empty_object" => $this->when(!isset($this->id), 1),
            "customize_ui_details" => CustomFieldAttributeApiResource::collection(isset($this->customizeUiDetail) && count($this->customizeUiDetail) > 0 ? $this->whenLoaded('customizeUiDetail') : ['xxx']),
        ];
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    private function getName($request)
    {
        return __(checkAndGetValue($this, 'name'), [], $request->language_symbol);
    }

    private function getPlaceholder($request)
    {
        return __(checkAndGetValue($this, 'placeholder'), [], $request->language_symbol);
    }

}
