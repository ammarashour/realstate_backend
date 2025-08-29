<?php
namespace Modules\Core\Http\Services;

use App\Config\ps_constant;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\SearchHistory;
use Illuminate\Support\Facades\Auth;

class SearchHistoryService extends PsService
{
    protected $fromHomePageSearch, $notFromHomePageSearch, $searchHistoryIsHomePageSearchCol, $subCategoryType, $itemType, $categoryType, $userAccessApiTokenService, $successStatus, $createdStatusCode, $okStatusCode, $internalServerErrorStatusCode, $noContentStatusCode, $notFoundStatusCode, $searchHistoryIdCol, $searchHistorySymbolCol, $searchHistoryShortFormCol, $publish, $unPublish, $default, $unDefault, $warningFlag, $successFlag, $dangerFlag, $searchHistoryIsDefaultCol, $searchHistoryStatusCol, $show, $hide, $delete, $unDelete, $viewAnyAbility, $createAbility, $editAbility, $deleteAbility, $code, $screenDisplayUiKeyCol, $screenDisplayUiIdCol, $screenDisplayUiIsShowCol, $coreFieldFilterModuleNameCol, $customUiIsDelCol;
    public function __construct(UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->successStatus = Constants::successStatus;
        $this->createdStatusCode = Constants::createdStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
        $this->noContentStatusCode = Constants::noContentStatusCode;
        $this->notFoundStatusCode = Constants::notFoundStatusCode;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;

        $this->searchHistoryIdCol = SearchHistory::id;
        $this->searchHistoryUserIdCol = SearchHistory::userId;
        $this->searchHistoryKeywordCol = SearchHistory::keyword;
        $this->searchHistoryTypeCol = SearchHistory::type;
        $this->searchHistoryIsHomePageSearchCol = SearchHistory::isHomePageSearch;

        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->default = Constants::default;
        $this->unDefault = Constants::unDefault;
        $this->warningFlag = Constants::warning;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->categoryType = Constants::categoryType;
        $this->itemType = Constants::itemType;
        $this->subCategoryType = Constants::subCategoryType;
        $this->notFromHomePageSearch = Constants::notFromHomePageSearch;
        $this->fromHomePageSearch = Constants::fromHomePageSearch;


        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->loginUserIdParaApi = ps_constant::loginUserIdParaFromApi;
        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $searchHistory = new SearchHistory();
            $searchHistory->user_id = $request->user_id;
            $searchHistory->type = $request->type;
            $searchHistory->keyword = $request->keyword;
            $searchHistory->is_home_page_search = $request->is_home_page_search;
            if(isset($request->added_user_id) && !empty($request->added_user_id)){
                $searchHistory->added_user_id = $request->added_user_id;
            }else{
                $searchHistory->added_user_id = Auth::user()->id;
            }
            $searchHistory->save();

            DB::commit();

        } catch (\Throwable $e){
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
       return $searchHistory;
    }

    public function update($id,$request)
    {
       DB::beginTransaction();
        try {
            $searchHistory = $this->getSearchHistory($id);
            $searchHistory->user_id = $request->user_id;
            $searchHistory->keyword = $request->keyword;
            $searchHistory->type = $request->type;
            if(isset($request->updated_user_id) && !empty($request->updated_user_id)){
                $searchHistory->updated_user_id = $request->updated_user_id;
            }else{
                $searchHistory->updated_user_id = Auth::user()->id;
            }
            $searchHistory->update();

            DB::commit();

        }catch (\Throwable $e){
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

        return $searchHistory;
    }

    public function getSearchHistories($conds, $relations = null, $limit=null, $offset=null, $isHomePageSearch=null){
        $searchHistories = SearchHistory::when($conds, function ($query, $conds) {

            if (isset($conds['user_id']) && $conds['user_id']) {
                $query->where($this->searchHistoryUserIdCol, $conds['user_id']);
            }

            if (isset($conds['type']) && $conds['type']) {
                $query->where($this->searchHistoryTypeCol, $conds['type']);
            }
        })
        ->when($isHomePageSearch, function($query) use ($isHomePageSearch){
            if ($isHomePageSearch !== null) {
                $query->where($this->searchHistoryIsHomePageSearchCol, $isHomePageSearch);
            }
        })
        ->when($relations, function ($query, $relations) {
            $query->with($relations);
        })
        ->when($limit, function ($query, $limit) {
            $query->limit($limit);
        })
        ->when($offset, function ($query, $offset) {
            $query->offset($offset);
        })
        ->latest()->get();
        return $searchHistories;
    }

    public function getSearchHistory($id){
        $searchHistory = SearchHistory::find($id);
        return $searchHistory;
    }

    // for api
    public function noDataError($offset, $data){
        if ($offset > 0 && $data->isEmpty()) {
            // no paginate data
            $data = [];
            return responseDataApi($data);

        } else if($data->isEmpty()) {
            // no data db
            return responseMsgApi(__('core__api_no_data'), $this->noContentStatusCode, $this->successStatus);
        }
    }

    public function deleteHistoryByType($type=null, $id=null, $langSymbol){

    }

    public function searchFromApi($request){

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('core__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            $offset = $request->offset;
            $limit = $request->limit;

            $conds['user_id'] = $request->user_id;
            $conds['type'] = $request->type;

            $searchHistoryApiRelation = ['user'];
            $searchHistories = $this->getSearchHistories($conds, $searchHistoryApiRelation, $limit, $offset);

            return $searchHistories;
        }
    }

    public function destroyFromApi($request){

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('core__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            //delete in search_histories table
            $ids = $request->ids;
            SearchHistory::destroy($ids);

            if (empty($ids)){
                return  ['success' =>  __('core__api_record_not_found',[],$request->language_symbol),'status' =>  $this->noContentStatusCode ];
            } else {
                return  ['success' =>  __('core__api_history_delete_success',[],$request->language_symbol),'status' =>  $this->okStatusCode ];
            }
        }
    }

    public function searchCategoryHistoryFromApi($request){

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('core__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            $offset = $request->offset;
            $limit = $request->limit;

            $conds['user_id'] = $request->login_user_id;
            $conds['type'] = $this->categoryType;

            $searchHistoryApiRelation = ['user'];
            $searchHistories = $this->getSearchHistories($conds, $searchHistoryApiRelation, $limit, $offset, $this->notFromHomePageSearch);

            return $searchHistories;
        }
    }

    public function destroyCategoryHistoryFromApi($request){

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('core__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            //delete in search_histories table
            // $ids = $request->ids;
            $id = $request->id;
            $searchHistory = SearchHistory::where("type", $this->categoryType)->where('id', $id)->first();
            if(empty($searchHistory)){
                return  ['error' =>  __('core__api_record_not_found',[], $request->language_symbol),'status' =>  $this->notFoundStatusCode ];
            } else {
                $searchHistory->delete();
            }

            if (empty($id)){
                return  ['success' =>  __('core__api_record_not_found',[],$request->language_symbol),'status' =>  $this->noContentStatusCode ];
            } else {
                return  ['success' =>  __('core__api_history_delete_success',[],$request->language_symbol),'status' =>  $this->okStatusCode ];
            }
        }
    }

    public function searchItemHistoryFromApi($request){

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('core__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            $offset = $request->offset;
            $limit = $request->limit;

            $conds['user_id'] = $request->login_user_id;
            $conds['type'] = $this->itemType;



            $searchHistoryApiRelation = ['user'];
            $searchHistories = $this->getSearchHistories($conds, $searchHistoryApiRelation, $limit, $offset, $this->notFromHomePageSearch);


            return $searchHistories;
        }
    }

    public function destroyItemHistoryFromApi($request){

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('core__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            //delete in search_histories table
            // $ids = $request->ids;
            $id = $request->id;
            $searchHistory = SearchHistory::where("type", $this->itemType)->where('id', $id)->first();
            if(empty($searchHistory)){
                return  ['error' =>  __('core__api_record_not_found',[], $request->language_symbol),'status' =>  $this->notFoundStatusCode ];
            } else {
                $searchHistory->delete();
            }

            if (empty($id)){
                return  ['success' =>  __('core__api_record_not_found',[],$request->language_symbol),'status' =>  $this->noContentStatusCode ];
            } else {
                return  ['success' =>  __('core__api_history_delete_success',[],$request->language_symbol),'status' =>  $this->okStatusCode ];
            }
        }
    }

    public function searchSubCatHistoryFromApi($request){

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('core__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            $offset = $request->offset;
            $limit = $request->limit;

            $conds['user_id'] = $request->login_user_id;
            $conds['type'] = $this->subCategoryType;

            $searchHistoryApiRelation = ['user'];
            $searchHistories = $this->getSearchHistories($conds, $searchHistoryApiRelation, $limit, $offset, $this->notFromHomePageSearch);

            return $searchHistories;
        }
    }

    public function destroySubCatHistoryFromApi($request){

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('core__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            //delete in search_histories table
            // $ids = $request->ids;
            $id = $request->id;
            $searchHistory = SearchHistory::where("type", $this->subCategoryType)->where('id', $id)->first();
            if(empty($searchHistory)){
                return  ['error' =>  __('core__api_record_not_found',[], $request->language_symbol),'status' =>  $this->notFoundStatusCode ];
            } else {
                $searchHistory->delete();
            }

            if (empty($id)){
                return  ['success' =>  __('core__api_record_not_found',[],$request->language_symbol),'status' =>  $this->noContentStatusCode ];
            } else {
                return  ['success' =>  __('core__api_history_delete_success',[],$request->language_symbol),'status' =>  $this->okStatusCode ];
            }
        }
    }

}
