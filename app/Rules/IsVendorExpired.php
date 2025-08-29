<?php

namespace App\Rules;

use App\Http\Contracts\Vendor\VendorServiceInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Support\Facades\Route;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Configuration\CoreKey;
use Modules\Payment\Entities\PaymentInfo;

class IsVendorExpired implements InvokableRule
{

    public $vendorId, $vendorService;

    public function __construct($vendorId = null)
    {
        $this->vendorService = app(VendorServiceInterface::class);
        $this->vendorId = $vendorId;
    }

    public function __invoke($attribute, $value, $fail)
    {
        $vendor = $this->vendorService->get($this->vendorId);
        $isExpired = Carbon::now()->gt($vendor?->expired_date);
        if ($isExpired && !$vendor->is_unlimited) {
            $fail('Your selected vendor is expired. Therefore, you can not upload the item in this vendor');
        }
    }
}
