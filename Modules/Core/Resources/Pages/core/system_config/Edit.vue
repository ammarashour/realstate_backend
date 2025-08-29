
<template>
    <Head :title="$t('system_config_module')" />
    <ps-layout>
        <!-- {{ system_config.item_price_types.price_selections[0].value }} -->
        <div class="">
            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
            <!-- breadcrumb end -->
            <ps-card class="w-full h-auto">
                <!-- card header start -->
                <div class="bg-primary-50 flex items-center dark:bg-primary-900 py-2.5 ps-4 rounded-t-lg">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100"> {{ $t(title) }}
                    </ps-label-header-6>
                    <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                        <template #content>
                            <div class="h-8 flex items-center">
                                <ps-icon name="info" w="20" h="20"
                                    class="ms-2.5 transition-all duration-300 text-primary-500" />
                            </div>
                        </template>
                        <template #tooltips>
                            For more details: <a target="_blank" :href="docu" class="underline">Refer to this doc</a>
                        </template>
                    </ps-tooltip>
                </div>
                <!-- card header end -->
                <div class="bg-background dark:bg-secondaryDark-black rounded-lg ">
                    <form @submit.prevent="handleSubmit(system_config.id, mobile_setting.id)">
                        <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4">
                            <div>
                                
                                <!-- Start Item Configuration setting -->
                                <div v-if="title === (settingColumn[0]?.label || '')">

                                    <div class="px-4 py-0">
                                        <ps-accordion class="w-130 md:items-right">
                                            <template #title>
                                                <ps-label-header-5>{{ $t("core_sys_ad_post_type") }}</ps-label-header-5>
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t("core_sys_ad_post_type_desc") }}</ps-label>
                                                <br>
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_ad_post_type') }}
                                                </ps-label>
                                                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                                    <template #select>
                                                        <ps-dropdown-select placeholder="Select Ad Post Type"
                                                            :selectedValue="(sysForm.ad_type == '') ? '' : adTypes.filter(adType => adType.id == sysForm.ad_type)[0].value" />
                                                    </template>
                                                    <template #list>
                                                        <ps-label class="text-base">{{ 'Monetization settings to define Ad Slot placement.' }}</ps-label><br>
                                                        <div class="rounded-md shadow-xs ">
                                                            <div class="pt-2 z-30 ">
                                                                <div class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                                    @click="[sysForm.ad_type = '']">
                                                                    <ps-label
                                                                        class="text-secondary-200">{{ $t('core_sys_select_ad_post_type') }}</ps-label>
                                                                </div>
                                                                <div v-for="adType in adTypes" :key="adType.id"
                                                                    class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                                    @click="[sysForm.ad_type = adType.id]">
                                                                    <ps-label class="ms-2"
                                                                        :class="adType.id == sysForm.ad_type ? ' font-bold' : ''">
                                                                        {{ adType.value }} </ps-label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </ps-dropdown>
                                                
                                                <div class="py-3">
                                                    <ps-label class="text-base mb-1">{{ $t('core_sys_promate_cell') }}</ps-label>
                                                    <ps-input type="text" v-model:value="sysForm.promo_cell_interval_no"
                                                        :placeholder="$t('core_sys_input_number')" @keypress="onlyNumber" />
                                                    <ps-label-caption
                                                        textColor="text-secondary-400 mt-1">{{ $t('core_sys_promate_cell_nfo') }}
                                                    </ps-label-caption>
                                                </div>

                                            </template>

                                            

                                        </ps-accordion> <!-- setting1 -->
                                        
                                    </div>
                                    

                                    <div class="px-4 py-0">
                                        <ps-accordion class="w-130 md:items-right">
        
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_item_advance_setting') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_item_advance_setting_desc') }}</ps-label>
                                                <br>
                                                
                                                
                                                
                                                <!-- item price types -->
                                                                        
                                                    <div class="px-4 py-3">
                                                        <ps-label class="text-base">{{ $t('core__be_price_types') }}</ps-label>
                                                        <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                                            <template #select>
                                                                <ps-dropdown-select
                                                                :placeholder="$t('core__be_price_types_placeholder')"
                                                                    :selectedValue="(sysForm.selected_price_type == '') ? '' : system_config.item_price_types.price_selections.filter(price_type => price_type.id == sysForm.selected_price_type)[0].value" />
                                                            </template>
                                                            <template #list>
                                                                <div class="rounded-md shadow-xs ">
                                                                    <div class="pt-2 z-30 ">
                                                                        <div class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                                            @click="[sysForm.selected_price_type = '']">
                                                                            <ps-label
                                                                                class="text-secondary-200">{{ $t('core_sys_select_price_types') }}</ps-label>
                                                                        </div>
                                                                        <div v-for="price in system_config.item_price_types.price_selections"
                                                                            :key="price.id"
                                                                            class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                                            @click="[sysForm.selected_price_type = price.id]">
                                                                            <ps-label class="ms-2"
                                                                                :class="price.id == sysForm.selected_price_type ? ' font-bold' : ''">
                                                                                {{ price.value }} </ps-label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </ps-dropdown>
                                                    </div>
                                                    <!-- end item prices types -->
                                                    <div class="px-4 py-3">
                                                        <ps-checkbox-value :title="$t('core_be__price_showhide_setting')"
                                                            v-model:value="sysForm.hide_price_setting" />
                                                        <ps-label-caption textColor="text-secondary-400 mt-1">{{ $t('core_be__price_showhide_setting_note') }}</ps-label-caption>
                                                    </div>
                                                    <!-- item chat types -->
                
                                                    <div class="px-4 py-3">
                                                        <ps-label class="text-base">{{ $t('core__be_chat_types') }}
                                                            </ps-label>
                                                        <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                                            <template #select>
                                                                <ps-dropdown-select
                                                                :placeholder="$t('core__be_chat_types_placeholder')"
                                                                    :selectedValue="(sysForm.selected_chat_type == '') ? '' : system_config.item_chat_types.item_chat_selections.filter(price_type => price_type.id == sysForm.selected_chat_type)[0].value" />
                                                            </template>
                                                            <template #list>
                                                                <div class="rounded-md shadow-xs w-56">
                                                                    <div class="pt-2 z-30 ">
                                                                        <div class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                                            @click="[sysForm.selected_chat_type = '']">
                                                                            <ps-label
                                                                                class="text-secondary-200">{{ $t('core_sys_select_chat_type') }}</ps-label>
                                                                        </div>
                                                                        <div v-for="chat in system_config.item_chat_types.item_chat_selections"
                                                                            :key="chat.id"
                                                                            class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                                            @click="[sysForm.selected_chat_type = chat.id]">
                                                                            <ps-label class="ms-2"
                                                                                :class="chat.id == sysForm.selected_chat_type ? ' font-bold' : ''">
                                                                                {{ chat.value }} </ps-label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </ps-dropdown>
                                                    </div>
                
                                                    <div class="px-4 py-3">
                                                        <ps-checkbox-value :title="$t('core_be__soldout_feature_setting')"
                                                            v-model:value="sysForm.soldout_feature_setting" />
                                                        <ps-label-caption textColor="text-secondary-400 mt-1">{{ $t('core_be__soldout_feature_setting_note') }}</ps-label-caption>
                                                    </div>
                                        
                                            </template>
                                        
                                            
                                        
                                        </ps-accordion> <!-- setting2 -->
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_mb_setting_video_duration') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core_mb_setting_video_duration_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                             <div class="flex items-center">
                                                <ps-label>{{ $t('core_mb_setting_video_duration') }} (MilliSeconds)</ps-label>
                                                <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                    <template #content>
                                                        <ps-icon name="info" w="20" h="20"
                                                            class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                    </template>
                                                    <template #tooltips>
                                                        For more details: <a target="_blank"
                                                            href="https://doc.clickup.com/24312566/p/h/q5yqp-85545/f82e80c13185db2"
                                                            class="underline">Refer to this doc</a>
                                                    </template>
                                                </ps-tooltip>
                                            </div>
                                            <ps-input type="text" v-model:value="form.video_duration"
                                                :placeholder="$t('core_mb_setting_video_duration')" @keypress="onlyNumber" />
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- setting3 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_mb_setting_is_show_info') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core_mb_setting_is_show_info_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-3 flex items-center">
                                                <ps-checkbox-value :title="$t('core_mb_setting_is_show_info')"
                                                    v-model:value="form.is_show_owner_info" />
                                                <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                    <template #content>
                                                        <ps-icon name="info" w="20" h="20"
                                                            class="-ms-1 transition-all duration-300 text-primary-500" />
                                                    </template>
                                                    <template #tooltips>
                                                        For more details: <a target="_blank"
                                                            href="https://doc.clickup.com/24312566/p/h/q5yqp-83925/2bf16fad021313e"
                                                            class="underline">Refer to this doc</a>
                                                    </template>
                                                </ps-tooltip>
                                            </div>
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- setting4 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_mb_setting_is_show_item_video') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core_mb_setting_is_show_item_video_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-3 flex items-center">
                                                <ps-checkbox-value :title="$t('core_mb_setting_is_show_item_video')"
                                                    v-model:value="form.is_show_item_video" />
                                                <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                    <template #content>
                                                        <ps-icon name="info" w="20" h="20"
                                                            class="-ms-1 transition-all duration-300 text-primary-500" />
                                                    </template>
                                                    <template #tooltips>
                                                        For more details: <a target="_blank"
                                                            href="https://doc.clickup.com/24312566/p/h/q5yqp-84045/ca7f015748e8cbd"
                                                            class="underline">Refer to this doc</a>
                                                    </template>
                                                </ps-tooltip>
                                            </div>
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- setting5 -->
                                
                                </div>
                                
                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_mb_setting_phone_list_count') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t("core_mb_setting_phone_list_count_desc") }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-3 ">
                                                <div class="flex items-center">
                                                    <ps-label
                                                        class="text-base">{{ $t('core_mb_setting_phone_list_count') }}</ps-label>
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-85465/5f63adc19379e0b"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                                <ps-input type="text" v-model:value="form.phone_list_count"
                                                    :placeholder="$t('core_mb_setting_phone_list_count')" @keypress="onlyNumber" />
                                            </div>
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- setting6 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_sys_config_max_image_upload') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t("core_sys_config_max_image_upload_desc") }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <ps-label
                                                        class="text-base mb-1">{{ $t('core_sys_config_max_image_upload') }}</ps-label>
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-77185/a11c9982008d190"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                                <ps-input type="text" v-model:value="sysForm.max_img_upload_of_item"
                                                    placeholder="Number" @keypress="onlyNumber" />
                                            </div>
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- setting7 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_sys_approval_system_on') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core_sys_approval_system_on_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <ps-checkbox-value :title="$t('core_sys_approval_system_on')"
                                                        v-model:value="sysForm.is_approved_enable" />
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="-ms-1 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-78365/3f80709a92e085b"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                                <ps-label-caption
                                                    textColor="text-secondary-400 mt-1">{{ $t('core_sys_config_approval_sys_info') }}
                                                </ps-label-caption>
                                            </div>
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- setting8 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_mb_setting_discount_on') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core_mb_setting_discount_on_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <ps-checkbox-value :title="$t('core_mb_setting_discount_on')"
                                                        v-model:value="form.is_show_discount" />
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="-ms-1 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-83285/9734168d14256fa"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                                <ps-label-caption textColor="text-secondary-400 mt-1"> {{
                                                    $t('core__mb_setting_discount_desc') }}</ps-label-caption>
                                            </div>
                                
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- setting9 -->
                                
                                </div>

                                  
                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_mb_setting_mile_range') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core_mb_setting_mile_range_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <ps-label class="text-base">{{ $t('core_mb_setting_mile_range') }}</ps-label>
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-81145/e7afa6b36103655"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                                <ps-input type="text" v-model:value="form.mile"
                                                    :placeholder="$t('core_mb_setting_mile_range')" @keypress="onlyNumber" />
                                            </div>


                                        </template>

                                        

                                    </ps-accordion> <!-- setting10 -->

                                </div>  

                                </div>
                                <!-- End Item Configuration setting -->

                                <!-- Start Image Configuration Setting -->

                                <div v-if="title === (settingColumn[1]?.label || '')">
                                    
                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_thumbnail') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_thumbnail_desc') }}</ps-label>
                                                <br>
                                                
                                                
                                                   <div class="px-4 py-3">
                                                        <div class="flex items-center">
                                                            <ps-checkbox-value :title="$t('core_sys_thumbnail')"
                                                                v-model:value="sysForm.is_thumb2x_3x_generate" />
                                                            <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                                <template #content>
                                                                    <ps-icon name="info" w="20" h="20"
                                                                        class="-ms-1 transition-all duration-300 text-primary-500" />
                                                                </template>
                                                                <template #tooltips>
                                                                    For more details: <a target="_blank"
                                                                        href="https://doc.clickup.com/24312566/p/h/q5yqp-80045/92b9253853337b5"
                                                                        class="underline">Refer to this doc</a>
                                                                </template>
                                                            </ps-tooltip>
                                                        </div>
                                                        <ps-label-caption
                                                            textColor="text-secondary-400 mt-1">{{ $t('core_sys_thumbnail_info') }}
                                                        </ps-label-caption>
                                                    </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- image_configuration_1 -->
                                    
                                    </div>

                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_mb_setting_thumbnail_as_placeholder') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_mb_setting_thumbnail_as_placeholder_desc') }}</ps-label>
                                                <br>
                                                
                                                
                                                  <div class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <ps-checkbox-value :title="$t('core_mb_setting_thumbnail_as_placeholder')"
                                                            v-model:value="form.is_use_thumbnail_as_placeholder" />
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="-ms-1 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-83585/a403095eeb9b260"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                                    <ps-label-caption textColor="text-secondary-400 mt-1"> {{
                                                        $t('core__mb_setting_thumbnail_desc') }}</ps-label-caption>
                                                </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- image_configuration_2 -->
                                    
                                    </div>
                                    
                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_mb_setting_image_size_config') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_mb_setting_image_size_config_desc') }}</ps-label>
                                                <br>
                                                
                                                
                                                  <div class="grid grid-rows gap-6 ms-4 me-18"> 
                                                    
                                                        
                                                        <div>
                                                            <ps-label class="text-base">{{ $t('core_mb_setting_profile_image_size') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.profile_image_size"
                                                                :placeholder="$t('core_mb_setting_profile_image_size')"
                                                                @keypress="onlyNumber" />
                                                        </div>
                                    
                                                        <div>
                                                            <ps-label class="text-base">{{ $t('core_mb_setting_upload_image_size') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.upload_image_size"
                                                                :placeholder="$t('core_mb_setting_upload_image_size')" @keypress="onlyNumber" />
                                                        </div>
                                    
                                                        <div>
                                                            <ps-label class="text-base">{{ $t('core_mb_setting_chat_image_size') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.chat_image_size"
                                                                :placeholder="$t('core_mb_setting_chat_image_size')" @keypress="onlyNumber" />
                                                        </div>
                                    
                                                        <div>
                                                            <ps-label class="text-base">{{ $t('core_mb_setting_bluemark_size') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.blue_mark_size"
                                                                :placeholder="$t('core_mb_setting_bluemark_size')" @keypress="onlyNumber" />
                                                        </div>
                                    
                                     
                                                </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- image_configuration_3 -->
                                    
                                    </div>



                                </div>


                                <!-- End Image Configuration Setting -->

                                <!-- Start Promotion Configuration Setting -->

                                <div v-if="title === (settingColumn[2]?.label || '')">
                                    
                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_promotion_config') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_promotion_config_desc') }}</ps-label>
                                                <br>
                                                
                                                
                                                  <div class="grid grid-rows gap-6 ms-4 me-18">
                                                        
                                                        <div class="">
                                                            <ps-label class="text-base mb-1">Price Per Day</ps-label>
                                                            <ps-input type="text" v-model:value="sysForm.one_day_per_price"
                                                                placeholder="Price Per Day"
                                                                @keyup="validatePriceInput('one_day_per_price', sysForm.one_day_per_price)"
                                                                @blur="validatePriceInput('one_day_per_price', sysForm.one_day_per_price)"
                                                                @keypress="onlyNumberWithCustomForPrice" />
                                                        </div>
                                                        <div>
                                                            <ps-label
                                                                class="text-base">{{ $t('core_mb_setting_promote_first_choice_day') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.promote_first_choice_day"
                                                                :placeholder="$t('core_mb_setting_promote_first_choice_day')"
                                                                @keypress="onlyNumber" />
                                                        </div>
                                    
                                                        <div>
                                                            <ps-label
                                                                class="text-base">{{ $t('core_mb_setting_promote_second_choice_day') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.promote_second_choice_day"
                                                                :placeholder="$t('core_mb_setting_promote_second_choice_day')"
                                                                @keypress="onlyNumber" />
                                                        </div>
                                    
                                                        <div>
                                                            <ps-label
                                                                class="text-base">{{ $t('core_mb_setting_promote_third_choice_day') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.promote_third_choice_day"
                                                                :placeholder="$t('core_mb_setting_promote_third_choice_day')"
                                                                @keypress="onlyNumber" />
                                                        </div>
                                    
                                                        <div>
                                                            <ps-label
                                                                class="text-base">{{ $t('core_mb_setting_promote_fourth_choice_day') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.promote_fourth_choice_day"
                                                                :placeholder="$t('core_mb_setting_promote_fourth_choice_day')"
                                                                @keypress="onlyNumber" />
                                                        </div>
                                    
                                    
                                                    
                                                </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- promotion_configuration_1 -->
                                    
                                        <ps-accordion class="w-130 md:items-right">
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_promote_enable_config') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                 <div class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <ps-checkbox-value :title="$t('core_sys_is_promote_enable')"
                                                            v-model:value="sysForm.is_promote_enable"/>
                                                    </div>
                                                    <ps-label-caption
                                                        textColor="text-secondary-400 mt-1">{{ $t('core_sys_is_promote_enable_info') }}
                                                    </ps-label-caption>
                                                </div>
                                            </template>
                                        </ps-accordion> <!-- promotion_configuration_2 -->
                                    
                                    </div>

                                </div>
                               


                                <!-- End Promotion Configuration Setting -->

                                <!-- Start Currency Configuration Setting -->

                                <div v-if="title === (settingColumn[3]?.label || '')">
                                    
                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_razor_currency_config') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_currency_config_desc') }}</ps-label>
                                                <br>
                                                
                                                
                                                  <div class="grid grid-rows gap-6 ms-4 me-18">
                                                        
                                                        <div>
                                                            <ps-label
                                                                class="text-base">{{ $t('core_mb_setting_default_razor_currency') }}</ps-label>
                                                            <ps-input type="text" v-model:value="form.default_razor_currency"
                                                                :placeholder="$t('core_mb_setting_default_razor_currency')" />
                                                        </div>
                                    
                                                        <div>
                                                            <ps-checkbox-value :title="$t('core_mb_setting_razor_support_multi_currency_on')"
                                                                v-model:value="form.is_razor_support_multi_currency" />
                                                            <ps-label-caption textColor="text-secondary-400 mt-1">(If this setting is "On", you
                                                                can accept razor payment in multi-currency in the
                                                                application.)</ps-label-caption>
                                                        </div>
                                    
                                    
                                                    
                                                </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- currency_configuration_1 -->
                                    
                                    </div>


                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_mb_setting_price_format') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_mb_setting_price_format_desc') }}</ps-label>
                                                <br>
                                                
                                                
                                                  <div class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <ps-label class="text-base">{{ $t('core_mb_setting_price_format') }}</ps-label>
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-79745/724c4ed362055ff"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                                    <ps-input type="text" v-model:value="form.price_format"
                                                        :placeholder="$t('core_mb_setting_price_format')" />
                                                    <ps-label-caption textColor="text-secondary-400 mt-1">{{
                                                        $t('core_mb_setting_price_format_ex') }}</ps-label-caption>
                                                </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- currency_configuration_2 -->
                                    
                                    </div>

                                </div>


                                <!-- End Currency Configuration Setting -->


                                <!-- Start Login Authentication Configuration Setting -->
                                

                                <div v-if="title === (settingColumn[4]?.label || '')">


                                    <div class="px-4 py-0 "> 

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_login_auth_config') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_login_auth_config_desc') }}</ps-label>
                                                <br>
                                                
                                                
                                                  <div class="grid grid-rows gap-6 ms-4 me-18">
                                                    <div>
                                                        <ps-checkbox-value :title="$t('core_mb_setting_facebook_login_on')"
                                                            v-model:value="form.show_facebook_login" />
                                                        <ps-label-caption
                                                            textColor="text-secondary-400 mt-1">{{ $t('core_mb_setting_facebook_login_on_info') }}</ps-label-caption>
                                                    </div>
                                    
                                                    <div>
                                                        <ps-checkbox-value :title="$t('core_mb_setting_phone_login_on')"
                                                            v-model:value="form.show_phone_login" />
                                                        <ps-label-caption
                                                            textColor="text-secondary-400 mt-1">{{ $t('core_mb_setting_phone_login_on_info') }}</ps-label-caption>
                                                    </div>
                                    
                                                    <div>
                                                        <ps-checkbox-value :title="$t('core_mb_setting_google_login_on')"
                                                            v-model:value="form.show_google_login" />
                                                        <ps-label-caption
                                                            textColor="text-secondary-400 mt-1">{{ $t('core_mb_setting_google_login_on_info') }}</ps-label-caption>
                                                    </div>
                                    
                                                    <div>
                                                        <ps-checkbox-value :title="$t('core_mb_setting_apple_login_on')"
                                                            v-model:value="form.show_apple_login" />
                                                        <ps-label-caption
                                                            textColor="text-secondary-400 mt-1">{{ $t('core_mb_setting_apple_login_on_info') }}</ps-label-caption>
                                                    </div>
                                    
                                                    <div>
                                                        <ps-checkbox-value :title="$t('core_mb_setting_is_force_login')"
                                                            v-model:value="form.is_force_login" />
                                                    </div>
                                                  </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- login_auth_configuration_1 -->
                                    
                                    </div>


                                </div>


                                <!-- End Login Authentication Configuration Setting -->

                                <!-- Start Category Configuration Setting -->

                                <div v-if="title === (settingColumn[5]?.label || '')">
                                    
                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_mb_setting_sub_cat_on') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_mb_setting_sub_cat_on_desc') }}</ps-label>
                                                <br>
                                                
                                                <div class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <ps-checkbox-value :title="$t('core_mb_setting_sub_cat_on')"
                                                            v-model:value="form.is_show_subcategory" />
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="-ms-1 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-82825/bce84bf4674ac99"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                                    <ps-label-caption textColor="text-secondary-400 mt-1">
                                                        {{ $t('core__mb_setting_subcat_desc') }}</ps-label-caption>
                                                </div>
                                                  
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- category_configuration_1 -->
                                    
                                    </div>

                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_sub_cat_sub') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_sub_cat_sub_desc') }}</ps-label>
                                                <br>
                                                
                                                <div class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <ps-checkbox-value :disabled="!form.is_show_subcategory"
                                                            :title="$t('core_sys_sub_cat_sub')"
                                                            v-model:value="sysForm.is_sub_subscription" />
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="-ms-1 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-81285/1bbc14e91b4a8d0"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                                    <ps-label-caption
                                                        textColor="text-secondary-400 mt-1">{{ $t('core_sys_sub_cat_sub_info') }}
                                                    </ps-label-caption>
                                                </div>
                                                  
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- category_configuration_2 -->
                                    
                                    </div>
                                
                                </div>


                                <!-- End Category Configuration Setting -->

                                <!-- Start User Configuration Setting -->

                                <div v-if="title === (settingColumn[6]?.label || '')">
                                    
                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_block_feauture') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_block_feauture_desc') }}</ps-label>
                                                <br>
                                                
                                                <div class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <ps-checkbox-value :title="$t('core_sys_block_feauture')"
                                                            v-model:value="sysForm.is_block_user" />
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="-ms-1 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-81805/5d2ebf68ed686ae"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                                    <ps-label-caption
                                                        textColor="text-secondary-400 mt-1">{{ $t('core_sys_block_feauture_nfo') }}
                                                    </ps-label-caption>
                                                </div>
                                                  
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- user_configuration_1 -->
                                    
                                    </div>
                                    
                                </div>

                                <!-- End User Configuration Setting -->

                                <!-- Start Map Configuration Setting -->

                                <div v-if="title === (settingColumn[7]?.label || '')">
                                
                                    <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_default_location_on_map_title') }}</ps-label-header-5>
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_default_location_on_map_title_desc') }}</ps-label>
                                            <br>
                                            
                                            <div class="px-4 py-0">
                                                
                                                <div class="mb-6" v-if="backendSetting.is_google_map == 1">
                                                    <google-map-pin-picker :mapKey="$page.props.mapKey"  :lat="parseFloat(sysForm.lat)" :lng="parseFloat(sysForm.lng)"
                                                        widthHeight="width: 258px; height: 300px" :onChange="updateCoordinates" />
                                                </div>
                                
                                                <div class="mb-6" v-else>
                                                    <open-street-map-pin-picker :dir="$page.props.dir" :lat="parseFloat(sysForm.lat)" :lng="parseFloat(sysForm.lng)" :onChange="setCoordinates" class="h-[500px]" />
                                                </div>
                                
                                                <div class="px-4 py-3">
                                                    <ps-label class="text-base">{{ $t('core_sys_config_lat') }}</ps-label>
                                                    <ps-input type="text" v-model:value="sysForm.lat" placeholder="Latitude"
                                                        @keypress="onlyNumberWithCustom"
                                                        @keyup="validateLatitudeInput('lat', sysForm.lat)"
                                                        @blur="validateLatitudeInput('lat', sysForm.lat)" />
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.lat }}
                                                    </ps-label-caption>
                                                </div>
                                
                                                <div class="px-4 py-3">
                                                    <ps-label class="text-base">{{ $t('core_sys_config_lng') }}</ps-label>
                                                    <ps-input type="text" v-model:value="sysForm.lng" placeholder="Longitude"
                                                        @keypress="onlyNumberWithCustom"
                                                        @keyup="validateLongitudeInput('lng', sysForm.lng)"
                                                        @blur="validateLongitudeInput('lng', sysForm.lng)" />
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.lng }}
                                                    </ps-label-caption>
                                                </div>
                                
                                            </div>
                                              
                                
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- map_configuration_1 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_sys_sub_location') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core_sys_sub_location_desc') }}</ps-label>
                                            <br>
                                            
                                            <div class="px-4 py-3">
                                                <div class="flex items-center">
                                                    <ps-checkbox-value :title="$t('core_sys_sub_location')"
                                                        v-model:value="sysForm.is_sub_location" />
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="-ms-1 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-79265/0aa4f436a52b6cd"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                                <ps-label-caption
                                                    textColor="text-secondary-400 mt-1">{{ $t('core_sys_sub_location_info') }}
                                                </ps-label-caption>
                                            </div>
                                              
                                
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- map_configuration_2 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core_mb_setting_is_filter_with_location') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core_mb_setting_is_filter_with_location_desc') }}</ps-label>
                                            <br>
                                            
                                             <div class="px-4 py-3 flex items-center">
                                                <ps-checkbox-value :title="$t('core_mb_setting_is_filter_with_location')"
                                                    v-model:value="form.no_filter_with_location_on_map" />
                                                <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                    <template #content>
                                                        <ps-icon name="info" w="20" h="20"
                                                            class="-ms-1 transition-all duration-300 text-primary-500" />
                                                    </template>
                                                    <template #tooltips>
                                                        For more details: <a target="_blank"
                                                            href="https://doc.clickup.com/24312566/p/h/q5yqp-83825/1a32cf3f9a9518f"
                                                            class="underline">Refer to this doc</a>
                                                    </template>
                                                </ps-tooltip>
                                            </div>
                                              
                                
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- map_configuration_3 -->
                                
                                </div>



                                </div>

                                <!-- End Map Configuration Setting -->

                                <!-- Start Display Data Configuration Setting -->

                                <div v-if="title === (settingColumn[8]?.label || '')">
                                    
                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_display_data_config') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core_sys_display_data_config_desc') }}</ps-label>
                                                <br>
                                                
                                                 <div class="grid grid-rows gap-6 ms-4 me-18">
                                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_default_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.default_loading_limit"
                                                            :placeholder="$t('core_mb_setting_default_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_category_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.category_loading_limit"
                                                            :placeholder="$t('core_mb_setting_category_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_recent_item_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.recent_item_loading_limit"
                                                            :placeholder="$t('core_mb_setting_recent_item_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_popular_item_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.popular_item_loading_limit"
                                                            :placeholder="$t('core_mb_setting_popular_item_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_discount_item_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.discount_item_loading_limit"
                                                            :placeholder="$t('core_mb_setting_discount_item_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_feature_item_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.featured_item_loading_limit"
                                                            :placeholder="$t('core_mb_setting_feature_item_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_block_slider_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.block_slider_loading_limit"
                                                            :placeholder="$t('core_mb_setting_block_slider_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_follower_item_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.follower_item_loading_limit"
                                                            :placeholder="$t('core_mb_setting_follower_item_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_block_item_loading_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.block_item_loading_limit"
                                                            :placeholder="$t('core_mb_setting_block_item_loading_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                                    <div>
                                                        <ps-label
                                                            class="text-base">{{ $t('core_mb_setting_recent_search_keyword_limit') }}</ps-label>
                                                        <ps-input type="text" v-model:value="form.recent_search_keyword_limit"
                                                            :placeholder="$t('core_mb_setting_recent_search_keyword_limit')"
                                                            @keypress="onlyNumber" />
                                                    </div>
                                    
                                    
                                                </div>
                                                  
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- display_data_configuration_1 -->
                                    
                                    </div>

                                </div>


                                <!-- End Display Data Configuration Setting -->

                                <!-- Start Google Ad Sense Configuration Setting -->

                                <div v-if="title === (settingColumn[9]?.label || '')">
                                    <div class="px-4 py-0 ">
                                        <ps-accordion class="w-130 md:items-right">
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_adsense_config') }}</ps-label-header-5>
                                            </template>
                                            <template #description>
                                                 <input class="" type="file" accept=".txt" ref="importFile" id="zip-imported" style="display: none;"
                                                    @change="handleImport($event)">

                                                <div class="flex justify-between px-4 py-3">
                                                    <ps-label class="text-base"> {{ $t("is_display_google_adsense_?") }}</ps-label>
                                                    <ps-toggle :selectedValue="sysForm.is_display_google_adsense == 1 ? true : false"
                                                        @click="handleDisplayGoogleAdsense()"
                                                        toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                                                </div>
                                                <div v-if=sysForm.is_display_google_adsense>
                                                    <div class="px-4 py-3">
                                                        <ps-label class="text-base mb-1">{{ $t('upload_ads_txt_file') }}</ps-label>
                                                        <div class="bg-secondary-100 p-2 text-sm dark:bg-secondary-900 text-secondary-800 dark:text-secondary-100 rounded border shadow-none  cursor-pointer" v-if="selectedFile" @click="importClicked">
                                                            {{ selectedFile }}
                                                        </div>

                                                        <div v-if="!selectedFile || selectedFile == ''" class="">
                                                            <ps-button colors='bg-white dark:bg-primary-800 text-white dark:text-secondary-50'
                                                                justify="justify-start items-start" border="border border-secondary-200" type="button"
                                                                @click="importClicked" class="mb-0.5  w-full flex  zip-import items-center" padding="px-3 py-2">
                                                                <ps-icon name="plus" class="mx-0.5 font-semibold justify-start text-primary-500 " />
                                                                <ps-label class="justify-start" textColor="text-secondary-500">
                                                                    {{ $t('upload_ads_txt_file')}}
                                                                </ps-label>
                                                            </ps-button>
                                                        </div>
                                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">
                                                            {{ errors['sysForm.ads_txt_file']}}
                                                        </ps-label-caption>
                                                    </div>

                                                    <div class="px-4 py-3">
                                                        <ps-label class="text-base mb-1">{{ $t('display_ads_id') }}</ps-label>
                                                        <ps-input type="text" v-model:value="sysForm.display_ads_id"
                                                            :placeholder="$t('enter_display_ads_id')"
                                                        />
                                                    </div>

                                                    <div class="px-4 py-3">
                                                        <ps-label class="text-base mb-1">{{ $t('ads_client') }}</ps-label>
                                                        <ps-input type="text" v-model:value="sysForm.ads_client"
                                                            :placeholder="$t('enter_ads_client')"
                                                        />
                                                    </div>
                                                </div>
                                            </template>
                                        </ps-accordion> <!-- google_adsense_configuration_1 -->
                                    </div>
                                </div>

                                <!-- End Google Ad Sense Configuration Setting -->

                                <!-- Start Paid App Configuration Setting -->

                                <div v-if="title === (settingColumn[10]?.label || '')">
                                    <div class="px-4 py-0 ">
                                        <ps-accordion class="w-130 md:items-right">
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_paid_app_config') }}</ps-label-header-5>
                                            </template>
                                            <template #description>
                                                <ps-label class="text-base">{{ $t('core_sys_paid_app_config_desc') }}</ps-label>
                                                <br>
                                                 <div class="px-4 py-3">
                                                    <div class="flex items-center">
                                                        <ps-checkbox-value :title="$t('core_sys_paid_app_on')"
                                                            v-model:value="sysForm.is_paid_app"/>
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="-ms-1 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-144465/73d82c1a6fd6d64"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                                    <ps-label-caption
                                                        textColor="text-secondary-400 mt-1">{{ $t('core_sys_paid_app_on_info') }}
                                                    </ps-label-caption>
                                                </div>
                                                <div class="px-4 py-3 " v-if="sysForm.is_paid_app">
                                                    <div class="flex items-center">
                                                        <ps-label class="text-base">{{ $t('core_be__free_ad_post_count') }}</ps-label>
                                                    </div>
                                                    <ps-input type="text" v-model:value="sysForm.free_ad_post_count"
                                                        :placeholder="$t('core_be__number')" @keypress="onlyNumber" />
                                                </div>

                                            </template>
                                        </ps-accordion> <!-- paid_app_configuration_1 -->
                                    </div>
                                </div>

                                <!-- End Paid App Configuration Setting -->

                                <!-- Start Demo Configuration Setting -->

                                <div v-if="title === (settingColumn[11]?.label || '')">
                                    <div class="px-4 py-0 ">
                                        <ps-accordion class="w-130 md:items-right">
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core_sys_demo_payment_config') }}</ps-label-header-5>
                                            </template>
                                            <template #description>
                                                <ps-label class="text-base">{{ $t('core_sys_demo_payment_config_desc') }}</ps-label>
                                                <br>
                                                <div class="px-4 py-3 flex items-center">
                                                    <ps-checkbox-value :title="$t('core_mb_setting_is_demo_for_payment')"
                                                        v-model:value="form.is_demo_for_payment" />
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="-ms-1 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-84925/a445ac339c98897"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                            </template>
                                        </ps-accordion> <!-- demo_configuration_1 -->
                                    </div>
                                </div>

                                <!-- End Demo Configuration Setting -->

                                <div class="flex flex-row px-4 py-3 justify-end mb-2.5 mt-4">
                                    <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4"
                                        colors="text-primary-500" focus=""
                                        hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                    <ps-button :disabled="!system_config.authorizations.update"
                                        class="transition-all duration-300 min-w-3xs" padding="px-6 py-2" rounded="rounded"
                                        hover="" focus="">
                                        <ps-loading v-if="loading"
                                            theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                            loadingSize="h-5 w-5" />
                                        <ps-icon v-if="success" name="check" w="20" h="20"
                                            class="me-1.5 transition-all duration-300" />
                                        <ps-label v-if="success" class="transition-all duration-300"
                                            textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_save')
                                            }}</ps-label>
                                        <ps-label v-if="!loading && !success"
                                            textColor="text-white dark:text-secondaryDark-black">
                                            {{ $t('core__be_btn_save') }} </ps-label>
                                    </ps-button>
                                </div>

                            </div>
                            <!-- setting column -->
                            <div>
                                <div v-for="column in settingColumn.slice().sort((a, b) => $t(a.label).localeCompare($t(b.label)))" :key="column.id">
                                    <div @click="changeSection(column)"
                                        :class="title == column.label ? 'border-l border-s-primary-500' : 'border-l border-s-secondary-300'"
                                        class="px-2 py-3 cursor-pointer">
                                        <ps-label
                                            :textColor="title == column.label ? 'text-primary-500 dark:text-primary-500' : 'text-secondary-800 dark:text-white'">
                                            {{ $t(column.label) }}
                                        </ps-label>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </ps-card>
        </div>
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive, defineAsyncComponent } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsCheckboxValue from '@/Components/Core/Checkbox/PsCheckboxValue.vue';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsTooltips from "@/Components/Core/Tooltips/PsTooltips.vue";
const GoogleMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/GoogleMapPinPicker.vue'));
const OpenStreetMapPinPicker = defineAsyncComponent(() => import("@/Components/Core/Map/OpenStreetMapPinPicker.vue"));
import { trans } from 'laravel-vue-i18n';
import PsTooltip from '@/Components/Core/Tooltips/PsTooltip.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsUtils from '@templateCore/utils/PsUtils';
import PsLabelTitle4 from "@/Components/Core/Label/PsLabelTitle4.vue";
import PsLabeHeader5 from "@/Components/Core/Label/PsLabelHeader5.vue";
import PsAccordion from "@/Components/Core/Accordion/PsAccordion.vue";

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsToggle,
        PsInput,
        GoogleMapPinPicker,
        OpenStreetMapPinPicker,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        Link,
        PsLabelCaption,
        PsLabelTitle3,
        PsImageUpload,
        PsCheckboxValue,
        PsDropdown,
        PsDropdownSelect,
        PsTooltips,
        PsTooltip,
        PsLabelTitle4,
        PsLabeHeader5,
        PsAccordion
    },
    layout: PsLayout,
    props: ['errors',
            'system_config',
            'mobile_setting',
            'status',
            'adTypes',
            'backendSetting',
            'SettingPage'],
    setup(props) {

        const loading = ref(false);
        const success = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        let selectedFile = ref();
        const importFile = ref();

        if(props.system_config.ads_txt_file_name){
            selectedFile.value = props.system_config.ads_txt_file_name;
        }



        let sysForm = useForm(
            {
                id: props.system_config.id,
                lat: props.system_config.lat,
                lng: props.system_config.lng,
                is_approved_enable: props.system_config.is_approved_enable == 1 ? true : false,
                is_sub_location: props.system_config.is_sub_location == 1 ? true : false,
                is_thumb2x_3x_generate: props.system_config.is_thumb2x_3x_generate == 1 ? true : false,
                is_sub_subscription: props.system_config.is_sub_subscription == 1 ? true : false,
                is_paid_app: props.system_config.is_paid_app == 1 ? true : false,
                free_ad_post_count: props.system_config.free_ad_post_count,
                is_promote_enable: props.system_config.is_promote_enable == 1 ? true : false,
                is_block_user: props.system_config.is_block_user == 1 ? true : false,
                is_hide_price: props.system_config.is_hide_price == 1 ? true : false,
                max_img_upload_of_item: props.system_config.max_img_upload_of_item,
                // ad_type: props.system_config.ad_type,
                ad_type: props.adTypes.find(element => element.id == props.system_config.ad_type) ? props.system_config.ad_type : '',
                promo_cell_interval_no: props.system_config.promo_cell_interval_no,
                one_day_per_price: props.system_config.one_day_per_price,
                selected_price_type: props.system_config.selected_price_type,
                selected_chat_type: props.system_config.selected_chat_type,
                soldout_feature_setting: props.system_config.soldout_feature_setting == 1 ? true : false,
                hide_price_setting: props.system_config.hide_price_setting == 1 ? true : false,
                display_ads_id: props.system_config.display_ads_id,
                ads_client: props.system_config.ads_client,
                ads_txt_file: '',
                is_display_google_adsense: props.system_config.is_display_google_adsense,
                // duplicate:[
                //     {
                //         id:'1',
                //         value:'gg'
                //     },
                //     {
                //         id:'2',
                //         value:'gg2'
                //     }
                // ],
                "_method": "put"
            }
        )

        let form = useForm(
            {
                id: props.mobile_setting.id,
                google_playstore_url: props.mobile_setting.google_playstore_url,
                apple_appstore_url: props.mobile_setting.apple_appstore_url,
                ios_appstore_id: props.mobile_setting.ios_appstore_id,

                promote_first_choice_day: props.mobile_setting.promote_first_choice_day,
                promote_second_choice_day: props.mobile_setting.promote_second_choice_day,
                promote_third_choice_day: props.mobile_setting.promote_third_choice_day,
                promote_fourth_choice_day: props.mobile_setting.promote_fourth_choice_day,

                profile_image_size: props.mobile_setting.profile_image_size,
                upload_image_size: props.mobile_setting.upload_image_size,
                chat_image_size: props.mobile_setting.chat_image_size,
                blue_mark_size: props.mobile_setting.blue_mark_size,

                item_detail_view_count_for_ads: props.mobile_setting.item_detail_view_count_for_ads,
                is_show_admob: props.mobile_setting.is_show_admob == 1 ? true : false,
                is_show_ads_in_item_detail: props.mobile_setting.is_show_ads_in_item_detail == 1 ? true : false,
                android_admob_banner_ad_unit_id: props.mobile_setting.android_admob_banner_ad_unit_id, //new field
                android_admob_native_unit_id: props.mobile_setting.android_admob_native_unit_id, //new field
                andorid_admob_interstitial_ad_unit_id: props.mobile_setting.andorid_admob_interstitial_ad_unit_id, //new field
                ios_admob_banner_ad_unit_id: props.mobile_setting.ios_admob_banner_ad_unit_id, //new field
                ios_admob_native_ad_unit_id: props.mobile_setting.ios_admob_native_ad_unit_id, //new field
                ios_admob_interstitial_ad_unit_id: props.mobile_setting.ios_admob_interstitial_ad_unit_id, //new field

                show_facebook_login: props.mobile_setting.show_facebook_login == 1 ? true : false,
                show_phone_login: props.mobile_setting.show_phone_login == 1 ? true : false,
                show_google_login: props.mobile_setting.show_google_login == 1 ? true : false,
                show_apple_login: props.mobile_setting.show_apple_login == 1 ? true : false,
                is_force_login: props.mobile_setting.is_force_login == 1 ? true : false,

                show_main_menu: props.mobile_setting.show_main_menu == 1 ? true : false,
                show_featured_items: props.mobile_setting.show_featured_items == 1 ? true : false,

                default_loading_limit: props.mobile_setting.default_loading_limit,
                category_loading_limit: props.mobile_setting.category_loading_limit,
                recent_item_loading_limit: props.mobile_setting.recent_item_loading_limit,
                popular_item_loading_limit: props.mobile_setting.popular_item_loading_limit,
                discount_item_loading_limit: props.mobile_setting.discount_item_loading_limit,
                featured_item_loading_limit: props.mobile_setting.featured_item_loading_limit,
                block_slider_loading_limit: props.mobile_setting.block_slider_loading_limit,
                follower_item_loading_limit: props.mobile_setting.follower_item_loading_limit,
                block_item_loading_limit: props.mobile_setting.block_item_loading_limit,
                recent_search_keyword_limit: props.mobile_setting.recent_search_keyword_limit, //new field

                version_no: props.mobile_setting.version_no,
                version_title: props.mobile_setting.version_title,
                version_message: props.mobile_setting.version_message,
                version_force_update: props.mobile_setting.version_force_update == 1 ? true : false,
                version_need_clear_data: props.mobile_setting.version_need_clear_data == 1 ? true : false,

                data_config_data_source_type: props.mobile_setting.data_config_data_source_type, //new field
                data_config_day: props.mobile_setting.data_config_day, //new field

                is_slider_auto_play: props.mobile_setting.is_slider_auto_play == 1 ? true : false, //new field
                auto_play_interval: props.mobile_setting.auto_play_interval, //new field

                fb_key: props.mobile_setting.fb_key,
                price_format: props.mobile_setting.price_format,
                date_format: props.mobile_setting.date_format,
                mile: props.mobile_setting.mile,
                is_use_google_map: props.mobile_setting.is_use_google_map == 1 ? true : false,
                is_show_subcategory: props.mobile_setting.is_show_subcategory == 1 ? true : false,
                is_show_discount: props.mobile_setting.is_show_discount == 1 ? true : false,
                is_use_thumbnail_as_placeholder: props.mobile_setting.is_use_thumbnail_as_placeholder == 1 ? true : false,
                no_filter_with_location_on_map: props.mobile_setting.no_filter_with_location_on_map == 1 ? true : false,
                is_show_owner_info: props.mobile_setting.is_show_owner_info == 1 ? true : false,
                is_show_item_video: props.mobile_setting.is_show_item_video == 1 ? true : false,
                is_language_config: props.mobile_setting.is_language_config == 1 ? true : false,
                is_demo_for_payment: props.mobile_setting.is_demo_for_payment == 1 ? true : false, //new field
                loading_shimmer_item_count: props.mobile_setting.loading_shimmer_item_count, //new field
                phone_list_count: props.mobile_setting.phone_list_count, //new field
                video_duration: props.mobile_setting.video_duration,

                default_razor_currency: props.mobile_setting.default_razor_currency,
                default_flutter_wave_currency: props.mobile_setting.default_flutter_wave_currency,
                is_razor_support_multi_currency: props.mobile_setting.is_razor_support_multi_currency == 1 ? true : false,

                commonColor: props.commonColor,
                lightColor: props.lightColor,
                darkColor: props.darkColor,



                deli_boy_version_force_update: props.mobile_setting.deli_boy_version_force_update == 1 ? true : false,
                deli_boy_version_need_clear_data: props.mobile_setting.deli_boy_version_need_clear_data == 1 ? true : false,
                //is_show_token_id: props.mobile_setting.is_show_token_id==1?true:false,
                after_item_count_admob_once: props.mobile_setting.after_item_count_admob_once,
                lat: props.mobile_setting.lat,
                lng: props.mobile_setting.lng,
                collection_item_loading_limit: props.mobile_setting.collection_item_loading_limit,
                trending_item_loading_limit: props.mobile_setting.trending_item_loading_limit,
                show_special_collections: props.mobile_setting.show_special_collections == 1 ? true : false,
                show_best_choice_slider: props.mobile_setting.show_best_choice_slider == 1 ? true : false,
                default_order_time: props.mobile_setting.default_order_time,
                deli_boy_version_no: props.mobile_setting.deli_boy_version_no,
                deli_boy_version_title: props.mobile_setting.deli_boy_version_title,
                deli_boy_version_message: props.mobile_setting.deli_boy_version_message,
                color_change_code: props.mobile_setting.color_change_code,

                shop_loading_limit: props.mobile_setting.shop_loading_limit,
                "_method": "put"
            }
        )


        const settingColumn = [


            /// all new menus are coming at below
            {
                index: 0,
                label: 'core_sys_item_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-169065/8bfadddf45800e8'
            },
            {
                index: 1,
                label: 'core_sys_image_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-84725/36ea77639121661'
            },
            {
                index: 2,
                label: 'core_sys_promotion_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-84625/103300b047eb0d3'
            },
            {
                index: 3,
                label: 'core_sys_currency_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-85845/3e6c7ecd05a62a7'
            },
            {
                index: 4,
                label: 'core_sys_login_auth_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-85205/32434eb42572fbd'
            },
            {
                index: 5,
                label: 'core_sys_category_config',
                docu: ''
            },
            {
                index: 6,
                label: 'core_sys_user_config',
                docu: ''
            },
            {
                index: 7,
                label: 'core_sys_map_config',
                docu: 'https://www.docs.panacea-soft.com/psx-mpc/configuration/system-configuration/map-configuration'
            },
            {
                index: 8,
                label: 'core_sys_display_data_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-85525/157d4f8df09c9aa'
            },
            {
                index: 9,
                label: 'core_adsense_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-199818/7042041cff8f06e'
            },
            {
                index: 10,
                label: 'core_sys_paid_app_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-144465/73d82c1a6fd6d64'
            },
            {
                index: 11,
                label: 'core_sys_demo_config',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-84925/a445ac339c98897'
            },
        ];

        function handleDisplayGoogleAdsense() {
            if (sysForm.is_display_google_adsense == 0) {
                sysForm.is_display_google_adsense = 1;
            }
            else {
                sysForm.is_display_google_adsense = 0;
            }
        }

        function clearSelectedFile() {
            // alert('clear')
            selectedFile.value = "";
            sysForm.ads_txt_file = "";
        }

        function handleImport(event) {
            let selectedFiles = event.target.files;
            selectedFile.value = selectedFiles[0].name;
            sysForm.ads_txt_file = selectedFiles[0];
        }

        function importClicked() {
            importFile.value.click();
        }

        var pageIndex = ( props.SettingPage ?? 0) - 1 ;
        if(pageIndex < 0 || pageIndex >= settingColumn.length){
            pageIndex = 5;
        }
        const title = ref(settingColumn[pageIndex].label);
        const docu = ref(settingColumn[pageIndex].docu);

        // Client Side Validation
        const { isLatitude, isLongitude, isPrice } = useValidators();

        const validateLatitudeInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? '' : isLatitude(fieldName, fieldValue);
            if (fieldName == 'lat') {
                lat.value.isError = (props.errors.lat == '') ? false : true;
            }
        }

        const validateLongitudeInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? '' : isLongitude(fieldName, fieldValue);
            if (fieldName == 'lng') {
                lng.value.isError = (props.errors.lng == '') ? false : true;
            }
        }

        // for only number input validate at keypress
        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }

        // for custom number input validate at keypress for lat and lng
        const onlyNumberWithCustom = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46 && keyCode !== 45) { // 46 is dot, 45 is dash
                $e.preventDefault();
            }
        }

        const validatePriceInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? '' : isPrice(fieldName, fieldValue);
            if (fieldName == 'price_per_day') {
                price_per_day.value.isError = (props.errors.price_per_day == '') ? false : true;
            }
        }
        // for custom number input validate at keypress for price
        const onlyNumberWithCustomForPrice = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46 && keyCode !== 45 && keyCode !== 44) { // 46 is dot, 45 is dash, 44 is comma
                $e.preventDefault();
            }
        }

        function changeSection(v) {
            title.value = v.label;
            docu.value = v.docu;

            PsUtils.updateBrowserUrl('system_config', `page=${v.index + 1}`);
        }
        function handleCancel() {
            this.$inertia.get(route('system_config.index'));
        }
        function handleSubmit(id, mb_id) {
            // this.$inertia.post(route('mobile_setting.update', mb_id), form, {
            //     forceFormData: true,
            //     onBefore: () => { loading.value = true },
            //     onSuccess: () => {
            //         loading.value = false;
            //         success.value = true;
            //         setTimeout(() => {
            //             success.value = false;
            //         }, 2000)
            //     },
            //     onError: () => {
            //         loading.value = false;;
            //     },
            // });
            if (form.is_show_subcategory == false) {
                sysForm.is_sub_subscription = false;
            }

            this.$inertia.post(route('system_config.update', id),
                useForm(
                    {
                        'sysForm': sysForm,
                        'form': form,
                        'edit_from': 'edit',
                        "_method": "put"
                    }
                ), {
                forceFormData: true,
                onBefore: () => { loading.value = true },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(() => {
                        success.value = false;
                    }, 2000)
                },
                onError: () => {
                    loading.value = false;;
                },
            });
        }

        function updateCoordinates(location) {
            sysForm.lat = location.latLng.lat();
            sysForm.lng = location.latLng.lng();
        }

        function setCoordinates(e){
            sysForm.lat = e['lat'];
            sysForm.lng = e['lng'];
        }

        return {
            importFile,
            handleImport,
            selectedFile,
            clearSelectedFile,
            importClicked,
            validateLatitudeInput,
            validateLongitudeInput,
            onlyNumberWithCustom,
            onlyNumberWithCustomForPrice,
            validatePriceInput,
            handleCancel,
            onlyNumber,
            sysForm,
            settingColumn,
            title,
            docu,
            changeSection,
            handleSubmit,
            loading,
            success,
            updateCoordinates,
            form,
            setCoordinates,
            handleDisplayGoogleAdsense
        }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('system_config_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
