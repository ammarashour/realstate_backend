<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Localization;

use Illuminate\Http\Request;
use App\Config\ps_constant;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsController;
use Modules\Core\Transformers\Backend\Model\Localization\LanguageWithKeyResource;
use Modules\Core\Entities\Localization\Language;
use Modules\Core\Http\Requests\Localization\StoreFeLanguageRequest;
use Modules\Core\Http\Requests\Localization\UpdateFeLanguageRequest;
use Illuminate\Support\Facades\Session;
use App\Http\Contracts\Localization\FeLanguageStringServiceInterface;
use App\Http\Contracts\Localization\LanguageServiceInterface;

class FeLanguageController extends PsController
{
    private const parentPath = "frontend_languages";
    private const indexPath = self::parentPath . "/Index";
    private const createPath = self::parentPath . "/Create";
    private const editPath = self::parentPath . "/Edit";
    private const indexRoute = "fe_language.index";
    private const createRoute = "fe_language.create";
    private const editRoute = "fe_language.edit";

    public function __construct(
        protected LanguageServiceInterface $languageService,
        protected FeLanguageStringServiceInterface $languageStringService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        // check permission
        $this->handlePermissionWithModel(Language::class, Constants::viewAnyAbility);

        $dataArr = $this->prepareIndexData($request);

        return renderView(self::indexPath, $dataArr);
    }

    public function create()
    {
        $this->handlePermissionWithModel(Language::class, Constants::createAbility);

        return renderView(self::createPath);
    }

    public function store(StoreFeLanguageRequest $request)
    {
        try
        {
            $validData = $request->validated();
    
            $this->languageService->save($validData);
    
            return redirectView(self::indexRoute);

        }catch(\Exception $e){
            return redirectViewWithError(self::createRoute, $e->getMessage());
        }
    }

    public function edit($id)
    {
        // check permission start
        $language = $this->languageService->get($id);
        $this->handlePermissionWithModel($language, Constants::editAbility);

        $dataArr = $this->prepareEditData($id);

        return renderView(self::editPath, $dataArr);
    }

    public function update(UpdateFeLanguageRequest $request, $id)
    {
        try
        {
            $validatedData = $request->validated();

            $this->languageService->update($id, $validatedData);
           
            return redirectView(self::indexRoute);

        }catch(\Exception $e)
        {
            return redirectViewWithError(self::editRoute, $e->getMessage(), $id);
        }
    }

    public function destroy($id)
    {
        try {
            $activeLang = $_COOKIE['activeLanguage'] ?? 'en';
            $language = $this->languageService->get($id);

            if (
                $language->status == Constants::publish
                || $language->symbol == $activeLang
            ) {
                return redirectViewWithError(self::indexRoute, __('core__be_cannot_delete'));
            }

            $this->handlePermissionWithModel($language, Constants::deleteAbility);

            $dataArr = $this->languageService->delete($id);

            return redirectView(self::indexRoute, $dataArr['msg'], $dataArr['flag']);
        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function statusChange($id)
    {
        try {

            $language = $this->languageService->get($id);

            $this->handlePermissionWithModel($language, Constants::editAbility);
            
            if($language->status == Constants::unPublish)
            {
                $status = $this->prepareStatusData($language);
    
                $this->languageService->setStatus($id, $status);
            }

            return redirectView(self::indexRoute, __('core__be_status_updated'));

        } catch (\Exception $e) {
            return redirectViewWithError(self::indexRoute, $e->getMessage());
        }
    }

    public function changeLanguage(Request $request){
        Session::put("applocale", $request->langSymbol);
        return redirect()->back();
    }

    public function screenDisplayUiStore(Request $request)
    {

        makeColumnHideShown($request);

        return redirect()->back();
    }
    ////////////////////////////////////////////////////////////////////
    /// Private Functions
    ////////////////////////////////////////////////////////////////////

    //-------------------------------------------------------------------
    // Data Preparation
    //-------------------------------------------------------------------

    private function prepareIndexData($request)
    {
        $conds = [
            'searchterm' => $request->input('search') ?? '',
            'order_by' => $request->input('sort_field') ?? 'added_date',
            'order_type' => $request->input('sort_order') ?? 'desc',
        ];
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        $relations = ['owner', 'editor'];
        $languages = LanguageWithKeyResource::collection($this->languageService->getAll($relations, $row, $conds));

        // taking for column and columnFilterOption
        $columnAndColumnFilter = takingForColumnAndFilterOption(Constants::language, $this->controlFieldArr());

        // prepare for permission
        $keyValueArr = [
            'createLanguage' => 'create-language',
        ];

        return [
            'frontend_languages' => $languages,
            'showCoreAndCustomFieldArr' => $columnAndColumnFilter[ps_constant::handlingColumn],
            'hideShowFieldForFilterArr' => $columnAndColumnFilter[ps_constant::handlingFilter],
            'sort_field' => $conds['order_by'],
            'sort_order' => $conds['order_type'],
            'search' => $conds['searchterm'],
            'can' => $this->permissionService->checkingForCreateAbilityWithModel($keyValueArr),
        ];
    }

    private function prepareEditData($id)
    {
        $language = $this->languageService->get($id);
        return [
            "language" => $language,
        ];
    }

    private function prepareStatusData($language)
    {
        return $language->status == Constants::publish
            ? Constants::unPublish
            : Constants::publish;
    }


    //-------------------------------------------------------------------
    // Others
    //-------------------------------------------------------------------
    private function controlFieldArr()
    {
        // for control
        $controlFieldArr = [];
        $controlFieldObj = takingForColumnProps(__('core__be_action'), 'action', 'Action', false, 0);
        array_push($controlFieldArr, $controlFieldObj);

        return $controlFieldArr;
    }

}

