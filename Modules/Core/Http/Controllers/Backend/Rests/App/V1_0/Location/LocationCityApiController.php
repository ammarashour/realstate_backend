<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Location;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\Location\LocationCityApiResource;
use App\Http\Controllers\PsApiController;

class LocationCityApiController extends PsApiController
{
    
    public function __construct(protected LocationCityServiceInterface $locationCityService)
    {
        parent::__construct();
    }

    public function search(Request $request)
    {
        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);
        $cities = $this->locationCityService->getAll(null, Constants::publish, $limit, $offset, $conds, 1);

        $data = LocationCityApiResource::collection($cities);

        return $this->handleNoDataResponse($request->offset, $data);
    }

    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->offset;
        $limit = $request->limit ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    private function getFilterConditions($request)
    {
        return [
            'searchterm' => $request->keyword,
            'order_by' => $request->order_by,
            'order_type' => $request->order_type,
        ];
    }
}
