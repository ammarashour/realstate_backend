<?php

namespace Tests\Unit\Location;

use App\Exceptions\PsApiException;
use App\Helpers\PsTestHelper;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Location\LocationCityApiController;
use Tests\TestCase;

class LocationCityApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected LocationCityApiController $locationCityApiController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->locationCityApiController = new LocationCityApiController(
            app(LocationCityServiceInterface::class),
        );

    }
}
