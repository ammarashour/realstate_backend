<?php

namespace Tests\Unit\Configuration;

use App\Config\ps_constant;
use App\Helpers\PsTestHelper;
use App\Http\Contracts\Authorization\PermissionServiceInterface;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Configuration\TableFieldServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Utilities\DynamicColumnVisibilityServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\Utilities\CoreField;
use Modules\Core\Entities\Utilities\CustomField;
use Modules\Core\Http\Controllers\Backend\Controllers\Configuration\TableFieldController;
use Modules\Core\Http\Requests\Utilities\StoreCustomFieldRequest;
use Modules\Core\Http\Requests\Utilities\UpdateCoreFieldRequest;
use Modules\Core\Http\Requests\Utilities\UpdateCustomFieldRequest;
use Modules\Core\Http\Services\CoreKeyCounterService;
use Modules\Core\Http\Services\Localization\BeLanguageStringService;
use Modules\Core\Http\Services\Localization\LanguageService;
use Modules\Core\Http\Services\ProjectService;
use Modules\Core\Http\Services\TableService;
use Tests\TestCase;

class TableFieldControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $tableFieldController;

    protected $tableFieldControllerOriginal;

    protected $coreFieldService;

    protected $customFieldService;

    protected $categoryService;

    protected $projectService;

    protected $languageService;

    protected $dynamicColumnVisibilityService;

    protected $coreKeyCounterService;

    protected $tableService;

    protected $tableFieldSerivce;

    protected $user;

    protected $languageStringService;

    protected $permissionService;

    protected $psTestHelper;

    protected $request;

    protected $storeCustomFieldRequest;

    protected $updateCustomFieldRequest;

    protected $updateCoreFieldRequest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = Mockery::mock(Request::class);
        $this->storeCustomFieldRequest = Mockery::mock(StoreCustomFieldRequest::class);
        $this->updateCustomFieldRequest = Mockery::mock(UpdateCustomFieldRequest::class);
        $this->updateCoreFieldRequest = Mockery::mock(UpdateCoreFieldRequest::class);

        $this->permissionService = Mockery::mock(PermissionServiceInterface::class);

        $this->languageStringService = Mockery::mock(BeLanguageStringService::class);

        $this->coreFieldService = Mockery::mock(CoreFieldServiceInterface::class);

        $this->customFieldService = Mockery::mock(CustomFieldServiceInterface::class);

        $this->categoryService = Mockery::mock(CategoryServiceInterface::class);

        $this->projectService = Mockery::mock(ProjectService::class);

        $this->languageService = Mockery::mock(LanguageService::class);

        $this->dynamicColumnVisibilityService = Mockery::mock(DynamicColumnVisibilityServiceInterface::class);

        $this->coreKeyCounterService = Mockery::mock(CoreKeyCounterService::class);

        $this->tableService = Mockery::mock(TableService::class);

        $this->tableFieldSerivce = Mockery::mock(TableFieldServiceInterface::class);

        $this->tableFieldController = Mockery::mock(TableFieldController::class, [
            $this->languageStringService,
            $this->tableService,
            $this->languageService,
            $this->tableFieldSerivce,
            $this->categoryService,
            $this->customFieldService,
            $this->coreFieldService,
        ])->makePartial();

        $this->tableFieldControllerOriginal = new TableFieldController(
            $this->languageStringService,
            $this->tableService,
            $this->languageService,
            $this->tableFieldSerivce,
            $this->categoryService,
            $this->customFieldService,
            $this->coreFieldService
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->tableFieldControllerOriginal);

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
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;

        $customFieldData = [
            'name' => 'loc00001_name',
            'placeholder' => 'loc00001_placeholder',
            'ui_type_id' => 'uit00001',
            'mandatory' => 0,
            'nameForm' => [],
            'placeholderForm' => [],
            'is_show_sorting' => 1,
            'is_show_in_filter' => 1,
            'ordering' => 1,
            'enable' => 1,
            'is_delete' => 0,
            'table_id' => 1,
            'project_id' => 1,
            'project_name' => 'Testing',
            'is_include_in_hideshow' => 1,
            'is_show' => 1,
            'is_core_field' => 0,
            'permission_for_enable_disable' => 0,
            'permission_for_delete' => 0,
            'permission_for_mandatory' => 0,
            'category_id' => null,
            'added_user_id' => 1,
            'updated_user_id' => 1,
        ];
        $generatedData = [
            'flag' => 'success',
            'name_key' => 'name_key_value',
            'placeholder_key' => 'placeholder_key_value',
            'core_keys_id' => 'loc00001',
            'module_name' => 'loc',
            'base_module_name' => 'ps-loc',
            'data_type' => 'string',
        ];

        // Mocking
        $this->storeCustomFieldRequest->shouldReceive('all')->andReturn($customFieldData);

        $this->storeCustomFieldRequest->shouldReceive('validated')
            ->twice()
            ->andReturn($customFieldData);

        $this->storeCustomFieldRequest->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->tableFieldSerivce->shouldReceive('generateCoreKeysId')
            ->twice()
            ->with($table, $customFieldData['ui_type_id'])
            ->andReturn($generatedData);

        $this->customFieldService->shouldReceive('get')
            ->twice()->with(null, null, null, $generatedData['core_keys_id'])
            ->andReturn(null);

        $this->tableFieldSerivce->shouldReceive('save')
            ->once()
            ->with(
                $customFieldData,
                $table,
                $generatedData
            );

        /**
         * Testing Store Method with success Case
         */

        // Call the method
        $response = $this->tableFieldController->store($this->storeCustomFieldRequest, $table);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing Store Method with fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('save')->once()->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->store($this->storeCustomFieldRequest, $table);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_updateCoreField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $validData = ['field_name' => 'test_field'];

        // Mocking
        $this->updateCoreFieldRequest->shouldReceive('validated')
            ->twice()
            ->andReturn($validData);

        $this->updateCoreFieldRequest->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->updateCoreFieldRequest->shouldReceive('route')
            ->with('field')
            ->andReturn($id);

        $this->tableFieldSerivce->shouldReceive('updateCoreField')
            ->once()
            ->with($id, $validData);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->updateCoreField($this->updateCoreFieldRequest, $table, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('updateCoreField')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->updateCoreField($this->updateCoreFieldRequest, $table, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_updateCustomField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $validData = ['field_name' => 'test_field'];

        // Mocking
        $this->updateCustomFieldRequest->shouldReceive('validated')
            ->twice()
            ->andReturn($validData);

        $this->updateCustomFieldRequest->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->updateCustomFieldRequest->shouldReceive('route')
            ->with('field')
            ->andReturn($id);

        $this->tableFieldSerivce->shouldReceive('updateCustomField')
            ->once()
            ->with($id, $validData);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->updateCustomField($this->updateCustomFieldRequest, $table, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('updateCustomField')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->updateCustomField($this->updateCustomFieldRequest, $table, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_deleteCoreField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;

        $dataArr = [
            'msg' => 'Success Message',
            'flag' => Constants::success,
        ];

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::deletePermission, $this->user->id);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->tableFieldSerivce->shouldReceive('deleteCoreField')
            ->once()
            ->with($id)
            ->andReturn($dataArr);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->deleteCoreField($this->request, 1, $id);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('deleteCoreField')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->deleteCoreField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_deleteCustomField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;

        $dataArr = [
            'msg' => 'Success Message',
            'flag' => Constants::success,
        ];

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::deletePermission, $this->user->id);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->tableFieldSerivce->shouldReceive('deleteCustomField')
            ->once()
            ->with($id)
            ->andReturn($dataArr);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->deleteCustomField($this->request, 1, $id);

        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('deleteCustomField')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->deleteCustomField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_enableChangeCoreField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $coreField = CoreField::factory()->create(['enable' => 0]);

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::updatePermission, $this->user->id);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->coreFieldService->shouldReceive('get')
            ->with($id)
            ->andReturn($coreField);

        // $this->tableFieldController->shouldReceive('prepareEnableData')
        //     ->with($coreField)
        //     ->andReturn($enable);

        $this->tableFieldSerivce->shouldReceive('setCoreFieldEnable')
            ->once()
            ->with($id, Constants::enable);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->enableChangeCoreField($this->request, 1, $id);
        $msg = __('core__be_status_updated');
        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertEquals($msg, $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('setCoreFieldEnable')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->enableChangeCoreField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_enableChangeCustomField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $customField = CustomField::factory()->create(['enable' => 0]);

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::updatePermission, $this->user->id);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->customFieldService->shouldReceive('get')
            ->with($id)
            ->andReturn($customField);

        $this->tableFieldSerivce->shouldReceive('setCustomFieldEnable')
            ->once()
            ->with($id, Constants::enable);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->enableChangeCustomField($this->request, 1, $id);
        $msg = __('core__be_status_updated');
        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertEquals($msg, $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('setCustomFieldEnable')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->enableChangeCustomField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_isShowSortingChangeCoreField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $coreField = CoreField::factory()->create(['is_show_sorting' => 0]);

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::updatePermission, $this->user->id);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->coreFieldService->shouldReceive('get')
            ->with($id)
            ->andReturn($coreField);

        $this->tableFieldSerivce->shouldReceive('setCoreFieldIsShowSorting')
            ->once()
            ->with($id, Constants::publish);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->isShowSortingChangeCoreField($this->request, 1, $id);
        $msg = __('core__be_status_updated');
        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertEquals($msg, $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('setCoreFieldIsShowSorting')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->isShowSortingChangeCoreField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_isShowSortingChangeCustomField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $customField = CustomField::factory()->create(['is_show_sorting' => 0]);

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::updatePermission, $this->user->id);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->customFieldService->shouldReceive('get')
            ->with($id)
            ->andReturn($customField);

        $this->tableFieldSerivce->shouldReceive('setCustomFieldIsShowSorting')
            ->once()
            ->with($id, Constants::publish);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->isShowSortingChangeCustomField($this->request, 1, $id);
        $msg = __('core__be_status_updated');
        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertEquals($msg, $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('setCustomFieldIsShowSorting')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->isShowSortingChangeCustomField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_mandatoryChangeCoreField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $coreField = CoreField::factory()->create(['mandatory' => 0]);

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::updatePermission, $this->user->id);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->coreFieldService->shouldReceive('get')
            ->with($id)
            ->andReturn($coreField);

        $this->tableFieldSerivce->shouldReceive('setCoreFieldMandatory')
            ->once()
            ->with($id, Constants::publish);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->mandatoryChangeCoreField($this->request, 1, $id);
        $msg = __('core__be_status_updated');
        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertEquals($msg, $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('setCoreFieldMandatory')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->mandatoryChangeCoreField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_mandatoryChangeCustomField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $customField = CustomField::factory()->create(['mandatory' => 0]);

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::updatePermission, $this->user->id);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->customFieldService->shouldReceive('get')
            ->with($id)
            ->andReturn($customField);

        $this->tableFieldSerivce->shouldReceive('setCustomFieldMandatory')
            ->once()
            ->with($id, Constants::publish);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->mandatoryChangeCustomField($this->request, 1, $id);
        $msg = __('core__be_status_updated');
        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertEquals($msg, $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('setCustomFieldMandatory')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->mandatoryChangeCustomField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_eyeStatusChangeCoreField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $eyeStatusData = [
            'id' => $id,
            'isIncluded' => 1,
            'isShow' => 1,
            'isShowInFilter' => 1,
        ];

        $eyeStatus = [
            'id' => $eyeStatusData['id'],
            'is_include_in_hideshow' => $eyeStatusData['isIncluded'],
            'is_show' => $eyeStatusData['isShow'],
            'is_show_in_filter' => $eyeStatusData['isShowInFilter'],
        ];

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::updatePermission, $this->user->id);

        $this->request->shouldReceive('all')
            ->andReturn($eyeStatusData);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->request->shouldReceive('route')
            ->with('id')
            ->andReturn($id);

        $this->tableFieldSerivce->shouldReceive('updateEyeStatusCoreField')
            ->once()
            ->with($id, $eyeStatus);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->eyeStatusChangeCoreField($this->request, 1, $id);
        $msg = __('core__be_status_updated');
        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertEquals($msg, $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('updateEyeStatusCoreField')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->eyeStatusChangeCoreField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    public function test_eyeStatusChangeCustomField()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        // Prepare Data
        $table = 1;
        $id = 1;
        $eyeStatusData = [
            'id' => $id,
            'isIncluded' => 1,
            'isShow' => 1,
            'isShowInFilter' => 1,
        ];

        $eyeStatus = [
            'id' => $eyeStatusData['id'],
            'is_include_in_hideshow' => $eyeStatusData['isIncluded'],
            'is_show' => $eyeStatusData['isShow'],
            'is_show_in_filter' => $eyeStatusData['isShowInFilter'],
        ];

        // Mocking
        $this->tableFieldController->shouldReceive('handlePermissionWithoutModel')
            ->with(Constants::tableFieldModule, ps_constant::updatePermission, $this->user->id);

        $this->request->shouldReceive('all')
            ->andReturn($eyeStatusData);

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($table);

        $this->request->shouldReceive('route')
            ->with('id')
            ->andReturn($id);

        $this->tableFieldSerivce->shouldReceive('updateEyeStatusCustomField')
            ->once()
            ->with($id, $eyeStatus);

        /**
         * Testing For Success Case
         */

        // Call the method
        $response = $this->tableFieldController->eyeStatusChangeCustomField($this->request, 1, $id);
        $msg = __('core__be_status_updated');
        $status = $response->getSession()->get('status');

        $this->assertNotNull($status);
        $this->assertEquals('success', $status['flag']);
        $this->assertEquals($msg, $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);

        /**
         * Testing For fail Case
         */

        // Mocking
        $this->tableFieldSerivce->shouldReceive('updateEyeStatusCustomField')
            ->once()
            ->andThrow(new \Exception('There is an error!'));

        // Call the method
        $response = $this->tableFieldController->eyeStatusChangeCustomField($this->request, 1, $id);

        $status = $response->getSession()->get('status');
        $this->assertNotNull($status);
        $this->assertEquals('danger', $status['flag']);
        $this->assertEquals('There is an error!', $status['msg']);
        $this->assertInstanceOf(RedirectResponse::class, $response);
    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareIndexData()
    {
        // Simulate user authentication
        $this->actingAs($this->user);

        $languageId = 1;
        $tableId = 1;
        $inputs = [
            'search' => 'keyword',
            'symbol' => 'en',
            'sort_field' => 'name',
            'sort_order' => 'desc',
            'row' => 10,
            'category_id' => 1,
        ];

        foreach ($inputs as $key => $value) {
            $this->request->shouldReceive('input')
                ->with($key)
                ->andReturn($value);
        }

        $this->request->shouldReceive('route')
            ->with('table')
            ->andReturn($tableId);

        $selectedTable = (object) [
            'core_key_type_id' => 1,
        ];

        $tableFieldsQuery = Mockery::mock(Builder::class);
        $tableFieldsQuery->shouldReceive('orderBy')->andReturnSelf();
        $tableFieldsQuery->shouldReceive('paginate')->andReturn(new LengthAwarePaginator([], 0, 10));

        $this->tableService->shouldReceive('getTable')
            ->with($tableId)
            ->andReturn($selectedTable);

        $this->languageService->shouldReceive('get')
            ->with(null, ['symbol' => $inputs['symbol']])
            ->andReturn((object) ['id' => $languageId]);

        $this->tableFieldSerivce->shouldReceive('getAll')
            ->andReturn($tableFieldsQuery);

        $this->categoryService->shouldReceive('getAll')
            ->andReturn(collect());

        $this->coreFieldService->shouldReceive('getAll')
            ->andReturn(collect());

        $this->customFieldService->shouldReceive('getAll')
            ->andReturn(collect());

        $dataArr = $this->psTestHelper->invokePrivateMethod('prepareIndexData', [$this->request]);

        $this->assertIsArray($dataArr);
        $this->assertArrayHasKey('selectedTable', $dataArr);
        $this->assertArrayHasKey('tableId', $dataArr);
        $this->assertArrayHasKey('fields', $dataArr);
        $this->assertArrayHasKey('search', $dataArr);
        $this->assertArrayHasKey('showCoreAndCustomFieldArr', $dataArr);
        $this->assertArrayHasKey('hideShowFieldForFilterArr', $dataArr);
        $this->assertArrayHasKey('categories', $dataArr);
        $this->assertArrayHasKey('categoryId', $dataArr);
        $this->assertArrayHasKey('generalCategroies', $dataArr);
        $this->assertArrayHasKey('sort_field', $dataArr);
        $this->assertArrayHasKey('sort_order', $dataArr);

    }
}
