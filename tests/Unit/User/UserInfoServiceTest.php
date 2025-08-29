<?php

namespace Tests\Unit\User;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Helpers\PsTestHelper;
use Modules\Core\Entities\Information\Blog;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Modules\Core\Entities\UserInfo;
use App\Http\Services\PsInfoService;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Information\BlogService;
use Modules\Core\Http\Services\User\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Contracts\Image\ImageServiceInterface;
use Modules\Core\Http\Services\User\UserInfoService;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Http\Services\Image\ImageProcessingService;
use Modules\Core\Http\Services\Utilities\CustomFieldService;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Authorization\UserPermissionService;

class UserInfoServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userInfoService;

    protected $userInfoServiceOriginal;

    protected $psInfoService;

    protected $customFieldServiceInterface;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->psInfoService = Mockery::mock(PsInfoService::class);
        $this->customFieldServiceInterface = Mockery::mock(CustomFieldService::class);

        $this->userInfoService = Mockery::mock(UserInfoService::class, [
            $this->psInfoService,
            $this->customFieldServiceInterface,
        ])->makePartial();

        $this->userInfoServiceOriginal = new UserInfoService(
            $this->psInfoService,
            $this->customFieldServiceInterface,
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->userInfoServiceOriginal);
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
        $user = User::factory()->create();
        $parentId = 1;
        $customField = [
            'ps-usr00001' => 'CityName'
        ];
        $this->psInfoService->shouldReceive('save')
            ->once()
            ->with(Constants::user, $customField, $parentId, UserInfo::class, 'user_id');

        $this->userInfoService->save($parentId, $customField);

        // $customFieldValues = $this->userInfoService->getAll(null, $parentId, null, true, null);
        $customFieldValues = $this->userInfoService->get(null, null, $parentId, 'ps-usr00001');

        $this->assertArrayHasKey('ps-usr00001', $customFieldValues);
    }

    public function test_update()
    {
        $parentId = 123;
        UserInfo::factory()->create([
            'id' => 1,
            'user_id' => $parentId,
            'core_keys_id' => 'ps-usr00001',
            'value' => 'CityName'
        ]);

        $customField = [
            'ps-usr00001' => 'CityName Update'
        ];
        $this->psInfoService->shouldReceive('update')
            ->once()
            ->with(Constants::user, $customField, $parentId, UserInfo::class, 'user_id');

        $this->userInfoService->update($parentId, $customField);

        $customFieldValues = $this->userInfoService->get(null, null, $parentId, 'ps-usr00001');

        $this->assertEquals($customField['ps-usr00001'], $customFieldValues['value']);
    }
}
