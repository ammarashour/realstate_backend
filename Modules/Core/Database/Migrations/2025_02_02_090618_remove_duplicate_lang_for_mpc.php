<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Core\Entities\Localization\FeLanguageString;
use Modules\Core\Entities\Localization\VendorLanguageString;
use Modules\Core\Entities\Project;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // This migration will only work for MPC
        try{
            $project = Project::first();
            if($project->base_project_id == 11){
                $this->removeFeDuplicateLangStrings();
                $this->removeVendorDuplicateLangStrings();
            }

        }catch(Exception $_) {}
    }

    private function removeFeDuplicateLangStrings()
    {
        $feDuplicateLangKeys = [
            'ui_collection__select_location',
            'ui_collection__ok',
            'ui_collection__white_dark',
            'ui_collection__label_ui',
            'ui_collection__label_h1',
            'ui_collection__label_h2',
            'ui_collection__label_h3',
            'ui_collection__label_h4',
            'ui_collection__label_h5',
            'ui_collection__label_h6',
            'ui_collection__label_title1',
            'ui_collection__label_title2',
            'ui_collection__label_title3',
            'ui_collection__body',
            'ui_collection__label_caption',
            'ui_collection__label_caption2',
            'ui_collection__input_ui',
            'ui_collection__email',
            'ui_collection__password',
            'ui_collection__what_are_you_looking_for',
            'ui_collection__button',
            'ui_collection__warning_button',
            'ui_collection__success_button',
            'ui_collection__error_button',
            'ui_collection__second_button',
            'ui_collection__primary_button',
            'ui_collection__disabled_button',
            'ui_collection__checkbox_fixed',
            'ui_collection__apple',
            'ui_collection__orange',
            'ui_collection__grape',
            'ui_collection__mango',
            'ui_collection__checkbox_dynamic',
            'ui_collection__radio_fixed',
            'ui_collection__radio_dynamic',
            'ui_collection__select_dynamic',
            'ui_collection__test',
            'ui_collection__transaction_history',
            'ui_collection__icons',
            'ui_collection__icon_form_assets',
            'ui_collection__icon_60_60',
            'ui_collection__images',
            'ui_collection__full_page_loading',
            'ui_collection__countdown',
            'ui_collection__speak',
            'ui_collection__modal',
            'ui_collection__open',
            'ui_collection__open_map_modal',
            'ui_collection__skeletor',
            'ps_dropdown_select__chooseone',
            'ps_model_view__title',
            'ps_model_view__message',
            'ps_model_view__save',
            'ps_model_view__end',
            'ps_confirm_dialog__confirm',
            'ps_confirm_dialog__are_you_confirm',
            'ps_confirm_dialog__yes',
            'ps_confirm_dialog__no',
            'ps_error_dialog__error',
            'ps_error_dialog__error_message',
            'ps_loading_dialog__loading',
            'ps_loading_dialog__please_wait',
            'ps_success_dialog__success',
            'ps_success_dialog__success_message',
            'ps_warning_dialog__warning',
            'ps_warning_dialog__warning_message',
            'ps_nav_bar__blogs',
            'ps_nav_bar__contact_us',
            'ps_nav_bar__support',
            'ps_nav_bar__submit_ad',
            'fe_paidad_item__progress',
            'fe_paidad_item__completed',
            'fe_paidad_item__not_reached',
            'fe_paidad_item__wait_approval',
            'fe_paidad_item__wait_rejected',
            'core__be_forgot_password_title',
            'core__fe_name_descriotion',
            'core__be_enter_valid_price',
            'payment_method',
            'shipping_address',
            'billing_address',
            'order_date',
            'delivery_charges',
            'order_summary'
        ];
        // i want to remove feDuplicateLangKeys from psx_fe_languages_strings
        FeLanguageString::whereIn('key', $feDuplicateLangKeys)->delete();
        
    }

    private function removeVendorDuplicateLangStrings()
    {
        $feDuplicateLangKeys = [
            'reject_vendor_module',
            'core_be__reject_vendor_details',
            'core__vendor_my_store_module',
            'core__vendor_item_list_module',
            'core__vendor_payment_module',
            'core__vendor_status_management_module',
            'core__be_option_5_flutterwave_payment',
            'flutterwave_setting',
            'core__be_flutterwave_enabled'
        ];
        // i want to remove feDuplicateLangKeys from psx_fe_languages_strings
        VendorLanguageString::whereIn('key', $feDuplicateLangKeys)->delete();
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
};
