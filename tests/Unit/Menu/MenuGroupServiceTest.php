<?php

namespace Tests\Unit\Menu;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\CoreMenuGroup;
use Modules\Core\Http\Services\Menu\MenuGroupService;
use Tests\TestCase;

class MenuGroupServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $menuGroupService;

    protected $menuGroupServiceOriginal;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->menuGroupService = Mockery::mock(MenuGroupService::class)->makePartial();

        $this->menuGroupServiceOriginal = new MenuGroupService();

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->menuGroupServiceOriginal);
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

        $menuGroupData = [
            'id' => '1',
            'group_name' => 'Test2',
            'group_lang_key' => 'new_group_lang_key2',
            'is_show_on_menu' => 1,
            'is_invisible_group_name' => 0,
            'group_icon' => 'icon', // this field is deleted but not nullable
        ];

        // For Success Case
        $this->menuGroupService->save($menuGroupData);

        $menuGroup = $this->menuGroupService->get(1);

        $this->assertEquals($menuGroupData['group_name'], $menuGroup->group_name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->menuGroupService->shouldReceive('save')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->menuGroupService->save($menuGroupData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $menuGroup = CoreMenuGroup::factory()->create();

        $menuGroupData = [
            'group_name' => 'update',
            'group_lang_key' => 'new_group_lang_key_update',
            'is_show_on_menu' => '1',
            'is_invisible_group_name' => '0',
        ];

        // For Success Case
        $this->menuGroupService->update($menuGroup->id, $menuGroupData);

        $updatedMenuGroup = $this->menuGroupService->get($menuGroup->id);
        $this->assertEquals($menuGroupData['group_name'], $updatedMenuGroup->group_name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->menuGroupService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->menuGroupService->update($menuGroup->id, $menuGroupData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $menuGroup = CoreMenuGroup::factory()->create();

        $result = $this->menuGroupService->delete($menuGroup->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_get()
    {

        $menuGroup = CoreMenuGroup::factory()->create();

        $result = $this->menuGroupService->get($menuGroup->id);

        $this->assertNotNull($result);
        $this->assertEquals($menuGroup->id, $result->id);
        $this->assertEquals($menuGroup->group_name, $result->group_name);
    }

    public function test_getAll()
    {
        $relation = ['owner', 'editor'];
        $row = 5;
        $hideVendor = false;
        $result = $this->menuGroupService->getAll($relation, $row, null, $hideVendor);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('owner', $result[0]);
        $this->assertEquals($row, $result->perPage());
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a menuGroup for testing
        $menuGroup = CoreMenuGroup::factory()->create(['is_show_on_menu' => Constants::publish]);

        // Call the setStatus method
        $result = $this->menuGroupService->setStatus($menuGroup->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(CoreMenuGroup::class, $result);
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

    public function test_saveMenuGroup()
    {

        $menuGroupData = [
            'group_name' => 'Test1',
            'group_lang_key' => 'new_group_lang_key',
            'is_show_on_menu' => '1',
            'is_invisible_group_name' => '0',
            'group_icon' => 'icon', //this field is deleted but not nullable
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveMenuGroup', [$menuGroupData]);

        $this->assertNotNull($result);
        $this->assertEquals($menuGroupData['group_name'], $result->group_name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateMenuGroup()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a menu-group for testing
        $menuGroup = CoreMenuGroup::factory()->create(['is_show_on_menu' => Constants::publish]);

        $menuGroupData = [
            'group_name' => 'Test1 update',
            'group_lang_key' => 'new_group_lang_key_update',
            'is_show_on_menu' => '0',
            'is_invisible_group_name' => '0',
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateMenuGroup', [$menuGroup->id, $menuGroupData]);
        $this->assertNotNull($result);
        $this->assertEquals($menuGroupData['group_name'], $result->group_name);
        $this->assertEquals($menuGroup->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteMenuGroup()
    {
        // Create a Menu group for testing
        $menuGroup = CoreMenuGroup::factory()->create(['is_show_on_menu' => Constants::publish]);

        $menuGroupName = $this->psTestHelper->invokePrivateMethod('deleteMenuGroup', [$menuGroup->id]);
        $this->assertEquals($menuGroup->group_name, $menuGroupName);

        $result = $this->menuGroupService->get($menuGroup->id);
        $this->assertNull($result);
    }
}
