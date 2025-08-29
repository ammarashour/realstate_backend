<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Location;

use App\Http\Contracts\Location\LocationTownshipServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Constants\Constants;
use Modules\Core\Transformers\Api\App\V1_0\Location\LocationTownshipApiResource;
use App\Http\Controllers\PsApiController;

class LocationTownshipApiController extends PsApiController
{
    public function __construct(protected LocationTownshipServiceInterface $locationTownshipService)
    {
        parent::__construct();
    }

    public function search(Request $request)
    {
        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);
        $townships = $this->locationTownshipService->getAll(['location_city'], Constants::publish, $limit, $offset, $conds, 1);

        $data = LocationTownshipApiResource::collection($townships);

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
            'location_city_id' => $request->city_id,
            'order_by' => $request->order_by,
            'order_type' => $request->order_type,
        ];
    }
}
