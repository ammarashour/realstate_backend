<?php

namespace Tests\Unit\User;

use App\Helpers\PsTestHelper;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Controllers\Backend\Controllers\User\UserController;
use Modules\Core\Http\Requests\UpdateUserImageRequest;
use Modules\Core\Http\Requests\User\StoreUserRequest;
use Modules\Core\Http\Requests\User\UpdateUserRequest;
use Modules\Core\Http\Services\RoleService;
use Modules\Core\Http\Services\User\UserService;
use Modules\Core\Http\Services\Utilities\CoreFieldService;
use Modules\Core\Http\Services\Utilities\CustomFieldAttributeService;
use Modules\Core\Http\Services\Utilities\CustomFieldService;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $userController;

    protected $userControllerOriginal;

    protected $userService;

    protected $roleService;

    protected $coreFieldService;

    protected $customFieldService;

    protected $customFieldAttributeService;

    protected $storeUserRequest;

    protected $updateUserRequest;

    protected $updateUserImageRequest;

    protected $request;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        // Init Service Mocks
        $this->userService = Mockery::mock(UserService::class);
        $this->roleService = Mockery::mock(RoleService::class);
        $this->coreFieldService = Mockery::mock(CoreFieldService::class);
        $this->customFieldService = Mockery::mock(CustomFieldService::class);
        $this->customFieldAttributeService = Mockery::mock(CustomFieldAttributeService::class);

        // Mock UserRequest
        $this->storeUserRequest = Mockery::mock(StoreUserRequest::class);
        $this->updateUserRequest = Mockery::mock(UpdateUserRequest::class);
        $this->updateUserImageRequest = Mockery::mock(UpdateUserImageRequest::class);
        $this->request = Mockery::mock(Request::class);

        // Create a partial mock of the UserController to mock the handlePermission method
        $this->userController = Mockery::mock(UserController::class, [
            $this->userService,
            $this->roleService,
            $this->coreFieldService,
            $this->customFieldService,
            $this->customFieldAttributeService,
        ])->makePartial();

        $this->userControllerOriginal = new UserController(
            $this->userService,
            $this->roleService,
            $this->coreFieldService,
            $this->customFieldService,
            $this->customFieldAttributeService,
        );

        // For Private Method Access
        $this->psTestHelper = new PsTestHelper($this->userControllerOriginal);

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
        // Simulate a file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        $this->storeUserRequest->shouldReceive('validated')->twice()->andReturn([
            'name' => 'Updated Name',
            'email' => 'user@gmail.com',
            'role_id' => 'user@gmail.com',
            'user_relation' => [],
        ]);

        $this->storeUserRequest->shouldReceive('input')
            ->with('user_relation', [])
            ->andReturn([]);

        $this->storeUserRequest->shouldReceive('file')
            ->with('user_cover_photo')
            ->andReturn($file);

        $this->storeUserRequest->shouldReceive('allFiles')
            ->with()
            ->andReturn();

        // Mock userService
        $this->userService->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->userController->store($this->storeUserRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->userService->shouldReceive('save')->once()->andReturn([]);

        $response = $this->userController->store($this->storeUserRequest);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_update()
    {
        // Simulate a file upload
        $file = UploadedFile::fake()->image('test_image.jpg');

        // Mock StoreBlogRequest
        $this->updateUserRequest->shouldReceive('validated')->twice()->andReturn([
            'id' => 1,
            'name' => 'Updated Name',
            'email' => 'user@gmail.com',
            'role_id' => 'user@gmail.com',
            'user_relation' => [],
        ]);

        $this->updateUserRequest->shouldReceive('file')
            ->with('user_cover_photo') // Replace with your actual file input name/key
            ->andReturn($file);

        $this->updateUserRequest->shouldReceive('input')
            ->with('user_relation', []) // Replace with your actual file input name/key
            ->andReturn([]);

        $this->updateUserRequest->shouldReceive('allFiles')
            ->with()
            ->andReturn();

        // Mock userService
        $this->userService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        /**
         * Testing Store Method with Error Blog Service Save
         */
        // Simulate a POST request to the store method
        $response = $this->userController->update($this->updateUserRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with success Case
         */
        $this->userService->shouldReceive('update')->once()->andReturn([]);

        $response = $this->userController->update($this->updateUserRequest, 1);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

    }

    public function test_destroy()
    {
        // Create a user and a user for testing
        $user = User::factory()->create();

        // Mock BlogService
        $this->userService->shouldReceive('get')->once()->with($user->id)->andReturn($user);

        // Ensure handlePermission does nothing
        $this->userController->shouldReceive('handlePermissionWithModel')
            ->with($user, Constants::deleteAbility);

        $this->userService->shouldReceive('delete')->once()->with($user->id)->andReturn([
            'msg' => 'User deleted successfully.',
            'flag' => 'success',
        ]);

        // Call the destroy method
        $response = $this->userController->destroy($user->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);

        // Assert that the session has the expected status message
        $this->assertEquals('User deleted successfully.', session('status')['msg']);
        $this->assertEquals('success', session('status')['flag']);
    }

    public function test_statusChange()
    {
        $user = User::factory()->create(['status' => Constants::publish]);

        $this->userService->shouldReceive('get')->once()
            ->andReturn($user);

        $this->userController->shouldReceive('handlePermissionWithModel')
            ->with($user, Constants::editAbility);

        $this->userService->shouldReceive('setStatus')->once()
            ->with($user->id, Constants::unPublish);

        $response = $this->userController->statusChange($user->id);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('The status has been updated successfully.', session('status')['msg']);
    }

    public function test_ban()
    {
        $user = User::factory()->create(['is_banned' => Constants::unBan]);

        $this->userService->shouldReceive('get')->once()
            ->andReturn($user);

        $this->userController->shouldReceive('handlePermissionWithModel')
            ->with($user, Constants::editAbility);

        $this->userService->shouldReceive('ban')->once()
            ->with($user->id, Constants::Ban);

        $response = $this->userController->ban($user->id);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('success', session('status')['flag']);
        $this->assertEquals('The status has been updated successfully.', session('status')['msg']);
    }

    public function test_profileUpdate()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('test_image.jpg');

        $this->updateUserRequest->shouldReceive('validated')->once()->andReturn([
            'name' => 'Normal User',
            'username' => 'user123',
            'password' => 'admin123',
            'confirm_password' => 'admin123',
        ]);

        $this->updateUserRequest->shouldReceive('file')
            ->with('user_cover_photo')
            ->andReturn($file);

        $response = $this->userController->profileUpdate($this->updateUserRequest, $user->id);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_replaceImage()
    {
        $user = User::factory()->create();

        $file = UploadedFile::fake()->image('test_image.jpg');

        $this->updateUserImageRequest->shouldReceive('file')
            ->with('image')
            ->andReturn($file);

        $this->userService->shouldReceive('replaceImage')
            ->once()
            ->with($user->id, $this->updateUserImageRequest->file('image'))
            ->andReturn([]);

        $response = $this->userController->replaceImage($this->updateUserImageRequest, $user->id);

        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_deleteImage()
    {
        $user = User::factory()->create();

        $this->userService->shouldReceive('deleteImage')
            ->once()
            ->with($user->id)
            ->andReturn(null);

        // Call the destroy method
        $response = $this->userController->deleteImage($user->id);

        // Assert that the response is a RedirectResponse
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////
    public function test_prepareIndexData()
    {
        $inputs = [
            'search' => 'keyword',
            'sort_field' => 'name',
            'sort_order' => 'desc',
            'role_filter' => 'all',
            'date_filter' => 'all',
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
            'role_id' => $inputs['role_filter'] == 'all' ? null : $inputs['role_filter'],
            'date_range' => $inputs['date_filter'] == 'all' ? null : $inputs['date_filter'],
        ];

        $relations = ['role', 'userRelation.uiType', 'userRelation.customizeUi', 'userRelation'];

        $this->userService->shouldReceive('getAll')
            ->once()
            ->with($relations, null, false, $conds, null, null, null, false, $inputs['row'])
            ->andReturn([]);

        $this->roleService->shouldReceive('getRoles')
            ->once()
            ->with()
            ->andReturn([]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        // Assertions
        $this->assertEquals('keyword', $result['search']);

        $this->assertArrayHasKey('users', $result);
        $this->assertArrayHasKey('roles', $result);
        $this->assertArrayHasKey('selectedRole', $result);
        $this->assertArrayHasKey('selectedDate', $result);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $result);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $result);
        $this->assertArrayHasKey('sort_field', $result);
        $this->assertArrayHasKey('sort_order', $result);
        $this->assertArrayHasKey('search', $result);
    }

    public function test_prepareCreateData()
    {
        $this->roleService->shouldReceive('getRoles')
            ->once()
            ->with()
            ->andReturn(null);

        $this->customFieldService->shouldReceive('getAll')
            ->once()
            ->with(null, null, true, null, null, null, null, null, null, 0, null, Constants::user)
            ->andReturn(collect());

        $this->customFieldAttributeService->shouldReceive('getAll')
            ->once()
            ->with(null, null, null, [])
            ->andReturn(null);

        $this->coreFieldService->shouldReceive('getAll')
            ->once()
            ->with(Constants::user,
                null, null, null, 0, 1)
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCreateData', []);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('roles', $result);
        $this->assertArrayHasKey('customizeHeaders', $result);
        $this->assertArrayHasKey('customizeDetails', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
    }

    public function test_prepareEditData()
    {
        $user = User::factory()->create(['role_id' => '2']);
        $dataWithRelation = ['userRelation'];
        $this->userService->shouldReceive('get')
            ->once()
            ->with($user->id, null, $dataWithRelation)
            ->andReturn($user);

        $this->roleService->shouldReceive('getRoles')
            ->once()
            ->with()
            ->andReturn(null);

        $this->coreFieldService->shouldReceive('getAll')
            ->once()
            ->with(Constants::user,
                null, null, null, 0, 1)
            ->andReturn(null);

        $this->customFieldService->shouldReceive('getAll')
            ->once()
            ->with(null, null, true, null, null, null, null, null, null, 0, null, Constants::user)
            ->andReturn(collect());

        $this->customFieldAttributeService->shouldReceive('getAll')
            ->once()
            ->with(null, null, null, [])
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareEditData', [$user->id]);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('roles', $result);
        $this->assertArrayHasKey('customizeHeaders', $result);
        $this->assertArrayHasKey('customizeDetails', $result);
        $this->assertArrayHasKey('coreFieldFilterSettings', $result);
        $this->assertArrayHasKey('validation', $result);
    }

    public function test_prepareStatusData()
    {
        $user = User::factory()->create(['status' => '1']);

        $result = $this->psTestHelper->invokePrivateMethod('prepareStatusData', [$user]);

        $this->assertNotNull($result);
        $this->assertEquals(0, $result);
    }

    public function test_prepareBanData()
    {
        $user = User::factory()->create(['is_banned' => '0']);

        $result = $this->psTestHelper->invokePrivateMethod('prepareBanData', [$user]);

        $this->assertNotNull($result);
        $this->assertEquals(1, $result);
    }

    public function test_prepareCustomFieldsData()
    {
        // Simulate a file upload
        $this->storeUserRequest->shouldReceive('allFiles')->once()->andReturn([
            'user_relation' => [],
        ]);

        $this->storeUserRequest->shouldReceive('input')->once()->andReturn([
            'user_relation' => [],
        ]);

        $result = $this->psTestHelper->invokePrivateMethod('prepareCustomFieldsData', [$this->storeUserRequest]);

        $this->assertNotNull($result);
        $this->assertArrayHasKey('user_relation', $result);
    }

    public function test_prepareProfileEditData()
    {
        $user = User::factory()->create(['role_id' => '2']);
        $this->userService->shouldReceive('get')
            ->once()
            ->with($user->id)
            ->andReturn($user);

        $this->roleService->shouldReceive('getRoles')
            ->once()
            ->with()
            ->andReturn(null);

        $result = $this->psTestHelper->invokePrivateMethod('prepareProfileEditData', [$user->id]);

        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('roles', $result);
        $this->assertArrayHasKey('yourPermissions', $result);
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
