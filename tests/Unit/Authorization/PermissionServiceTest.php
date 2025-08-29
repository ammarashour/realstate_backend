<?php

namespace Tests\Unit\Authorization;

use App\Config\ps_constant;
use App\Helpers\PsTestHelper;
use App\Http\Contracts\Authorization\RolePermissionServiceInterface;
use App\Http\Contracts\Authorization\UserPermissionServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Entities\Information\Blog;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Menu\Module;
use Modules\Core\Entities\Role;
use Modules\Core\Entities\Authorization\RolePermission;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Http\Services\Authorization\PermissionService;
use Tests\TestCase;


use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

class PermissionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $permissionServiceOriginal;

    protected $psTestHelper;

    protected $rolePermissionService;

    protected $userPermissionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->rolePermissionService = app(RolePermissionServiceInterface::class);
        $this->userPermissionService = app(UserPermissionServiceInterface::class);

        // Use dependency injection to instantiate the PermissionService
        $this->permissionServiceOriginal = new PermissionService(
            $this->userPermissionService,
            $this->rolePermissionService
        );

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->permissionServiceOriginal);
    }

    ////////////////////////////////////////////////////////////////////
    /// Public Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_checkingForPermissionWithModel()
    {
        // Prepare Data For Success
        $user = User::factory()->create();
        $this->actingAs($user);

        $role = Role::factory()->create([
            'id' => 1,
            'name' => 'Super Admin',
            'status' => 1,
            'added_user_id' => $user->id,
        ]);

        $userPermission = UserPermission::factory()->create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'added_user_id' => $user->id,
        ]);

        $module = Module::factory()->create([
            'id' => Constants::blogModule,
            'added_user_id' => $user->id,
        ]);

        $rolePermission = RolePermission::factory()->create([
            'role_id' => $role->id,
            'module_id' => $module->id,
            'permission_id' => '1,2,3,4',
            'added_user_id' => $user->id,
        ]);

        // Define test parameters for success
        $ability = Constants::viewAnyAbility;
        $model = Blog::class;
        $expectedSuccessResult = null;

        // Call the method
        $successResult = $this->permissionServiceOriginal->CheckingForPermissionWithModel($ability, $model);

        // Prepare Data For Fail
        $rolePermissionForFail = RolePermission::where('id', $rolePermission->id)->first();
        $rolePermissionForFail->permission_id = '';
        $rolePermissionForFail->update();

        // Call the method
        $failResult = $this->permissionServiceOriginal->CheckingForPermissionWithModel($ability, $model);

        $this->assertSame($expectedSuccessResult, $successResult);
        $this->assertNotEmpty($failResult);
    }

    public function test_checkingForPermissionWithoutModel()
    {
        // Prepare Data For Success
        $user = User::factory()->create();
        $this->actingAs($user);

        $role = Role::factory()->create([
            'id' => 1,
            'name' => 'Super Admin',
            'status' => 1,
            'added_user_id' => $user->id,
        ]);

        $userPermission = UserPermission::factory()->create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'added_user_id' => $user->id,
        ]);

        $module = Module::factory()->create([
            'id' => Constants::categoryReportModule,
            'added_user_id' => $user->id,
        ]);

        $rolePermission = RolePermission::factory()->create([
            'role_id' => $role->id,
            'module_id' => $module->id,
            'permission_id' => '1,2,3,4',
            'added_user_id' => $user->id,
        ]);

        // Define test parameters for success
        $moduleId = $module->id;
        $permissionId = ps_constant::readPermission;
        $loginUserId = $user->id;
        $expectedSuccessResult = null;

        // Call the method
        $successResult = $this->permissionServiceOriginal->checkingForPermissionWithoutModel($moduleId, $permissionId, $loginUserId);

        // Prepare Data For Fail
        $rolePermissionForFail = RolePermission::where('id', $rolePermission->id)->first();
        $rolePermissionForFail->permission_id = '';
        $rolePermissionForFail->update();

        // Call the method
        $failResult = $this->permissionServiceOriginal->checkingForPermissionWithoutModel($moduleId, $permissionId, $loginUserId);

        $this->assertSame($expectedSuccessResult, $successResult);
        $this->assertNotEmpty($failResult);
    }

    public function test_checkingForCreateAbilityWithModel()
    {
        // Prepare Data For Success
        $user = User::factory()->create();
        $this->actingAs($user);

        $role = Role::factory()->create([
            'id' => 1,
            'name' => 'Super Admin',
            'status' => 1,
            'added_user_id' => $user->id,
        ]);

        $userPermission = UserPermission::factory()->create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'added_user_id' => $user->id,
        ]);

        $module = Module::factory()->create([
            'id' => Constants::blogModule,
            'added_user_id' => $user->id,
        ]);

        $rolePermission = RolePermission::factory()->create([
            'role_id' => $role->id,
            'module_id' => $module->id,
            'permission_id' => ps_constant::createPermission,
            'added_user_id' => $user->id,
        ]);

        // Define test parameters for success
        $dataArr = [
            'createBlog' => 'create-blog',
        ];

        // Call the method
        $successResult = $this->permissionServiceOriginal->checkingForCreateAbilityWithModel($dataArr);

        // Prepare Data For Fail
        $rolePermissionForFail = RolePermission::where('id', $rolePermission->id)->first();
        $rolePermissionForFail->permission_id = '';
        $rolePermissionForFail->update();

        // Call the method
        $failResult = $this->permissionServiceOriginal->checkingForCreateAbilityWithModel($dataArr);

        $this->assertArrayHasKey('createBlog', $successResult);
        $this->assertTrue($successResult['createBlog']);

        $this->assertArrayHasKey('createBlog', $failResult);
        $this->assertFalse($failResult['createBlog']);
    }

    public function test_checkingForCreateAbilityWithoutModel()
    {
        // Prepare Data For Success
        $user = User::factory()->create();
        $this->actingAs($user);

        $role = Role::factory()->create([
            'id' => 1,
            'name' => 'Super Admin',
            'status' => 1,
            'added_user_id' => $user->id,
        ]);

        $userPermission = UserPermission::factory()->create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'added_user_id' => $user->id,
        ]);

        $module = Module::factory()->create([
            'id' => Constants::categoryReportModule,
            'added_user_id' => $user->id,
        ]);

        $rolePermission = RolePermission::factory()->create([
            'role_id' => $role->id,
            'module_id' => $module->id,
            'permission_id' => ps_constant::createPermission,
            'added_user_id' => $user->id,
        ]);

        // Define test parameters for success
        $dataArr = [
            'createCategoryReport' => Constants::categoryReportModule,
        ];
        $loginUserId = $user->id;

        // Call the method
        $successResult = $this->permissionServiceOriginal->checkingForCreateAbilityWithoutModel($dataArr, $loginUserId);

        // Prepare Data For Fail
        $rolePermissionForFail = RolePermission::where('id', $rolePermission->id)->first();
        $rolePermissionForFail->permission_id = '';
        $rolePermissionForFail->update();

        // Call the method
        $failResult = $this->permissionServiceOriginal->checkingForCreateAbilityWithoutModel($dataArr, $loginUserId);

        $this->assertArrayHasKey('createCategoryReport', $successResult);
        $this->assertTrue($successResult['createCategoryReport']);

        $this->assertArrayHasKey('createCategoryReport', $failResult);
        $this->assertFalse($failResult['createCategoryReport']);
    }

    public function test_permissionControl()
    {
        // Prepare Data For Success
        $user = User::factory()->create();
        $this->actingAs($user);

        $role = Role::factory()->create([
            'id' => 1,
            'name' => 'Super Admin',
            'status' => 1,
            'added_user_id' => $user->id,
        ]);

        $userPermission = UserPermission::factory()->create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'added_user_id' => $user->id,
        ]);

        $module = Module::factory()->create([
            'id' => Constants::categoryReportModule,
            'added_user_id' => $user->id,
        ]);

        $rolePermission = RolePermission::factory()->create([
            'role_id' => $role->id,
            'module_id' => $module->id,
            'permission_id' => ps_constant::createPermission,
            'added_user_id' => $user->id,
        ]);

        // Define test parameters for success
        $moduleId = Constants::categoryReportModule;
        $permissionId = ps_constant::createPermission;
        $loginUserId = $user->id;

        // Call the method
        $successResult = $this->permissionServiceOriginal->permissionControl($moduleId, $permissionId, $loginUserId);

        // Prepare Data For Fail
        $rolePermissionForFail = RolePermission::where('id', $rolePermission->id)->first();
        $rolePermissionForFail->permission_id = ps_constant::deletePermission;
        $rolePermissionForFail->update();

        // Call the method
        $failResult = $this->permissionServiceOriginal->permissionControl($moduleId, $permissionId, $loginUserId);

        // var_dump($successResult, $failResult);

        $this->assertTrue($successResult);
        $this->assertNull($failResult);
    }

    public function test_authorizationWithoutModel()
    {
        // Prepare Data For Success
        $user = User::factory()->create();
        $this->actingAs($user);

        $role = Role::factory()->create([
            'id' => 1,
            'name' => 'Super Admin',
            'status' => 1,
            'added_user_id' => $user->id,
        ]);

        $userPermission = UserPermission::factory()->create([
            'user_id' => $user->id,
            'role_id' => $role->id,
            'added_user_id' => $user->id,
        ]);

        $module = Module::factory()->create([
            'id' => Constants::categoryReportModule,
            'added_user_id' => $user->id,
        ]);

        $rolePermission = RolePermission::factory()->create([
            'role_id' => $role->id,
            'module_id' => $module->id,
            'permission_id' => '1,3,4',
            'added_user_id' => $user->id,
        ]);

        // Define test parameters for success
        $moduleId = Constants::categoryReportModule;
        $loginUserId = $user->id;

        // Call the method
        $successResult = $this->permissionServiceOriginal->authorizationWithoutModel($moduleId, $loginUserId);

        // Prepare Data For Fail
        $rolePermissionForFail = RolePermission::where('id', $rolePermission->id)->first();
        $rolePermissionForFail->delete();

        // Call the method
        $failResult = $this->permissionServiceOriginal->authorizationWithoutModel($moduleId, $loginUserId);

        $this->assertTrue($successResult['create']);
        $this->assertTrue($successResult['update']);
        $this->assertTrue($successResult['delete']);

        $this->assertFalse($failResult['create']);
        $this->assertFalse($failResult['update']);
        $this->assertFalse($failResult['delete']);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_handlePermissionForModule()
    {
        // Define test parameters for success
        $userAccesses = [
            (object) ['permission_id' => '1,2,3']
        ];
        $permissionId = ps_constant::readPermission;

        // Call the method
        $successResult = $this->psTestHelper->invokePrivateMethod('handlePermissionForModule', [$userAccesses, $permissionId]);
        assertTrue($successResult);

        // Define test parameters for fail
        $userAccesses = [];
        $permissionId = ps_constant::readPermission;

        // Call the method
        $failResult = $this->psTestHelper->invokePrivateMethod('handlePermissionForModule', [$userAccesses, $permissionId]);
        assertNull($failResult);
    }
}
