<?php

namespace Tests\Unit\Utilities;

use App\Helpers\PsTestHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Controllers\Backend\Controllers\Utilities\CustomFieldAttributeController;
use Modules\Core\Http\Requests\Utilities\StoreCustomFieldAttributeRequest;
use Modules\Core\Http\Requests\Utilities\UpdateCustomFieldAttributeRequest;
use Modules\Core\Http\Services\Utilities\CustomFieldAttributeService;
use Modules\Core\Http\Services\Utilities\CustomFieldService;
use Tests\TestCase;

class CustomFieldAttributeControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $customFieldAttributeController;

    protected $customFieldAttributeControllerOriginal;

    protected $customFieldAttributeService;

    protected $customFieldService;

    protected $storeCustomFieldAttributeRequest;

    protected $updateCustomFieldAttributeRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->customFieldAttributeService = Mockery::mock(CustomFieldAttributeService::class);
        $this->customFieldService = Mockery::mock(CustomFieldService::class);

        // Mock Request
        $this->storeCustomFieldAttributeRequest = Mockery::mock(StoreCustomFieldAttributeRequest::class);
        $this->updateCustomFieldAttributeRequest = Mockery::mock(UpdateCustomFieldAttributeRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the CustomFieldAttributeController to mock the handlePermission method
        $this->customFieldAttributeController = Mockery::mock(CustomFieldAttributeController::class, [
            $this->customFieldAttributeService,
            $this->customFieldService,
        ])->makePartial();

        $this->customFieldAttributeControllerOriginal = new CustomFieldAttributeController(
            $this->customFieldAttributeService,
            $this->customFieldService,
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->customFieldAttributeControllerOriginal);

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
        // Simulate a file upload

        $params = ['table' => 'exampleTable', 'field' => 'exampleField'];

        // Set expectations
        $this->storeCustomFieldAttributeRequest->shouldReceive('route')->with('table')->andReturn($params['table']);
        $this->storeCustomFieldAttributeRequest->shouldReceive('route')->with('field')->andReturn($params['field']);

        $this->storeCustomFieldAttributeRequest->shouldReceive('validated')->twice()->andReturn([
            'name' => 'Test',
            'core_keys_id' => 'loc00001',
        ]);

        // Mock customFieldAttributeService
        $this->customFieldAttributeService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error CustomFieldAttribute Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->customFieldAttributeController->store($this->storeCustomFieldAttributeRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->customFieldAttributeService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->customFieldAttributeController->store($this->storeCustomFieldAttributeRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

    }

    public function test_update()
    {
        // Simulate a file upload

        $params = [
            'table' => 'exampleTable',
            'field' => 'exampleField',
            'attribute' => 'exampleAttribute',
        ];

        $indexRouteParams = [
            'table' => $params['table'],
            'field' => $params['field'],
        ];

        // Set expectations
        $this->updateCustomFieldAttributeRequest->shouldReceive('route')->with('table')->andReturn($params['table']);
        $this->updateCustomFieldAttributeRequest->shouldReceive('route')->with('field')->andReturn($params['field']);
        $this->updateCustomFieldAttributeRequest->shouldReceive('route')->with('attribute')->andReturn($params['attribute']);

        $this->updateCustomFieldAttributeRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'name' => 'Test',
            'core_keys_id' => 'loc00001',
        ]);

        // Mock cutomFieldAttributeService
        $this->customFieldAttributeService->shouldReceive('update')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error CustomFieldAttribute Service update
         */
        // Simulate a POST request to the store method
        $response = $this->customFieldAttributeController->update($this->updateCustomFieldAttributeRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->customFieldAttributeService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->customFieldAttributeController->update($this->updateCustomFieldAttributeRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

    }

    public function test_destroy()
    {
        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn(2);

        $this->request->shouldReceive('route')
            ->with('field')
            ->andReturn('loc00001');

        $this->request->shouldReceive('route')
            ->with('attribute')
            ->andReturn(1);

        $customFieldAttribute = (object) ['id' => 1];

        $this->customFieldAttributeService
            ->shouldReceive('get')
            ->once()
            ->with(1)
            ->andReturn($customFieldAttribute);

        $this->customFieldAttributeController->shouldReceive('handlePermissionWithModel')
            ->with($customFieldAttribute, Constants::deleteAbility);

        $this->customFieldAttributeService->shouldReceive('delete')->once()->with($customFieldAttribute->id)->andReturn([
            'msg' => 'CustomFieldAttribute deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->customFieldAttributeController->destroy($this->request);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('CustomFieldAttribute deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('success', session('status')['flag']);

        // Assert route para
        $this->assertEquals(route('attribute.index', [2, 'loc00001']), $response->getTargetUrl());

    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareCreateData()
    {

        $this->request->shouldReceive('route')->once()->with('field')->andReturn('loc00001');
        $this->request->shouldReceive('route')->once()->with('table')->andReturn('2');

        $customField = new \stdClass();
        $customField->id = 1;

        $this->customFieldService->shouldReceive('get')
            ->with(null, null, null, 'loc00001')
            ->once()->andReturn($customField);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', [$this->request]);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('tableId', $result);
        $this->assertArrayHasKey('customizeHeader', $result);
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

        $this->request->shouldReceive('route')
            ->with('field')->andReturn('loc00001');

        $this->request->shouldReceive('route')
            ->with('table')->andReturn(2);

        $conds = [
            'searchterm' => $inputs['search'],
            'order_by' => $inputs['sort_field'],
            'order_type' => $inputs['sort_order'],
        ];

        $customField = new \stdClass();
        $customField->id = 1;

        $this->customFieldService->shouldReceive('get')
            ->once()
            ->with(null, null, null, 'loc00001')
            ->andReturn($customField);

        $this->customFieldAttributeService->shouldReceive('getAll')
            ->once()
            ->with(
                'loc00001',
                Constants::no,
                $inputs['row'],
                null, null,
                $conds,

            )
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);
        $this->assertArrayHasKey('tableId', $result);
        $this->assertArrayHasKey('customizeHeader', $result);
        $this->assertArrayHasKey('customizeDetails', $result);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $result);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
        $this->assertArrayHasKey('can', $result);
    }

    public function test_prepareEditData()
    {
        $id = 1;
        $this->request->shouldReceive('route')->once()->with('field')->andReturn('loc00001');
        $this->request->shouldReceive('route')->once()->with('table')->andReturn('2');
        $this->request->shouldReceive('route')->once()->with('attribute')->andReturn($id);

        $customField = new \stdClass();
        $customField->id = 1;

        $this->customFieldService->shouldReceive('get')
            ->with(null, null, null, 'loc00001')
            ->once()->andReturn($customField);

        $customFieldAttribute = new \stdClass();
        $customFieldAttribute->id = 1;
        $this->customFieldAttributeService->shouldReceive('get')
            ->with($id)
            ->once()->andReturn($customFieldAttribute);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$this->request]);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('tableId', $result);
        $this->assertArrayHasKey('customizeHeader', $result);
        $this->assertArrayHasKey('customizeDetail', $result);
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
