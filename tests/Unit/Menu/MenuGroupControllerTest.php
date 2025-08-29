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
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Modules\Core\Entities\Project;
use Modules\Core\Http\Controllers\Backend\Controllers\Menu\MenuGroupController;
use Modules\Core\Http\Requests\StoreMenuGroupRequest;
use Modules\Core\Http\Requests\UpdateMenuGroupRequest;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Menu\MenuGroupService;
use Tests\TestCase;

class MenuGroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $menuGroupController;

    protected $menuGroupControllerOriginal;

    protected $menuGroupService;

    protected $coreFieldFilterSettingService;

    protected $storeMenuGroupRequest;

    protected $updateMenuGroupRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->menuGroupService = Mockery::mock(MenuGroupService::class);
        $this->coreFieldFilterSettingService = Mockery::mock(CoreFieldFilterSettingService::class);

        // Mock Request
        $this->storeMenuGroupRequest = Mockery::mock(StoreMenuGroupRequest::class);
        $this->updateMenuGroupRequest = Mockery::mock(UpdateMenuGroupRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the MenuGroupController to mock the handlePermission method
        $this->menuGroupController = Mockery::mock(MenuGroupController::class, [
            $this->menuGroupService,
            $this->coreFieldFilterSettingService,
        ])->makePartial();

        $this->menuGroupControllerOriginal = new MenuGroupController(
            $this->menuGroupService,
            $this->coreFieldFilterSettingService,
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->menuGroupControllerOriginal);
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
        $this->storeMenuGroupRequest->shouldReceive('validated')->twice()->andReturn([
            'group_name' => 'Test Group',
            'group_lang_key' => 'test_group',
            'is_show_on_menu' => '1',
            'is_invisible_group_name' => '0',
        ]);

        // Mock menuGroupService
        $this->menuGroupService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->menuGroupController->store($this->storeMenuGroupRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->menuGroupService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->menuGroupController->store($this->storeMenuGroupRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        $this->updateMenuGroupRequest->shouldReceive('validated')->twice()->andReturn([
            'group_name' => 'Test Group',
            'group_lang_key' => 'test_group',
            'is_show_on_menu' => '1',
            'is_invisible_group_name' => '0',
        ]);

        // Mock menuGroupService
        $this->menuGroupService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->menuGroupController->update($this->updateMenuGroupRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->menuGroupService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->menuGroupController->update($this->updateMenuGroupRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_destroy()
    {
        // Create a user and a menu-group for testing
        $menuGroup = CoreMenuGroup::factory()->create();

        // Mock menuGroupService
        $this->menuGroupService->shouldReceive('get')->once()->with($menuGroup->id)->andReturn($menuGroup);

        // Ensure handlePermission does nothing
        $this->menuGroupController->shouldReceive('handlePermissionWithModel')
            ->with($menuGroup, Constants::deleteAbility);

        $this->menuGroupService->shouldReceive('delete')->once()->with($menuGroup->id)->andReturn([
            'msg' => 'Menu group deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->menuGroupController->destroy($menuGroup->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('Menu group deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {

        // Prepare Menug Group Data
        $menuGroup = CoreMenuGroup::factory()->create(['is_show_on_menu' => Constants::publish]);

        // Mock the get method to return the menuGroup instance
        $this->menuGroupService->shouldReceive('get')->once()
            ->andReturn($menuGroup);

        // Ensure handlePermission does nothing
        $this->menuGroupController->shouldReceive('handlePermissionWithModel')
            ->with($menuGroup, Constants::editAbility);

        // Mock the setStatus method
        $this->menuGroupService->shouldReceive('setStatus')->once()
            ->with($menuGroup->id, Constants::unPublish);

        // Call the statusChange method
        $response = $this->menuGroupController->statusChange($menuGroup->id);

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
        Project::factory()->create();
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

        $relations = ['owner', 'editor'];

        $isHideVendor = false;

        $this->menuGroupService->shouldReceive('getAll')
            ->once()
            ->with($relations, $inputs['row'], $conds, $isHideVendor)
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('menu_groups', $result);
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
                Constants::coreMenuGroup
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
                Constants::coreMenuGroup
            )->andReturn([]);

        $this->menuGroupService->shouldReceive('get')
            ->once()
            ->with($id)
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$id]);

        $this->assertArrayHasKey('menu_group', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareStatusData()
    {
        $module = new \stdClass();
        $module->is_show_on_menu = Constants::publish;

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
