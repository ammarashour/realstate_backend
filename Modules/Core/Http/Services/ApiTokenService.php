<?php

namespace Modules\Core\Http\Services;

use App\Config\Cache\PersonalAccessTokenCache;
use App\Http\Services\PsService;
use Modules\Core\Entities\Authorization\ApiToken;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use Laravel\Sanctum\PersonalAccessToken;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Entities\Project;
use Modules\Core\Entities\Utilities\DynamicColumnVisibility;
use Modules\Core\Http\Facades\PsCache;
use Modules\Core\Transformers\Backend\Model\Authorization\ApiTokenWithKeyResource;
/**
 * @deprecated
 */
class ApiTokenService extends PsService
{
    protected $radioUi, $dropDownUi, $publish, $unPublish, $default, $apiTokenIdCol, $apiTokenNameCol, $apiTokenTokenableTypeCol, $apiTokenAddedDate, $apiTokenAddedUserId,
        $apiTokenTokenableIdCol, $apiTokenTokenCol, $apiTokenAbilitiesCol, $apiTokenLastUsedAtCol,
        $warningFlag, $successFlag, $dangerFlag, $show, $hide, $delete, $unDelete,
        $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $code,
        $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol,
        $coreFieldFilterModuleNameCol, $customUiIsDelCol;

    public function __construct()
    {
        $this->apiTokenIdCol = ApiToken::id;
        $this->apiTokenNameCol = ApiToken::name;
        $this->apiTokenAddedDate = ApiToken::addedDate;
        $this->apiTokenAddedUserId = ApiToken::addedUserId;
        $this->apiTokenTokenableTypeCol = ApiToken::tokenableType;
        $this->apiTokenTokenableIdCol = ApiToken::tokenableId;
        $this->apiTokenTokenCol = ApiToken::token;
        $this->apiTokenAbilitiesCol = ApiToken::abilities;
        $this->apiTokenLastUsedAtCol = ApiToken::lastUsedAt;

        $this->warningFlag = Constants::warning;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;

        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;
        $this->default = Constants::default;
        $this->unPublish = Constants::unPublish;
        $this->publish = Constants::publish;

        $this->dropDownUi = Constants::dropDownUi;
        $this->radioUi = Constants::radioUi;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;
        $this->code = Constants::apiToken;

        $this->screenDisplayUiKeyCol = DynamicColumnVisibility::key;
        $this->screenDisplayUiIdCol = DynamicColumnVisibility::id;
        $this->screenDisplayUiIsShowCol = DynamicColumnVisibility::isShow;

        $this->coreFieldFilterModuleNameCol = CoreField::moduleName;

        $this->customUiIsDelCol = CustomField::isDelete;
    }

    public function searching($query, $conds)
    {

        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where($this->apiTokenNameCol, 'like', '%' . $search . '%');
            });
        }

        if (isset($conds['added_date']) && $conds['added_date']) {
            $date_filter = $conds['added_date'];
            $query->whereHas($this->apiTokenAddedDate, function ($q) use ($date_filter) {
                $q->where($this->apiTokenAddedDate, $date_filter);
            });
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where($this->apiTokenAddedDate, $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']) {
            if ($conds['order_by'] == 'id') {
                $query->orderBy('id', $conds['order_type']);
            } else {
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getApiTokens($status = null, $limit = null, $offset = null, $noPagination = null, $pagPerPage = null, $conds = null, $abilities = null)
    {

        $param = [$status, $limit, $offset, $noPagination, $pagPerPage, $conds, $abilities];

        return PsCache::remember(
            [PersonalAccessTokenCache::BASE],
            PersonalAccessTokenCache::GET_ALL_EXPIRY,
            $param,
            function () use ($status, $limit, $offset, $noPagination, $pagPerPage, $conds, $abilities) {
                $apiTokens = ApiToken::when($conds, function ($query, $conds) {
                    $query = $this->searching($query, $conds);
                })
                    ->when($limit, function ($query, $limit) {
                        $query->limit($limit);
                    })
                    ->when($offset, function ($query, $offset) {
                        $query->offset($offset);
                    })
                    ->when($abilities, function ($query, $abilities) {
                        $query->where('abilities', 'like', '%' . $abilities . '%');
                    })
                    ->latest();
                if ($pagPerPage) {
                    $apiTokens = $apiTokens->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                } elseif ($noPagination == 1) {
                    $apiTokens = $apiTokens->get();
                }
                return $apiTokens;
            }
        );
    }

    public function getApiToken($id = null, $conds = null)
    {
        $param = [$id, $conds];
        return PsCache::remember(
            [PersonalAccessTokenCache::BASE],
            PersonalAccessTokenCache::GET_EXPIRY,
            $param,
            function () use ($id, $conds) {
                $available_currency = ApiToken::when($id, function ($query, $id) {
                    $query->where(
                        $this->apiTokenIdCol,
                        $id
                    );
                })
                    ->when($conds, function ($query, $conds) {
                        $query->where($conds);
                    })->first();
                return $available_currency;
            }
        );
    }

    public function delete($id)
    {
        $apiToken = $this->getApiToken($id);
        $name = $apiToken->currency_short_form;
        if ($apiToken->is_default == 1) {
            // $msg = 'The '.$name.' row cannot be deleted because of default language.';
            $msg = __('core__be_undelete', ['attribute' => $name]);
            return $msg;
        }
        $apiToken->delete();

        PsCache::clear(PersonalAccessTokenCache::BASE);
    }

    public function makePublishOrUnpublish($id)
    {
        $apiToken = $this->getApiToken($id);
        if ($apiToken->is_default !== $this->default) {
            if ($apiToken->status == $this->publish) {
                $apiToken->status = $this->unPublish;
            } else {
                $apiToken->status = $this->publish;
            }
            $apiToken->updated_user_id = Auth::user()->id;
            $apiToken->update();
            PsCache::clear(PersonalAccessTokenCache::BASE);
        } else {
            // $msg = ' Sorry, the ' . $apiToken->currency_short_form . ' row cannot be able to unpublish in default apiToken.';
            $msg = __('core__be_unplish_default_currency', ['attribute' => $apiToken->currency_short_form]);
            return $msg;
        }
    }

    public function makeColumnHideShown($request)
    {
        $hideShowFields = $request->value;
        //        foreach ($hideShowFields as $hideShowField){
        //            $DynamicColumnVisibility = DynamicColumnVisibility::find($hideShowField[$this->screenDisplayUiIdCol]);
        //            $DynamicColumnVisibility->is_show = $hideShowField['hidden'] ? $this->hide : $this->show;
        //            $DynamicColumnVisibility->update();
        //        }
        foreach ($hideShowFields as $hideShowField) {
            $hideShowFieldData[] = [
                'id' => $hideShowField['id'],
                'is_show' => $hideShowField['hidden'] ? $this->hide : $this->show,
            ];
        }
        DynamicColumnVisibility::upsert(
            $hideShowFieldData,
            ['id'],
            ['is_show']
        );
    }

    // ----------------
    public function index($request)
    {
        // check permission
        $checkPermission = $this->checkPermission($this->viewAnyAbility, ApiToken::class, "admin.index");

        // search filter
        $conds['searchterm'] = $request->input('search') ?? '';

        $conds['order_by'] = null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if ($request->sort_field) {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $apiTokens = ApiTokenWithKeyResource::collection($this->getApiTokens(null, null, null, false,  $row, $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = $this->takingForColumnAndFilterOption();
        $showProductCols = $columnAndColumnFilter['showCoreField'];
        $columnProps = $columnAndColumnFilter['arrForColumnProps'];
        $columnFilterOptionProps = $columnAndColumnFilter['arrForColumnFilterProps'];

        // $availablePermissions = [
        //     "adminMobileToken",
        //     "userMobileToken",
        //     "userWebsiteToken",
        //     "deliboyMobileToken"
        // ];

        $availablePermissions = Jetstream::$permissions;

        if ($conds['order_by']) {
            $dataArr = [
                "availablePermissions" => $availablePermissions,
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'apiTokens' => $apiTokens,
                'sort_field' => $conds['order_by'],
                'sort_order' => $request->sort_order,
                'search' => $conds['searchterm'],
            ];
        } else {
            $dataArr = [
                "availablePermissions" => $availablePermissions,
                "checkPermission" => $checkPermission,
                'showCoreAndCustomFieldArr' => $columnProps,
                'hideShowFieldForFilterArr' => $columnFilterOptionProps,
                'apiTokens' => $apiTokens,
                'search' => $conds['searchterm'],
            ];
        }

        return $dataArr;
    }

    public function create()
    {

        // check permission
        $checkPermission = $this->checkPermission($this->createAbility, ApiToken::class, "admin.index");

        $availablePermissions = Jetstream::$permissions;
        $dataArr = [
            'checkPermission' => $checkPermission,
            "availablePermissions" => $availablePermissions
        ];
        return $dataArr;
    }

    public function destroy($id)
    {
        $apiToken = $this->getApiToken($id);

        // check permission
        $checkPermission = $this->checkPermission($this->deleteAbility, $apiToken, "admin.index");

        // delete token from project
        $project = Project::first();
        $token = PersonalAccessToken::findToken($project->token);

        if (isset($token)) {
            if ($token->token == $apiToken->token) {
                $project->token = "";
                $project->update();
            }
        }

        $name = $apiToken->name;
        $this->delete($id);

        $dataArr = [
            "checkPermission" => $checkPermission,
            "msg" =>  __('core__be_delete_success', ['attribute' => $name]),
            "flag" => $this->successFlag
        ];
        return $dataArr;
    }

    public function statusChange($id)
    {
        $name = $this->getApiToken($id)->available_currency_short_form;

        $msg = $this->makePublishOrUnpublish($id);

        // if default available_currency
        if ($msg) {
            $dataArr = [
                "msg" => $msg,
                "flag" => $this->warningFlag
            ];
            return $dataArr;
        }

        // $msg = 'The status of '.$name.' row has been updated successfully.';
        $msg = __('core__be_status_updated', ['attribute' => $name]);
        $dataArr = [
            "msg" => $msg,
            "flag" => $this->successFlag
        ];
        return $dataArr;
    }

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;
        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action") {
            $hideShowCoreAndCustomFieldObj->action = 'Action';
        }

        return $hideShowCoreAndCustomFieldObj;
    }

    public function takingForColumnAndFilterOption()
    {

        // for table
        $hideShowCoreFieldForColumnArr = [];
        $hideShowCustomFieldForColumnArr = [];

        $showProductCols = [];

        // for eye
        $hideShowFieldForColumnFilterArr = [];

        // for control
        $controlFieldArr = [];
        $controlFieldObj = $this->takingForColumnProps(__('core__be_action'), "action", "Action", false, 0);
        array_push($controlFieldArr, $controlFieldObj);
        // $controlFieldObj = $this->takingForColumnProps(__('core__be_permission_link'), "Permission Link", "Action", false, 0);
        // array_push($controlFieldArr, $controlFieldObj);

        $code = $this->code;
        $hiddenForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->hide, $code);
        $shownForCoreAndCustomField = $this->hiddenShownForCoreAndCustomField($this->show, $code);
        $hideShowForCoreAndCustomFields = $shownForCoreAndCustomField->merge($hiddenForCoreAndCustomField);

        foreach ($hideShowForCoreAndCustomFields as $showFields) {
            if ($showFields->coreField !== null) {

                $label = $showFields->coreField->label_name;
                $field = $showFields->coreField->field_name;
                $colName = $showFields->coreField->field_name;
                $type = $showFields->coreField->data_type;
                $isShowSorting = $showFields->coreField->is_show_sorting;
                $ordering = $showFields->coreField->ordering;

                $cols = $colName;
                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->coreField->module_name;
                $keyId = $showFields->coreField->id;


                $coreFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $coreFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $coreFieldObjForColumnFilter);
                array_push($hideShowCoreFieldForColumnArr, $coreFieldObjForColumnsProps);
                array_push($showProductCols, $cols);
            }
            if ($showFields->customizeField !== null) {

                $label = $showFields->customizeField->name;
                $uiHaveAttribute = [$this->dropDownUi, $this->radioUi];
                if (in_array($showFields->customizeField->ui_type_id, $uiHaveAttribute)) {
                    $field = $showFields->customizeField->core_keys_id . "@@name";
                } else {
                    $field = $showFields->customizeField->core_keys_id;
                }
                $type = $showFields->customizeField->data_type;
                $isShowSorting = $showFields->customizeField->is_show_sorting;
                $ordering = $showFields->customizeField->ordering;

                $id = $showFields->id;
                $hidden = $showFields->is_show ? false : true;
                $moduleName = $showFields->customizeField->module_name;
                $keyId = $showFields->customizeField->core_keys_id;

                $customFieldObjForColumnsProps = $this->takingForColumnProps($label, $field, $type, $isShowSorting, $ordering);
                $customFieldObjForColumnFilter = $this->takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId);

                array_push($hideShowFieldForColumnFilterArr, $customFieldObjForColumnFilter);
                array_push($hideShowCustomFieldForColumnArr, $customFieldObjForColumnsProps);
            }
        }

        // for columns props
        $showCoreAndCustomFieldArr = array_merge($hideShowCoreFieldForColumnArr, $controlFieldArr, $hideShowCustomFieldForColumnArr);
        $sortedColumnArr = columnOrdering("ordering", $showCoreAndCustomFieldArr);

        // for eye
        $hideShowCoreAndCustomFieldArr = array_merge($hideShowFieldForColumnFilterArr);

        $dataArr = [
            "arrForColumnProps" => $sortedColumnArr,
            "arrForColumnFilterProps" => $hideShowCoreAndCustomFieldArr,
            "showCoreField" => $showProductCols,
        ];
        return $dataArr;
    }

    public function hiddenShownForCoreAndCustomField($isShown, $code)
    {
        $hiddenShownForFields = DynamicColumnVisibility::with(['customizeField' => function ($q) {
            $q->where($this->customUiIsDelCol, $this->unDelete);
        }, 'coreField' => function ($q) {
            $q->where($this->coreFieldFilterModuleNameCol, $this->code);
        }])
            ->where($this->coreFieldFilterModuleNameCol, $code)
            ->where($this->screenDisplayUiIsShowCol, $isShown)
            ->get();
        return $hiddenShownForFields;
    }

    public function takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId)
    {
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->id = $id;
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->key = $field;
        $hideShowCoreAndCustomFieldObj->hidden = $hidden;
        $hideShowCoreAndCustomFieldObj->module_name = $moduleName;
        $hideShowCoreAndCustomFieldObj->key_id = $keyId;

        return $hideShowCoreAndCustomFieldObj;
    }
}
