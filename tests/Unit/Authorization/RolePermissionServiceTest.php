<?php

namespace Tests\Unit\Authorization;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Authorization\RolePermission;
use Modules\Core\Entities\Menu\Module;
use Modules\Core\Entities\Role;
use Modules\Core\Http\Services\Authorization\RolePermissionService;
use Tests\TestCase;

class RolePermissionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $rolePermissionServiceOriginal;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rolePermissionServiceOriginal = new RolePermissionService();

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->rolePermissionServiceOriginal);
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

        // Prepare Data For Success
        $role = Role::factory([
            'id' => 2,
        ])->create();

        $module = Module::factory([
            'id' => 2,
        ])->create();

        $rolePermissionData = (object) [
            'role_id' => $role->id,
            'module_id' => $module->id,
            'permission_id' => '1,2,3,4',
        ];

        $rolePermission = $this->rolePermissionServiceOriginal->save($rolePermissionData);

        $this->assertEquals($rolePermissionData->role_id, $rolePermission->role_id);

        // Prepare Data For Fail
        $rolePermission = $this->rolePermissionServiceOriginal->save(null);
        $this->assertNull($rolePermission);
    }

    public function test_deleteAll()
    {
        // Prepare Data
        $role = Role::factory([
            'id' => 2,
        ])->create();

        $module = Module::factory([
            'id' => 2,
        ])->create();

        $rolePermissions = RolePermission::factory([
            'role_id' => $role->id,
            'module_id' => $module->id,
        ])->count(2)->create();

        // Get the IDs of the created records
        $ids = $rolePermissions->pluck('id');

        // Ensure the records exist in the database
        $this->assertDatabaseHas(RolePermission::tableName, ['id' => $ids[0]]);
        $this->assertDatabaseHas(RolePermission::tableName, ['id' => $ids[1]]);

        // Call the deleteAll function
        $this->rolePermissionServiceOriginal->deleteAll($ids);

        // Ensure the records have been deleted
        $this->assertDatabaseMissing(RolePermission::tableName, ['id' => $ids[0]]);
        $this->assertDatabaseMissing(RolePermission::tableName, ['id' => $ids[1]]);
    }

    public function test_getAll()
    {
        // Prepare Data
        $role = Role::factory([
            'id' => 2,
        ])->create();

        $module = Module::factory([
            'id' => 2,
        ])->create();

        RolePermission::factory([
            'role_id' => $role->id,
            'module_id' => $module->id,
        ])->count(2)->create();

        $result = $this->rolePermissionServiceOriginal->getAll(noPagination: Constants::yes);
        $this->assertCount(2, $result);
    }
}
