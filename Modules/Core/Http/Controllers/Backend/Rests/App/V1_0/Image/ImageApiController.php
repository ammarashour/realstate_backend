<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Image;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Constants\Constants;
use Illuminate\Support\Facades\Validator;

class ImageApiController extends Controller
{

    protected $badRequestStatusCode, $internalServerErrorStatusCode;
    

    public function __construct()
    {
        $this->badRequestStatusCode = Constants::badRequestStatusCode;
        $this->internalServerErrorStatusCode = Constants::internalServerErrorStatusCode;

    }

    
}

