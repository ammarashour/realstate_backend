<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\User;

use App\Config\ps_constant;
use Illuminate\Http\Request;
use App\Exceptions\PsApiException;
use App\Http\Contracts\Configuration\MobileSettingServiceInterface;
use Modules\Core\Constants\Constants;
use App\Http\Controllers\PsApiController;
use Illuminate\Contracts\Translation\Translator;
use App\Http\Contracts\User\RatingServiceInterface;
use Modules\Core\Http\Requests\User\StoreRatingRequest;
use Modules\Core\Transformers\Api\App\V1_0\User\RatingApiResource;

class RatingApiController extends PsApiController
{
    protected $ratingApiRelation;

    public function __construct(protected Translator $translator,
        protected RatingServiceInterface $ratingService,
        protected MobileSettingServiceInterface $mobileSettingService)
    {
        parent::__construct();
        $this->ratingApiRelation = ['fromUser', 'toUser'];
    }

    public function rating(StoreRatingRequest $request)
    {
        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);
        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        try{
            $validateData = $request->validated();

            $conds = [
                'from_user_id' => $request->input('from_user_id'),
                'to_user_id' => $request->input('to_user_id')
            ];
            $rating = $this->ratingService->get(null, $conds, null, null);

            if(empty($rating)){
                $ratings = $this->ratingService->save($validateData);
            }else{
                $ratings = $this->ratingService->update($rating->id, $validateData);
            }

            return responseDataApi(new RatingApiResource($ratings));
        }catch(\Exception $e){
            throw new PsApiException($e->getMessage(), Constants::internalServerErrorStatusCode);
        }
    }

    public function search(Request $request)
    {

        // Get Limit and Offset
        [$limit, $offset] = $this->getLimitOffsetFromSetting($request);

        // Prepare Filter Conditions
        $conds = $this->getFilterConditions($request);

        $data = RatingApiResource::collection($this->ratingService->getAll(
            relation: $this->ratingApiRelation,
            conds: $conds,
            limit: $limit,
            offset: $offset));

        // Prepare and Check No Data Return
        return $this->handleNoDataResponse($request->offset, $data);
    }

    ///////////////////////////////////////////////////////////////
    /// Private Functions
    ///////////////////////////////////////////////////////////////

    ///------------------------------------------------------------
    /// Other
    ///------------------------------------------------------------

    private function getLimitOffsetFromSetting($request)
    {
        $offset = $request->offset;
        $limit = $request->limit ?: $this->getDefaultLimit();

        return [$limit, $offset];
    }

    private function getDefaultLimit()
    {
        $defaultLimit = $this->mobileSettingService->get()->default_loading_limit;

        return $defaultLimit ?: 9;
    }

    private function getFilterConditions($request)
    {
        return [
            'to_user_id' => $request->input('user_id'),
            'type' => $request->input('type'),
        ];
    }
}
