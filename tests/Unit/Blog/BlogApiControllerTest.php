<?php

namespace Tests\Unit\Blog;

use App\Exceptions\PsApiException;
use App\Helpers\PsTestHelper;
use App\Http\Contracts\Blog\BlogServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Backend\Rests\App\V1_0\Information\BlogApiController;
use Modules\Core\Http\Services\Configuration\MobileSettingService;
use Tests\TestCase;

class BlogApiControllerTest extends TestCase
{

    use RefreshDatabase;

    protected BlogApiController $blogApiController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blogApiController = new BlogApiController(
            app(BlogServiceInterface::class),
            // app(BlogService::class),
            app(MobileSettingService::class)
        );

    }

    public function test_getBlogId() {

        // Test : id = 1
        $request = Request::create('/blog', 'GET', [
            'id'=>1,
        ]);

        $result1 = PsTestHelper::invokeMethod($this->blogApiController, "getBlogId", [$request]);
        $this->assertEquals(1, $result1);

        // Test : blogId = 2
        $request = Request::create('/blog', 'GET', [
            'blogId'=>2,
        ]);

        $result1 = PsTestHelper::invokeMethod($this->blogApiController, "getBlogId", [$request]);
        $this->assertEquals(2, $result1);

        // Test : No passing both id or blogId in request
        $request = Request::create('/blog', 'GET', []);

        try {
            $result1 = PsTestHelper::invokeMethod($this->blogApiController, "getBlogId", [$request]);
            $this->fail('Expected InvalidFormatException was not thrown.');
        } catch (PsApiException $e) {
            $this->assertEquals('There is no record for this request.', $e->getMessage());
        } catch (\Exception $e) {
            $this->fail('Unexpected exception type: ' . get_class($e));
        }

    }

}
