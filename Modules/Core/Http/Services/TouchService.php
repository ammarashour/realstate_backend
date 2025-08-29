<?php
namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Touch;
use Modules\Core\Entities\Item;
use Modules\Core\Http\Services\ItemService;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use App\Config\ps_constant;
use Modules\Core\Http\Services\Category\CategoryService;

class TouchService extends PsService
{
    protected $userAccessApiTokenService;
    public function __construct(ItemService $itemService,UserAccessApiTokenService $userAccessApiTokenService, CategoryService $categoryService)
    {
        $this->successStatus = Constants::successStatus;
        $this->createdStatusCode = Constants::createdStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
        $this->noContentStatusCode = Constants::noContentStatusCode;
        $this->notFoundStatusCode = Constants::notFoundStatusCode;
        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;

        $this->loginUserIdParaApi = ps_constant::loginUserIdParaFromApi;
        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;

        $this->itemService = $itemService;
        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->categoryService = $categoryService;


    }

    public function addItemTouchCountFromApi($request){
         // check permission start
         $deviceToken = $request->header($this->deviceTokenParaApi);
         $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken( $request->login_user_id, $deviceToken);

         if (empty($userAccessApiToken)){
             $msg = __('core__api_update_no_permission',[],$request->language_symbol);
             return ['error' =>  $msg,'status' =>  $this->forbiddenStatusCode ];
         }
         // check permission end

        $dataRow = [];
        if($request->type_name == 'item'){
            $dataRow = $this->itemService->getItem($request->type_id);
        }
        if($request->type_name == 'category'){
            $dataRow = $this->categoryService->getCategory($request->type_id);
        }

        if (!$dataRow || $dataRow->status == -1) {
            return  ['error' => __('touch__api_invalid_'.$request->type_name,[],$request->language_symbol) ,'status' =>  $this->badRequestStatusCode ];
        } else {
            $touch = $this->store($request);

            $conds['type_id'] = $request->type_id;
            $conds['type_name'] = $request->type_name;

            $item_touch_count = Touch::where($conds)->count();

            if($request->type_name == 'item'){
                $item_update = Item::select("id", "item_touch_count")->where('id', $request->type_id)->first();
                $item_update->id = $request->type_id;
                $item_update->item_touch_count = $item_touch_count;
                $item_update->update();
            }


            if(isset($touch['error'])){
                return  ['error' => __('touch__api_db_error',[],$request->language_symbol) ,'status' =>  $this->internalServerErrorStatusCode ];
            }
            return  ['success' => __('touch__api_success',[],$request->language_symbol) ,'status' =>  $this->createdStatusCode ];

        }

    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $touch = new Touch();
            $touch->type_id = $request->type_id;
            $touch->shop_id = '0';
            $touch->type_name = $request->type_name;
            $touch->user_id = $request->user_id;
            $touch->added_user_id = Auth::user()->id;


            $touch->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];

        }

        return $touch;
    }

    public function deleteAllBy($conds = null)
    {
        DB::beginTransaction();
        try {
            Touch::when($conds, function ($query, $conds) {
                $query->where($conds);

            })->delete();

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }
    }
}
