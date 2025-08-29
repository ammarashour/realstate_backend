<?php

namespace Tests\Unit\Authorization;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Entities\Role;
use Modules\Core\Http\Services\Authorization\UserPermissionService;
use Tests\TestCase;

class UserPermissionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userPermissionServiceOriginal;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userPermissionServiceOriginal = new UserPermissionService();

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->userPermissionServiceOriginal);

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

        $userPermissionData = (object) [
            'role_id' => $role->id,
            'user_id' => 1,
        ];

        $userPermission = $this->userPermissionServiceOriginal->save($userPermissionData);

        $this->assertEquals($userPermissionData->role_id, $userPermission->role_id);

        // Prepare Data For Fail
        $userPermission = $this->userPermissionServiceOriginal->save(null);
        $this->assertNull($userPermission);
    }

    public function test_get()
    {
        // Prepare Data
        $role = Role::factory([
            'id' => 2,
        ])->create();

        UserPermission::factory([
            'role_id' => $role->id,
            'user_id' => 1,
        ])->create();

        $result = $this->userPermissionServiceOriginal->get(roleId: 2);
        $this->assertEquals(2, $result->role_id);
    }

    public function test_getAll()
    {
        // Prepare Data
        $role = Role::factory([
            'id' => 2,
        ])->create();

        UserPermission::factory([
            'role_id' => $role->id,
            'user_id' => 1,
        ])->count(2)->create();

        $result = $this->userPermissionServiceOriginal->getAll(noPagination: Constants::yes);
        $this->assertCount(2, $result);
    }
}
