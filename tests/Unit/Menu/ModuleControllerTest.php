<?php

namespace Tests\Unit\Menu;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mockery;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\Module;
use Modules\Core\Http\Controllers\Backend\Controllers\Menu\ModuleController;
use Modules\Core\Http\Requests\StoreModuleRequest;
use Modules\Core\Http\Requests\UpdateModuleRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Menu\ModuleService;
use Tests\TestCase;

class ModuleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $moduleController;

    protected $moduleControllerOriginal;

    protected $moduleService;

    protected $coreFieldFilterSettingService;

    protected $storeModuleRequest;

    protected $updateModuleRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->moduleService = Mockery::mock(ModuleService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);

        // Mock Request
        $this->storeModuleRequest = Mockery::mock(StoreModuleRequest::class);
        $this->updateModuleRequest = Mockery::mock(UpdateModuleRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the ModuleController to mock the handlePermission method
        $this->moduleController = Mockery::mock(ModuleController::class, [
            $this->moduleService,
            $this->coreFieldFilterSettingService,
        ])->makePartial();

        $this->moduleControllerOriginal = new ModuleController(
            $this->moduleService,
            $this->coreFieldFilterSettingService,
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->moduleControllerOriginal);
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
        $this->storeModuleRequest->shouldReceive('validated')->twice()->andReturn([
            'title' => 'Item Site Map',
            'lang_key' => 'item_site_map',
            'route_name' => 'itemSitemap',
            'is_not_from_sidebar' => '0',
            'status' => '1',
        ]);

        // Mock moduleService
        $this->moduleService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->moduleController->store($this->storeModuleRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->moduleService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->moduleController->store($this->storeModuleRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        $this->updateModuleRequest->shouldReceive('validated')->twice()->andReturn([
            'title' => 'Item Site Map',
            'lang_key' => 'item_site_map',
            'route_name' => 'itemSitemap',
            'is_not_from_sidebar' => '1',
            'status' => '0',
        ]);

        // Mock moduleService
        $this->moduleService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->moduleController->update($this->updateModuleRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->moduleService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->moduleController->update($this->updateModuleRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a menu-group for testing
        $module = Module::factory()->create();

        // Mock moduleService
        $this->moduleService->shouldReceive('get')->once()->with($module->id)->andReturn($module);

        // Ensure handlePermission does nothing
        $this->moduleController->shouldReceive('handlePermissionWithModel')
            ->with($module, Constants::deleteAbility);

        $this->moduleService->shouldReceive('delete')->once()->with($module->id)->andReturn([
            'msg' => 'Module deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->moduleController->destroy($module->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Module deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Menug Group Data
        $module = Module::factory()->create(['status' => Constants::publish]);

        // Mock the get method to return the module instance
        $this->moduleService->shouldReceive('get')->once()
            ->andReturn($module);

        // Ensure handlePermission does nothing
        $this->moduleController->shouldReceive('handlePermissionWithModel')
            ->with($module, Constants::editAbility);

        // Mock the setStatus method
        $this->moduleService->shouldReceive('setStatus')->once()
            ->with($module->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->moduleController->statusChange($module->id);

        // Assert the response is a redirect
        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('The status has been updated successfully.', session('status')['msg']);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareIndexData()
    {
        // Create a fake request with parameters
        $inputs = [
            'search' => 'keyword',
            'sort_field' => 'route_name',
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

        $relations = ['owner', 'editor'];

        $this->moduleService->shouldReceive('getAll')
            ->once()
            ->with($relations, $inputs['row'], $conds)
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('modules', $result);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $result);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
    }

    public function test_prepareCreateData()
    {
        $this->coreFieldFilterSettingService->shouldReceive('getCoreFields')
            ->once()
            ->with(
                null,
                null,
                null,
                1,
                null,
                null,
                null,
                null,
                null,
                Constants::module
            )->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareEditData()
    {

        $id = 1;

        $this->coreFieldFilterSettingService->shouldReceive('getCoreFields')
            ->once()
            ->with(
                null,
                null,
                null,
                1,
                null,
                null,
                null,
                null,
                null,
                Constants::module
            )->andReturn([]);

        $this->moduleService->shouldReceive('get')
            ->once()
            ->with($id)
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('module', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareStatusData()
    {
        $module = new \stdClass();
        $module->status = Constants::publish;

        $result = $this->psTestHelper->invokePrivateMethod('prepareStatusData', [$module]);

        $this->assertEquals(Constants::unPublish, $result);
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
