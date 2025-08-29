<?php


// use Illuminate\Support\Facades\Route;
// use Modules\Rating\Http\Controllers\Backend\Rests\RatingApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\MobileLanguage\MobileLanguageApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\MobileLanguageString\MobileLanguageStringApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Product\ProductApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Subcategory\SubcategoryApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Category\CategoryApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\About\AboutApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\LocationCity\LocationCityApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\LocationTownship\LocationTownshipApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\User\UserApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware(['auth:sanctum'])->name("api.")->group(function () {
//     Route::prefix('user')->controller(UserApiController::class)->group(function () {
//         Route::post("/login", 'login');
//         Route::post("/register", 'register');
//     });
// });

// // with laravel jebstream token
// Route::middleware(['auth:sanctum', 'isAdminAndEditor'])->name("api.")->group(function (){
//     Route::middleware(['hasUser'])->group(function(){
//         // ProductApiController
//         Route::prefix('product')->controller(ProductApiController::class)->group(function () {
//             Route::get("/create", 'create');
//             Route::get("/customize-header/{customize_header_key}/customize-details", "customizeDetails");
//             Route::get("/ui-types/for-customize-details", "customizeHeadersForCustomizeDetails");

//             Route::post("app-info", "appInfo");
//         });
//         Route::apiResource('product', ProductApiController::class);
//         //    Route::controller(ProductApiController::class)->group(function (){
//         //        Route::get("/product/login_user_id/{login_user_id}/limit/{limit}/offset/{offset}","index")->name("mobile_language.index");
//         //    });

//     // ProductApiController
//     Route::prefix('product')->controller(ProductApiController::class)->group(function (){
//         Route::get("/create",'create');
//         Route::get("/customize-header/{customize_header_key}/customize-details","customizeDetails");
//         Route::get("/ui-types/for-customize-details","customizeHeadersForCustomizeDetails");
//         Route::post('/cover/upload', "coverUpload");
//         Route::post('/icon/upload', "iconUpload");
//         Route::post('/video/upload', "videoUpload");
//         Route::post("app-info","appInfo");
//     });
//     Route::apiResource('product',ProductApiController::class);
// //    Route::controller(ProductApiController::class)->group(function (){
// //        Route::get("/product/login_user_id/{login_user_id}/limit/{limit}/offset/{offset}","index")->name("mobile_language.index");
// //    });

//         // MobileLanguageStringApiController
//         Route::apiResource("/mobile_language/{mobile_language}/mobile_language_string", MobileLanguageStringApiController::class);

//         //    Route::controller(MobileLanguageStringApiController::class)->group(function (){
//         //        Route::get("/mobile_language/{mobile_language}/mobile_language_string/login_user_id/{login_user_id}/limit/{limit}/offset/{offset}","index");
//         //    });

//         Route::prefix('category')->controller(CategoryApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });

//         Route::prefix('location-city')->controller(LocationCityApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });

//         Route::prefix('location-township')->controller(LocationTownshipApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });

//         Route::prefix('about')->controller(AboutApiController::class)->group(function () {
//             Route::get("/", 'index');
//         });

//         Route::prefix('rating')->controller(RatingApiController::class)->group(function () {
//             Route::post("/", 'rating');
//         });

//     });
    
//     Route::prefix('user')->controller(UserApiController::class)->group(function () {
//         Route::post("/login", 'login');
//         Route::post("/logout", 'logout');
//         Route::post("/register", 'register');
//         Route::post("/google_register", 'google_register');
//         Route::post("/verify_code", 'verify_code');
//         Route::post("/request_code", 'request_code');
//         Route::post("/reset_password", 'reset_password');
//     });

// });
