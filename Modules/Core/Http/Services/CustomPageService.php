<?php

namespace Modules\Core\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CustomPage;



use Modules\Core\Entities\SpecificUser;

class CustomPageService extends PsService
{
    protected $notFromHomePageSearch, $dropDownUi, $radioUi, $customPageNameCol, $categoryApiRelation, $tableName, $searchHistoryService, $searchHistoryCategoryType, $deleteAbility, $editAbility, $createAbility, $viewAnyAbility, $customPageStatusCol, $csvFileName, $iconImgType, $coverImgType, $imageService, $customPageIdCol, $coreImageImgParentIdCol, $publish, $unPublish, $coreImageImgTypeCol, $noContentStatusCode, $successStatus,
    $customUiIsDelCol,$screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $screenDisplayUiKeyCol,$code,$coreFieldFilterModuleNameCol,$delete, $unDelete, $enable, $disable, $hide, $show;

    public function __construct(ImageService $imageService, SearchHistoryService $searchHistoryService)
    {
        $this->dropDownUi = Constants::dropDownUi;
        $this->radioUi = Constants::radioUi;

        $this->imageService = $imageService;
        $this->searchHistoryService = $searchHistoryService;
        $this->searchHistoryCategoryType = Constants::searchHistoryCategoryType;

        $this->tableName = CustomPage::tableName;
        $this->customPageIdCol = CustomPage::id;
        $this->customPageStatusCol = CustomPage::status;
        $this->customPageNameCol = CustomPage::title;
        // $this->coreImageImgParentIdCol = CoreImage::imgParentId;
        // $this->coreImageImgTypeCol = CoreImage::imgType;

        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->show = Constants::show;
        $this->hide = Constants::hide;
        $this->enable = Constants::enable;
        $this->disable = Constants::disable;
        $this->delete = Constants::delete;
        $this->unDelete = Constants::unDelete;

        $this->coverImgType = "category-cover";
        $this->iconImgType = "category-icon";
        $this->csvFileName = "category_report";
        $this->categoryApiRelation = ['defaultPhoto', 'defaultIcon'];

        $this->noContentStatusCode = Constants::noContentStatusCode;
        $this->successStatus = Constants::successStatus;

        $this->viewAnyAbility = Constants::viewAnyAbility;
        $this->createAbility = Constants::createAbility;
        $this->editAbility = Constants::editAbility;
        $this->deleteAbility = Constants::deleteAbility;
        $this->code = Constants::customPage;

        $this->notFromHomePageSearch = Constants::notFromHomePageSearch;

    }


    public function getCustomPage($relation = null, $status = null, $limit = null, $offset = null, $conds = null,$noPagination = null,  $pagPerPage = null,$touchCount = null){
        $sort = '';
        if(isset($conds['order_by'])){
            $sort = $conds['order_by'];
        }
        $custompages = CustomPage::when($relation, function ($q, $relation){
                                    $q->with($relation);
                                })
                                ->when($status, function ($q, $status){
                                    $q->where($this->customPageStatusCol, $status);
                                })
                                ->when($limit, function ($query, $limit) {
                                    $query->limit($limit);
                                })
                                ->when($offset, function ($query, $offset) {
                                    $query->offset($offset);
                                })
                                ->when($conds, function ($query, $conds) {
                                    $query = $this->searching($query, $conds);
                                })
                                ->when(empty($sort), function ($query, $conds){
                                    $query->orderBy('added_date', 'desc')->orderBy('status', 'desc')->orderBy('title', 'asc');
                                });

                                if ($pagPerPage){
                                $custompages = $custompages->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                                } elseif ($noPagination){
                                    $custompages = $custompages->get();
                                }else{
                                    $custompages = $custompages->get();
                                }
                                return $custompages;
    }

    public function searching($query, $conds){
        // search term
        if (isset($conds['keyword']) && $conds['keyword']) {
            $conds['searchterm'] = $conds['keyword'];
        }
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where($this->tableName . '.' . $this->customPageNameCol, 'like', '%' . $search . '%');
            });
        }
        if(isset($conds['selected_date']) && $conds['selected_date']){
            $date_filter = $conds['selected_date'];
                // dd($date_filter);
            if($date_filter[1] == ''){
                $date_filter[1] = Carbon::now();
            }
            $query->whereBetween($this->tableName . '.added_date', $date_filter);
            // $date_filter=$conds['selected_date'];
            // $new_date=date('Y-m-d', strtotime($date_filter));

            // $query->whereDate($this->tableName . '.added_date','=',$new_date);
        }

        if(isset($conds['added_date']) && $conds['added_date']){
            $date_filter = $conds['added_date'];
            $query->where($this->tableName .'.added_date', $date_filter);
        }

        if(isset($conds['date_range']) && $conds['date_range']){
            $date_filter = $conds['date_range'];
            if($date_filter[1] == ''){
                $date_filter[1] = Carbon::now();
            }
            $query->whereBetween($this->tableName . '.added_date', $date_filter);
        }

        if (isset($conds['added_user_id']) && $conds['added_user_id']) {
            $query->where('added_user', $conds['added_user_id']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']){

            if($conds['order_by'] == 'id'){
                $query->orderBy('categories.id', $conds['order_type']);
            }
            else{
                $query->orderBy($conds['order_by'], $conds['order_type']);
            }
        }

        return $query;
    }

    public function getCategory($id){
        $custompage = CustomPage::where($this->customPageIdCol, $id)->with()->first();
        return $custompage;
    }
}
