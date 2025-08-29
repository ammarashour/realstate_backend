<?php

namespace Tests\Unit\Location;

use App\Helpers\PsTestHelper;
use App\Http\Contracts\Location\LocationCityServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Location\LocationTownship;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Http\Controllers\Backend\Controllers\Location\LocationTownshipController;
use Modules\Core\Http\Requests\StoreLocationTownshipRequest;
use Modules\Core\Http\Requests\UpdateLocationTownshipRequest;
use Modules\Core\Http\Services\Configuration\BackendSettingService;
use Modules\Core\Http\Services\Location\LocationTownshipService;
use Modules\Core\Http\Services\Utilities\CoreFieldService;
use Tests\TestCase;

class LocationTownshipControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $locationTownshipController;

    protected $locationTownshipControllerOriginal;

    protected $locationCityServiceInterface;

    protected $BackendSettingService;

    protected $coreFieldService;

    protected $customFieldService;

    protected $locationTownshipService;

    protected $customizeUiService;

    protected $customizeUiDetailService;

    protected $storeLocationTownshipRequest;

    protected $updateLocationTownshipRequest;

    protected $city;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->locationTownshipService = Mockery::mock(LocationTownshipService::class);
        $this->locationCityServiceInterface = Mockery::mock(LocationCityServiceInterface::class);
        $this->BackendSettingService = Mockery::mock(BackendSettingService::class);
        $this->coreFieldService = Mockery::mock(CoreFieldService::class);
        $this->customizeUiService = Mockery::mock(CustomFieldServiceInterface::class);
        $this->customizeUiDetailService = Mockery::mock(CustomFieldAttributeServiceInterface::class);

        // Mock storeLocationCityRequest
        $this->storeLocationTownshipRequest = Mockery::mock(StoreLocationTownshipRequest::class);
        $this->updateLocationTownshipRequest = Mockery::mock(UpdateLocationTownshipRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the BlogController to mock the handlePermission method
        $this->locationTownshipController = Mockery::mock(LocationTownshipController::class, [
            $this->locationTownshipService,
            $this->coreFieldService,
            $this->locationCityServiceInterface,
            $this->BackendSettingService,
            $this->customizeUiService,
            $this->customizeUiDetailService,
        ])->makePartial();

        $this->locationTownshipControllerOriginal = new LocationTownshipController(
            $this->locationTownshipService,
            $this->coreFieldService,
            $this->locationCityServiceInterface,
            $this->BackendSettingService,
            $this->customizeUiService,
            $this->customizeUiDetailService

        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->locationTownshipControllerOriginal);

        $this->city = LocationCity::factory()->create();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Clean up Mockery
        Mockery::close();
    }

    ////////////////////////////////////////////////////////////////////
    /// Public Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_store()
    {

        $this->storeLocationTownshipRequest->shouldReceive('validated')->twice()->andReturn([
            'name' => 'Test Location Township',
            'location_city_id' => $this->city->id,
            'description' => 'This is a test location city description.',
            'lat' => 65.77,
            'lng' => 78.66,
        ]);

        // Mock locationCityService
        $this->locationTownshipService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->locationTownshipController->store($this->storeLocationTownshipRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->locationTownshipService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->locationTownshipController->store($this->storeLocationTownshipRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {

        // Mock StoreBlogRequest
        $this->updateLocationTownshipRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'name' => 'Test Location Township1',
            'location_city_id' => $this->city->id,
            'description' => 'This is a test location city description.',
            'lat' => 65.77,
            'lng' => 78.66,
        ]);

        // Mock locationCityService
        $this->locationTownshipService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->locationTownshipController->update($this->updateLocationTownshipRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->locationTownshipService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->locationTownshipController->update($this->updateLocationTownshipRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a blog for testing
        $township = LocationTownship::factory()->create();

        // Mock BlogService
        $this->locationTownshipService->shouldReceive('get')->once()->with($township->id)->andReturn($township);

        // Ensure handlePermission does nothing
        $this->locationTownshipController->shouldReceive('handlePermissionWithModel')
            ->with($township, Constants::deleteAbility);

        $this->locationTownshipService->shouldReceive('delete')->once()->with($township->id)->andReturn([
            'msg' => 'Township deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->locationTownshipController->destroy($township->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Township deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Blog Data
        $township = LocationTownship::factory()->create(['status' => Constants::publish]);

        // Mock the get method to return the blog instance
        $this->locationTownshipService->shouldReceive('get')->once()
            ->andReturn($township);

        // Ensure handlePermission does nothing
        $this->locationTownshipController->shouldReceive('handlePermissionWithModel')
            ->with($township, Constants::editAbility);

        // Mock the setStatus method
        $this->locationTownshipService->shouldReceive('setStatus')->once()
            ->with($township->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->locationTownshipController->statusChange($township->id);

        // Assert the response is a redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('The status has been updated successfully.', session('status')['msg']);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareCreateData()
    {

        $this->locationCityServiceInterface->shouldReceive('getAll')
            ->once()->andReturn([]);

        $this->BackendSettingService->shouldReceive('get')
            ->once()->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        $this->assertNotNull($result);

        $this->assertArrayHasKey('cities', $result);

        $this->assertArrayHasKey('backendSettings', $result);
    }

    public function test_prepareIndexData()
    {
        // Create a fake request with parameters
        $inputs = [
            'search' => 'keyword',
            'sort_field' => 'name',
            'sort_order' => 'desc',
            'row' => 10,
            'city_filter' => 'all',
        ];

        $this->request->shouldReceive('all')
            ->andReturn($inputs);

        foreach ($inputs as $key => $value) {
            $this->request->shouldReceive('input')
                ->with($key)
                ->andReturn($value);
        }

        $this->request->shouldReceive('sort_field')
            ->andReturn($inputs['sort_field']);

        $this->request->shouldReceive('sort_order')
            ->andReturn($inputs['sort_order']);

        $conds = [
            'searchterm' => $inputs['search'],
            'order_by' => $inputs['sort_field'],
            'order_type' => $inputs['sort_order'],
            'location_city_id' => $inputs['city_filter'] == 'all' ? null : $inputs['city_filter'],
        ];

        $this->locationTownshipService->shouldReceive('getAll')
            ->once()
            ->with(['location_city', 'owner', 'editor'], null, null, null, $conds, false, $inputs['row'])
            ->andReturn([]);

        $customizeUis = ['cus0001' => 'Phone'];

        $this->customizeUiService->shouldReceive('getAll')
            ->once()
            ->andReturn([]);

        $this->customizeUiDetailService->shouldReceive('getAll')
            ->once()
            ->andReturn([]);

        $this->locationCityServiceInterface->shouldReceive('get')
            ->once()
            ->with($conds['location_city_id'])
            ->andReturn(null); // Assuming `get` will return null if city_filter is 'all'

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('townships', $result);
        $this->assertArrayHasKey('selectedCity', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
        $this->assertArrayHasKey('customizeDetails', $result);
        $this->assertArrayHasKey('customizeHeaders', $result);
    }

    public function test_prepareEditData()
    {
        $id = 1;

        $this->locationTownshipService->shouldReceive('get')
            ->once()
            ->with($id)
            ->andReturn([]);

        $this->locationCityServiceInterface->shouldReceive('getAll')
            ->once()
            ->with(null, Constants::publish)
            ->andReturn([]);

        $this->BackendSettingService->shouldReceive('get')
            ->once()->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('township', $result);

        $this->assertArrayHasKey('cities', $result);

        $this->assertArrayHasKey('backendSettings', $result);
    }

    public function test_controlFieldArr()
    {

        $result = $this->psTestHelper->invokePrivateMethod('controlFieldArr', []);

        $this->assertNotNull($result);
        $this->assertEquals('core__be_action', $result[0]->label);
        $this->assertEquals('action', $result[0]->field);
        $this->assertEquals('Action', $result[0]->type);
        $this->assertEquals(false, $result[0]->sort);
        $this->assertEquals(0, $result[0]->ordering);
    }
}
