<template>
    <Head :title="$t('core_mb_setting_mobile_setting_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="bg-background dark:bg-secondaryDark-black rounded-lg ">
                <!-- card header start -->
                <div class="bg-primary-50 flex items-center dark:bg-primary-900 py-2.5 ps-4 rounded-t-lg">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100"> {{ $t(title) }} </ps-label-header-6>



                    <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                        <template #content>
                            <div class="h-8 flex items-center">
                                <ps-icon name="info" w="20" h="20" class="ms-2.5 transition-all duration-300 text-primary-500" />
                            </div>
                        </template>
                        <template #tooltips>
                            For more details: <a target="_blank" :href="docu" class="underline">Refer to this doc</a>
                        </template>
                    </ps-tooltip>



                </div>
                <!-- card header end -->

                <form @submit.prevent="handleSubmit()">
                    <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4">
                        <div>
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[0].label">
                                <div>
                                    <ps-label class="text-base">{{ $t('core_mb_setting_google_play_url') }}</ps-label>
                                    <ps-input type="text" v-model:value="form.google_playstore_url" :placeholder="$t('core_mb_setting_google_play_url')"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{ $t('core_mb_setting_app_store_url') }}</ps-label>
                                    <ps-input type="text" v-model:value="form.apple_appstore_url" :placeholder="$t('core_mb_setting_app_store_url')"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{ $t('core_mb_setting_ios_store_id') }}</ps-label>
                                    <ps-input type="text" v-model:value="form.ios_appstore_id" :placeholder="$t('core_mb_setting_ios_store_id')"/>
                                </div>
                            </div>
                            <!-- End Mobile setting-->
                            <!-- <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[1].label">
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_promote_first_choice_day')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.promote_first_choice_day" :placeholder="$t('core_mb_setting_promote_first_choice_day')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_promote_second_choice_day')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.promote_second_choice_day" :placeholder="$t('core_mb_setting_promote_second_choice_day')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_promote_third_choice_day')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.promote_third_choice_day" :placeholder="$t('core_mb_setting_promote_third_choice_day')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_promote_fourth_choice_day')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.promote_fourth_choice_day" :placeholder="$t('core_mb_setting_promote_fourth_choice_day')"
                                        @keypress="onlyNumber"/>
                                </div>
                            </div> -->
                            <!-- End Promotion setting -->
                            <!-- Start Image setting -->
                            <!-- <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[2].label">
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_profile_image_size')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.profile_image_size" :placeholder="$t('core_mb_setting_profile_image_size')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_upload_image_size')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.upload_image_size" :placeholder="$t('core_mb_setting_upload_image_size')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_chat_image_size')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.chat_image_size" :placeholder="$t('core_mb_setting_chat_image_size')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{ $t('core_mb_setting_bluemark_size') }}</ps-label>
                                    <ps-input type="text" v-model:value="form.blue_mark_size" :placeholder="$t('core_mb_setting_bluemark_size')"
                                        @keypress="onlyNumber" />
                                </div>
                            </div> -->
                            <!-- End Image setting -->
                            <!-- Start AdMob setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[1].label">
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_item_detail_view_count_for_ads')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.item_detail_view_count_for_ads" :placeholder="$t('core_mb_setting_item_detail_view_count_for_ads')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_AdMob_setting_on')" v-model:value="form.is_show_admob" />
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_is_show_admob_inside_item')" v-model:value="form.is_show_ads_in_item_detail" />
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_android_admob_banner_ad_unit_id')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.android_admob_banner_ad_unit_id" :placeholder="$t('core_mb_setting_android_admob_banner_ad_unit_id')"
                                        @keypress="onlyNumber"/>
                                </div>
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_android_admob_native_unit_id')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.android_admob_native_unit_id" :placeholder="$t('core_mb_setting_android_admob_native_unit_id')"
                                        @keypress="onlyNumber"/>
                                </div>
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_android_admob_interstitial_ad_unit_id')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.andorid_admob_interstitial_ad_unit_id" :placeholder="$t('core_mb_setting_android_admob_interstitial_ad_unit_id')"
                                        @keypress="onlyNumber"/>
                                </div>
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_ios_admob_banner_ad_unit_id')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.ios_admob_banner_ad_unit_id" :placeholder="$t('core_mb_setting_ios_admob_banner_ad_unit_id')"
                                        @keypress="onlyNumber"/>
                                </div>
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_ios_admob_native_unit_id')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.ios_admob_native_ad_unit_id" :placeholder="$t('core_mb_setting_ios_admob_native_unit_id')"
                                        @keypress="onlyNumber"/>
                                </div>
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_ios_admob_interstitial_ad_unit_id')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.ios_admob_interstitial_ad_unit_id" :placeholder="$t('core_mb_setting_ios_admob_interstitial_ad_unit_id')"
                                        @keypress="onlyNumber"/>
                                </div>
                            </div>
                            <!-- End AdMob setting -->
                            <!-- Start Login setting -->
                            <!-- <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[4].label">
                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_facebook_login_on')" v-model:value="form.show_facebook_login" />
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{$t('core_mb_setting_facebook_login_on_info')}}</ps-label-caption>
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_phone_login_on')" v-model:value="form.show_phone_login" />
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{$t('core_mb_setting_phone_login_on_info')}}</ps-label-caption>
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_google_login_on')" v-model:value="form.show_google_login" />
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{$t('core_mb_setting_google_login_on_info')}}</ps-label-caption>
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_is_force_login')" v-model:value="form.is_force_login" />
                                </div>
                            </div> -->
                            <!-- End Login setting -->
                            <!-- Start Dashboard setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[2].label">

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_main_menu_on')" v-model:value="form.show_main_menu" />
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{$t('core_mb_setting_main_menu_on_info')}}</ps-label-caption>
                                </div>

                                <!-- <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_special_collections_on')" v-model:value="form.show_special_collections" />
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{$t('core_mb_setting_special_collections_on_info')}}</ps-label-caption>
                                </div> -->

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_featured_items_on')" v-model:value="form.show_featured_items" />
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{$t('core_mb_setting_featured_items_on_info')}}</ps-label-caption>
                                </div>

                                <!-- <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_best_choice_slider_on')" v-model:value="form.show_best_choice_slider" />
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{$t('core_mb_setting_best_choice_slider_on_info')}}</ps-label-caption>
                                </div> -->

                            </div>
                            <!-- End Dashboard setting -->
                            <!-- Start Default Limit setting -->
                            <!-- <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[6].label">
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_default_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.default_loading_limit" :placeholder="$t('core_mb_setting_default_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_category_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.category_loading_limit" :placeholder="$t('core_mb_setting_category_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_recent_item_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.recent_item_loading_limit" :placeholder="$t('core_mb_setting_recent_item_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_popular_item_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.popular_item_loading_limit" :placeholder="$t('core_mb_setting_popular_item_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_discount_item_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.discount_item_loading_limit" :placeholder="$t('core_mb_setting_discount_item_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_feature_item_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.featured_item_loading_limit" :placeholder="$t('core_mb_setting_feature_item_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_block_slider_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.block_slider_loading_limit" :placeholder="$t('core_mb_setting_block_slider_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_follower_item_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.follower_item_loading_limit" :placeholder="$t('core_mb_setting_follower_item_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_block_item_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.block_item_loading_limit" :placeholder="$t('core_mb_setting_block_item_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_recent_search_keyword_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.recent_search_keyword_limit" :placeholder="$t('core_mb_setting_recent_search_keyword_limit')"
                                        @keypress="onlyNumber"/>
                                </div>
                                 <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_collection_item_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.collection_item_loading_limit" :placeholder="$t('core_mb_setting_collection_item_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_trending_item_loading_limit')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.trending_item_loading_limit" :placeholder="$t('core_mb_setting_trending_item_loading_limit')"
                                        @keypress="onlyNumber"/>
                                </div> 
                            </div> -->
                            <!-- End Default Limit setting -->
                            <!-- Start Version setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[3].label">
                                <div>
                                    <ps-label>{{$t('core_mb_setting_version_no')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.version_no" :placeholder="$t('core_mb_setting_version_no')"
                                        />
                                </div>

                                <div>
                                    <ps-label>{{$t('core_mb_setting_version_title')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.version_title" :placeholder="$t('core_mb_setting_version_title')"
                                        />
                                </div>

                                <div>
                                    <ps-label>{{$t('core_mb_setting_version_message')}}</ps-label>
                                    <ps-textarea rows="4" v-model:value="form.version_message" :placeholder="$t('core_mb_setting_version_message')"></ps-textarea>
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_version_force_update')" v-model:value="form.version_force_update" />
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_version_need_clear_data')" v-model:value="form.version_need_clear_data" />
                                </div>

                                <!-- <div>
                                    <ps-label>{{$t('core_mb_setting_deliboy_app_version_no')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.deli_boy_version_no" :placeholder="$t('core_mb_setting_deliboy_app_version_no')"
                                        />
                                </div>

                                <div>
                                    <ps-label>{{$t('core_mb_setting_deliboy_app_version_title')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.deli_boy_version_title" :placeholder="$t('core_mb_setting_deliboy_app_version_title')"
                                        />
                                </div>

                                <div>
                                    <ps-label>{{$t('core_mb_setting_deliboy_app_version_message')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.deli_boy_version_message" :placeholder="$t('core_mb_setting_deliboy_app_version_message')"
                                        />
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_deliboy_app_version_force_update')" v-model:value="form.deli_boy_version_force_update" />
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_deliboy_app_version_need_clear_data')" v-model:value="form.deli_boy_version_need_clear_data" />
                                </div> -->
                            </div>
                            <!-- End Version setting -->
                            <!-- Start Color setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[4].label">
                                <!-- <div>
                                    <ps-label>{{$t('core_mb_setting_color_change_code')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.color_change_code" :placeholder="$t('core_mb_setting_color_change_code')"
                                        />
                                </div> -->
                                <div>
                                    <ps-label-header-4>{{$t('core_mb_setting_common_color')}}</ps-label-header-4>
                                    <div class="grid grid-cols-2">
                                        <div v-for="common_color in form.commonColor" :key="common_color.id">
                                            <div class="flex flex-row p-1">
                                                <div class="w-6 h-6 m-1 rounded shadow-sm cursor-pointer">
                                                    <div @click="colorPickerClicked(common_color)" class="w-6 h-6 rounded shadow-sm cursor-pointer" :style="{ 'background-color': common_color.value }"></div>
                                                </div>
                                                <ps-label class="leading-7">{{ common_color.title}}</ps-label>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <ps-label-header-4>{{$t('core_mb_setting_light_color')}}</ps-label-header-4>
                                    <div class="grid grid-cols-2">
                                        <div v-for="light_color in form.lightColor" :key="light_color.id">
                                            <div class="flex flex-row p-1">
                                                <div class="w-6 h-6 m-1 rounded shadow-sm cursor-pointer">
                                                    <div  @click="colorPickerClicked(light_color)" class="w-6 h-6 rounded shadow-sm cursor-pointer" :style="{ 'background-color': light_color.value }"></div>
                                                </div>
                                                <ps-label class="leading-7">{{ light_color.title}}</ps-label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <ps-label-header-4>{{$t('core_mb_setting_dark_color')}}</ps-label-header-4>
                                    <div class="grid grid-cols-2">
                                        <div v-for="dark_color in form.darkColor" :key="dark_color.id">
                                            <div class="flex flex-row p-1">
                                                <div class="w-6 h-6 m-1 rounded shadow-sm cursor-pointer">
                                                    <div @click="colorPickerClicked(dark_color)" class="w-6 h-6 rounded shadow-sm cursor-pointer" :style="{ 'background-color': dark_color.value }"></div>
                                                </div>
                                                <ps-label class="leading-7">{{ dark_color.title}}</ps-label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Color setting -->
                            <!-- Start Defautl Data setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[5].label">
                                <!-- for ad post type dropdown -->
                                <div class="">
                                    <ps-label class="text-base">{{$t('core_mb_setting_data_config_data_source_type')}}
                                    </ps-label>
                                    <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                        <template #select>
                                            <ps-dropdown-select :selectedValue="(form.data_config_data_source_type== '') ? '' : form.data_config_data_source_type"  placeholder="Select Type" />
                                        </template>
                                        <template #list>
                                            <div class="rounded-md shadow-xs w-full">
                                                <div class="pt-2 z-30 ">
                                                    <!-- <div class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                                                        <ps-label class="text-secondary-200">{{$t('core_sys_select_ad_post_type')}}</ps-label>
                                                    </div> -->
                                                    <div @click="form.data_config_data_source_type = 'FULL_CACHE'" class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                                                        <ps-label class="ms-2">FULL_CACHE</ps-label>
                                                    </div>
                                                    <div @click="form.data_config_data_source_type = 'SERVER_DIRECT'" class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center">
                                                        <ps-label class="ms-2">SERVER_DIRECT</ps-label>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </ps-dropdown>
                                </div>
                                <!-- end ad post type -->
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_data_config_day')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.data_config_day" :placeholder="$t('core_mb_setting_data_config_day')"
                                        @keypress="onlyNumber"/>
                                </div>
                            </div>
                            <!-- End Defautl Data setting -->
                             <!-- Start Slider setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[6].label">
                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_is_slider_autoplay')" v-model:value="form.is_slider_auto_play" />
                                </div>
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_slider_autoplay')}} (Milliseconds)</ps-label>
                                    <ps-input type="text" v-model:value="form.auto_play_interval" :placeholder="$t('core_mb_setting_slider_autoplay')"
                                        @keypress="onlyNumber"/>
                                </div>
                            </div>
                            <!-- End Slider setting -->
                            <!-- <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[11].label">
                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_default_razor_currency')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.default_razor_currency" :placeholder="$t('core_mb_setting_default_razor_currency')"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_default_flutter_currency')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.default_flutter_wave_currency" :placeholder="$t('core_mb_setting_default_flutter_currency')"/>
                                </div>

                                <div>
                                    <ps-checkbox-value :title="$t('core_mb_setting_razor_support_multi_currency_on')" v-model:value="form.is_razor_support_multi_currency" />
                                    <ps-label-caption textColor="text-secondary-400 mt-1">(If this setting is "On", you can accept razor payment in multi-currency in the application.)</ps-label-caption>
                                </div>
                            </div> -->
                             <!-- Start Other setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[7].label">

                                <div>
                                    <div class="flex items-center">
                                        <ps-label class="text-base">
                                            {{ $t('core_mb_setting_facebook_key') }}
                                        </ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-79305/1afb3b05d1416bd" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>

                                    <ps-input type="text" v-model:value="form.fb_key" :placeholder="$t('core_mb_setting_facebook_key')"/>
                                </div>

                                <!-- <div>
                                    <div class="flex items-center">
                                        <ps-label class="text-base">{{ $t('core_mb_setting_price_format') }}</ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-46383" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-input type="text" v-model:value="form.price_format" :placeholder="$t('core_mb_setting_price_format')"/>
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{ $t('core_mb_setting_price_format_ex') }}You need to change according to your format that you need (e.g ",##0.00" => 2,555.00, "##0.00" => 2555.00 ,".00" => 2555.00, ",##0" => 2,555, ",##0.0" => 2,555.0)</ps-label-caption>
                                </div> -->

                                <div>
                                    <div class="flex items-center">
                                        <ps-label class="text-base">{{ $t('core_mb_setting_date_format') }}</ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-80705/bb50824fae048c2" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-input type="text" v-model:value="form.date_format" :placeholder="$t('core_mb_setting_date_format')"/>
                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{ $t('core_mb_setting_date_format_ex') }}dd for the day, MM for the month, yyyy for the year, hh for the hour, mm for the minute, ss for the second (e.g. dd-MM-yyyy hh:mm:ss)</ps-label-caption>
                                </div>

                                <!-- <div>
                                    <div class="flex items-center">
                                        <ps-label class="text-base">{{$t('core_mb_setting_mile_range')}}</ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-46703" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-input type="text" v-model:value="form.mile" :placeholder="$t('core_mb_setting_mile_range')"
                                        @keypress="onlyNumber" />
                                </div> -->

                                <!-- <div>
                                    <ps-label>{{$t('core_mb_setting_default_order_time')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.default_order_time" :placeholder="$t('core_mb_setting_default_order_time')"
                                        @keypress="onlyNumber" />
                                </div> -->

                                <div class="flex items-center">
                                    <ps-checkbox-value :title="$t('core_mb_setting_use_google_map_on')" v-model:value="form.is_use_google_map" />
                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                        <template #content>
                                            <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                        </template>
                                        <template #tooltips>
                                            For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-81685/a727d26cbbd9977" class="underline">Refer to this doc</a>
                                        </template>
                                    </ps-tooltip>
                                </div>



                                <!-- <div>
                                    <div class="flex items-center">
                                        <ps-checkbox-value :title="$t('core_mb_setting_sub_cat_on')" v-model:value="form.is_show_subcategory" />
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-46723" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-label-caption textColor="text-secondary-400 mt-1"> {{$t('core__mb_setting_subcat_desc')}}</ps-label-caption>
                                </div>

                                <div>
                                    <div class="flex items-center">
                                        <ps-checkbox-value :title="$t('core_mb_setting_discount_on')" v-model:value="form.is_show_discount" />
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-56283" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-label-caption textColor="text-secondary-400 mt-1"> {{ $t('core__mb_setting_discount_desc') }}</ps-label-caption>
                                </div> -->

                                <!-- <div>
                                    <div class="flex items-center">
                                        <ps-checkbox-value :title="$t('core_mb_setting_thumbnail_as_placeholder')" v-model:value="form.is_use_thumbnail_as_placeholder" />
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-46843" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-label-caption textColor="text-secondary-400 mt-1"> {{ $t('core__mb_setting_thumbnail_desc') }}</ps-label-caption>
                                </div> -->
                                <!-- <div class="flex items-center">
                                    <ps-checkbox-value :title="$t('core_mb_setting_is_no_filter_with_location')" v-model:value="form.no_filter_with_location_on_map" />
                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                        <template #content>
                                            <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                        </template>
                                        <template #tooltips>
                                            For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-47463" class="underline">Refer to this doc</a>
                                        </template>
                                    </ps-tooltip>
                                </div> -->

                                <!-- <div class="flex items-center">
                                    <ps-checkbox-value :title="$t('core_mb_setting_is_show_info')" v-model:value="form.is_show_owner_info" />
                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                        <template #content>
                                            <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                        </template>
                                        <template #tooltips>
                                            For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-46363" class="underline">Refer to this doc</a>
                                        </template>
                                    </ps-tooltip>
                                </div> -->

                                <!-- <div class="flex items-center">
                                    <ps-checkbox-value :title="$t('core_mb_setting_is_show_item_video')" v-model:value="form.is_show_item_video" />
                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                        <template #content>
                                            <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                        </template>
                                        <template #tooltips>
                                            For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-56303" class="underline">Refer to this doc</a>
                                        </template>
                                    </ps-tooltip>
                                </div> -->
                                <div class="flex items-center">
                                    <ps-checkbox-value :title="$t('core_mb_setting_is_language_config')" v-model:value="form.is_language_config" />
                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                        <template #content>
                                            <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                        </template>
                                        <template #tooltips>
                                            For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-84745/0d66cec92b46197" class="underline">Refer to this doc</a>
                                        </template>
                                    </ps-tooltip>
                                </div>

                                <!-- <div class="flex items-center">
                                    <ps-checkbox-value :title="$t('core_mb_setting_is_demo_for_payment')" v-model:value="form.is_demo_for_payment" />
                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                        <template #content>
                                            <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                        </template>
                                        <template #tooltips>
                                            For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-57403" class="underline">Refer to this doc</a>
                                        </template>
                                    </ps-tooltip>
                                </div> -->

                                <div>
                                    <div class="flex items-center">
                                        <ps-label class="text-base">{{$t('core_mb_setting_item_loading_cell_count')}}</ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-85385/a92d0c602507852" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-input type="text" v-model:value="form.loading_shimmer_item_count" :placeholder="$t('core_mb_setting_item_loading_cell_count')"
                                        @keypress="onlyNumber"/>
                                </div>

                                <!-- <div>
                                    <div class="flex items-center">
                                        <ps-label class="text-base">{{$t('core_mb_setting_phone_list_count')}}</ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-57443" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-input type="text" v-model:value="form.phone_list_count" :placeholder="$t('core_mb_setting_phone_list_count')"
                                        @keypress="onlyNumber"/>
                                </div> -->

                                <!-- <div>
                                    <div class="flex items-center">
                                        <ps-label>{{$t('core_mb_setting_video_duration')}} (MilliSeconds)</ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-45763/8123917c95d1d7d/q5yqp-46683" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-input type="text" v-model:value="form.video_duration" :placeholder="$t('core_mb_setting_video_duration')"
                                        @keypress="onlyNumber" />
                                </div> -->



                                <!-- <div>
                                    <google-map-pin-picker :mapKey="$page.props.mapKey"  :lat="parseFloat(form.lat)" :lng="parseFloat(form.lng)" widthHeight="width: 458px; height: 500px"
                                        :onChange="updateCoordinates" />
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_latitude')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.lat" :placeholder="$t('core_mb_setting_latitude')"
                                        @keypress="onlyNumberWithCustom"
                                        @keyup="validateLatitudeInput('lat', form.lat)"
                                        @blur="validateLatitudeInput('lat', form.lat)"/>
                                </div>

                                <div>
                                    <ps-label class="text-base">{{$t('core_mb_setting_longitute')}}</ps-label>
                                    <ps-input type="text" v-model:value="form.lng" :placeholder="$t('core_mb_setting_longitute')"
                                        @keypress="onlyNumberWithCustom"
                                        @keyup="validateLongitudeInput('lng', form.lng)"
                                        @blur="validateLongitudeInput('lng', form.lng)"/>
                                </div> -->
                            </div>
                            <!-- End Other setting -->
                            <div class="flex flex-row justify-end mt-6 mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" viewBox="0 0 18 14" w="14" h="10" class="me-1.5 transition-all duration-300" />
                                    <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_saved') }}</ps-label>
                                    <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{ $t('core__be_btn_save') }} </ps-label>
                                </ps-button>
                            </div>
                        </div>
                        <!-- column 1 end -->

                        <div class="px-4">

                            <div v-for="column in settingColumn" :key="column.id">
                                <div @click="changeSection(column)"
                                    :class="title == column.label ? 'border-l border-s-primary-500' : 'border-l border-s-secondary-300'"
                                    class="px-2 py-3 cursor-pointer">
                                    <ps-label :textColor="title == column.label ? 'text-primary-500 dark:text-primary-500' : 'text-secondary-800 dark:text-white'" >
                                        {{ $t(column.label) }}
                                    </ps-label>
                                </div>
                            </div>

                        </div>

                    </div>
                </form>
            </div>
        </ps-card>

    </ps-layout>
    <PsColorPickerModal ref="ps_color_picker_modal" />
</template>

<script>
import { defineComponent, defineAsyncComponent, ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import { trans } from 'laravel-vue-i18n';
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsTooltips from '@/Components/Core/Tooltips/PsTooltips.vue';
import PsCheckboxValue from '@/Components/Core/Checkbox/PsCheckboxValue.vue';
import PsCheckbox from '@/Components/Core/Checkbox/PsCheckbox.vue';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";


import PsTooltip from '@/Components/Core/Tooltips/PsTooltip.vue';



const GoogleMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/GoogleMapPinPicker.vue'));
const PsColorPickerModal = defineAsyncComponent(() => import('@/Components/Core/Modals/PsColorPickerModal.vue'));

export default defineComponent({
    name: "Create",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption,
        PsBreadcrumb2,
        PsLabelHeader6,
        PsCheckboxValue,
        PsCheckbox,
        GoogleMapPinPicker,
        PsDropdown,
        PsDropdownSelect,
        PsColorPickerModal,
        PsIcon,
        PsTooltips,
        PsTooltip,
    },
    layout: PsLayout,
    props: ['errors', 'lightColor', 'darkColor', 'commonColor', 'available_languages','coreFieldFilterSettings'],
    setup(props) {
        // Client Side Validation
        const { isEmpty, isLatitude, isLongitude } = useValidators();

        const ps_color_picker_modal = ref();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
        }

        const validateLatitudeInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isLatitude(fieldName, fieldValue);
        }

        const validateLongitudeInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isLongitude(fieldName, fieldValue);
        }

        let form =useForm(
            {
                google_playstore_url: "",
                apple_appstore_url: "",
                ios_appstore_id: "",

                promote_first_choice_day: "",
                promote_second_choice_day: "",
                promote_third_choice_day: "",
                promote_fourth_choice_day: "",

                profile_image_size: "",
                upload_image_size: "",
                chat_image_size: "",
                blue_mark_size: "",

                item_detail_view_count_for_ads: "",
                is_show_admob: false,
                is_show_ads_in_item_detail: false,
                android_admob_banner_ad_unit_id: "",
                android_admob_native_unit_id: "",
                andorid_admob_interstitial_ad_unit_id: "",
                ios_admob_banner_ad_unit_id: "",
                ios_admob_native_ad_unit_id: "",
                ios_admob_interstitial_ad_unit_id: "",

                show_facebook_login: false,
                show_phone_login: false,
                show_google_login: false,
                show_apple_login: false,
                is_force_login: false,

                show_main_menu: false,
                show_featured_items: false,

                default_loading_limit: "",
                category_loading_limit: "",
                recent_item_loading_limit: "",
                popular_item_loading_limit: "",
                discount_item_loading_limit: "",
                featured_item_loading_limit: "",
                block_slider_loading_limit: "",
                follower_item_loading_limit: "",
                block_item_loading_limit: "",
                recent_search_keyword_limit: "",

                version_no: "",
                version_title: "",
                version_message: "",
                version_force_update: false,
                version_need_clear_data: false,

                data_config_data_source_type:"",
                data_config_day: "",

                is_slider_auto_play: false, //new field
                auto_play_interval: "",

                fb_key: "",
                price_format: "",
                date_format: "",
                mile: "",
                is_use_google_map: false,
                is_show_subcategory: false,
                is_show_discount: false,
                is_use_thumbnail_as_placeholder: false,
                no_filter_with_location_on_map: false,
                is_show_owner_info: false,
                is_show_item_video: false,
                is_language_config: false,
                is_demo_for_payment: false, //new field
                loading_shimmer_item_count:"",//new field
                phone_list_count: "", //new field
                video_duration: "",

                default_razor_currency: "",
                default_flutter_wave_currency: "",
                is_razor_support_multi_currency: false,

                commonColor: "",
                lightColor: "",
                darkColor: "",



                deli_boy_version_force_update: false,
                deli_boy_version_need_clear_data: false,
                //is_show_token_id: props.mobile_setting.is_show_token_id==1?true:false,
                after_item_count_admob_once: "",
                lat: "",
                lng: "",
                collection_item_loading_limit: "",
                trending_item_loading_limit: "",
                show_special_collections: false,
                show_best_choice_slider: false,
                default_order_time: "",
                deli_boy_version_no: "",
                deli_boy_version_title: "",
                deli_boy_version_message: "",
                color_change_code: "",

                shop_loading_limit: "",
            }
        )


        // for custom number input validate at keypress
        const onlyNumberWithCustom = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46 && keyCode !== 45) { // 46 is dot, 45 is dash
                $e.preventDefault();
            }
        }

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }

        function updateCoordinates(location) {
            form.lat = location.latLng.lat();
            form.lng = location.latLng.lng();
        }

        const settingColumn = [
            {
                label: 'core_mb_setting_play_store_and_app_store_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-77225/ce5c1cf35b2ced6'
            },
           
            {
                label: 'core_mb_setting_Admob_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-84845/909f7da12a4291b'

            },
           
            {
                label: 'core_mb_setting_dashboard_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-85445/bb2e7a3432269ff'

            },
           
            {
                label: 'core_mb_setting_version_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-75485/21439a9d395235a'

            },
            {
                label: 'core_mb_setting_color_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-159005/7eb64021ffd92ba'

            },
            {
                label: 'core_mb_setting_default_data_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-85745/85f0d458bcd42fb'

            },
            {
                label: 'core_mb_setting_slider_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-85805/d99a8aa0746478b'
            },

            {
                label: 'core_mb_setting_others_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-85925/f6bcd43fb4e19ab'

            },
        ];

        const title = ref(settingColumn[0].label);
        const docu = ref(settingColumn[0].docu);

        function changeSection(v){
            title.value = v.label;
            docu.value = v.docu;
        }

        function colorPickerClicked(data) {
            ps_color_picker_modal.value.openModal(
                data.value,
                (selectedColor) => {
                    console.log(selectedColor)
                    let colorForm = useForm({
                        value: selectedColor,
                        "_method": "put"
                    })
                    this.$inertia.post(route("color.update", data.id), colorForm,{
                        forceFormData: true,
                        onFinish: () => {
                            this.$inertia.get(route("mobile_setting.index",))
                        },
                        });
                },
                () => {}
            );
        }

        return {
                    ps_color_picker_modal,
            title,docu,
            settingColumn,
            changeSection,
            colorPickerClicked,
            form,validateEmptyInput, validateLatitudeInput, validateLongitudeInput, onlyNumberWithCustom, onlyNumber, updateCoordinates }
    },
    methods: {
        handleSubmit() {
            this.$inertia.post(route('mobile_setting.store'), this.form,{forceFormData: true});
        },
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('mobile_setting_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
