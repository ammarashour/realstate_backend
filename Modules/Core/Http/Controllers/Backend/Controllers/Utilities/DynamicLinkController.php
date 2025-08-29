<?php

namespace Modules\Core\Http\Controllers\Backend\Controllers\Utilities;

use App\Http\Contracts\Utilities\DynamicLinkServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;

class DynamicLinkController extends Controller
{
    public function __construct(protected DynamicLinkServiceInterface $dynamicLinkService) {}
    const parentPath = "utilities/fallback/";
    const indexPath = self::parentPath . "Index";

    public function redirect(Request $request, $shortCode)
    {
        $userAgent = $request->header('User-Agent', '');

        $dataArr = $this->dynamicLinkService->getDynamicLinkRedirectData($shortCode);

        if (Str::contains($userAgent, ['iPhone', 'iPad', 'Android'])) {
            return renderView(self::indexPath, $dataArr);
        } else {
            return redirect()->to($dataArr['webRedirect']);
        }
    }
}
