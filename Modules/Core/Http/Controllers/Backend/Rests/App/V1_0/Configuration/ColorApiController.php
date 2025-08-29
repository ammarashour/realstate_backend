<?php

namespace Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Configuration;

use Illuminate\Http\Request;
use App\Http\Controllers\PsApiController;
use Modules\Core\Constants\Constants;
use App\Http\Contracts\Configuration\ColorServiceInterface;
use Modules\Core\Transformers\Api\App\V1_0\Configuration\ColorApiResource;

class ColorApiController extends PsApiController
{
    public function __construct(protected ColorServiceInterface $colorService)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $data = new ColorApiResource('colors');
        return $data;
    }

}

