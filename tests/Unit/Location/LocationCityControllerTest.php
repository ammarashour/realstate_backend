<?php

namespace Tests\Unit\Location;

use App\Helpers\PsTestHelper;
use app\Http\Contracts\Utilities\CustomFieldAttributeServiceInterface;
use app\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Location\LocationCity;
use Modules\Core\Http\Controllers\Backend\Controllers\Location\LocationCityController;
// use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Requests\StoreLocationCityRequest;
use Modules\Core\Http\Requests\UpdateLocationCityRequest;
use Modules\Core\Http\Services\Configuration\BackendSettingService;
use Modules\Core\Http\Services\Location\locationCityService;
use Modules\Core\Http\Services\Utilities\CoreFieldService;
use Modules\Core\Http\Services\Utilities\CustomFieldService;
use Tests\TestCase;

class LocationCityControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $locationCityController;

    protected $locationCityControllerOriginal;

    protected $BackendSettingService;

    protected $coreFieldFilterSettingService;

    protected $customFieldService;

    protected $locationCityService;

    protected $customizeUiService;

    protected $customizeUiDetailService;

    protected $locationCityServiceInterface;

    protected $storeLocationCityRequest;

    protected $updateLocationCityRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->locationCityService = Mockery::mock(LocationCityService::class);
        $this->BackendSettingService = Mockery::mock(BackendSettingService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldService::class);
        $this->customFieldService = Mockery::mock(CustomFieldService::class);
        $this->customizeUiService = Mockery::mock(CustomFieldServiceInterface::class);
        $this->customizeUiDetailService = Mockery::mock(CustomFieldAttributeServiceInterface::class);

        // Mock storeLocationCityRequest
        $this->storeLocationCityRequest = Mockery::mock(StoreLocationCityRequest::class);
        $this->updateLocationCityRequest = Mockery::mock(UpdateLocationCityRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the BlogController to mock the handlePermission method
        $this->locationCityController = Mockery::mock(LocationCityController::class, [
            $this->locationCityService,
            $this->customFieldService,
            $this->coreFieldFilterSettingService,
            $this->BackendSettingService,
            $this->customizeUiService,
            $this->customizeUiDetailService,
        ])->makePartial();

        $this->locationCityControllerOriginal = new LocationCityController(
            $this->locationCityService,
            $this->customFieldService,
            $this->coreFieldFilterSettingService,
            $this->BackendSettingService,
            $this->customizeUiService,
            $this->customizeUiDetailService

        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->locationCityControllerOriginal);
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

        $this->storeLocationCityRequest->shouldReceive('validated')->twice()->andReturn([
            'title' => 'Test Location City',
            'description' => 'This is a test location city description.',
        ]);

        // Mock locationCityService
        $this->locationCityService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->locationCityController->store($this->storeLocationCityRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->locationCityService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->locationCityController->store($this->storeLocationCityRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {

        // Mock StoreBlogRequest
        $this->updateLocationCityRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'title' => 'Test City',
            'description' => 'This is a test city description.',
        ]);

        // Mock locationCityService
        $this->locationCityService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->locationCityController->update($this->updateLocationCityRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->locationCityService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->locationCityController->update($this->updateLocationCityRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a blog for testing
        $city = LocationCity::factory()->create();

        // Mock BlogService
        $this->locationCityService->shouldReceive('get')->once()->with($city->id)->andReturn($city);

        // Ensure handlePermission does nothing
        $this->locationCityController->shouldReceive('handlePermissionWithModel')
            ->with($city, Constants::deleteAbility);

        $this->locationCityService->shouldReceive('delete')->once()->with($city->id)->andReturn([
            'msg' => 'City deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->locationCityController->destroy($city->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('City deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Blog Data
        $blog = LocationCity::factory()->create(['status' => Constants::publish]);

        // Mock the get method to return the blog instance
        $this->locationCityService->shouldReceive('get')->once()
            ->andReturn($blog);

        // Ensure handlePermission does nothing
        $this->locationCityController->shouldReceive('handlePermissionWithModel')
            ->with($blog, Constants::editAbility);

        // Mock the setStatus method
        $this->locationCityService->shouldReceive('setStatus')->once()
            ->with($blog->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->locationCityController->statusChange($blog->id);

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

        $this->customizeUiService->shouldReceive('getAll')
            ->once()->andReturn([]);

        $this->customizeUiDetailService->shouldReceive('get')
            ->once()->andReturn([]);

        $this->BackendSettingService->shouldReceive('get')
            ->once()->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        $this->assertNotNull($result);

        $this->assertArrayHasKey('customizeHeaders', $result);

        $this->assertArrayHasKey('customizeDetails', $result);

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
        ];

        foreach ($inputs as $key => $value) {
            $this->request->shouldReceive('input')
                ->with($key)
                ->andReturn($value);
        }

        $conds = [
            'searchterm' => $inputs['search'],
            'order_by' => $inputs['sort_field'],
            'order_type' => $inputs['sort_order'],
        ];

        $this->locationCityService->shouldReceive('getAll')
            ->once()
            ->with(null, null, null, null, $conds, false, $inputs['row'])
            ->andReturn([]);

        $customizeUis = ['cus0001' => 'Phone'];

        $this->customizeUiDetailService->shouldReceive('getAll')
            ->once()
            ->with(coreKeysId: $customizeUis)
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('cities', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
        $this->assertArrayHasKey('customizeDetails', $result);
        $this->assertArrayHasKey('customizeHeaders', $result);
    }

    public function test_prepareEditData()
    {

        $id = 1;
        $this->coreFieldFilterSettingService->shouldReceive('getAll')
            ->once()
            ->with(
                Constants::locationCity
            )->andReturn([]);

        $dataWithRelation = ['cityRelation'];
        $this->locationCityService->shouldReceive('get')
            ->once()
            ->with($id, $dataWithRelation)
            ->andReturn(null);

        $this->customizeUiService->shouldReceive('getAll')
            ->once()->andReturn([]);

        $this->customizeUiDetailService->shouldReceive('get')
            ->once()->andReturn([]);

        $this->BackendSettingService->shouldReceive('get')
            ->once()->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('city', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
        $this->assertArrayHasKey('customizeHeaders', $result);

        $this->assertArrayHasKey('customizeDetails', $result);

        $this->assertArrayHasKey('backendSettings', $result);
    }

    public function test_prepareDataCustomFields()
    {
        // Create a mock Request object
        $request = Mockery::mock(Request::class);

        // Fake input data
        $fakeInputData = [
            'city_relation' => ['relation1', 'relation2'],
        ];
        $fakeFilesData = [
            'city_relation' => [],
        ];
        // Setting up the expectations for the mock object
        $request->shouldReceive('input')
            ->with('city_relation', [])
            ->andReturn($fakeInputData['city_relation']);

        $request->shouldReceive('allFiles')
            ->with('city_relation')
            ->andReturn($fakeFilesData['city_relation']);

        $result = $this->psTestHelper->invokePrivateMethod('prepareDataCustomFields', [$request]);

        // Assert that the result is as expected
        $expectedResult = array_merge($fakeInputData['city_relation'], $fakeFilesData['city_relation']);
        $this->assertEquals($expectedResult, $result);
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
