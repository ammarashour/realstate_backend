<?php
namespace Modules\Core\Http\Services\Item;

use App\Config\ps_constant;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Services\PsService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Item\PackageBoughtTransaction;
use Modules\Core\Exports\PackageReportExport;
use Modules\Core\Http\Services\UserAccessApiTokenService;
use Modules\Core\Http\Services\UserInfoService;
use Modules\Core\Http\Services\UserService;
use Modules\Core\Transformers\Api\App\V1_0\Item\PackageBoughtTransactionApiResource;
use Modules\Core\Transformers\Backend\NoModel\Item\OfflinePackageBoughtWithKeyResource;
use Modules\Payment\Http\Services\PaymentSettingService;
use Modules\Payment\Entities\PaymentInfo;
use Modules\Payment\Entities\PaymentAttribute;

class PackageBoughtTransactionService extends PsService
{
    protected $csvFileName, $code, $pkgStatusCol, $successStatus, $createdStatusCode, $okStatusCode, $internalServerErrorStatusCode, $noContentStatusCode, $notFoundStatusCode, $packageIdCol, $packageTitleCol, $packagePriceCol, $packageCurrencyIdCol, $packageIapPrdIdCol, $packageTypeCol, $publish, $unPublish, $warningFlag, $successFlag, $dangerFlag, $packageStatusCol, $packageService, $packageBoughtTransactionApiRelations, $userService, $offlinePaymentMethod, $paystackPaymentMethod, $razorPaymentMethod, $paypalPaymentMethod, $stripePaymentMethod, $iapPaymentMethod, $paymentSettingService,
        $stripeSecretKey, $paypalPrivateKey, $paypalPublicKey, $paypalClientId, $paypalSecretKey, $paypalEnvironment,
        $pkgIdCol, $pkgUserIdCol, $pkgPackageIdCol, $pkgPaymentMethodCol, $pkgPriceCol, $userRemainingPost, $userInfoService,  $deviceTokenParaApi, $loginUserIdParaApi, $userAccessApiTokenService, $forbiddenStatusCode;

    public function __construct(UserService $userService,PackageService $packageService, PaymentSettingService $paymentSettingService, UserInfoService $userInfoService, UserAccessApiTokenService $userAccessApiTokenService)
    {
        $this->userService = $userService;
        $this->paymentSettingService = $paymentSettingService;
        $this->userInfoService = $userInfoService;
        $this->packageService = $packageService;

        $this->successStatus = Constants::successStatus;
        $this->createdStatusCode = Constants::createdStatusCode;
        $this->okStatusCode = Constants::okStatusCode;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;
        $this->noContentStatusCode = Constants::noContentStatusCode;
        $this->notFoundStatusCode = Constants::notFoundStatusCode;
        $this->forbiddenStatusCode = Constants::forbiddenStatusCode;

        $this->pkgIdCol = PackageBoughtTransaction::id;
        $this->pkgUserIdCol = PackageBoughtTransaction::userId;
        $this->pkgPackageIdCol = PackageBoughtTransaction::packageId;
        $this->pkgPaymentMethodCol = PackageBoughtTransaction::paymentMethod;
        $this->pkgPriceCol = PackageBoughtTransaction::price;
        $this->pkgStatusCol = PackageBoughtTransaction::status;
        $this->pkgTableName = PackageBoughtTransaction::tableName;
        $this->pkgAddedDateCol = PackageBoughtTransaction::addedDate;

        $this->userTableName = User::tableName;
        $this->userIdCol = User::id;
        $this->userNameCol = User::name;

        $this->paymentInfoTableName = PaymentInfo::tableName;
        $this->paymentInfoIdCol = PaymentInfo::id;
        $this->paymentInfoValueCol = PaymentInfo::value;


        $this->publish = Constants::publish;
        $this->unPublish = Constants::unPublish;
        $this->warningFlag = Constants::warning;
        $this->successFlag = Constants::success;
        $this->dangerFlag = Constants::danger;

        $this->packageBoughtTransactionApiRelations = ['user', 'package'];

        $this->paypalPrivateKey = Constants::paypalPrivateKey;
        $this->paypalPublicKey = Constants::paypalPublicKey;
        $this->paypalClientId = Constants::paypalClientId;
        $this->paypalMerchantId = Constants::paypalMerchantId;
        $this->paypalSecretKey = Constants::paypalSecretKey;
        $this->paypalEnvironment = Constants::paypalEnvironment;

        $this->stripeSecretKey = Constants::stripeSecretKey;
        $this->code = Constants::coreSubMenuGroup;

        $this->offlinePaymentMethod = Constants::offlinePaymentMethod;
        $this->paystackPaymentMethod = Constants::paystackPaymentMethod;
        $this->razorPaymentMethod = Constants::razorPaymentMethod;
        $this->paypalPaymentMethod = Constants::paypalPaymentMethod;
        $this->stripePaymentMethod = Constants::stripePaymentMethod;
        $this->iapPaymentMethod = Constants::iapPaymentMethod;

        $this->userRemainingPost = Constants::usrRemainingPost;

        $this->userAccessApiTokenService = $userAccessApiTokenService;
        $this->loginUserIdParaApi = ps_constant::loginUserIdParaFromApi;
        $this->deviceTokenParaApi = ps_constant::deviceTokenKeyFromApi;

        $this->csvFileName = 'package_report';
    }

    public function getPackageBoughtTransactions($relation = null, $status = null, $limit = null, $offset = null, $conds = null,$pagPerPage = null,$searchConds = null){

        $sort = '';
        if(isset($conds['order_by'])){
            $sort = $conds['order_by'];
        }
        $transactions = PackageBoughtTransaction::leftJoin($this->paymentInfoTableName, $this->pkgTableName.'.'.$this->pkgPackageIdCol, '=', $this->paymentInfoTableName.'.'.$this->paymentInfoIdCol)
                                // ->select('psx_package_bought_transactions.*', 'psx_payment_infos.value')
                                ->leftjoin($this->userTableName, $this->pkgTableName.'.'.$this->pkgUserIdCol, '=', $this->userTableName.'.'.$this->userIdCol)
                                ->select($this->pkgTableName.'.*', $this->userTableName.'.'.$this->userNameCol.' as user_name', $this->paymentInfoTableName.'.'.$this->paymentInfoValueCol)
                                ->when($relation, function ($q, $relation){
                                    $q->with($relation);
                                })
                                ->when($status, function ($q, $status){
                                    $q->where($this->pkgTableName.'.'.$this->pkgStatusCol, $status);
                                })
                                ->when($limit, function ($query, $limit) {
                                    $query->limit($limit);
                                })
                                ->when($offset, function ($query, $offset) {
                                    $query->offset($offset);
                                })
                                ->when($conds, function ($query, $conds) {
                                    $query->where($conds);
                                })->when($searchConds, function ($query, $conds) {
                                    $query = $this->searching($query, $conds);
                                })
                                ->when(empty($sort), function ($query, $conds){
                                    $query->orderBy($this->pkgTableName.'.'.$this->pkgAddedDateCol, 'desc');
                                });
                                if ($pagPerPage){
                                $transactions = $transactions->paginate($pagPerPage)->onEachSide(1)->withQueryString();

                                } else{
                                    $transactions = $transactions->get();
                                }
        return $transactions;
    }

    public function getRevenueFromPackage($relation = null, $status = null, $limit = null, $offset = null, $conds = null,$pagPerPage = null){
        $transactions = PackageBoughtTransaction::
                                leftJoin($this->paymentInfoTableName, $this->pkgTableName.'.'.$this->pkgPackageIdCol, '=', $this->paymentInfoTableName.'.'.$this->paymentInfoIdCol)
                                // ->select('psx_package_bought_transactions.*', 'psx_payment_infos.value')
                                ->leftjoin($this->userTableName, $this->pkgTableName.'.'.$this->pkgUserIdCol, '=', $this->userTableName.'.'.$this->userIdCol)
                                ->select($this->pkgTableName.'.*', $this->userTableName.'.'.$this->userNameCol.' as user_name', $this->paymentInfoTableName.'.'.$this->paymentInfoValueCol)
                                ->selectRaw('SUM(price) as total, DATE(psx_package_bought_transactions.added_date) as date')
                                ->groupBy('date')
                                ->when($relation, function ($q, $relation){
                                    $q->with($relation);
                                })
                                ->when($status, function ($q, $status){
                                    $q->where($this->pkgStatusCol, $status);
                                })
                                ->when($limit, function ($query, $limit) {
                                    $query->limit($limit);
                                })
                                ->when($offset, function ($query, $offset) {
                                    $query->offset($offset);
                                })
                                ->when($conds, function ($query, $conds) {
                                    $query = $this->searching($query, $conds);
                                });

                                if ($pagPerPage){
                                    $transactions = $transactions->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                                } else{
                                    $transactions = $transactions->get();
                                }
        return $transactions;
    }

    public function getMostPurchsedPackages($relation = null, $status = null, $limit = null, $offset = null, $conds = null,$pagPerPage = null){
        $transactions = PackageBoughtTransaction::
                                leftJoin($this->paymentInfoTableName, $this->pkgTableName.'.'.$this->pkgPackageIdCol, '=', $this->paymentInfoTableName.'.'.$this->paymentInfoIdCol)
                                // ->select('psx_package_bought_transactions.*', 'psx_payment_infos.value')
                                ->leftjoin($this->userTableName, $this->pkgTableName.'.'.$this->pkgUserIdCol, '=', $this->userTableName.'.'.$this->userIdCol)
                                ->select($this->pkgTableName.'.*', $this->userTableName.'.'.$this->userNameCol.' as user_name', $this->paymentInfoTableName.'.'.$this->paymentInfoValueCol)
                                ->selectRaw('COUNT(package_id) as total')
                                ->groupBy('package_id')
                                ->when($relation, function ($q, $relation){
                                    $q->with($relation);
                                })
                                ->when($status, function ($q, $status){
                                    $q->where($this->pkgStatusCol, $status);
                                })
                                ->when($limit, function ($query, $limit) {
                                    $query->limit($limit);
                                })
                                ->when($offset, function ($query, $offset) {
                                    $query->offset($offset);
                                })
                                ->when($conds, function ($query, $conds) {
                                    $query = $this->searching($query, $conds);
                                });

                                if ($pagPerPage){
                                    $transactions = $transactions->paginate($pagPerPage)->onEachSide(1)->withQueryString();
                                } else{
                                    $transactions = $transactions->get();
                                }
        return $transactions;
    }

    public function searching($query, $conds){
        // search term
        if (isset($conds['searchterm']) && $conds['searchterm']) {
            $search = $conds['searchterm'];
            $query->where(function ($query) use ($search) {
                $query->where('users.name','like','%'.$search.'%');
            });
        }
        if(isset($conds['package_id']) && $conds['package_id']){
            $package_filter=$conds['package_id'];
            $query->whereHas('package', function($q) use($package_filter){
                $q->where($this->pkgTableName.'.'.$this->pkgPackageIdCol, $package_filter);
            });
        }
        if(isset($conds['selected_date']) && $conds['selected_date']){
            $added_date_filter = $conds['selected_date'];
            if($added_date_filter[1] == ''){
                $added_date_filter[1] = Carbon::now();
            }
            $query->whereBetween($this->pkgTableName.'.'.$this->pkgAddedDateCol, $added_date_filter);

//            $date_filter=$conds['selected_date'];
//            $new_date=date('Y-m-d', strtotime($date_filter));
//            $query->whereDate($this->pkgTableName.'.'.$this->pkgAddedDateCol, '=', $new_date);
        }
        if(isset($conds['selected_payment_method']) && $conds['selected_payment_method']){
            $payment_method=$conds['selected_payment_method'];
            $query->where($this->pkgTableName.'.'.$this->pkgPaymentMethodCol, '=', $payment_method);
        }

        if(isset($conds['added_date_range']) && $conds['added_date_range']){
            $added_date_filter = $conds['added_date_range'];
            if($added_date_filter[1] == ''){
                $added_date_filter[1] = Carbon::now();
            }
            $query->whereBetween($this->pkgTableName.'.'.$this->pkgAddedDateCol, $added_date_filter);
        }

        if(isset($conds['status'])){

            $query->where($this->pkgTableName.'.'.$this->pkgStatusCol, $conds['status']);
        }

        // order by
        if (isset($conds['order_by']) && isset($conds['order_type']) && $conds['order_by'] && $conds['order_type']){

            if($conds['order_by'] == 'add_user_id' || $conds['order_by'] == 'updated_user_id'){
                $query->orderBy('owner', $conds['order_type']);
            }
            else if($conds['order_by'] == 'package_id'){

                $query->orderBy('value', $conds['order_type']);
            }
            else if($conds['order_by'] == 'added_date'){

                $query->orderBy($this->pkgTableName.'.'.$this->pkgAddedDateCol, $conds['order_type']);
            }
            else if($conds['order_by'] == 'post_count'){


            }
            else{

                $query->orderBy($conds['order_by'], $conds['order_type']);
            }

        }
        return $query;
    }

    public function getPaymentInfoByPackageId($id){
        $package = PaymentInfo::find($id);
        return $package;
    }

    public function getPackageBoughtTransaction($id = null, $conds = null, $relation=null){
        $transaction = PackageBoughtTransaction::when($id, function ($q, $id) {
                            $q->where($this->pkgTableName.'.'.$this->pkgIdCol, $id);
                        })
                        ->when($conds, function ($q, $conds) {
                            $q->where($conds);
                        })
                        ->when($relation, function ($q, $relation){
                            $q->with($relation);
                        })
                        ->first();
        return $transaction;
    }

    public function index(){
        $relation = ['user', 'package'];
        $transactions = $this->getPackageBoughtTransactions($relation);
        $dataArr = [
            "transactions" => $transactions,
        ];
        return $dataArr;
    }

    public function create()
    {
        $packages = $this->packageService->getPackages(null, $this->publish);
        $dataArr = [
            'packages' => $packages
        ];
        return $dataArr;
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $transaction = new PackageBoughtTransaction();

            if (isset($request->user_id) && !empty($request->user_id))
                $transaction->user_id = $request->user_id;

            if (isset($request->package_id) && !empty($request->package_id))
                $transaction->package_id = $request->package_id;

            if (isset($request->payment_method) && !empty($request->payment_method))
                $transaction->payment_method = $request->payment_method;

            if (isset($request->price) && !empty($request->price))
                $transaction->price = $request->price;

            if (isset($request->razor_id) && !empty($request->razor_id))
                $transaction->razor_id = $request->razor_id;

            if (isset($request->is_paystack) && !empty($request->is_paystack))
                $transaction->is_paystack = $request->is_paystack;

            if (isset($request->status))
                $transaction->status = $request->status;

            if (isset($request->added_user_id) && !empty($request->added_user_id))
                $transaction->added_user_id = $request->added_user_id;
            else
                $transaction->added_user_id = Auth::user()->id;

            $transaction->save();

            DB::commit();

        } catch (\Throwable $e){
            DB::rollBack();
            return ['error' => $e->getMessage()];
        }

       return $transaction;
    }

    public function update($id,$request)
    {
       DB::beginTransaction();
        try {
            $transaction = $this->getPackageBoughtTransaction($id);
            $isApproveToApprove = false;
            if (isset($request->status) && !empty($request->status)){
                if($transaction->status == $request->status && $transaction->status == 1){
                    $isApproveToApprove = true;
                }
            }

            if (isset($request->user_id) && !empty($request->user_id))
                $transaction->user_id = $request->user_id;

            if (isset($request->package_id) && !empty($request->package_id))
                $transaction->package_id = $request->package_id;

            if (isset($request->payment_method) && !empty($request->payment_method))
                $transaction->payment_method = $request->payment_method;

            if (isset($request->price) && !empty($request->price))
                $transaction->price = $request->price;

            if (isset($request->razor_id) && !empty($request->razor_id))
                $transaction->razor_id = $request->razor_id;

            if (isset($request->is_paystack) && !empty($request->is_paystack))
                $transaction->is_paystack = $request->is_paystack;

            if (isset($request->status) && !empty($request->status))
                $transaction->status = $request->status;

            if (isset($request->updated_user_id) && !empty($request->updated_user_id))
                $transaction->updated_user_id = $request->updated_user_id;
            else
                $transaction->updated_user_id = Auth::user()->id;

            $transaction->update();

            if($transaction->status == "1" && !$isApproveToApprove){
                // get post count by package id
                $packageBoughtTransactionApiRelations = $this->packageBoughtTransactionApiRelations;
                $packageDetail = $this->getPackageBoughtTransaction($transaction->id,null,$packageBoughtTransactionApiRelations);

                $objs  = PaymentAttribute::whereIn("core_keys_id", [$packageDetail->package->core_keys_id])->get();

                $attributes = $objs->map(function ($key, $value){
                    return [
                        $key['attribute_key'] => $key['attribute_value']
                    ];
                })->collapse();
                $post_count = intval($attributes['count']);

                $conds['user_id'] = $request->user_id;
                $userInfo = $this->userInfoService->getUserInfo(null, null, $this->userRemainingPost, $conds);

                // set/update post count to package buyer user
                if(!empty($userInfo)){
                    $remaining_post = (int)$userInfo->value + (int)$post_count;
                    $user_data = new \stdClass();
                    $user_data->value = $remaining_post;
                    $this->userInfoService->update($userInfo->id, $user_data);
                }else{
                    $remaining_post = $post_count;
                    $user_data = new \stdClass();
                    $user_data->value = $remaining_post;
                    $user_data->user_id = $request->user_id;
                    $user_data->core_keys_id = $this->userRemainingPost;
                    $this->userInfoService->store($user_data);
                }
            }


            DB::commit();

        }catch (\Throwable $e){
            DB::rollBack();
            print_r($e->getMessage());
exit;
            return ['error' => $e->getMessage()];
        }

       return $transaction;
    }

    public function destroy($id){
        $transaction = $this->getPackageBoughtTransaction($id);
        $transaction->delete();

        $status = [
            // 'success' => 'This transaction row has been deleted successfully.',
            'success'=>__('core__be_delete_success',['attribute'=>'transaction']),
            'status' => $this->okStatusCode,
        ];

        return $status;
    }

    public function offlinePackageEdit($id)
    {
        $dataWithRelation = ['cover', 'city'];
        $transaction = $this->getPackageBoughtTransaction($id, $dataWithRelation);
        $dataArr = [
            "transaction" => $transaction,
        ];
        return $dataArr;
    }

    public function offlinePackageBoughtIndex($request){

        $relation = ['user', 'package'];
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['package_id'] = $request->input('package_filter') == 'all'? null  : $request->package_filter;
        $conds['selected_date'] = $request->input('date_filter') == 'all'? null  : $request->date_filter;
        $conds['selected_payment_method'] = $request->input('selected_payment_method') == 'all'? null  : $request->selected_payment_method;
        $conds['status'] = $request->status_filter == 'all'? null  : $request->status_filter;

        $conds['order_by']= null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if($request->sort_field)
        {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }
        $packages = $this->paymentSettingService->getPaymentInfos(noPagination: Constants::yes);


        $transactions = OfflinePackageBoughtWithKeyResource::collection($this->getPackageBoughtTransactions($relation,null,null,null,null,$row,$conds));
        $payment_methods_filters= [];
        $payment_methods = PackageBoughtTransaction::groupBy('payment_method')->pluck('payment_method');

        if($conds['order_by'])
        {
            $dataArr = [
                "transactions" => $transactions,
                'sort_field' =>$conds['order_by'],
                'sort_order'=>$request->sort_order,
                'search'=>$conds['searchterm'] ,
                'packages'=>$packages ,
                'selected_package'=>$conds['package_id'] ,
                'selected_payment_method'=>$conds['selected_payment_method'] ,
                'payment_methods'=>$payment_methods,
                'selectedDate'=> $conds['selected_date'],
                'selectedStatus'=> $conds['status'],
            ];
        }
        else{
            $dataArr = [
                "transactions" => $transactions,
                'search'=>$conds['searchterm'],
                'packages'=>$packages,
                'selected_package'=>$conds['package_id'] ,
                'payment_methods'=>$payment_methods ,
                'selected_payment_method'=>$conds['selected_payment_method'],
                'selectedDate'=> $conds['selected_date'],
                'selectedStatus'=> $conds['status'],
            ];

        }


        return $dataArr;
    }

    // for package report
    public function packageReportIndex($request){

        $relation = ['user', 'package'];
        $conds['searchterm'] = $request->input('search') ?? '';
        $conds['package_id'] = $request->input('package_filter') == 'all'? null  : $request->package_filter;
        $conds['selected_date'] = $request->input('date_filter') == 'all'? null  : $request->date_filter;
        $conds['selected_payment_method'] = $request->input('selected_payment_method') == 'all'? null  : $request->selected_payment_method;

        $conds['order_by']= null;
        $conds['order_type'] = null;
        $row = $request->input('row') ?? Constants::dataTableDefaultRow;

        if($request->sort_field)
        {
            $conds['order_by'] = $request->sort_field;
            $conds['order_type'] = $request->sort_order;
        }

        $packageConds['payment_id'] = 'payment00007';
        $packages = $this->paymentSettingService->getPaymentInfos(null,null,null,$packageConds,1);
        // dd($packages);

        $transactions = PackageBoughtTransactionApiResource::collection($this->getPackageBoughtTransactions($relation,null,null,null,null,$row,$conds));
        $payment_methods_filters= [];
        $payment_methods = PackageBoughtTransaction::groupBy('payment_method')->get();
        $payment_methods = PackageBoughtTransaction::groupBy('payment_method')->pluck('payment_method');

        if($conds['order_by'])
        {
            $dataArr = [
                "transactions" => $transactions,
                'sort_field' =>$conds['order_by'],
                'sort_order'=>$request->sort_order,
                'search'=>$conds['searchterm'] ,
                'packages'=>$packages ,
                'selected_package'=>$conds['package_id'] ,
                'selected_payment_method'=>$conds['selected_payment_method'] ,
                'payment_methods'=>$payment_methods,
                'selectedDate'=> $conds['selected_date'],


            ];
        }
        else{
            $dataArr = [
                "transactions" => $transactions,
                'search'=>$conds['searchterm'],
                'packages'=>$packages,
                'selected_package'=>$conds['package_id'] ,
                'payment_methods'=>$payment_methods ,
                'selected_payment_method'=>$conds['selected_payment_method'],
                'selectedDate'=> $conds['selected_date'],
            ];

        }


        return $dataArr;
    }

    public function packageReportCsvExport(){
        $filename = newFileNameForExport($this->csvFileName);
        return (new PackageReportExport)->download($filename, \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // for api
    public function indexFromApi($request)
    {
        $offset = $request->offset;
        $limit = $request->limit;

        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('package__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            $packageBoughtTransactionApiRelations = $this->packageBoughtTransactionApiRelations;
            $paidItems = PackageBoughtTransactionApiResource::collection($this->getPackageBoughtTransactions($packageBoughtTransactionApiRelations, 1, $limit, $offset));

            if ($offset > 0 && $paidItems->isEmpty()) {
                // no paginate data
                $data = [];
                return responseDataApi($data);

            } else if($paidItems->isEmpty()) {
                // no data db
                return responseMsgApi(__('package__api_no_data'), $this->noContentStatusCode, $this->successStatus);
            } else {
                return responseDataApi($paidItems);
            }
        }
    }

    public function storeFromApi($request)
    {
        /// check permission start
        $deviceToken = $request->header($this->deviceTokenParaApi);
        $userId = $request->login_user_id;

        // user token layer permission start
        $userAccessApiToken = $this->userAccessApiTokenService->getUserAccessApiToken($userId, $deviceToken);
        // user token layer permission end

        /// check permission end

        if (empty($userAccessApiToken)){
            $msg = __('package__api_create_no_permissin',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            $paypal_result = 0;
            $stripe_result = 0;
            $razor_result = 0;
            $paystack_result = 0;
            $in_app_purchase_result = 0;
            $offline_result = 0;
            $flutterwave_result = 0;

            $data = new \stdClass();
            $data->user_id = $request->user_id;
            $data->package_id = $request->package_id;
            $data->razor_id = $request->razor_id;
            $data->price = $request->price;
            $data->is_paystack = $request->is_paystack;

            if ($request->payment_method == constants::paypalPaymentMethod) {
                //User using Paypal to submit the transaction
                $payment_method = $this->paypalPaymentMethod;
                $gateway = new \Braintree\Gateway([
                    'environment' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalEnvironment)->value),
                    'merchantId' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalMerchantId)->value),
                    'publicKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalPublicKey)->value),
                    'privateKey' => trim($this->paymentSettingService->getPaymentInfo(null, null, $this->paypalPrivateKey)->value)
                ]);

                $result = $gateway->transaction()->sale([
                    'amount'                => $request->price,
                    'paymentMethodNonce' => $request->payment_method_nonce,
                    'options' => [
                        'submitForSettlement' => True
                    ]
                ]);

                if ($result->success == 1) {
                    $data->payment_method = $request->payment_method;
                    $paypal_result = $result->success;
                } else {
                    return responseMsgApi($result->message, $this->internalServerErrorStatusCode);
                }
            } else if ($request->payment_method == constants::stripePaymentMethod) {
                $payment_method = $this->stripePaymentMethod;
                //User using Stripe to submit the transaction
                $payment_method_nonce = explode('_', $request->payment_method_nonce);

                if ($payment_method_nonce[0] == 'tok') {

                    try {

                        # set stripe test key
                        \Stripe\Stripe::setApiKey(trim($this->paymentSettingService->getPaymentInfo(null, null, $this->stripeSecretKey)->value));

                        $charge = \Stripe\Charge::create(array(
                            "amount"       => $request->price * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                            "currency"    => trim($request->currency),
                            "source"      => $request->payment_method_nonce,
                            "description" => __('package__api_order_desc',[],$request->language_symbol)
                        ));

                        if ($charge->status == "succeeded") {
                            $data->payment_method = $request->payment_method;
                            $stripe_result = 1;
                        } else {
                            return responseMsgApi(__('package__api_stripe_transaction_failed',[],$request->language_symbol), $this->internalServerErrorStatusCode);
                        }
                    } catch (\Throwable $e) {

                        return responseMsgApi(__('package__api_stripe_transaction_failed',[],$request->language_symbol), $this->internalServerErrorStatusCode);
                    }
                } else if ($payment_method_nonce[0] == 'pm') {
                    try {
                        \Stripe\Stripe::setApiKey(trim($this->paymentSettingService->getPaymentInfo(null, null, $this->stripeSecretKey)->value));

                        $paymentIntent = \Stripe\PaymentIntent::create([
                            'payment_method' => $request->payment_method_nonce,
                            'amount' => $request->price * 100, // amount in cents, so need to multiply with 100 .. $amount * 100
                            'currency' => trim($request->currency),
                            'confirmation_method' => 'manual',
                            'confirm' => true,
                            'payment_method_types' => ['card']
                        ]);

                        if ($paymentIntent->status == "succeeded") {
                            $data->payment_method = $request->payment_method;
                            $stripe_result = 1;
                        } else {
                            return responseMsgApi(__('package__api_stripe_transaction_failed',[],$request->language_symbol), $this->internalServerErrorStatusCode);
                        }
                    } catch (\Throwable $e) {
                        return responseMsgApi(__('package__api_stripe_transaction_failed',[],$request->language_symbol), $this->internalServerErrorStatusCode);
                    }
                }
            } else if ($request->payment_method == constants::razorPaymentMethod) {

                //User Using Razor
                $payment_method = $this->razorPaymentMethod;
                $data->payment_method = $payment_method;
                $razor_result = 1;
            } else if ($request->payment_method == constants::paystackPaymentMethod) {

                //User Using Paystack
                $payment_method = $this->paystackPaymentMethod;
                $data->payment_method = $payment_method;
                $paystack_result = 1;
            } else if ($request->payment_method == constants::offlinePaymentMethod) {

                //User Using Offline
                $payment_method = $this->offlinePaymentMethod;
                $data->payment_method = $payment_method;
                $offline_result = 1;
            } else if ($request->payment_method == constants::flutterwavePaymentMethod) {

                //User Using Offline
                $payment_method = constants::flutterwavePaymentMethod;
                $data->payment_method = $payment_method;
                $flutterwave_result = 1;
            } else if ($request->payment_method == constants::iapPaymentMethod) {

                //User Using COD
                $payment_method = $this->iapPaymentMethod;
                $data->payment_method = $payment_method;
                $in_app_purchase_result = 1;
            }
            if ($offline_result == 1) {
                $data->payment_method = $payment_method;
                $data->status = 0;
            }

            // save package bought transaction

            $transaction = $this->store($data);

            if ($paypal_result == 1 || $stripe_result == 1  || $razor_result == 1 || $paystack_result == 1 || $flutterwave_result == 1 || $in_app_purchase_result == 1) {

                // get post count by package id
                // $post_count = $this->packageService->getPackage($request->package_id)->post_count;
                $packageBoughtTransactionApiRelations = $this->packageBoughtTransactionApiRelations;
                $packageDetail = $this->getPackageBoughtTransaction($transaction->id,null,$packageBoughtTransactionApiRelations);

                $objs  = PaymentAttribute::whereIn("core_keys_id", [$packageDetail->package->core_keys_id])->get();

                $attributes = $objs->map(function ($key, $value){
                    return [
                        $key['attribute_key'] => $key['attribute_value']
                    ];
                })->collapse();
                $post_count = intval($attributes['count']);


                $conds['user_id'] = $request->user_id;
                $userInfo = $this->userInfoService->getUserInfo(null, null, $this->userRemainingPost, $conds);

                // set/update post count to package buyer user
                if(!empty($userInfo)){
                    $remaining_post = (int)$userInfo->value + (int)$post_count;
                    $user_data = new \stdClass();
                    $user_data->value = $remaining_post;
                    $this->userInfoService->update($userInfo->id, $user_data);
                }else{
                    $remaining_post = $post_count;
                    $user_data = new \stdClass();
                    $user_data->value = $remaining_post;
                    $user_data->user_id = $request->user_id;
                    $user_data->core_keys_id = $this->userRemainingPost;
                    $this->userInfoService->store($user_data);
                }

                $data->payment_method = $payment_method;
                $data->status = 1;
            }


            if(isset($transaction['error'])){
                return responseMsgApi(__('package__api_db_error',[],$request->language_symbol), $this->internalServerErrorStatusCode);
            }

            return responseMsgApi(__('package__api_success_pkg_bought',[],$request->language_symbol), $this->createdStatusCode, $this->successFlag);
        }
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
            $msg = __('package__api_no_permission',[],$request->language_symbol);
            return responseMsgApi($msg, $this->forbiddenStatusCode);
        }else{
            $offset = $request->offset;
            $limit = $request->limit;
            $conds = [];
            if($request->user_id){
                $conds['user_id'] = $request->user_id;
            }

            $packageBoughtTransactionApiRelations = $this->packageBoughtTransactionApiRelations;
            $transactions = PackageBoughtTransactionApiResource::collection($this->getPackageBoughtTransactions($packageBoughtTransactionApiRelations, 1, $limit, $offset, $conds));

            if ($offset > 0 && $transactions->isEmpty()) {
                // no paginate data
                $data = [];
                return responseDataApi($data);

            } else if($transactions->isEmpty()) {
                // no data db
                return responseMsgApi(__('package__api_no_data',[],$request->language_symbol), $this->noContentStatusCode, $this->successStatus);
            } else {
                return responseDataApi($transactions);
            }
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
            foreach($ids as $id){
                PackageBoughtTransaction::where('id', $id)->delete();
            }

            if (empty($ids)){
                return  ['success' =>  __('core__api_record_not_found',[],$request->language_symbol),'status' =>  $this->noContentStatusCode ];
            } else {
                return  ['success' =>  __('core__api_transaction_delete_success',[],$request->language_symbol),'status' =>  $this->okStatusCode ];
            }
        }
    }

    public function takingForColumnProps($label, $field, $type, $isShowSorting, $ordering){
        $hideShowCoreAndCustomFieldObj = new \stdClass();
        $hideShowCoreAndCustomFieldObj->label = $label;
        $hideShowCoreAndCustomFieldObj->field = $field;
        $hideShowCoreAndCustomFieldObj->type = $type;
        $hideShowCoreAndCustomFieldObj->sort = $isShowSorting;
        $hideShowCoreAndCustomFieldObj->ordering = $ordering;
        if ($type !== "Image" && $type !== "MultiSelect" && $type !== "Action"){
        $hideShowCoreAndCustomFieldObj->action = 'Action';
        }

        return $hideShowCoreAndCustomFieldObj;
    }





    public function takingForColumnFilterProps($id, $label, $field, $hidden, $moduleName, $keyId){
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
