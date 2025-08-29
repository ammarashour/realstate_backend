<?php

namespace Tests\Unit\Menu;

use App\Helpers\PsTestHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\CoreMenu;
use Modules\Core\Http\Controllers\Backend\Controllers\Menu\CoreMenuController;
use Modules\Core\Http\Requests\StoreCoreMenuRequest;
use Modules\Core\Http\Requests\UpdateCoreMenuRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\IconService;
use Modules\Core\Http\Services\Menu\CoreMenuService;
use Modules\Core\Http\Services\Menu\ModuleService;
use Modules\Core\Http\services\Menu\SubMenuGroupService;
use Tests\TestCase;

class CoreMenuControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $coreMenuController;

    protected $coreMenuControllerOriginal;

    protected $coreMenuService;

    protected $moduleService;

    protected $subMenuGroupService;

    protected $iconService;

    protected $coreFieldFilterSettingService;

    protected $storeCoreMenuRequest;

    protected $updateCoreMenuRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->coreMenuService = Mockery::mock(CoreMenuService::class);
        $this->moduleService = Mockery::mock(ModuleService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);
        $this->subMenuGroupService = Mockery::mock(SubMenuGroupService::class);
        $this->iconService = Mockery::mock(IconService::class);

        // Mock Request
        $this->storeCoreMenuRequest = Mockery::mock(StoreCoreMenuRequest::class);
        $this->updateCoreMenuRequest = Mockery::mock(UpdateCoreMenuRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the CoreMenuController to mock the handlePermission method
        $this->coreMenuController = Mockery::mock(CoreMenuController::class, [
            $this->coreMenuService,
            $this->coreFieldFilterSettingService,
            $this->subMenuGroupService,
            $this->moduleService,
            $this->iconService,
        ])->makePartial();

        $this->coreMenuControllerOriginal = new CoreMenuController(
            $this->coreMenuService,
            $this->coreFieldFilterSettingService,
            $this->subMenuGroupService,
            $this->moduleService,
            $this->iconService,
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->coreMenuControllerOriginal);
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
        $this->storeCoreMenuRequest->shouldReceive('validated')->twice()->andReturn([
            'module_name' => 'test_menu',
            'module_desc' => 'Test Menu',
            'module_lang_key' => 'test_menu_lang_key',
            'ordering' => '3',
            'is_show_on_menu' => '1',
            'module_id' => '9',
            'core_sub_menu_group_id' => '5',
        ]);

        // Mock coreMenuService
        $this->coreMenuService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->coreMenuController->store($this->storeCoreMenuRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->coreMenuService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->coreMenuController->store($this->storeCoreMenuRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        $this->updateCoreMenuRequest->shouldReceive('validated')->twice()->andReturn([
            'module_name' => 'test_menu',
            'module_desc' => 'Test Menu',
            'module_lang_key' => 'test_menu_lang_key',
            'ordering' => '3',
            'is_show_on_menu' => '1',
            'module_id' => '9',
            'core_sub_menu_group_id' => '5',
        ]);

        // Mock coreMenuService
        $this->coreMenuService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->coreMenuController->update($this->updateCoreMenuRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->coreMenuService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->coreMenuController->update($this->updateCoreMenuRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a menu-group for testing
        $coreMenu = CoreMenu::factory()->create();

        // Mock coreMenuService
        $this->coreMenuService->shouldReceive('get')->once()->with($coreMenu->id)->andReturn($coreMenu);

        // Ensure handlePermission does nothing
        $this->coreMenuController->shouldReceive('handlePermissionWithModel')
            ->with($coreMenu, Constants::deleteAbility);

        $this->coreMenuService->shouldReceive('delete')->once()->with($coreMenu->id)->andReturn([
            'msg' => 'Module deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->coreMenuController->destroy($coreMenu->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Module deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare core menu Data
        $coreMenu = CoreMenu::factory()->create(['is_show_on_menu' => Constants::publish]);

        // Mock the get method to return the coreMenu instance
        $this->coreMenuService->shouldReceive('get')->once()
            ->andReturn($coreMenu);

        // Ensure handlePermission does nothing
        $this->coreMenuController->shouldReceive('handlePermissionWithModel')
            ->with($coreMenu, Constants::editAbility);

        // Mock the setStatus method
        $this->coreMenuService->shouldReceive('setStatus')->once()
            ->with($coreMenu->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->coreMenuController->statusChange($coreMenu->id);

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
            'sub_menu_filter' => 'all',
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
            'sub_menu_id' => $inputs['sub_menu_filter'] === 'all' ? null : $inputs['sub_menu_filter'],
        ];

        $this->subMenuGroupService->shouldReceive('getSubMenuGroups')
            ->once()
            ->with()
            ->andReturn([]);

        $relations = ['core_sub_menu_group', 'owner', 'editor'];
        $this->coreMenuService->shouldReceive('getAll')
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
        $this->assertArrayHasKey('sub_menu_groups', $result);
        $this->assertArrayHasKey('selectedSubMenu', $result);
        $this->assertArrayHasKey('search', $result);
    }

    public function test_prepareCreateData()
    {
        $this->subMenuGroupService->shouldReceive('getSubMenuGroups')
            ->once()->with()->andReturn([]);

        $this->iconService->shouldReceive('getIcons')
            ->once()->with()->andReturn([]);

        $this->moduleService->shouldReceive('getAll')
            ->once()
            ->with(null, null, null, Constants::publish, Constants::yes)
            ->andReturn([]);

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
                Constants::coreModule
            )->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('sub_menu_groups', $result);
        $this->assertArrayHasKey('modules', $result);
        $this->assertArrayHasKey('icons', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareEditData()
    {

        $id = 1;

        $this->subMenuGroupService->shouldReceive('getSubMenuGroups')
            ->once()->with()->andReturn([]);

        $this->iconService->shouldReceive('getIcons')
            ->once()->with()->andReturn([]);

        $this->moduleService->shouldReceive('getAll')
            ->once()
            ->with(null, null, null, Constants::publish, Constants::yes)
            ->andReturn([]);

        $this->moduleService->shouldReceive('getAll')
            ->once()
            ->with()
            ->andReturn([]);

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
                Constants::coreModule
            )->andReturn([]);

        $this->coreMenuService->shouldReceive('get')
            ->once()
            ->with($id)
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('menu', $result);
        $this->assertArrayHasKey('sub_menu_groups', $result);
        $this->assertArrayHasKey('icons', $result);
        $this->assertArrayHasKey('modules', $result);
        $this->assertArrayHasKey('forSelectedModules', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareStatusData()
    {
        $coreMenu = new \stdClass();
        $coreMenu->is_show_on_menu = Constants::publish;

        $result = $this->psTestHelper->invokePrivateMethod('prepareStatusData', [$coreMenu]);

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
