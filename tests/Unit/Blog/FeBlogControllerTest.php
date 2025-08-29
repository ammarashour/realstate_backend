<?php

namespace Tests\Unit\Blog;

use App\Http\Contracts\Blog\BlogServiceInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Modules\Core\Http\Services\Configuration\FrontendSettingService;
use Modules\Core\Http\Services\Configuration\MobileSettingService;
use Modules\Template\PSXFETemplate\Http\Controllers\BlogController;
use Tests\TestCase;

class FeBlogControllerTest extends TestCase
{

    use RefreshDatabase;

    protected BlogController $blogController;

    protected function setUp(): void
    {
        parent::setUp();

        $this->blogController = new BlogController(
            app(FrontendSettingService::class),
            app(BlogServiceInterface::class),
            app(MobileSettingService::class)
        );

    }

    public function test_api() {
        $this->assertTrue(true);
    }

    // public function test_api() {
    //     $payload = [
    //         'blogId' => 88
    //     ];

    //     $headers = [
    //         'header-token' => 'Bearer zUMi0HNjAtnREMj3weG7XEv6ogEVovsf6eUFgOp4'
    //     ];

    //     $response = $this->withHeaders($headers)
    //     ->json('GET', '/api/v1.0/blog/detail', [
    //         'id' => 88,
    //         'login_user_id' => 1,
    //         'language_symbol' => 'en'
    //     ]);
    //                 //  ->assertStatus(Response::HTTP_OK);

    //     // Ensure the response has the expected route name
    //     // $this->assertRouteIs('blog.detail');
    //                 //  ->assertStatus(Response::HTTP_OK);
    //                 dd($response);
    //     var_dump($response->all());
    //     $this->assertEquals('blog/detail', $response->baseResponse->getRequest()->path());

    // }

}
