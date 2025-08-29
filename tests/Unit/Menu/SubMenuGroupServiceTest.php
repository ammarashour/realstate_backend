<?php

namespace Tests\Unit\Menu;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Helpers\PsTestHelper;
use Modules\Core\Entities\Project;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Modules\Core\Entities\Menu\CoreSubMenuGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Http\Services\Menu\ModuleService;
use Modules\Core\Http\Services\Menu\SubMenuGroupService;

class SubMenuGroupServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $moduleService;

    protected $subMenuGroupService;

    protected $subMenuGroupServiceOriginal;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->moduleService = Mockery::mock(ModuleService::class);

        $this->subMenuGroupService = Mockery::mock(SubMenuGroupService::class, [
            $this->moduleService
        ])->makePartial();

        $this->subMenuGroupServiceOriginal = new SubMenuGroupService(
            $this->moduleService
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->subMenuGroupServiceOriginal);
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
    public function test_save()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $subMenuGroupData = [
            'id' => 123,
            'sub_menu_name' => 'Test Sub Menu Group',
            'sub_menu_desc' => 'Test Sub Menu Group',
            'icon_id' => '1',
            'sub_menu_lang_key' => 'test_sub_menu_group',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'module_id' => '1',
            'core_menu_group_id' => '5',
            'added_user_id' => '1',
            'is_dropdown' => '1'
        ];

        // For Success Case
        $moduleData = ['sub_menu_id' => 123];
        $this->moduleService->shouldReceive('update')
            ->once()
            ->with($subMenuGroupData['module_id'], $moduleData);

        $this->subMenuGroupService->save($subMenuGroupData);

        $subMenuGroup = $this->subMenuGroupService->get(123);

        $this->assertEquals($subMenuGroupData['sub_menu_name'], $subMenuGroup->sub_menu_name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->subMenuGroupService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->subMenuGroupService->save($subMenuGroupData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $subMenuGroup = CoreSubMenuGroup::factory()->create();

        $subMenuGroupData = [
            'sub_menu_name' => 'Test Sub Menu Group',
            'sub_menu_desc' => 'Test Sub Menu Group',
            'icon_id' => '1',
            'sub_menu_lang_key' => 'test_sub_menu_group',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'module_id' => '1',
            'core_menu_group_id' => '5',
            'added_user_id' => '1',
            'is_dropdown' => '1'
        ];

        // For Success Case
        $oldModuleData = ['sub_menu_id' => 0];
        $this->moduleService->shouldReceive('update')
            ->once()
            ->with($subMenuGroup->module_id, $oldModuleData);

        $moduleData = ['sub_menu_id' => $subMenuGroup->id];
        $this->moduleService->shouldReceive('update')
            ->once()
            ->with($subMenuGroupData['module_id'], $moduleData);

        $this->subMenuGroupService->update($subMenuGroup->id, $subMenuGroupData);

        $updatedModule = $this->subMenuGroupService->get($subMenuGroup->id);
        $this->assertEquals($subMenuGroupData['sub_menu_name'], $updatedModule->sub_menu_name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->subMenuGroupService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->subMenuGroupService->update($subMenuGroup->id, $subMenuGroupData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $subMenuGroup = CoreSubMenuGroup::factory()->create();

        $moduleData = ['sub_menu_id' => 0];
        $this->moduleService->shouldReceive('update')->once()->with(
            $subMenuGroup->module_id,
            $moduleData
        )->andReturn();

        $result = $this->subMenuGroupService->delete($subMenuGroup->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $subMenuGroup = CoreSubMenuGroup::factory()->create();

        $result = $this->subMenuGroupService->get($subMenuGroup->id);

        $this->assertNotNull($result);
        $this->assertEquals($subMenuGroup->id, $result->id);
        $this->assertEquals($subMenuGroup->sub_menu_name, $result->sub_menu_name);
    }

    public function test_getAll()
    {
        Project::factory()->create(['base_project_id' => 11]);
        $relation = ['core_menu_group', 'owner', 'editor'];
        $row = 5;

        $result = $this->subMenuGroupService->getAll($relation, $row, null);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('owner', $result[0]);
        $this->assertArrayHasKey('core_menu_group', $result[0]);
        $this->assertEquals($row, $result->perPage());
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a subMenuGroup for testing
        $subMenuGroup = CoreSubMenuGroup::factory()->create(['is_show_on_menu' => Constants::publish]);

        // Call the setStatus method
        $result = $this->subMenuGroupService->setStatus($subMenuGroup->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(CoreSubMenuGroup::class, $result);
        $this->assertEquals(Constants::unPublish, $result->is_show_on_menu);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////
    public function test_prepareUpdateStausData()
    {
        $status = 1;

        // Assert the expected result
        $expected = ['is_show_on_menu' => $status];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateStausData', [$status]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_prepareModuleData()
    {
        $subMenuId = 1;

        // Assert the expected result
        $expected = ['sub_menu_id' => $subMenuId];

        $result = $this->psTestHelper->invokePrivateMethod('prepareModuleData', [$subMenuId]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_saveSubMenuGroup()
    {

        $subMenuGroupData = [
            'sub_menu_name' => 'Test Sub Menu Group',
            'sub_menu_desc' => 'Test Sub Menu Group',
            'icon_id' => '1',
            'sub_menu_lang_key' => 'test_sub_menu_group',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '5',
            'added_user_id' => '1',
            'is_dropdown' => '1'
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveSubMenuGroup', [$subMenuGroupData]);

        $this->assertNotNull($result);
        $this->assertEquals($subMenuGroupData['sub_menu_desc'], $result->sub_menu_desc);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateSubMenuGroup()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $subMenuGroup = CoreSubMenuGroup::factory()->create(['is_show_on_menu' => Constants::publish]);

        $subMenuGroupData = [
            'sub_menu_name' => 'Test Sub Menu Group',
            'sub_menu_desc' => 'Test Sub Menu Group',
            'icon_id' => '1',
            'sub_menu_lang_key' => 'test_sub_menu_group',
            'ordering' => '1',
            'is_show_on_menu' => '1',
            'core_menu_group_id' => '5',
            'added_user_id' => '1',
            'is_dropdown' => '1'
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateSubMenuGroup', [$subMenuGroup->id, $subMenuGroupData]);
        $this->assertNotNull($result);
        $this->assertEquals($subMenuGroupData['sub_menu_name'], $result->sub_menu_name);
        $this->assertEquals($subMenuGroup->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteSubMenuGroup()
    {
        // Create a Sub Menu group for testing
        $subMenuGroup = CoreSubMenuGroup::factory()->create(['is_show_on_menu' => Constants::publish]);

        $subMenuGroupName = $this->psTestHelper->invokePrivateMethod('deleteSubMenuGroup', [$subMenuGroup->id]);
        $this->assertEquals($subMenuGroup->sub_menu_desc, $subMenuGroupName);

        $result = $this->subMenuGroupService->get($subMenuGroup->id);
        $this->assertNull($result);
    }
}
