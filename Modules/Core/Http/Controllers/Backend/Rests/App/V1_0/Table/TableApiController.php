<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Table;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Services\TableService;

class TableApiController extends Controller
{
    protected $tableService, $okStatusCode, $sucessStatus, $errorStatus, $internalServerErrorStatusCode;
    public function __construct(TableService $tableService){
        $this->tableService = $tableService;
        $this->okStatusCode = Constants::okStatusCode;
        $this->sucessStatus = Constants::successStatus;
        $this->errorStatus = Constants::errorStatus;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;

    }

    public function forConnectionCheck()
    {

        return responseMsgApi("Connection Success", 200, Constants::successStatus);
    }

    public function takeBuilderApiKey(Request $request)
    {
        $response = $this->tableService->takeBuilderApiKey($request);
        if($response['success']){
            return responseMsgApi($response['success'], $this->okStatusCode, $this->sucessStatus);
        } else {
            return responseMsgApi($response['error'], $this->internalServerErrorStatusCode, $this->errorStatus);
        }

    }

    public function index()
    {
        return response()->json([
            "message" => "san kyi tar par"
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('core::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('core::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('core::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function tableAutoSync($request){
        $responeData = $this->tableService->autoSync($request);

        if(!empty($responeData)){
            return responseMsgApi("table auto sync successfully", $this->okStatusCode, $this->sucessStatus);
        }

        return responseMsgApi("table auto sync fail", $this->internalServerErrorStatusCode, $this->errorStatus);
    }
}
