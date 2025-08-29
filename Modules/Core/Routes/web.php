<?php
//
///*
//|--------------------------------------------------------------------------
//| Web Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register web routes for your application. These
//| routes are loaded by the RouteServiceProvider within a group which
//| contains the "web" middleware group. Now create something great!
//|
//*/
//
////Route::prefix('core')->group(function() {
////    Route::get('/', 'CoreController@index');
////});
//
//
//use Illuminate\Support\Facades\Route;
//
//use Modules\Core\Http\Controllers\ItemController;
//use Modules\Core\Http\Controllers\RoleController;
//use Modules\Core\Http\Controllers\UserController;
//use Modules\Core\Http\Controllers\AboutController;
//use Modules\Core\Http\Controllers\ImageController;
//use Modules\Core\Http\Controllers\ApiKeyController;
//use Modules\Core\Http\Controllers\ModuleController;
//use Modules\Core\Http\Controllers\RejectController;
//use Modules\Core\Http\Controllers\DisableController;
//
//use Modules\Core\Http\Controllers\PendingController;
//
//use Modules\Core\Http\Controllers\ProductController;
//use Modules\Core\Http\Controllers\CategoryController;
//use Modules\Core\Http\Controllers\CurrencyController;
//use Modules\Core\Http\Controllers\DeeplinkController;
//use Modules\Core\Http\Controllers\LanguageController;
//use Modules\Core\Http\Controllers\ShippingController;
//use Modules\Core\Http\Controllers\MenuGroupController;
//use Modules\Core\Http\Controllers\BannedUserController;
//use Modules\Core\Http\Controllers\ItemReportController;
//use Modules\Core\Http\Controllers\CoreKeyTypeController;
//use Modules\Core\Http\Controllers\SubcategoryController;
//use Modules\Core\Http\Controllers\TransactionController;
//use Modules\Core\Http\Controllers\LocationCityController;
//use Modules\Core\Http\Controllers\SubMenuGroupController;
//use Modules\Core\Http\Controllers\ComplaintItemController;
//use Modules\Core\Http\Controllers\MobileSettingController;
//use Modules\Core\Http\Controllers\PaymentStatusController;
//use Modules\Core\Http\Controllers\PrivacyPolicyController;
//use Modules\Core\Http\Controllers\BackendSettingController;
//use Modules\Core\Http\Controllers\LanguageStringController;
//use Modules\Core\Http\Controllers\MobileLanguageController;
//use Modules\Core\Http\Controllers\FrontendSettingController;
//use Modules\Core\Http\Controllers\LocationTownshipController;
//use Modules\Core\Http\Controllers\TransactionStatusController;
//use Modules\Core\Http\Controllers\MobileLanguageStringController;
//
//Route::prefix('core')->middleware(['auth:sanctum', 'verified'])->group(function (){
////    Route::resource('/core-category',SubcategoryController::class);
//
//    Route::controller(ProductController::class)->group(function (){
//
//        Route::get('/product/core-field-filter-setting',"filterForCoreField")->name("product.coreFieldFilterSetting");
//        Route::post('/product/core-field-filter-setting',"filterForCoreFieldStore")->name("product.coreFieldFilterSetting.store");
//
//        Route::post('/product/screen-display-ui-setting',"screenDisplayUiStore")->name("product.screenDisplayUiSetting.store");
//
//
//
//        Route::get('/product/setting','customization')->name("product.customization");
//        Route::delete('/product/setting/{customizeHeader}','customizationDestroy')->name("product.customization.delete");
//
//        Route::get('/product/setting/customizationDetailIndex/{coreKeysId}','customizationDetailIndex')->name("product.customizationDetail.index");
//        Route::get('/product/setting/customizationDetail/{coreKeysId}/edit','customizationDetailCreate')->name("product.customizationDetail.create");
//        Route::post('/product/setting/customizationDetail','customizationDetailStore')->name("product.customizationDetail.store");
//        Route::delete('/product/setting/customizationDetail/{id}','customizationDetailDestroy')->name("product.customizationDetail.destroy");
//        Route::get('/product/setting/customizationDetailEdit/{id}','customizationDetailEdit')->name("product.customizationDetail.edit");
//        Route::put('/product/setting/customizationDetailUpdate/{id}','customizationDetailUpdate')->name("product.customizationDetail.update");
//
//
//
//
//
//        Route::get('/product/setting/addNewField','addNewField')->name("product.addNewField");
//        Route::post('/product/setting/addNewField','addNewFieldStore')->name("product.addNewField.store");
//        Route::put('/product/setting/addNewFieldUpdate/{id}','addNewFieldUpdate')->name('product.addNewField.update');
//
//        Route::get('/product/setting/delete-temporary-fields','deletedTemFields')->name("product.deletedTemFields");
//        Route::put('/product/setting/undelete/{customizeHeader}','undeletedForCustomization')->name("product.customization.undelete");
//
//
//        Route::put('/product/setting/enableOrDisable/{id}','enableOrDisableField')->name('product.addNewField.enableOrDisable');
//        Route::get('/product/export/configs','exportConfigs')->name('product.export.configs');
//        Route::post('/product/import/configs','importConfigs')->name('product.import.configs');
//    });
//
//    Route::resource('/product',ProductController::class);
//
//});
//
//    Route::resource('/core-key-type',CoreKeyTypeController::class);
//
//
//
//Route::prefix('admin')->group(function (){
//
//    Route::controller(DashboardController::class)->group(function (){
//        Route::get('/', 'index')->name('admin.index');
//    });
//
//    // For Currency
//    Route::resource('/currency',CurrencyController::class);
//    Route::controller(CurrencyController::class)->group(function (){
//        Route::put('/currency/status/{currency}', 'statusChange')->name('currency.statusChange');
//        Route::put('/currency/default/{currency}', 'defaultChange')->name('currency.defaultChange');
//    });
//
//    // For Location City
//    Route::resource('/city',LocationCityController::class);
//    Route::prefix('city')->controller(LocationCityController::class)->group(function (){
//        Route::put('/status/{city}', 'statusChange')->name('city.statusChange');
//        Route::post('/import/csv', 'importCSV')->name('city.import.csv');
//        // Route::get('/restore/{city}', 'restore')->name('city.restore');
//        // Route::get('/restore/all/city', 'restoreAll')->name('city.restoreAll');
//
//        // For Location City Setting
//        Route::prefix('setting')->group(function (){
//
//            // For Location City Custom Field
//            Route::prefix('custom_field')->group(function(){
//                // For custom field header
//                Route::get('/','customization')->name("city.customization");
//                Route::get('/create','addNewField')->name("city.addNewField");
//                Route::post('/store','addNewFieldStore')->name("city.addNewField.store");
//                Route::delete('/{custom_field_header}','customizationDestroy')->name("city.customization.delete");
//                Route::put('/optionOrMandatory/{custom_field_header}','optionalOrMandatoryChange')->name('city.addNewField.optionalOrMandatory');
//                Route::put('/enableOrDisable/{custom_field_header}','enableOrDisableChange')->name('city.addNewField.enableOrDisable');
//
//                // For custom field detail
//                Route::prefix('{custom_field_header}/detail')->group(function(){
//                    Route::get('/','customizationDetailIndex')->name("city.customizationDetail.index");
//                    Route::get('/create','customizationDetailCreate')->name("city.customizationDetail.create");
//                    Route::post('/','customizationDetailStore')->name("city.customizationDetail.store");
//                    Route::get('/{custom_field_detail}/edit','customizationDetailEdit')->name("city.customizationDetail.edit");
//                    Route::put('/{custom_field_detail}','customizationDetailUpdate')->name("city.customizationDetail.update");
//                    Route::delete('/{custom_field_detail}','customizationDetailDestroy')->name("city.customizationDetail.destroy");
//                });
//            });
//        });
//    });
//
//    // For Location Township
//    Route::resource('/township',LocationTownshipController::class);
//    Route::prefix('township')->controller(LocationTownshipController::class)->group(function (){
//        Route::put('/status/{township}', 'statusChange')->name('township.statusChange');
//        Route::post('/import/csv', 'importCSV')->name('township.import.csv');
//    });
//
//    // For Core Menu Group
//    Route::resource('/menu_group',MenuGroupController::class);
//
//    // For Core Sub Menu Group
//    Route::resource('/sub_menu_group',SubMenuGroupController::class);
//    Route::prefix('sub_menu_group')->controller(SubMenuGroupController::class)->group(function (){
//        Route::put('/status/{sub_menu_group}', 'statusChange')->name('sub_menu_group.statusChange');
//    });
//
//    // For Core Module
//    Route::resource('/module',ModuleController::class);
//    Route::prefix('module')->controller(ModuleController::class)->group(function (){
//        Route::put('/status/{module}', 'statusChange')->name('module.statusChange');
//    });
//
//    // For Language
//    Route::resource('/language',LanguageController::class);
//    Route::prefix('language')->controller(LanguageController::class)->group(function (){
//        Route::put('/status/{language}', 'statusChange')->name('language.statusChange');
//    });
//
//    // For language string
//    Route::resource('language/{language}/language_string',LanguageStringController::class);
//    Route::prefix('language/{language}/language_string')->controller(LanguageStringController::class)->group(function (){
//        Route::post('/import/csv', 'importCSV')->name('language_string.import.csv');
//    });
//
//    // For Mobile Language
//    Route::resource('/mobile_language',MobileLanguageController::class);
//    Route::prefix('mobile_language')->controller(MobileLanguageController::class)->group(function (){
//        Route::put('/status/{mobile_language}', 'statusChange')->name('mobile_language.statusChange');
//        Route::put('/enable_disable/{mobile_language}', 'enableDisable')->name('mobile_language.enableDisable');
//
//    });
//
//    // For mobile language string
//    Route::resource('mobile_language/{mobile_language}/mobile_language_string',MobileLanguageStringController::class);
//
//
//    // For Transaction
//    Route::prefix('transaction')->controller(TransactionController::class)->group(function (){
//        Route::get('/', 'index')->name('transaction.index');
//        Route::get('/{transaction}/edit', 'edit')->name('transaction.edit');
//        Route::put('/{transaction}', 'update')->name('transaction.update');
//        Route::delete('/{transaction}', 'destroy')->name('transaction.destroy');
//        Route::get('/csv/export', 'csvExport')->name('transaction.csv.export');
//    });
//
//    // For PaymentStatus
//    Route::resource('/payment_status',PaymentStatusController::class);
//
//    // For TransactionStatus
//    Route::resource('/transaction_status',TransactionStatusController::class);
//
//    // For Shipping
//    Route::resource('/shipping',ShippingController::class);
//    Route::prefix('shipping')->controller(ShippingController::class)->group(function (){
//        Route::put('/status/{shipping}', 'statusChange')->name('shipping.statusChange');
//    });
//
//    // For Api Key
//    Route::prefix('api_key')->controller(ApiKeyController::class)->group(function (){
//        Route::get('/', 'index')->name('api_key.index');
//        Route::post('/', 'store')->name('api_key.store');
//        Route::put('/{api_key}', 'update')->name('api_key.update');
//    });
//
//    // For Privacy Policy
//    Route::prefix('privacy_policy')->controller(PrivacyPolicyController::class)->group(function (){
//        Route::get('/', 'index')->name('privacy_policy.index');
//        Route::post('/', 'store')->name('privacy_policy.store');
//        Route::put('/{privacy_policy}', 'update')->name('privacy_policy.update');
//    });
//
//    // For About
//    Route::prefix('about')->controller(AboutController::class)->group(function (){
//        Route::get('/', 'index')->name('about.index');
//        Route::post('/', 'store')->name('about.store');
//        Route::put('/{about}', 'update')->name('about.update');
//    });
//
//    // For Category
//    Route::resource('/category',CategoryController::class);
//    Route::prefix('category')->group(function (){
//        Route::controller(CategoryController::class)->group(function(){
//            Route::put('/status/{category}', 'statusChange')->name('category.statusChange');
//            Route::post('/import/csv', 'importCSV')->name('category.import.csv');
//        });
//    });
//
//    // For Subategory
//    Route::resource('/subcategory',SubcategoryController::class);
//    Route::prefix('subcategory')->controller(SubcategoryController::class)->group(function (){
//        Route::put('/status/{subcategory}', 'statusChange')->name('subcategory.statusChange');
//        Route::post('/import/csv', 'importCSV')->name('subcategory.import.csv');
//    });
//
//    // For Image
//    Route::prefix('image')->controller(ImageController::class)->group(function (){
//        Route::delete('/{image}', 'destroy')->name('image.destroy');
//        Route::put('/{image}', 'update')->name('image.replace');
//    });
//
//
//    // For Item
//    Route::resource('/item',ItemController::class);
//    Route::prefix('item')->group(function (){
//        Route::controller(ItemController::class)->group(function(){
//            Route::put('/duplicate/{item}', 'duplicateRow')->name('item.duplicate');
//            Route::put('/deeplink/{item}', 'deeplink')->name('item.deeplink');
//            Route::put('/', 'search')->name('item.search');
//
//            // For Item Setting
//            Route::prefix('setting')->group(function (){
//
//                // For Item Custom Field
//                Route::prefix('custom_field')->group(function(){
//                    // For custom field header
//                    Route::get('/','customization')->name("item.customization");
//                    Route::get('/create','addNewField')->name("item.addNewField");
//                    Route::post('/store','addNewFieldStore')->name("item.addNewField.store");
//                    Route::delete('/{custom_field_header}','customizationDestroy')->name("item.customization.delete");
//                    Route::put('/optionOrMandatory/{custom_field_header}','optionalOrMandatoryChange')->name('item.addNewField.optionalOrMandatory');
//                    Route::put('/enableOrDisable/{custom_field_header}','enableOrDisableChange')->name('item.addNewField.enableOrDisable');
//
//                    // For custom field detail
//                    Route::prefix('{custom_field_header}/detail')->group(function(){
//                        Route::get('/','customizationDetailIndex')->name("item.customizationDetail.index");
//                        Route::get('/create','customizationDetailCreate')->name("item.customizationDetail.create");
//                        Route::post('/','customizationDetailStore')->name("item.customizationDetail.store");
//                        Route::get('/{custom_field_detail}/edit','customizationDetailEdit')->name("item.customizationDetail.edit");
//                        Route::put('/{custom_field_detail}','customizationDetailUpdate')->name("item.customizationDetail.update");
//                        Route::delete('/{custom_field_detail}','customizationDetailDestroy')->name("item.customizationDetail.destroy");
//                    });
//                });
//
//                // Config file export / import
//                Route::prefix('configs')->group(function(){
//                    Route::get('/export','exportConfigs')->name('item.configs.export');
//                    Route::post('/import','importConfigs')->name('item.configs.import');
//                });
//            });
//
//        });
//
//        // For Item Report
//        Route::prefix('report/item_report')->controller(ItemReportController::class)->group(function(){
//            Route::get('/', 'itemReportIndex')->name('item_report.index');
//            Route::get('/{item}', 'itemReportShow')->name('item_report.show');
//            Route::get('/csv/export', 'itemReportCsvExport')->name('item_report.csv.export');
//        });
//
//        // For Item Report
//        Route::prefix('report/complaint_item_report')->controller(ComplaintItemController::class)->group(function(){
//            Route::get('/', 'index')->name('complaint_item_report.index');
//            Route::get('/{item}', 'show')->name('complaint_item_report.show');
//            Route::delete('/{item}', 'destroy')->name('complaint_item_report.destroy');
//            Route::put('/{item}', 'statusChange')->name('complaint_item_report.statusChange');
//            Route::get('/csv/export', 'csvExport')->name('complaint_item_report.csv.export');
//        });
//
//        // For Item Approval
//        Route::prefix('approval')->group(function (){
//            // For Pending Items
//            Route::controller(PendingController::class)->group(function(){
//                Route::get('/pending', 'index')->name('pending_item.index');
//                Route::get('/pending/{item}/edit', 'edit')->name('pending_item.edit');
//                Route::put('/pending/{item}', 'statusChange')->name('pending_item.status.update');
//            });
//
//            // For Disable Items
//            Route::controller(DisableController::class)->group(function(){
//                Route::get('/disable', 'index')->name('disable_item.index');
//                Route::get('/disable/{item}/edit', 'edit')->name('disable_item.edit');
//                Route::put('/disable/{item}', 'statusChange')->name('disable_item.status.update');
//            });
//
//            // For Reject Items
//            Route::controller(RejectController::class)->group(function(){
//                Route::get('/reject', 'index')->name('reject_item.index');
//                Route::get('/reject/{item}', 'edit')->name('reject_item.edit');
//                Route::put('/reject/{item}', 'statusChange')->name('reject_item.status.update');
//            });
//        });
//
//
//    });
//
//    // For Banned User
//    Route::resource('/banned_user',BannedUserController::class);
//    Route::prefix('banned_user')->controller(BannedUserController::class)->group(function (){
//        Route::put('/ban/{banned_user}', 'ban')->name('banned_user.ban');
//    });
//
//    // For User
//    Route::resource('/user', UserController::class);
//    Route::prefix('user')->group(function (){
//
//        Route::controller(UserController::class)->group(function (){
//
//            Route::put('/status/{user}', 'statusChange')->name('user.statusChange');
//            Route::put('/ban/{user}', 'ban')->name('user.ban');
//
//            // For Profile
//            Route::get('/profile/{user}', 'profileEdit')->name('user.profile.edit');
//            Route::put('/profile/{user}', 'profileUpdate')->name('user.profile.update');
//
//            // For User Setting
//            Route::prefix('setting')->group(function (){
//
//                // For User Custom Field
//                Route::prefix('custom_field')->group(function(){
//                    // For custom field header
//                    Route::get('/','customization')->name("user.customization");
//                    Route::get('/create','addNewField')->name("user.addNewField");
//                    Route::post('/store','addNewFieldStore')->name("user.addNewField.store");
//                    Route::delete('/{custom_field_header}','customizationDestroy')->name("user.customization.delete");
//                    Route::put('/optionOrMandatory/{custom_field_header}','optionalOrMandatoryChange')->name('user.addNewField.optionalOrMandatory');
//                    Route::put('/enableOrDisable/{custom_field_header}','enableOrDisableChange')->name('user.addNewField.enableOrDisable');
//
//                    // For custom field detail
//                    Route::prefix('{custom_field_header}/detail')->group(function(){
//                        Route::get('/','customizationDetailIndex')->name("user.customizationDetail.index");
//                        Route::get('/create','customizationDetailCreate')->name("user.customizationDetail.create");
//                        Route::post('/','customizationDetailStore')->name("user.customizationDetail.store");
//                        Route::get('/{custom_field_detail}/edit','customizationDetailEdit')->name("user.customizationDetail.edit");
//                        Route::put('/{custom_field_detail}','customizationDetailUpdate')->name("user.customizationDetail.update");
//                        Route::delete('/{custom_field_detail}','customizationDetailDestroy')->name("user.customizationDetail.destroy");
//                    });
//                });
//            });
//        });
//
//        // For User Report
//        Route::prefix('report')->controller(UserReportController::class)->group(function(){
//            Route::get('/buyer_report', 'buyerReportIndex')->name('buyer_report.index');
//            Route::get('/buyer_report/{user}', 'buyerReportShow')->name('buyer_report.show');
//            Route::get('/buyer_report/csv/export', 'buyerReportCsvExport')->name('buyer_report.csv.export');
//
//            Route::get('/seller_report', 'sellerReportIndex')->name('seller_report.index');
//            Route::get('/seller_report/{user}', 'sellerReportShow')->name('seller_report.show');
//            Route::get('/seller_report/csv/export', 'sellerReportCsvExport')->name('seller_report.csv.export');
//        });
//
//        // User Role
//        Route::resource('/user_role',RoleController::class);
//        Route::controller(RoleController::class)->group(function (){
//            Route::put('/role/status/{item}', 'statusChange')->name('user_role.statusChange');
//        });
//    });
//
//    // For Backend Setting
//    Route::prefix('backend_setting')->controller(BackendSettingController::class)->group(function (){
//        Route::get('/', 'index')->name('backend_setting.index');
//        Route::post('/', 'store')->name('backend_setting.store');
//        Route::put('/{backend_setting}', 'update')->name('backend_setting.update');
//    });
//
//    // For Mobile Setting
//    Route::prefix('mobile_setting')->controller(MobileSettingController::class)->group(function (){
//        Route::get('/', 'index')->name('mobile_setting.index');
//        Route::post('/', 'store')->name('mobile_setting.store');
//        Route::put('/{mobile_setting}', 'update')->name('mobile_setting.update');
//    });
//
//    // For Frontend Setting
//    Route::prefix('frontend_setting')->controller(FrontendSettingController::class)->group(function (){
//        Route::get('/', 'index')->name('frontend_setting.index');
//        Route::post('/', 'store')->name('frontend_setting.store');
//        Route::put('/{frontend_setting}', 'update')->name('frontend_setting.update');
//    });
//
//    // For Deeplink Generator
//    Route::prefix('deeplink_generator')->controller(DeeplinkController::class)->group(function (){
//        Route::get('/', 'index')->name('deeplink_generator.index');
//        Route::put('/', 'deeplink')->name('deeplink_generator.update');
//    });
//
//    // For Contact Us Message
//    Route::prefix('contact')->controller(ContactUsMessageController::class)->group(function (){
//        Route::get('/', 'index')->name('contact.index');
//        Route::get('/{contact}', 'show')->name('contact.show');
//        Route::get('/csv/export', 'csvExport')->name('contact.csv.export');
//    });
//
//});
//
//
