<?php

namespace Modules\Core\Transformers\Backend\NoModel\Configuration;

use App\Http\Contracts\Authorization\PermissionServiceInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Modules\Core\Entities\Utilities\UiType;
use Modules\Core\Constants\Constants;

class TableFieldWithKeyResource extends JsonResource
{

    public function toArray($request)
    {
        $uiTypes = UiType::latest()->get();

        if ($this->ui_type_id != NULL) {
            foreach ($uiTypes as $uiType) {
                if ($uiType->core_keys_id == $this->ui_type_id) {
                    $this->ui_type_id = $uiType;
                }
            }
        }
        // return parent::toArray($request);
        return [
            // 'id'=> (string) $this->id,
            "name" => (string) $this->name,
            "nameKey" => (string) $this->nameKey,
            "placeholderKey" => (string) $this->placeholderKey,
            "placeholder" => (string) $this->placeholder,
            "id" => (string) $this->id,
            "module_name" => (string) $this->module_name,
            "field_name" => (string) $this->field_name,
            "ui_type_id" => $this->ui_type_id,
            "core_keys_id" => (string) $this->core_keys_id,
            "data_type" => (string) $this->data_type,
            "table_id" => (string) $this->table_id,
            "base_module_name" => (string) $this->base_module_name,
            "project_id" => (string) $this->project_id,
            "project_name" => (string) $this->project_name,
            "is_core_field" => (string) $this->is_core_field,
            "is_delete" => (string) $this->is_delete,
            "enable" => (string) $this->enable,
            "is_show_sorting" => (string) $this->is_show_sorting,
            "ordering" => (string) $this->ordering,
            "is_show_in_filter" => (string) $this->is_show_in_filter,
            "mandatory" => (string) $this->mandatory,
            "is_include_in_hideshow" => (string) $this->is_include_in_hideshow,
            "is_show" => (string) $this->is_show,
            "permission_for_enable_disable" => (string) $this->permission_for_enable_disable,
            "permission_for_delete" => (string) $this->permission_for_delete,
            "permission_for_mandatory" => (string) $this->permission_for_mandatory,
            "added_date" => (string) $this->added_date,
            "added_user_id" => (string) $this->added_user_id,
            "updated_date" => (string) $this->updated_date,
            "updated_user_id" => (string) $this->updated_user_id,
            "updated_flag" => (string) $this->updated_flag,
            'authorizations' => app(PermissionServiceInterface::class)->authorizationWithoutModel(Constants::tableFieldModule, Auth::id())
        ];
    }
}
