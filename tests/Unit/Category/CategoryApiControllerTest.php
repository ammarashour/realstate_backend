<?php

namespace Tests\Unit\Category;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Helpers\PsTestHelper;
use App\Exceptions\PsApiException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Contracts\Category\CategoryServiceInterface;
use App\Http\Contracts\Localization\LanguageServiceInterface;
use Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Category\CategoryApiController;
use Modules\Core\Http\Services\Configuration\MobileSettingService;

class CategoryApiControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryApiController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->categoryApiController = new CategoryApiController(
            app(CategoryServiceInterface::class),
            app(LanguageServiceInterface::class),
            app(MobileSettingService::class),
        );

    }

    ////////////////////////////////////////////////////////////////////
    /// Private Function Test Cases
    ////////////////////////////////////////////////////////////////////

    public function test_prepareLanguageData()
    {
        $request = Request::create('/category', 'POST', [
            'language_symbol' => 'ar'
        ]);
        $result = PsTestHelper::invokeMethod($this->categoryApiController, "prepareLanguageData", [$request]);
        $this->assertNotNull($result);
        $this->assertEquals('ar', $result['symbol']);
    }

    public function test_getLimitOffsetFromSetting()
    {
        $request = Request::create('/category', 'POST', [
            'limit' => 10,
            'offset' => 0
        ]);
        $result = PsTestHelper::invokeMethod($this->categoryApiController, "getLimitOffsetFromSetting", [$request]);
        $this->assertNotNull($result);
        $this->assertEquals(10, $result[0]);
        $this->assertEquals(0, $result[1]);
    }

    public function test_getFilterConditions()
    {
        $request = Request::create('/category', 'POST', [
            'keyword' => 'car',
            'order_by' => 'added_date',
            'order_type' => 'desc'
        ]);
        $result = PsTestHelper::invokeMethod($this->categoryApiController, "getFilterConditions", [$request]);
        $this->assertNotNull($result);
        $this->assertEquals('car', $result['searchterm']);
        $this->assertEquals('added_date', $result['order_by']);
        $this->assertEquals('desc', $result['order_type']);
    }
}
