<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Vendor;

use App\Config\ps_constant;
use App\Http\Contracts\Vendor\VendorInfoServiceInterface;
use App\Http\Controllers\PsApiController;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Routing\Controller;
use Modules\Core\Http\Requests\Vendor\StoreVendorInfoRequest;
use Modules\StoreFront\VendorPanel\Transformers\Api\App\V1_0\Vendor\VendorConfigInfoApiResource;

class VendorInfoApiController extends PsApiController
{
    public function __construct(
        protected Translator $translator,
        protected VendorInfoServiceInterface $vendorInfoService
    )
    {
        parent::__construct();
    }

    public function getVendorInfo(StoreVendorInfoRequest $request)
    {
        $validatedData = $request->validated();

        if ($request->language_symbol) {
            $this->translator->setLocale($request->language_symbol);
        }

        $loginUserId = $request->query('login_user_id');
        $langSymbol = $request->query('language_symbol');
        $headerToken = $request->header(ps_constant::deviceTokenKeyFromApi);

        // check permission start
        $this->checkApiPermission($loginUserId, $headerToken, $langSymbol);
        // check permission end

        $vendorId = $request->vendor_id;

        $data = new VendorConfigInfoApiResource($vendorId);
        return responseDataApi($data);

    }
}
