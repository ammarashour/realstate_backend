<?php
namespace Modules\Core\Http\Services;

use App\Http\Services\PsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Favourite;
use Modules\Core\Entities\Item;
use Modules\Core\Http\Services\ItemService;
use Modules\Core\Http\Services\UserService;
use Modules\Core\Transformers\Api\App\V1_0\Product\ProductApiResource;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use App\Config\ps_constant;

class FavouriteService extends PsService
{
    protected $userAccessApiTokenService;

    public function __construct(ItemService $itemService, UserService $userService,UserAccessApiTokenService $userAccessApiTokenService)
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

        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->itemService = $itemService;
        $this->userService = $userService;

        $this->itemApiRelation = ['category', 'subcategory', 'city', 'township', 'currency', 'owner','itemRelation', 'cover', 'video', 'icon'];

    }

    public function noDataError($offset, $data){
        if ($offset > 0 && $data->isEmpty()) {
            // no paginate data
            $data = [];
            return responseDataApi($data);

        } else if($data->isEmpty()) {
            // no data db
            return responseMsgApi(__('item__api_no_data'), $this->noContentStatusCode, $this->successStatus);
        }
    }

    public function favouriteItemFromApi($request){
        $item = Item::where('id', $request->item_id)->first();

         // check permission start
         $deviceToken = $request->header($this->deviceTokenParaApi);
         $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken( $request->login_user_id, $deviceToken);

        //  $checkOwnerShip = checkOwnerShip($item, $request->login_user_id);

         if (empty($userAccessApiToken)){
             $msg = __('core__api_update_no_permission',[],$request->language_symbol);
             return ['error' =>  $msg,'status' =>  $this->forbiddenStatusCode ];
         }
         // check permission end

        $user = $this->userService->getUser($request->user_id);

        if (!$user) {
            return ['error' => __('favourite__api_invalid_user',[],$request->language_symbol),'status' =>  $this->badRequestStatusCode ];
        } else {

            $data = [
                'user_id' => $request->user_id,
                'item_id' => $request->item_id,
            ];

            if (Favourite::where($data)->first()) {

                $favourite = Favourite::where($data)->first();
                $id = $favourite->id;

                $favourite = $this->destroy($id);

            } else {

                $favourite = $this->store($request);

                if(isset($favourite['error'])){
                    return ['error' => __('favourite__api_db_error',[],$request->language_symbol),'status' =>  $this->internalServerErrorStatusCode ];
                }

            }

            //update favourite count at item

            $conds['item_id'] = $request->item_id;

            $favourite_count = Favourite::where($conds)->count();
            $item_update = Item::where('id', $request->item_id)->first();
            $item_update->id = $request->item_id;
            $item_update->favourite_count = $favourite_count;
            $item_update->update();

            $itemApiRelation = $this->itemApiRelation;

            $item = $this->itemService->getItem($request->item_id,$itemApiRelation);
            return $item;

        }

    }

    // for api
    public function indexFromApi($request){
         // check permission start
         $deviceToken = $request->header($this->deviceTokenParaApi);
         $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken( $request->login_user_id, $deviceToken);

         if (empty($userAccessApiToken)){
             $msg = __('core__api_update_no_permission',[],$request->language_symbol);
             return ['error' =>  $msg,'status' =>  $this->forbiddenStatusCode ];
         }
         // check permission end

        $userId =  $request->login_user_id;
        $limit = $request->limit;
        $offset = $request->offset;

        $dataConds = [];

        $user = $this->userService->getUser($userId);

        if (!$user) {
            return ['error' => __('favourite__api_invalid_user',[],$request->language_symbol),'status' =>  $this->badRequestStatusCode ];
        } else {

            $data_exist = Favourite::where('user_id',$userId)->latest()->get();

            if ($data_exist->isEmpty()) {
                // no data db
                return ['error' => __('favourite__api_no_data',[],$request->language_symbol),'status' =>  $this->noContentStatusCode ];
            } else {

                $conds = [];
                foreach ($data_exist as $cond){
                    array_push($conds, $cond->item_id);
                }
                $conds_in['ids'] = $conds;

                // return $conds;
                $itemApiRelation = ['category', 'subcategory', 'city', 'township', 'currency', 'owner','itemRelation'];
                $items = $this->itemService->getItems($itemApiRelation, true, null, $limit, $offset, $dataConds, $conds_in);

                return $items;


            }
        }
    }

    public function store($request)
    {
        DB::beginTransaction();

        try {
            $favourite = new Favourite();
            $favourite->item_id = $request->item_id;
            $favourite->user_id = $request->user_id;
            $favourite->added_user_id = Auth::user()->id;


            $favourite->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return $favourite;
    }

    public function destroy($id){
        $favourite = Favourite::find($id);

        $favourite->delete();
    }

    public function deleteAllBy($conds = null)
    {
        DB::beginTransaction();
        try {
            Favourite::when($conds, function ($query, $conds) {
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
