<?php

namespace Tests\Unit\Configuration;

use App\Config\ps_constant;
use App\Config\ps_url;
use App\Helpers\PsTestHelper;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Utilities\CoreFieldServiceInterface;
use App\Http\Contracts\Utilities\CustomFieldServiceInterface;
use App\Http\Contracts\Utilities\DynamicColumnVisibilityServiceInterface;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mockery;
use Modules\Core\Constants\Constants;
use Modules\Core\Http\Services\Configuration\TableFieldService;
use Modules\Core\Http\Services\CoreKeyCounterService;
use Modules\Core\Http\Services\CoreKeyTypeService;
use Modules\Core\Http\Services\Localization\BeLanguageStringService;
use Modules\Core\Http\Services\ProjectService;
use Modules\Core\Http\Services\TableService;
use Modules\Core\Http\Services\Utilities\CoreFieldService;
use Tests\TestCase;

class TableFieldServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $tableFieldService;

    protected $tableFieldServiceOriginal;

    protected $coreFieldService;

    protected $customFieldService;

    protected $categoryService;

    protected $projectService;

    protected $languageStringService;

    protected $dynamicColumnVisibilityService;

    protected $coreKeyCounterService;

    protected $tableService;

    protected $coreKeyTypeSerivce;

    protected $user;

    protected $psTestHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->coreFieldService = Mockery::mock(CoreFieldServiceInterface::class);

        $this->customFieldService = Mockery::mock(CustomFieldServiceInterface::class);

        $this->categoryService = Mockery::mock(CategoryServiceInterface::class);

        $this->projectService = Mockery::mock(ProjectService::class);

        $this->languageStringService = Mockery::mock(BeLanguageStringService::class);

        $this->dynamicColumnVisibilityService = Mockery::mock(DynamicColumnVisibilityServiceInterface::class);

        $this->coreKeyCounterService = Mockery::mock(CoreKeyCounterService::class);

        $this->tableService = Mockery::mock(TableService::class);

        $this->coreKeyTypeSerivce = Mockery::mock(CoreKeyTypeService::class);

        $this->tableFieldService = Mockery::mock(TableFieldService::class, [
            $this->coreFieldService,
            $this->customFieldService,
            $this->categoryService,
            $this->projectService,
            $this->languageStringService,
            $this->dynamicColumnVisibilityService,
            $this->coreKeyCounterService,
            $this->tableService,
            $this->coreKeyTypeSerivce,
        ])->makePartial();

        $this->tableFieldServiceOriginal = new TableFieldService(
            $this->coreFieldService,
            $this->customFieldService,
            $this->categoryService,
            $this->projectService,
            $this->languageStringService,
            $this->dynamicColumnVisibilityService,
            $this->coreKeyCounterService,
            $this->tableService,
            $this->coreKeyTypeSerivce
        );

        // For Auth User
        $this->user = User::factory()->create(['role_id' => '1']);

        // For Private Functions
        $this->psTestHelper = new PsTestHelper($this->tableFieldServiceOriginal);

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

        // Prepare Data
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
        $tableId = 1;
        $generatedData = [
            'name_key' => 'name_key_value',
            'placeholder_key' => 'placeholder_key_value',
            'core_keys_id' => 'loc00001',
            'module_name' => 'loc',
            'base_module_name' => 'ps-loc',
            'data_type' => 'string',
        ];

        // Mock method return values
        $this->projectService->shouldReceive('getProject')
            ->andReturn((object) [
                'id' => 1,
                'project_name' => 'testing',
            ]);

        $this->languageStringService->shouldReceive('save')->twice();
        $this->dynamicColumnVisibilityService->shouldReceive('updateOrCreate')->once();
        $this->coreKeyCounterService->shouldReceive('saveOrUpdate')
            ->once()
            ->with($generatedData['core_keys_id'], $generatedData['module_name']);

        // Call the method
        $this->tableFieldServiceOriginal->save($customFieldData, $tableId, $generatedData);

        $this->assertTrue(true);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->coreKeyCounterService->shouldReceive('saveOrUpdate')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        $result = $this->tableFieldServiceOriginal->save($customFieldData, $tableId, $generatedData);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_deleteCoreField()
    {
        //Prepare Data
        $id = 1;

        // Mock the CoreFieldService
        $this->coreFieldService->shouldReceive('delete')
            ->with($id)
            ->andReturn('field_name');

        // Call the method with test data
        $result = $this->tableFieldServiceOriginal->deleteCoreField($id);

        // Assertions to ensure the correct response
        $this->assertEquals(__('core__be_delete_success', ['attribute' => __('field_name')]), $result['msg']);
        $this->assertEquals(Constants::success, $result['flag']);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->expectException(\Throwable::class);

        // Mock the CoreFieldService
        $this->coreFieldService->shouldReceive('delete')
            ->with($id)
            ->andThrow(new \Exception($errorMessage));

        // Call the method with test data and expect an exception
        $result = $this->tableFieldServiceOriginal->deleteCoreField($id);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_deleteCustomField()
    {
        //Prepare Data
        $id = 1;

        // Mock the customField
        $this->customFieldService->shouldReceive('delete')
            ->with($id)
            ->andReturn('field_name');

        // Call the method with test data
        $result = $this->tableFieldServiceOriginal->deleteCustomField($id);

        // Assertions to ensure the correct response
        $this->assertEquals(__('core__be_delete_success', ['attribute' => __('field_name')]), $result['msg']);
        $this->assertEquals(Constants::success, $result['flag']);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->expectException(\Throwable::class);

        // Mock the customField
        $this->customFieldService->shouldReceive('delete')
            ->with($id)
            ->andThrow(new \Exception($errorMessage));

        // Call the method with test data and expect an exception
        $result = $this->tableFieldServiceOriginal->deleteCustomField($id);
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());
    }

    public function test_generateCoreKeysId_fail()
    {

        // Mock external functions
        Http::fake([
            ps_constant::base_url.ps_url::checkBuilderConnection => Http::response(['status' => 'error', 'message' => 'connection failed'], 500),
        ]);

        // Mock Service
        $this->projectService->shouldReceive('getProject')->andReturn((object) []);
        $this->tableService->shouldReceive('getTable')->with(1)->andReturn((object) ['core_key_type_id' => 1]);
        $this->coreKeyTypeSerivce->shouldReceive('getCoreKeyType')->with(1)->andReturn((object) ['client_code' => 'client_code_value']);
        $this->coreKeyCounterService->shouldReceive('getCoreKeyCounter')->andReturn('core_key_counter_value');

        // Call the method with test data
        $response = $this->tableFieldService->generateCoreKeysId(1, 1);

        // Assertions to ensure the correct response
        $this->assertEquals('error', $response['flag']);
        $this->assertEquals('connection failed', $response['msg']);
    }

    public function test_generateCoreKeysId_success()
    {

        // Mock external functions
        Http::fake([
            ps_constant::base_url.ps_url::checkBuilderConnection => Http::response(['status' => 'success'], 200),
            ps_constant::base_url.ps_url::generateCoreKeysId => Http::response([
                'status' => 'success',
                'message' => [
                    'data_type' => 'data_type_value',
                    'module_name' => 'module_name_value',
                    'base_module_name' => 'base_module_name_value',
                    'name_key' => 'name_key_value',
                    'placeholder_key' => 'placeholder_key_value',
                    'core_keys_id' => 'core_keys_id_value',
                ],
            ], 200),
        ]);

        // Mock Service
        $this->projectService->shouldReceive('getProject')->andReturn((object) []);
        $this->tableService->shouldReceive('getTable')->with(1)->andReturn((object) ['core_key_type_id' => 1]);
        $this->coreKeyTypeSerivce->shouldReceive('getCoreKeyType')->with(1)->andReturn((object) ['client_code' => 'client_code_value']);
        $this->coreKeyCounterService->shouldReceive('getCoreKeyCounter')->andReturn('core_key_counter_value');

        // Call the method with test data
        $response = $this->tableFieldService->generateCoreKeysId(1, 1);

        // Assertions to ensure the correct response
        $this->assertEquals('success', $response['flag']);
        $this->assertEquals('data_type_value', $response['data_type']);
        $this->assertEquals('module_name_value', $response['module_name']);
        $this->assertEquals('base_module_name_value', $response['base_module_name']);
        $this->assertEquals('name_key_value', $response['name_key']);
        $this->assertEquals('placeholder_key_value', $response['placeholder_key']);
        $this->assertEquals('core_keys_id_value', $response['core_keys_id']);

    }

    public function test_updateCoreField()
    {
        // Prepare Data
        $id = 1;
        $coreFieldData = [
            'field_name' => 'example_field_name',
            'nameForm' => [],
            'placeholderForm' => [],
        ];

        $coreField = (object) [
            'field_name' => 'example_field_name',
            'is_include_in_hideshow' => true,
            'module_name' => 'example_module',
            'is_show' => 1,
        ];

        // Mocking
        $this->coreFieldService->shouldReceive('update')
            ->with($id, $coreFieldData)
            ->andReturn($coreField)
            ->once();

        $dynamicColumnVisibility = (object) [];

        $this->dynamicColumnVisibilityService->shouldReceive('get')
            ->with(null, 'example_field_name', 'example_module')
            ->andReturn($dynamicColumnVisibility)
            ->once();

        $this->dynamicColumnVisibilityService->shouldReceive('updateOrCreate')
            ->with(Mockery::on(function ($dataArrWhere) {
                return true;
            }), Mockery::on(function ($data) {
                return true;
            }))
            ->once();

        if (empty($coreField->is_include_in_hideshow)) {
            $this->dynamicColumnVisibilityService->shouldReceive('delete')
                ->with($dynamicColumnVisibility)
                ->once();
        }

        $this->languageStringService->shouldReceive('update')
            ->with(Mockery::on(function ($LangData) {
                return true;
            }))
            ->twice();

        // Call the method
        $this->tableFieldServiceOriginal->updateCoreField($id, $coreFieldData);
        $this->assertTrue(true);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->coreFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->updateCoreField($id, $coreFieldData);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_updateCustomField()
    {
        // Prepare Data
        $id = 1;
        $customFieldData = [
            'field_name' => 'example_field_name',
            'nameForm' => [],
            'placeholderForm' => [],
        ];

        $customField = (object) [
            'field_name' => 'example_field_name',
            'core_keys_id' => 'loc00001',
            'is_include_in_hideshow' => true,
            'module_name' => 'example_module',
            'is_show' => 1,
        ];

        // Mocking
        $this->customFieldService->shouldReceive('update')
            ->with($id, $customFieldData)
            ->andReturn($customField)
            ->once();

        $dynamicColumnVisibility = (object) [];

        $this->dynamicColumnVisibilityService->shouldReceive('get')
            ->with(null, $customField->core_keys_id, $customField->module_name)
            ->andReturn($dynamicColumnVisibility)
            ->once();

        $this->dynamicColumnVisibilityService->shouldReceive('updateOrCreate')
            ->with(Mockery::on(function ($dataArrWhere) {
                return true;
            }), Mockery::on(function ($data) {
                return true;
            }))
            ->once();

        if (empty($customField->is_include_in_hideshow)) {
            $this->dynamicColumnVisibilityService->shouldReceive('delete')
                ->with($dynamicColumnVisibility)
                ->once();
        }

        $this->languageStringService->shouldReceive('update')
            ->with(Mockery::on(function ($LangData) {
                return true;
            }))
            ->twice();

        // Call the method
        $this->tableFieldServiceOriginal->updateCustomField($id, $customFieldData);
        $this->assertTrue(true);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->customFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->updateCustomField($id, $customFieldData);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_setCoreFieldEnable()
    {
        // Prepare Data
        $id = 1;
        $enable = 1;

        // Mocking
        $this->coreFieldService->shouldReceive('update')
            ->with($id, ['enable' => $enable])
            ->andReturn((object) [
                'id' => 1,
                'enable' => $enable,
            ])
            ->once();

        // Call the method
        $result = $this->tableFieldService->setCoreFieldEnable($id, $enable);

        $this->assertEquals($id, $result->id);
        $this->assertEquals($enable, $result->enable);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->coreFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->setCoreFieldEnable($id, $enable);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_setCustomFieldEnable()
    {
        // Prepare Data
        $id = 1;
        $enable = 1;

        // Mocking
        $this->customFieldService->shouldReceive('update')
            ->with($id, ['enable' => $enable])
            ->andReturn((object) [
                'id' => 1,
                'enable' => $enable,
            ])
            ->once();

        // Call the method
        $result = $this->tableFieldService->setCustomFieldEnable($id, $enable);

        $this->assertEquals($id, $result->id);
        $this->assertEquals($enable, $result->enable);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->customFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->setCustomFieldEnable($id, $enable);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_setCoreFieldIsShowSorting()
    {
        // Prepare Data
        $id = 1;
        $isShowSorting = 1;

        // Mocking
        $this->coreFieldService->shouldReceive('update')
            ->with($id, ['is_show_sorting' => $isShowSorting])
            ->andReturn((object) [
                'id' => 1,
                'isShowSorting' => $isShowSorting,
            ])
            ->once();

        // Call the method
        $result = $this->tableFieldService->setCoreFieldIsShowSorting($id, $isShowSorting);

        $this->assertEquals($id, $result->id);
        $this->assertEquals($isShowSorting, $result->isShowSorting);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->coreFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->setCoreFieldIsShowSorting($id, $isShowSorting);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_setCustomFieldIsShowSorting()
    {
        // Prepare Data
        $id = 1;
        $isShowSorting = 1;

        // Mocking
        $this->customFieldService->shouldReceive('update')
            ->with($id, ['is_show_sorting' => $isShowSorting])
            ->andReturn((object) [
                'id' => 1,
                'isShowSorting' => $isShowSorting,
            ])
            ->once();

        // Call the method
        $result = $this->tableFieldService->setCustomFieldIsShowSorting($id, $isShowSorting);

        $this->assertEquals($id, $result->id);
        $this->assertEquals($isShowSorting, $result->isShowSorting);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->customFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->setCustomFieldIsShowSorting($id, $isShowSorting);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_setCoreFieldMandatory()
    {
        // Prepare Data
        $id = 1;
        $mandatory = 1;

        // Mocking
        $this->coreFieldService->shouldReceive('update')
            ->with($id, ['mandatory' => $mandatory])
            ->andReturn((object) [
                'id' => 1,
                'mandatory' => $mandatory,
            ])
            ->once();

        // Call the method
        $result = $this->tableFieldService->setCoreFieldMandatory($id, $mandatory);

        $this->assertEquals($id, $result->id);
        $this->assertEquals($mandatory, $result->mandatory);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->coreFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->setCoreFieldMandatory($id, $mandatory);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_setCustomFieldMandatory()
    {
        // Prepare Data
        $id = 1;
        $mandatory = 1;

        // Mocking
        $this->customFieldService->shouldReceive('update')
            ->with($id, ['mandatory' => $mandatory])
            ->andReturn((object) [
                'id' => 1,
                'mandatory' => $mandatory,
            ])
            ->once();

        // Call the method
        $result = $this->tableFieldService->setCustomFieldMandatory($id, $mandatory);

        $this->assertEquals($id, $result->id);
        $this->assertEquals($mandatory, $result->mandatory);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->customFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->setCustomFieldMandatory($id, $mandatory);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_updateEyeStatusCoreField()
    {
        // Prepare Data
        $id = 1;
        $eyeStatusData = [
            'id' => $id,
            'is_include_in_hideshow' => 1,
            'is_show' => 1,
            'is_show_in_filter' => 1,
        ];

        // Mocking
        $this->coreFieldService->shouldReceive('update')
            ->with($id, $eyeStatusData)
            ->andReturn((object) [
                'id' => 1,
                'field_name' => 'Testing',
                'module_name' => 'loc',
                'is_include_in_hideshow' => 1,
                'is_show' => 1,
            ])
            ->once();

        $dynamicColumnVisibility = (object) [];

        $this->dynamicColumnVisibilityService->shouldReceive('get')
            ->with(null, 'Testing', 'loc')
            ->andReturn($dynamicColumnVisibility)
            ->once();

        $this->dynamicColumnVisibilityService->shouldReceive('updateOrCreate')
            ->with(Mockery::on(function ($dataArrWhere) {
                return true;
            }), Mockery::on(function ($data) {
                return true;
            }))
            ->once();

        // Call the method
        $result = $this->tableFieldService->updateEyeStatusCoreField($id, $eyeStatusData);

        $this->assertTrue(true);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->coreFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->updateEyeStatusCoreField($id, $eyeStatusData);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    public function test_updateEyeStatusCustomField()
    {
        // Prepare Data
        $id = 1;
        $eyeStatusData = [
            'id' => $id,
            'is_include_in_hideshow' => 1,
            'is_show' => 1,
            'is_show_in_filter' => 1,
        ];

        // Mocking
        $this->customFieldService->shouldReceive('update')
            ->with($id, $eyeStatusData)
            ->andReturn((object) [
                'id' => 1,
                'core_keys_id' => 'Testing',
                'module_name' => 'loc',
                'is_include_in_hideshow' => 1,
                'is_show' => 1,
            ])
            ->once();

        $dynamicColumnVisibility = (object) [];

        $this->dynamicColumnVisibilityService->shouldReceive('get')
            ->with(null, 'Testing', 'loc')
            ->andReturn($dynamicColumnVisibility)
            ->once();

        $this->dynamicColumnVisibilityService->shouldReceive('updateOrCreate')
            ->with(Mockery::on(function ($dataArrWhere) {
                return true;
            }), Mockery::on(function ($data) {
                return true;
            }))
            ->once();

        // Call the method
        $result = $this->tableFieldService->updateEyeStatusCustomField($id, $eyeStatusData);

        $this->assertTrue(true);

        // For Exception Case
        $errorMessage = 'Error Message!';
        $this->customFieldService->shouldReceive('update')
            ->once()
            ->andThrow(new \Exception($errorMessage));

        $this->expectException(\Exception::class);

        // Call the method
        $result = $this->tableFieldServiceOriginal->updateEyeStatusCustomField($id, $eyeStatusData);

        // assert
        $this->assertNotNull($result);
        $this->assertEquals($errorMessage, $result->getMessage());

    }

    //////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    //////////////////////////////////////////////////////////////////////

    public function test_prepareSaveLanguageStringData()
    {
        // Prepare Testing Data
        $fieldData = [
            'param' => [
                'key1' => 'value1',
                'key2' => 'value2',
                'values' => 'someValue',
            ],
        ];
        $para = 'param';

        // Prepare Expected Data
        $expected = new \stdClass();
        $expected->key1 = 'value1';
        $expected->key2 = 'value2';
        $expected->values = 'someValue';

        // Call Private Method
        $result = $this->psTestHelper->invokePrivateMethod('prepareSaveLanguageStringData', [$fieldData, $para]);
        $this->assertEquals($expected, $result);

        // Case: Test with empty 'values' key
        $fieldData = [
            'param' => [
                'key1' => 'value1',
                'key2' => 'value2',
                'values' => '',
            ],
        ];
        $expected = new \stdClass();
        $expected->key1 = 'value1';
        $expected->key2 = 'value2';
        $expected->values = [];

        $result = $this->psTestHelper->invokePrivateMethod('prepareSaveLanguageStringData', [$fieldData, $para]);
        $this->assertEquals($expected, $result);

        // Case: Test without 'values' key
        $fieldData = [
            'param' => [
                'key1' => 'value1',
                'key2' => 'value2',
            ],
        ];
        $expected = new \stdClass();
        $expected->key1 = 'value1';
        $expected->key2 = 'value2';
        $expected->values = [];

        $result = $this->psTestHelper->invokePrivateMethod('prepareSaveLanguageStringData', [$fieldData, $para]);
        $this->assertEquals($expected, $result);
    }

    public function test_prepareUpdateEnableData()
    {
        $data = 1;
        $expected = 'enable';
        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateEnableData', [$data]);
        $this->assertArrayHasKey($expected, $result);
        $this->assertEquals($data, $result[$expected]);
    }

    public function test_prepareUpdateIsShowSortingData()
    {
        $data = 1;
        $expected = 'is_show_sorting';
        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateIsShowSortingData', [$data]);
        $this->assertArrayHasKey($expected, $result);
        $this->assertEquals($data, $result[$expected]);
    }

    public function test_prepareUpdateMandatoryData()
    {
        $data = 1;
        $expected = 'mandatory';
        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateMandatoryData', [$data]);
        $this->assertArrayHasKey($expected, $result);
        $this->assertEquals($data, $result[$expected]);
    }

    public function test_prepareUpdateOrCreateDynamicColumnVisibilityData()
    {

        // Create a mock field object
        $field = (object) [
            'module_name' => 'test_module',
            'is_show' => true,
        ];
        $key = 'test_key';

        // Expected results
        $expectedDataArrWhere = [
            'module_name' => 'test_module',
            'key' => 'test_key',
        ];

        $expectedData = (object) [
            'module_name' => 'test_module',
            'key' => 'test_key',
            'is_show' => true,
        ];

        // Invoke the private method and get the result
        $result = $this->psTestHelper->invokePrivateMethod('prepareUpdateOrCreateDynamicColumnVisibilityData', [$field, $key]);

        // Check if the result contains the expected keys and values
        $this->assertArrayHasKey('dataArrWhere', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals($expectedDataArrWhere, $result['dataArrWhere']);
        $this->assertEquals($expectedData, $result['data']);
    }
}
