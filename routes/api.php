<?php

// use Illuminate\Support\Facades\Route;
// use Modules\BlockUser\Http\Controllers\Backend\Rests\BlockUserApiController;
// use Modules\FollowUser\Http\Controllers\Backend\Rests\FollowUserApiController;
// use Modules\Blog\Http\Controllers\Backend\Rests\BlogApiController;
// use Modules\Chat\Http\Controllers\Backend\Rests\ChatApiController;
// use Modules\ComplaintItem\Http\Controllers\Backend\Rests\ComplaintItemApiController;
// use Modules\ContactUsMessage\Http\Controllers\Backend\Rests\ContactUsMessageApiController;
// use Modules\Rating\Http\Controllers\Backend\Rests\RatingApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\MobileLanguage\MobileLanguageApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\MobileLanguageString\MobileLanguageStringApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Product\ProductApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Subcategory\SubcategoryApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Category\CategoryApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\About\AboutApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\AppInfo\AppInfoApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\LocationCity\LocationCityApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\LocationTownship\LocationTownshipApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\User\UserApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Image\ImageApiController;
// use Modules\ItemPromotion\Http\Controllers\Backend\Rests\PaidItemApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Currency\CurrencyApiController;
// use Modules\Package\Http\Controllers\Backend\Rests\PackageBoughtTransactionApiController;
// use Modules\Payment\Http\Controllers\Backend\Rests\OfflinePaymentSetting\OfflinePaymentSettingApiController;
// use Modules\Payment\Http\Controllers\Backend\Rests\PackageInAppPurchaseSetting\PackageInAppPurchaseSettingApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\PushNotificationToken\PushNotificationTokenApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\PushNotificationReadUser\PushNotificationReadUserApiController;
// use Modules\PushNotificationMessage\Http\Controllers\Backend\Rests\PushNotificationMessageApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\SubCatSubscribe\SubCatSubscribeApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Touch\TouchApiController;
// use Modules\Core\Http\Controllers\Backend\Rests\Favourite\FavouriteApiController;

// /*
// |--------------------------------------------------------------------------
// | API Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register API routes for your application. These
// | routes are loaded by the RouteServiceProvider within a group which
// | is assigned the "api" middleware group. Enjoy building your API!
// |
// */

// // this api include token and policy layer testing
// Route::middleware(['auth:sanctum', 'isUserAndDeliboyMobileToken', 'checkAuthUserForApi'])->name("api.")->group(function () {
//     Route::apiResource('/sub-category', SubcategoryApiController::class);
//     Route::get('/token', [SubcategoryApiController::class, "token"]);
//     // this apiResource route will support for SubcategoryApiController => index, store, show, update, destroy - KZL test
// });

// // with laravel jebstream token
// Route::middleware(['auth:sanctum', 'isUserMobileToken', 'checkAuthUserForApi'])->name("api.")->group(function () {
//     // Route::middleware(['hasUser'])->group(function () {
//         // ProductApiController
//         // Route::prefix('product')->controller(ProductApiController::class)->group(function () {
//         //     Route::get("/create", 'create');
//         //     Route::get("/customize-header/{customize_header_key}/customize-details", "customizeDetails");
//         //     Route::get("/ui-types/for-customize-details", "customizeHeadersForCustomizeDetails");
//         //     Route::post("app-info", "appInfo");

//         // });
//         //    Route::controller(ProductApiController::class)->group(function (){
//         //        Route::get("/product/login_user_id/{login_user_id}/limit/{limit}/offset/{offset}","index")->name("mobile_language.index");
//         //    });

//         // ProductApiController
//         Route::prefix('product')->controller(ProductApiController::class)->group(function () {
//             Route::get("/create", 'create');
//             Route::get("/get_product", 'getProductById');
//             Route::post("search", "search");
//             Route::post("/sold_out_item_detail", 'soldOutFromItemDetail');

//             // Custom Field
//             Route::get("/customize-header/{customize_header_key}/customize-details", "customizeDetails");
//             Route::get("/ui-types/for-customize-details", "customizeHeadersForCustomizeDetails");

//             // Item Image, Icon and Video
//             Route::post('/cover/upload', "coverUpload");
//             Route::post('/icon/upload', "iconUpload");
//             Route::post('/video/upload', "videoUpload");
//             Route::post("/delete_image", 'destroyImage');
//             Route::post("/delete_video_and_icon", 'destroyVideo');
//             Route::post("reorder_images", "reorderImages");
//             Route::get("/gallery_list", 'getGalleryList');
//         });
//         Route::apiResource('product', ProductApiController::class);

//         // app info api
//         Route::prefix('app_info')->controller(AppInfoApiController::class)->group(function () {
//             Route::post("/", 'appInfo');
//         });

//         Route::apiResource('product', ProductApiController::class);
//         //    Route::controller(ProductApiController::class)->group(function (){
//         //        Route::get("/product/login_user_id/{login_user_id}/limit/{limit}/offset/{offset}","index")->name("mobile_language.index");
//         //    });

//         // MobileLanguageApiController
//         Route::apiResource("/mobile_language", MobileLanguageApiController::class);

//         // MobileLanguageStringApiController
//         Route::apiResource("/mobile_language/{mobile_language}/mobile_language_string", MobileLanguageStringApiController::class);

//         //    Route::controller(MobileLanguageStringApiController::class)->group(function (){
//         //        Route::get("/mobile_language/{mobile_language}/mobile_language_string/login_user_id/{login_user_id}/limit/{limit}/offset/{offset}","index");
//         //    });


//         // category api
//         Route::prefix('category')->controller(CategoryApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });

//         // location city api
//         Route::prefix('location-city')->controller(LocationCityApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });

//         // location township api
//         Route::prefix('location-township')->controller(LocationTownshipApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });

//         // about us api
//         Route::prefix('about')->controller(AboutApiController::class)->group(function () {
//             Route::get("/", 'index');
//         });

//         // rating api
//         Route::prefix('rating')->controller(RatingApiController::class)->group(function () {
//             Route::post("/", 'rating');
//             Route::post("/search", 'search');
//         });

//         // blog api
//         Route::prefix('blog')->controller(BlogApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });

//         // contact us message api
//         Route::prefix('contact')->controller(ContactUsMessageApiController::class)->group(function () {
//             Route::post("/", 'contact');
//             Route::get("/get_in_touch", "getInTouchForContact");
//         });

//         // currency api controller
//         Route::apiResource('/currency', CurrencyApiController::class);

//         // image api controller        
//         Route::apiResource('image', ImageApiController::class);

//         // block api controller
//         Route::prefix('block')->controller(BlockUserApiController::class)->group(function () {
//             Route::post("/block_user", 'blockUser');
//             Route::post("/unblock_user", 'unblockUser');
//             Route::get("/get_blocked_user_by_loginuser", 'getBlockedUser');
//         });
//         Route::apiResource("/block", BlockUserApiController::class);

//         // follow api controller
//         Route::prefix('follow')->controller(FollowUserApiController::class)->group(function () {
//             Route::post("/follow_user", 'followUser');
//             Route::get("/get_follower_by_loginuser", 'getFollower');
//             Route::post("/search_follow_user", 'searchFollower');
//             Route::post("/item_from_follower", 'itemListFromFollower');
//         });
//         Route::apiResource("/follow", FollowUserApiController::class);

//         // paid item api
//         Route::apiResource("/paid_item", PaidItemApiController::class);

//         // package api
//         Route::prefix('package_bought')->controller(PackageBoughtTransactionApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });
//         Route::apiResource("/package_bought", PackageBoughtTransactionApiController::class);

//         // offline Payment api
//         Route::prefix('offline_payment')->controller(OfflinePaymentSettingApiController::class)->group(function () {
//             Route::get("/", 'index');
//         });

//         // complaint item api
//         Route::apiResource("/complaint_item", ComplaintItemApiController::class);

//         // Package IAP api
//         Route::prefix('package_in_app_purchase')->controller(PackageInAppPurchaseSettingApiController::class)->group(function () {
//             Route::get("/", 'index');
//         });

//         // push noti token api
//         Route::prefix('push_noti_token')->controller(PushNotificationTokenApiController::class)->group(function () {
//             Route::post("/register_noti", 'registerNoti');
//             Route::post("/unregister_noti", 'unregisterNoti');
//             Route::post("/test_status_code", 'testStatusCode');
//         });

//         // push noti read user
//         Route::prefix('push_noti_read_user')->controller(PushNotificationReadUserApiController::class)->group(function () {
//             Route::post("/is_read", 'isReadNoti');
//         });

//         // push noti message
//         Route::prefix('push_noti_message')->controller(PushNotificationMessageApiController::class)->group(function () {
//             Route::post("/all_notis", 'allNotis');
//         });

//         // subcategory api
//         Route::prefix('sub_category')->controller(SubcategoryApiController::class)->group(function () {
//             Route::post("/search", 'search');
//         });

//         // subcategory subscribe api
//         Route::prefix('subcat_subscribe')->controller(SubCatSubscribeApiController::class)->group(function () {
//             Route::post("/subcategory_subscribe", 'subCategorySubscribe');
//         });

//         // item touch api
//         Route::prefix('touch')->controller(TouchApiController::class)->group(function () {
//             Route::post("/item_touch", 'addItemTouchCount');
//         });

//         // item favourite api
//         Route::prefix('favourite')->controller(FavouriteApiController::class)->group(function () {
//             Route::post("/item_favourite", 'favouriteItem');
//             Route::get("/get_favourite", 'getAllFavouriteItem');
//         });

//     });
//         // chat api
//         Route::prefix('chat')->controller(ChatApiController::class)->group(function () {
//             Route::post("/", 'store');
//             Route::post("/update_price", 'updatePrice');
//             Route::post("/get_chat_history", 'show');
//             Route::post("/chat_image_upload", "chatImageUpload");
//             Route::post("/reset_count", "resetCount");
//             Route::post("/unread_count", "unreadCount");
//             Route::post("/get_offer_list", "getOfferList");
//             Route::post("/is_user_bought", "isUserBought");
//             Route::post("/update_accept", "updateAccept");
//             Route::post("/get_buyer_seller_list", "getBuyerSellerList");
//             Route::post("/item_sold_out", "itemSoldOut");
//         });
//     // });

//     Route::prefix('user')->controller(UserApiController::class)->group(function () {
//         Route::post("/login", 'login');
//         Route::post("/logout", 'logout');
//         Route::post("/register", 'register');
//         Route::post("/google_register", 'googleRegister');
//         Route::post("/phone_register", 'phoneRegister');
//         Route::post("/apple_register", 'appleRegister');
//         Route::post("/facebook_register", 'facebookRegister');
//         Route::post("/user_image_upload", 'userImageUpload');
//         Route::post("/verify_code", 'verifyCode');
//         Route::post("/request_code", 'requestCode');
//         Route::post("/reset_password", 'resetPassword');
//         Route::post("/update_password", 'userPasswordUpdate');
//         Route::post("/profile_update", 'userProfileUpdate');
//         Route::post("/search", 'search');
//         Route::post("/delete_user", 'deleteUser');
//         Route::post("/verify_blue_mark", 'verifyBlueMark');
//         Route::get("/get_detail", 'userDetail');
//     });

