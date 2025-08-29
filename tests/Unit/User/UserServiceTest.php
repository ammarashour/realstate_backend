<?php

namespace Tests\Unit\User;

use Mockery;
use Tests\TestCase;
use App\Models\User;
use App\Helpers\PsTestHelper;
use Modules\Core\Entities\Information\Blog;
use Illuminate\Http\Testing\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Information\BlogService;
use Modules\Core\Http\Services\User\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Contracts\Image\ImageServiceInterface;
use Modules\Core\Http\Services\User\UserInfoService;
use Modules\Core\Entities\Authorization\UserPermission;
use Modules\Core\Http\Services\Image\ImageProcessingService;
use Modules\Core\Http\Services\CoreFieldFilterSettingService;
use Modules\Core\Http\Services\Authorization\UserPermissionService;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;

    protected $userServiceOriginal;

    protected $imageService;

    protected $imageProcessingService;

    protected $userInfoService;

    protected $userPermissionService;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->imageService = Mockery::mock(ImageServiceInterface::class);
        $this->imageProcessingService = Mockery::mock(ImageProcessingService::class);
        $this->userInfoService = Mockery::mock(UserInfoService::class);
        $this->userPermissionService = Mockery::mock(UserPermissionService::class);

        $this->userService = Mockery::mock(UserService::class, [
            $this->imageService,
            $this->imageProcessingService,
            $this->userInfoService,
            $this->userPermissionService,
        ])->makePartial();

        $this->userServiceOriginal = new UserService(
            $this->imageService,
            $this->imageProcessingService,
            $this->userInfoService,
            $this->userPermissionService,
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->userServiceOriginal);
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

        $userData = [
            'id' => 123,
            'name' => 'Joey',
            'email' => 'joey@gmail.com',
            'password' => 'user1234',
            'status' => 1,
            'role_id' => 2,
        ];

        $userCoverPhoto = UploadedFile::fake()->image('test-image.jpg');
        $fileName = newFileName($userCoverPhoto);
        $relationalData = [];
        $userPermission = new \stdClass();
        $userPermission->user_id = 123;
        $userPermission->role_id = 2;
        $resolutions = ['1x', '2x', '3x', 'original'];

        // For Success Case
        $this->userInfoService->shouldReceive('save')
            ->once()
            ->with(123, $relationalData);

        $this->userPermissionService->shouldReceive('save')
            ->once()
            ->with(Mockery::on(function ($userPermission) {
                return !is_array($userPermission);
            }));

        $this->imageProcessingService->shouldReceive('createImageFiles')
            ->twice()
            ->with($userCoverPhoto, Mockery::on(function ($fileName) {
                return !is_array($fileName);
            }), 'profile', $resolutions)
            ->andReturn();

        $this->userService->save($userData, $userCoverPhoto, $relationalData);

        $user = $this->userService->get(123);
        $this->assertEquals($userData['name'], $user->name);

        // For Exception Case
        $errorMessage = 'Error Message!';
        // $this->userInfoService->shouldReceive('save')
        //     ->once()
        //     ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->userService->save($userData, $userCoverPhoto, $relationalData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_update()
    {
        // Simulate user authentication
        $this->actingAs($this->user);
        $user = User::factory()->create();

        $userData = [
            'name' => 'New Name',
            'email' => 'joeyupdate@gmail.com',
            'password' => null,
            'status' => 1,
            'role_id' => 2,
        ];

        $userCoverPhoto = UploadedFile::fake()->image('test-image.jpg');
        $fileName = newFileName($userCoverPhoto);
        $relationalData = [];
        $userPermission = new \stdClass();
        $userPermission->user_id = $user->id;
        $userPermission->role_id = 2;
        $resolutions = ['1x', '2x', '3x', 'original'];

        // For Success Case
        $this->userInfoService->shouldReceive('update')
            ->once()
            ->with($user->id, $relationalData);

        $this->userPermissionService->shouldReceive('update')
            ->once()
            ->with(null, $user->id, Mockery::on(function ($userPermission) {
                return !is_array($userPermission);
            }));

        $this->imageProcessingService->shouldReceive('deleteImageFile')
            ->once()
            ->with($user->user_cover_photo);

        $this->imageProcessingService->shouldReceive('createImageFiles')
            ->once()
            ->with($userCoverPhoto, Mockery::on(function ($fileName) {
                return !is_array($fileName);
            }), 'profile', $resolutions)
            ->andReturn();

        $this->userService->update($user->id, $userData, $userCoverPhoto, $relationalData);

        $updatedBlog = $this->userService->get($user->id);
        $this->assertEquals($userData['name'], $updatedBlog->name);

        // For Exception Case
        $errorMessage = 'Error Message!';

        $this->expectException(\Exception::class);

        $result = $this->userService->update($user->id, $userData, $userCoverPhoto, $relationalData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_delete()
    {

        $user = User::factory()->create();
        $this->imageProcessingService->shouldReceive('deleteImageFile')
            ->once()
            ->with($user->user_cover_photo);

        $this->userInfoService->shouldReceive('deleteAll')
            ->once()
            ->with($user->id);

        $this->userPermissionService->shouldReceive('delete')
            ->once()
            ->with(null, $user->id);

        $result = $this->userService->delete($user->id);

        $this->assertNotNull($result);
        $this->assertEquals('success', $result['flag']);
        $this->assertArrayHasKey('msg', $result);
    }

    public function test_setStatus()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a user for testing
        $user = User::factory()->create(['status' => Constants::publish]);

        // Call the setStatus method
        $result = $this->userService->setStatus($user->id, Constants::unPublish);

        // Assertions
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals(Constants::unPublish, $result->status);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_ban()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a user for testing
        $user = User::factory()->create(['is_banned' => Constants::unBan]);

        // Call the ban method
        $result = $this->userService->ban($user->id, Constants::Ban);

        // Assertions
        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals(Constants::Ban, $result->is_banned);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_replaceImage()
    {
        $this->actingAs($this->user);
        $user = User::factory()->create();
        $userCoverPhoto = UploadedFile::fake()->image('test-image.jpg');
        $fileName = "new_file_name.jpg";
        $resolutions = ['1x', '2x', '3x', 'original'];

        $this->imageProcessingService->shouldReceive('deleteImageFile')
            ->once()
            ->with($user->user_cover_photo);

        $this->imageProcessingService->shouldReceive('createImageFiles')
            ->once()
            ->with($userCoverPhoto, Mockery::on(function ($fileName) {
                return !is_array($fileName);
            }), 'profile', $resolutions)
            ->andReturn($fileName);

        $this->userService->replaceImage($user->id, $userCoverPhoto);

        $updatedUser = $this->userService->get($user->id);
        $this->assertNotEquals($user->user_cover_photo, $updatedUser->user_cover_photo);
        $this->assertNotNull($updatedUser->user_cover_photo);
    }

    public function test_deleteImage()
    {
        $this->actingAs($this->user);
        $user = User::factory()->create();

        $this->imageProcessingService->shouldReceive('deleteImageFile')
            ->once()
            ->with($user->user_cover_photo);

        $this->userService->deleteImage($user->id);

        $updatedUser = $this->userService->get($user->id);
        $this->assertNull($updatedUser->user_cover_photo);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareUpdateStausData()
    {
        $status = 1;

        // Assert the expected result
        $expected = ['status' => $status];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateStausData', [$status]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_prepareUpdateBanData()
    {
        $isBanned = Constants::Ban;

        // Assert the expected result
        $expected = ['is_banned' => $isBanned];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateBanData', [$isBanned]);

        $this->assertNotNull($result);
        $this->assertEquals($expected, $result);
    }

    public function test_prepareUpdateUserData()
    {
        $userData = [
            'password' => 'admin123'
        ];

        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateUserData', [$userData]);

        $this->assertNotNull($result);
        $this->assertTrue(Hash::check('admin123', $result['password']));
    }

    public function test_prepareUserPermissionData()
    {
        $user = User::factory()->create(['role_id' => 2]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareUserPermissionData', [$user]);

        $this->assertNotNull($result);
        $this->assertEquals($user->id, $result->user_id);
        $this->assertEquals($user->role_id, $result->role_id);
    }

    public function test_saveUser()
    {

        $usreData = [
            'name' => 'Joey',
            'role_id' => 2,
            'password' => 'user1234',
        ];

        // Simulate user authentication
        $this->actingAs($this->user);

        $result = $this->psTestHelper->invokePrivateMethod('saveUser', [$usreData]);

        $this->assertNotNull($result);
        $this->assertEquals($usreData['name'], $result->name);
        $this->assertEquals($this->user->id, $result->added_user_id);
    }

    public function test_updateUser()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Create a user for testing
        $user = User::factory()->create([
            'status' => Constants::publish,
            'role_id' => 2
        ]);

        $userData = [
            'name' => 'New Name',
            'status' => Constants::unpublished,
            'role_id' => 3,
        ];

        $result = $this->psTestHelper->invokePrivateMethod('updateUser', [$user->id, $userData]);
        $this->assertNotNull($result);
        $this->assertEquals($userData['name'], $result->name);
        $this->assertEquals($user->id, $result->id);
        $this->assertEquals($this->user->id, $result->updated_user_id);
    }

    public function test_deleteUser()
    {
        // Create a user for testing
        $user = User::factory()->create(['status' => Constants::publish]);

        $this->imageProcessingService->shouldReceive('deleteImageFile')
            ->once()
            ->with($user->user_cover_photo)
            ->andReturn();

        $userName = $this->psTestHelper->invokePrivateMethod('deleteUser', [$user->id]);
        $this->assertEquals($user->name, $userName);

        $result = $this->userService->get($user->id);
        $this->assertNull($result);
    }

    public function test_saveProfilePhoto()
    {
        $user = User::factory()->create();

        $userCoverPhoto = UploadedFile::fake()->image('test-image.jpg');
        $fileName = newFileName($userCoverPhoto);

        $this->imageProcessingService->shouldReceive('deleteImageFile')
            ->once()
            ->with($user->user_cover_photo)
            ->andReturn();

        $resolutions = ['1x', '2x', '3x', 'original'];

        $this->imageProcessingService->shouldReceive('createImageFiles')
            ->once()
            ->with($userCoverPhoto, Mockery::on(function ($fileName) {
                return !is_array($fileName);
            }), 'profile', $resolutions)
            ->andReturn($fileName);

        $result = $this->psTestHelper->invokePrivateMethod('saveProfilePhoto', [$user->id, $userCoverPhoto]);

        $this->assertNotNull($result);
        $this->assertIsString($result);
    }
}
