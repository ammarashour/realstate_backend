<?php

namespace Tests\Unit\Menu;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\PsTestHelper;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Entities\Project;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Modules\Core\Http\Services\IconService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Http\Services\Menu\ModuleService;
use Modules\Core\Http\Services\Menu\MenuGroupService;
use Modules\Core\Http\Requests\StoreSubMenuGroupRequest;
use Modules\Core\Http\services\Menu\SubMenuGroupService;
use Modules\Core\Http\Requests\UpdateSubMenuGroupRequest;
use Modules\Core\Http\Services\Utilities\CoreFieldService;
use Modules\Core\Http\Controllers\Backend\Controllers\Menu\SubMenuGroupController;

class SubMenuGroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $subMenuGroupController;

    protected $subMenuGroupControllerOriginal;

    protected $subMenuGroupService;

    protected $menuGroupService;

    protected $moduleService;

    protected $iconService;

    protected $coreFieldService;

    protected $storeSubMenuGroupRequest;

    protected $updateSubMenuGroupRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->subMenuGroupService = Mockery::mock(SubMenuGroupService::class);
        $this->menuGroupService = Mockery::mock(MenuGroupService::class);
        $this->moduleService = Mockery::mock(ModuleService::class);
        $this->iconService = Mockery::mock(IconService::class);
        $this->coreFieldService = Mockery::mock(CoreFieldService::class);

        // Mock Request
        $this->storeSubMenuGroupRequest = Mockery::mock(StoreSubMenuGroupRequest::class);
        $this->updateSubMenuGroupRequest = Mockery::mock(UpdateSubMenuGroupRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the SubMenuGroupController to mock the handlePermission method
        $this->subMenuGroupController = Mockery::mock(SubMenuGroupController::class, [
            $this->subMenuGroupService,
            $this->menuGroupService,
            $this->moduleService,
            $this->iconService,
            $this->coreFieldService
        ])->makePartial();

        $this->subMenuGroupControllerOriginal = new SubMenuGroupController(
            $this->subMenuGroupService,
            $this->menuGroupService,
            $this->moduleService,
            $this->iconService,
            $this->coreFieldService
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->subMenuGroupControllerOriginal);
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
        $this->storeSubMenuGroupRequest->shouldReceive('validated')->twice()->andReturn([
            'sub_menu_name' => 'Test Sub Menu Group',
            'sub_menu_desc' => 'Test Sub Menu Group',
            'icon_id' => '1',
            'sub_menu_lang_key' => 'test_sub_menu_group',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '5',
            'added_user_id' => '1',
            'is_dropdown' => '1'
        ]);

        // Mock menuGroupService
        $this->subMenuGroupService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->subMenuGroupController->store($this->storeSubMenuGroupRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->subMenuGroupService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->subMenuGroupController->store($this->storeSubMenuGroupRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        $this->updateSubMenuGroupRequest->shouldReceive('validated')->twice()->andReturn([
            'sub_menu_name' => 'Test Sub Menu Group',
            'sub_menu_desc' => 'Test Sub Menu Group',
            'icon_id' => '1',
            'sub_menu_lang_key' => 'test_sub_menu_group',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '5',
            'added_user_id' => '1',
            'is_dropdown' => '1'
        ]);

        // Mock subMenuGroupService
        $this->subMenuGroupService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->subMenuGroupController->update($this->updateSubMenuGroupRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->subMenuGroupService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->subMenuGroupController->update($this->updateSubMenuGroupRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a menu-group for testing
        $subMenuGroup = CoreSubMenuGroup::factory()->create();

        // Mock subMenuGroupService
        $this->subMenuGroupService->shouldReceive('get')->once()->with($subMenuGroup->id)->andReturn($subMenuGroup);

        // Ensure handlePermission does nothing
        $this->subMenuGroupController->shouldReceive('handlePermissionWithModel')
            ->with($subMenuGroup, Constants::deleteAbility);

        $this->subMenuGroupService->shouldReceive('delete')->once()->with($subMenuGroup->id)->andReturn([
            'msg' => 'Menu group deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->subMenuGroupController->destroy($subMenuGroup->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Menu group deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Menug Group Data
        $subMenuGroup = CoreSubMenuGroup::factory()->create(['is_show_on_menu' => Constants::publish]);

        // Mock the get method to return the subMenuGroup instance
        $this->subMenuGroupService->shouldReceive('get')->once()
            ->andReturn($subMenuGroup);

        // Ensure handlePermission does nothing
        $this->subMenuGroupController->shouldReceive('handlePermissionWithModel')
            ->with($subMenuGroup, Constants::editAbility);

        // Mock the setStatus method
        $this->subMenuGroupService->shouldReceive('setStatus')->once()
            ->with($subMenuGroup->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->subMenuGroupController->statusChange($subMenuGroup->id);

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
            'sort_field' => 'name',
            'sort_order' => 'desc',
            'menu_filter' => 'all',
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
            'menu_id' => $inputs['menu_filter'] == 'all' ? null : $inputs['menu_filter'],
        ];

        $this->menuGroupService->shouldReceive('getAll')
            ->once()
            ->with()
            ->andReturn([]);

        $relations = ['core_menu_group', 'owner', 'editor'];

        $this->subMenuGroupService->shouldReceive('getAll')
            ->once()
            ->with($relations, $inputs['row'], $conds)
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('sub_menu_groups', $result);
        $this->assertArrayHasKey('menu_groups', $result);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $result);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
    }

    public function test_prepareCreateData()
    {
        $this->menuGroupService->shouldReceive('getAll')
            ->once()
            ->with()
            ->andReturn([]);

        $this->moduleService->shouldReceive('getAll')
            ->once()
            ->with(null, null, null, Constants::publish, Constants::yes)
            ->andReturn([]);

        $this->iconService->shouldReceive('getIcons')
            ->once()
            ->with()
            ->andReturn([]);

        $this->coreFieldService->shouldReceive('getAll')
            ->once()
            ->with(
                Constants::coreSubMenuGroup,
                null,
                null,
                null,
                0,
                1
            )->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('menu_groups', $result);
        $this->assertArrayHasKey('modules', $result);
        $this->assertArrayHasKey('icons', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareEditData()
    {
        $id = 1;

        $this->menuGroupService->shouldReceive('getAll')
            ->once()
            ->with()
            ->andReturn([]);

        $this->moduleService->shouldReceive('getAll')
            ->once()
            ->with(null, null, null, Constants::publish, Constants::yes)
            ->andReturn([]);

        $this->moduleService->shouldReceive('getAll')
            ->once()
            ->with()
            ->andReturn([]);

        $this->iconService->shouldReceive('getIcons')
            ->once()
            ->with()
            ->andReturn([]);

        $this->coreFieldService->shouldReceive('getAll')
            ->once()
            ->with(
                Constants::coreSubMenuGroup,
                null,
                null,
                null,
                0,
                1
            )->andReturn([]);

        $this->subMenuGroupService->shouldReceive('get')
            ->once()
            ->with($id)
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('menu_groups', $result);
        $this->assertArrayHasKey('modules', $result);
        $this->assertArrayHasKey('forSelectedModules', $result);
        $this->assertArrayHasKey('icons', $result);
        $this->assertArrayHasKey('sub_menu_group', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareStatusData()
    {
        $subMenuGroup = new \stdClass();
        $subMenuGroup->is_show_on_menu = Constants::publish;

        $result = $this->psTestHelper->invokePrivateMethod('prepareStatusData', [$subMenuGroup]);

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
