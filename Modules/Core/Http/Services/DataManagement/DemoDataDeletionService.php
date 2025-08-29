<?php

namespace Modules\Core\Http\Services\DataManagement;

use App\Http\Services\PsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Constants\Constants;
use Modules\Core\Entities\CoreImage;
use Modules\Core\Entities\DataManagement\DataResetlog;
use Modules\Core\Http\Services\ImageService;
use Modules\Core\Http\Services\MenuGroupService;

class DemoDataDeletionService extends PsService
{
    protected $menuGroupService, $superAdminRoleId, $normalUserRoleId, $imageService;

    protected $upload_path = 'storage/uploads/';
    protected $thumb1x_path = 'storage/thumbnail/';
    protected $thumb2x_path = 'storage/thumbnail2x/';
    protected $thumb3x_path = 'storage/thumbnail3x/';

    public function __construct(MenuGroupService $menuGroupService, ImageService $imageService)
    {
        $this->menuGroupService = $menuGroupService;
        $this->imageService = $imageService;

        $this->superAdminRoleId= Constants::superAdminRoleId;
        $this->normalUserRoleId = Constants::normalUserRoleId;
        $this->superAdminRoleId = Constants::superAdminRoleId;
        $this->normalUserRoleId = Constants::normalUserRoleId;
    }

    public function index()
    {
        $relation = ['sub_menu_group'];
        $menus = $this->menuGroupService->getMenuGroups($relation);
        $dataArr = [
            "menus" => $menus,
        ];
        return $dataArr;
    }

    public function destroy(){
//        $systemTables = ['ad_post_types', 'authorizations', 'available_currencies', 'backend_settings', 'core_keys', 'core_field_filter_settings', 'core_reset_codes', 'core_key_types', 'core_data_deletions', 'core_privacy_policies', 'core_menu_groups', 'core_modules', 'core_sub_menu_groups', 'frontend_settings', 'languages', 'language_strings', 'migrations', 'mobile_languages', 'mobile_language_strings', 'mobile_settings', 'modules', 'permissions', 'personal_access_tokens', 'ui_types', 'payments','payment_infos', 'payment_attributes', 'payment_statuses', 'core_key_payment_relations', 'sessions' ];
//        $someRemoveTables = [ 'roles', 'role_permissions', 'users', 'user_permissions', 'core_key_counters', 'core_images' ];

        // System Tables
        $systemTables = ['psx_block_users', 'psx_blogs', 'psx_blue_mark_users', 'psx_categories', 'psx_chat_histories', 'psx_chat_notis', 'psx_contacts', 'psx_currencies', 'psx_customize_ui_details', 'psx_favourites', 'psx_item_discounts', 'psx_follow_users', 'psx_item_infos', 'psx_item_reports', 'psx_items', 'psx_location_cities', 'psx_location_city_infos', 'psx_location_townships',
                        'psx_package_bought_transactions', 'psx_paid_item_histories', 'psx_push_notification_messages', 'psx_push_notification_tokens', 'psx_push_notification_users', 'psx_ratings', 'psx_search_histories', 'psx_subcat_subscribes', 'psx_subcategories', 'psx_touches', 'psx_user_boughts', 'psx_user_infos', 'psx_vendors', 'psx_vendor_applications', 'psx_vendor_branches',
                        'psx_vendor_delivery_settings', 'psx_vendor_infos', 'psx_vendor_transactions', 'psx_orders', 'psx_order_items', 'psx_order_statuses'];
        $someRemoveSystemTables = ['psx_core_images', 'psx_user_permissions', 'psx_role_permissions'];

        // default Include Tables
        $defaultIncludeTables = ['team_invitations', 'team_user', 'teams'];
        $someRemoveDefaultIncludeTables = ['users'];

        // Include In Ecommerce Tables
        $ecommerceTables = ['psx_shippings', 'psx_shop_custom_fields', 'psx_shops', 'psx_transaction_counts', 'psx_transaction_details', 'psx_transaction_headers', 'psx_transaction_statuses', 'psx_user_address_information', 'psx_user_shops'];

        $tables = DB::select('SHOW TABLES');

        foreach ($tables as $table) {
                foreach ($table as $key => $value) {
                    if(in_array($value, $systemTables)) {

                        DB::table($value)->delete();

                    } else if(in_array($value, $someRemoveSystemTables)) {

                        if($value == 'psx_user_permissions' ){
                            $user_role_conds['user_id'] = $this->superAdminRoleId;
                            $user_role_conds['role_id'] = $this->superAdminRoleId;
                            DB::table($value)->whereNot($user_role_conds)->delete();
                        } else if($value == 'psx_role_permissions' ){
                            $role_permission_conds['role_id'] = $this->superAdminRoleId;
                            DB::table($value)->whereNot($role_permission_conds)->delete();
                        }else if($value == 'psx_core_images'){
                            $img_conds = ['backend-login-image', 'backend-logo', 'fav-icon', 'about'];
                            $images = $this->imageService->getImages(null, null, null, null, $img_conds)->pluck('img_path');

                            // delete file first
                            foreach ($images as $image) {
                                Storage::delete($this->upload_path . $image);
                                Storage::delete($this->thumb1x_path . $image);
                                Storage::delete($this->thumb2x_path . $image);
                                Storage::delete($this->thumb3x_path . $image);
                            }

                            // delete data from db second
                            DB::table($value)->whereNotIn('img_type', $img_conds)->delete();

                        }

                    } else if(in_array($value, $defaultIncludeTables)) {

                        DB::table($value)->delete();

                    } else if(in_array($value, $someRemoveDefaultIncludeTables)) {

                        if($value == 'users'){
                            $user_conds = [$this->superAdminRoleId];
                            DB::table($value)->whereNotIn('role_id', $user_conds)->delete();
                        }

                    } else if(in_array($value, $ecommerceTables)) {
                        DB::table($value)->delete();
                    }
                }
            }

        $dataResetLog = new DataResetlog();
        $dataResetLog->user_id = Auth::id();
        $dataResetLog->date = Carbon::now();
        $dataResetLog->save();

        $activityLogs = [
            __('block_users_tb_del'),
            __('blogs_tb_del'),
            __('blue_mark_users_tb_del'),
            __('categories_tb_del'),
            __('chat_histories_tb_del'),
            __('chat_notis'),
            __('contacts_tb_del'),
            __('currencies_tb_del'),
            __('customize_ui_details_tb_del'),
            __('favourites_tb_del'),
            __('item_discounts_tb_del'),
            __('follow_users_tb_del'),
            __('item_infos_tb_del'),
            __('item_reports_tb_del'),
            __('items_tb_del'),
            __('location_cities_tb_del'),
            __('location_city_infos_tb_del'),
            __('location_townships_tb_del'),
            __('package_bought_transactions_tb_del'),
            __('paid_item_histories_tb_del'),
            __("push_notification_messages_tb_del"),
            __('push_notification_tokens_tb_del'),
            __('push_notification_users_tb_del'),
            __('ratings_tb_del'),
            __('search_histories_tb_del'),
            __('subcat_subscribes_tb_del'),
            __('subcategories_tb_del'),
            __('touches__tb_del'),
            __('user_boughts_tb_del'),
            __('user_infos_tb_del'),
            __('core_images_tb_del'),
            __('user_permissions_tb_del'),
            __('role_permissions_tb_del'),
            __('team_invitations_tb_del'),
            __('team_user_tb_del'),
            __('teams_tb_del'),
            __('users_tb_del'),
            __('shippings_tb_del'),
            __('shop_custom_fields_tb_del'),
            __('shops_tb_del'),
            __('transaction_counts_tb_del'),
            __('transaction_details_tb_del'),
            __('transaction_headers_tb_del'),
            __('transaction_statuses_tb_del'),
            __('user_address_information_tb_del'),
            __('user_shops_tb_del'),
            __('vendors_tb_del'),
            __('vendor_applications_tb_del'),
            __('vendor_branches_tb_del'),
            __('vendor_delivery_settings_tb_del'),
            __('vendor_infos_tb_del'),
            __('vendor_transactions_tb_del'),
            __('orders_tb_del'),
            __('order_items_tb_del'),
            __('order_statuses_tb_del')
        ];

        $dataArr = [
           "activityLogs" => $activityLogs
        ];

        return $dataArr;

    }
}
