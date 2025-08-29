<template>
    <Head :title="$t('backend_setting_module')" />
    <ps-layout>
        <div class="">

            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
            <!-- breadcrumb end -->

            <!-- alert banner start -->
            <ps-banner-icon v-if="visible" :visible="visible"
                :theme="(status.flag) == 'danger' ? 'bg-red-500' : (status.flag) == 'warning' ? 'bg-yellow-500' : 'bg-green-500'"
                :iconName="(status.flag) == 'danger' ? 'close-circle' : (status.flag) == 'warning' ? 'alert-triangle' : 'rightalert'"
                class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{ status.msg }}</ps-banner-icon>
            <!-- alert banner end -->

            <ps-card class="w-full h-auto">
                <div class="bg-background dark:bg-secondaryDark-black rounded-lg  mb-20 ">
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

                    <div>
                        <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4">
                            <div>

                                <!-- Start Image Configuration -->   
                                <div v-if="title == settingColumn[0].label"> 
                                    
                                    <div class="px-4 py-3 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core__be_admin_logo_fav_title') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                 
                                                <div class="px-4 py-3">
                                                    <ps-label class="text-base mb-1">{{ $t('core__backend_logo') }} </ps-label>
                                                   
                                                    <ps-label
                                                        textColor="text-secondary-400 text-xs"> {{
                                                            $t('core__be_recommended_size_256_256') }}
                                                    </ps-label>
                                                    
                                                    <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.backend_logo"
                                                        :imagePath="$page.props.uploadUrl + '/' + backend_setting.backend_logo?.img_path"/>
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{
                                                        errors.backend_logo }}</ps-label-caption>
                                                </div>
                                                
                                                <div class="px-4 py-3">
                                                    <ps-label class="text-base mb-1">{{ $t('core__backend_fav_icon') }}</ps-label>
                                                    <ps-label
                                                        textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_16_16')
                                                        }}
                                                    </ps-label>
                                                    
                                                    <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.fav_icon"
                                                        :imagePath="$page.props.uploadUrl + '/' + backend_setting.fav_icon?.img_path"/>
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.fav_icon
                                                    }}</ps-label-caption>
                                                </div>
                                                
                                                
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- image_configuration_1 -->
                                    
                                    </div>

                                </div>
                                <!-- End Image Configuration --> 

                                <!-- Start Language Configuration -->
                                <div v-if="title == settingColumn[1].label">

                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core__be_language_refresh_title') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="dark:text-white text-secondary-800 font-normal">{{ $t('core__be_language_refresh_desc') }}</ps-label>
                                                <br>
                                                
                                                 
                                                <ps-card class="w-full h-auto">
                                                    <div class="rounded-xl">
                                                        <!-- card body start -->
                                    
                                                        <div class="mt-6">
                                    
                                                            <div class="">
                                                                <div class="border border-1 rounded p-4">
                                                                    <div class="h-auto">
                                                                        
                                                                        <div>
                                                                            <ps-label
                                                                                class="dark:text-white text-secondary-800 font-normal">{{
                                                                                    $t('core__be_language_refresh_all_lang') }}
                                                                            </ps-label>
                                                                        </div>
                                    
                                                                        <div v-if="can.updateBackendSetting" class="flex flex-row justify-between items-center mt-6">
                                                                            <ps-label class="dark:text-white text-secondary-800 font-normal">
                                                                                {{ $t('core__be_all') }}
                                                                            </ps-label>
                                                                            <ps-button type="button" @click="handleLangRefresh('')" rounded="rounded"
                                                                                class="flex flex-wrap items-center">
                                                                                <ps-icon name="refresh" class="me-2 font-semibold" />
                                                                                <ps-label
                                                                                    textColor="text-white dark:text-secondary-800">{{
                                                                                        $t('core__be_lang_refresh') }}</ps-label>
                                                                            </ps-button>
                                                                        </div>
                                                                        <hr class="mt-6" />
                                                                        
                                                                        <div>
                                                                            <ps-label
                                                                                class="dark:text-white text-secondary-800 font-normal mt-5">{{
                                                                                    $t('core__be_language_refresh_spec_lang') }}
                                                                            </ps-label>
                                                                        </div>

                                                                        <div v-for="language in languages" :key="language.id">
                                                                            <div v-if="can.updateBackendSetting" class="flex flex-row justify-between items-center mt-6">
                                                                                <ps-label class="dark:text-white text-secondary-800 font-normal">
                                                                                    {{ language.name }}
                                                                                </ps-label>
                                                                                <ps-button type="button" @click="handleLangRefresh(language.id)" rounded="rounded"
                                                                                    class="flex flex-wrap items-center">
                                                                                    <ps-icon name="refresh" class="me-2 font-semibold" />
                                                                                    <ps-label
                                                                                        textColor="text-white dark:text-secondary-800">{{
                                                                                            $t('core__be_lang_refresh') }}</ps-label>
                                                                                </ps-button>
                                                                            </div>
                                                                        </div>
                                    
                                                                    </div>
                                                                </div>
                                                            </div>
                                    
                                                        </div>
                                                        <!-- card body end -->
                                                    </div>
                                                </ps-card>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- langauge_configuration_1 -->
                                    
                                    </div>

                                </div>
                                <!-- End Langauge Configuration -->
                                
                                <!-- Start Core Configuration -->
                                <div v-if="title == settingColumn[2].label">

                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core__be_admin_version_no_title') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core__be_admin_version_no_desc') }}</ps-label>
                                                <br>
                                                
                                                 
                                                <div class="px-4 py-3"
                                                        v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'backend_version_no' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                        :key="index">
                                                        <div class="flex items-center">
                                                            <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                                    v-if="coreField.mandatory = 1"
                                                                    class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                            <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                                <template #content>
                                                                    <ps-icon name="info" w="20" h="20"
                                                                        class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                                </template>
                                                                <template #tooltips>
                                                                    For more details: <a target="_blank"
                                                                        href="https://doc.clickup.com/24312566/p/h/q5yqp-81325/01ae928e06a86ac"
                                                                        class="underline">Refer to this doc</a>
                                                                </template>
                                                            </ps-tooltip>
                                                        </div>
                                                        <ps-input ref="backend_version_no" type="text"
                                                            v-model:value="form.backend_version_no" :placeholder="$t(coreField.placeholder)"
                                                            @keyup="coreField.mandatory == 1 ? validateEmptyInput('backend_version_no', form.backend_version_no) : ''"
                                                            @blur="coreField.mandatory == 1 ? validateEmptyInput('backend_version_no', form.backend_version_no) : ''" />
                                                        <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                            class="mt-2 block">{{ errors.backend_version_no }}</ps-label-caption>
                                                    </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- core_configuration_1 -->
                                    
                                    </div>

                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core__be_frontend_enable_title') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core__be_frontend_enable_desc') }}</ps-label>
                                                <br>
                                                
                                                 
                                                <div class="px-4 py-3"
                                                    v-for="( coreField, index) in
                                                        coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'fe_setting' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <div class="flex items-center">
                                                        <ps-checkbox-value v-model:value="form.fe_setting" class="font-normal"
                                                            :title="$t(coreField.placeholder)" />
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="-ms-1 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-158964/e4356ff6c4739ee"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                                </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- core_configuration_2 -->
                                    
                                    </div>

                                </div>
                                <!-- End Core Configuration -->

                                <!-- Start Map Configuration -->
                                <div v-if="title == settingColumn[3].label">
                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core__be_default_map_title') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core__be_default_map_desc') }}</ps-label>
                                                <br>
                                                
                                                 
                                                <div class="px-4 py-3">
                                                    <div class="flex justify-between">
                                                        <ps-radio-value v-model:value="form.is_google_map" :title="$t('is_google_map')"
                                                            class="me-4" />
                                                        <ps-radio-value v-model:value="form.is_google_map"
                                                            :title="$t('is_open_street_map')" class="me-4" />
                                                    </div>
                                                </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- map_configuration_1 -->
                                    
                                    </div>

                                    <div class="px-4 py-0 ">

                                        <ps-accordion class="w-130 md:items-right">
                                            
                                            <template #title>
                                                <ps-label-header-5>{{ $t('core__be_google_map_key_title') }}</ps-label-header-5>      
                                            </template>
                                            <template #description>
                                                
                                                
                                                <ps-label class="text-base">{{ $t('core__be_google_map_key_desc') }}</ps-label>
                                                <br>
                                                
                                                 
                                                <div class="px-4 py-3"
                                                        v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'map_key' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                        :key="index">
                                                        <div class="flex items-center">
                                                            <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory = 1"
                                                                    class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                            <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                                <template #content>
                                                                    <ps-icon name="info" w="20" h="20"
                                                                        class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                                </template>
                                                                <template #tooltips>
                                                                    For more details: <a target="_blank"
                                                                        href="https://doc.clickup.com/24312566/p/h/q5yqp-77245/5ed0ce87caa866c"
                                                                        class="underline">Refer to this doc</a>
                                                                </template>
                                                            </ps-tooltip>
                                                        </div>
                                                        <ps-textarea rows="4" ref="map_key" v-model:value="form.map_key"
                                                            :placeholder="$t(coreField.placeholder)"
                                                            @keyup="coreField.mandatory == 1 ? validateEmptyInput('map_key', form.map_key) : ''"
                                                            @blur="coreField.mandatory == 1 ? validateEmptyInput('map_key', form.map_key) : ''"></ps-textarea>
                                                        <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                            class="mt-2 block">{{ errors.map_key }}</ps-label-caption>
                                                    </div>
                                    
                                    
                                            </template>
                                    
                                            
                                    
                                        </ps-accordion> <!-- map_configuration_2 -->
                                    
                                    </div>

                                </div>
                                <!-- End Map Configuration -->

                               <!--  Start Date Configuration  --> 
                               <div v-if="title == settingColumn[4].label">

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_date_format_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_date_format_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'date_format' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <div class="flex items-center">
                                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                                v-if="coreField.mandatory == 1"
                                                                class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-78425/f52485b24b02ab2"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                    </div>
                                                    <ps-dropdown align="left" class="lg:mt-2 mt-1 w-full">
                                                        <template #select>
                                                            <ps-dropdown-select ref="date_format"
                                                                :placeholder="$t(coreField.placeholder)" :showCenter="true"
                                                                :selectedValue="form.date_format == '' ? '' : dateFormatList.filter((date) => date == form.date_format)[0]"
                                                                @change="coreField.mandatory = 1 ? validateEmptyInput('date_format', form.date_format) : ''"
                                                                @blur="coreField.mandatory = 1 ? validateEmptyInput('date_format', form.date_format) : ''" />
                                                        </template>
                                                        <template #list>
                                                            <div
                                                                class="rounded-md shadow-xs w-full bg-background dark:bg-backgroundDark">
                                                                <div class="pt-2 z-30">
                                                                    <div>
                                                                        <div v-for="date in dateFormatList" :key="date"
                                                                            class="w-96 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                                                            @click="[(form.date_format = date), coreField.mandatory = 1 ? validateEmptyInput('date_format', form.date_format) : '']">
                                                                            <ps-label class="ms-2"
                                                                                :class="date == form.date_format ? ' font-bold' : ''">{{
                                                                                    date }}</ps-label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </ps-dropdown>
                                                    <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                        class="mt-2 block">{{ errors.date_format }}</ps-label-caption>
                                                </div> 
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- date_configuration_1 -->
                                
                                </div>

                               </div>
                               <!--  End Date Configuration  --> 
                               
                               <!-- Start Notification Configuration -->
                               <div v-if="title == settingColumn[5].label">

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_push_noti_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_push_noti_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                            <input class="" type="file" accept=".json" ref="importFile" id="zip-imported" style="display: none;"
                                                                            @change="handleImport($event)">
                                
                                                <!--start-->
                                                <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'fcm_api_key' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                   <div class="flex items-center">
                                                        <ps-label class="text-base">{{ $t('firebase_private_json_file') }} <span v-if="coreField.mandatory = 1"
                                                                    class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                            <template #content>
                                                                <ps-icon name="info" w="20" h="20"
                                                                    class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                            </template>
                                                            <template #tooltips>
                                                                For more details: <a target="_blank"
                                                                    href="https://doc.clickup.com/24312566/p/h/q5yqp-199038/b283d1c051b8362"
                                                                    class="underline">Refer to this doc</a>
                                                            </template>
                                                        </ps-tooltip>
                                                   </div>
                                
                                                    <div class="bg-secondary-100 p-2 text-sm dark:bg-secondary-900 text-secondary-800 dark:text-secondary-100 rounded border shadow-none  cursor-pointer" v-if="selectedFile" @click="importClicked">
                                                        <!-- <ps-input-with-right-icon
                                                            @click="importClicked"
                                                            class="w-full mb-3"
                                                            :disabled="false"
                                                            disabledTheme="text-secondary-300 border-secondary-200 shadow-none placeholder-secondary-300 cursor-pointer"
                                                            theme="bg-secondary-100 dark:bg-secondary-900 text-secondary-800 dark:text-secondary-100 cursor-pointer"
                                                            v-model:value="selectedFile"
                                                        /> -->
                                                        {{ selectedFile }}
                                                    </div>
                                                    <!-- for exported zip file import start -->
                                
                                                    <div v-if="!selectedFile || selectedFile == ''" class="">
                                                        <!-- {{ 'here' }} -->
                                                        <ps-button colors='bg-white dark:bg-primary-800 text-white dark:text-secondary-50'
                                                            justify="justify-start items-start" border="border border-secondary-200" type="button"
                                                            @click="importClicked" class="mb-0.5  w-full flex  zip-import items-center" padding="px-3 py-2">
                                                            <ps-icon name="plus" class="mx-0.5 font-semibold justify-start text-primary-500 " />
                                                            <ps-label class="justify-start" textColor="text-secondary-500">{{ $t('import_file')
                                                            }}</ps-label>
                                                        </ps-button>
                                                    </div>
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.firebasePrivateKeyJsonFile
                                                        }}</ps-label-caption>
                                
                                                    <!-- for exported zip file import end -->
                                                </div>
                                
                                                <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'topics' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory = 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-input ref="topics" type="text" v-model:value="form.topics"
                                                        :placeholder="$t(coreField.placeholder)"
                                                        @keyup="coreField.mandatory == 1 ? validateEmptyInput('topics', form.topics) : ''"
                                                        @blur="coreField.mandatory == 1 ? validateEmptyInput('topics', form.topics) : ''" />
                                                    <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                        class="mt-2 block">{{ errors.topics }}</ps-label-caption>
                                                </div>
                                
                                                <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'topics_fe' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory == 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-input ref="topics_fe" type="text" v-model:value="form.topics_fe"
                                                        :placeholder="$t(coreField.placeholder)"
                                                        @keyup="coreField.mandatory == 1 ? validateEmptyInput('topics_fe', form.topics_fe) : ''"
                                                        @blur="coreField.mandatory == 1 ? validateEmptyInput('topics_fe', form.topics_fe) : ''" />
                                                    <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                        class="mt-2 block">{{ errors.topics_fe }}</ps-label-caption>
                                                </div>
                                                <!--end-->
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- notification_configuration_1 -->
                                
                                </div>

                               </div> 
                               <!-- End Notification Configuration -->
                                

                               <!-- Start Data Configuration -->
                               <div v-if="title == settingColumn[6].label">

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_slow_moving_item_limit_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'slow_moving_item_limit' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="slow_moving_item_limit" type="text"
                                                    v-model:value="form.slow_moving_item_limit"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('slow_moving_item_limit', form.slow_moving_item_limit) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('slow_moving_item_limit', form.slow_moving_item_limit) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.slow_moving_item_limit }}</ps-label-caption>
                                
                                                <ps-label class="ms-2 mt-1 text-sm" textColor="text-secondary-400">{{
                                                    $t('slow_moving_item_limit_info') }}<span
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                            </div>   
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- data_configuration_1 -->
                                
                                </div>


                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__search_limit_configuration') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            
                                            
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'search_item_limit' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="search_item_limit" type="text" v-model:value="form.search_item_limit"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('search_item_limit', form.search_item_limit) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('search_item_limit', form.search_item_limit) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.search_item_limit }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'search_category_limit' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="search_category_limit" type="text"
                                                    v-model:value="form.search_category_limit"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('search_category_limit', form.search_category_limit) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('search_category_limit', form.search_category_limit) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.search_category_limit }}</ps-label-caption>
                                            </div>
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'search_user_limit' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="search_user_limit" type="text" v-model:value="form.search_user_limit"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('search_user_limit', form.search_user_limit) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('search_user_limit', form.search_user_limit) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.search_user_limit }}</ps-label-caption>
                                            </div>
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- data_configuration_2 -->
                                
                                </div>

                               </div>
                               <!-- End Data Configuration -->
                              
                              <!-- Start Watermark Configuration -->
                              <div v-if="title == settingColumn[7].label">
                                
                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_watermark_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_watermark_desc') }}</ps-label>
                                            <br>
                                            
                                            
                                            <div class="px-4 py-0"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'is_watermask' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <div class="flex justify-between">
                                                    <ps-label class="text-base"> {{ $t(coreField.label_name) }}<span
                                                            v-if="coreField.mandatory = 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-toggle :selectedValue="form.is_watermask == 1 ? true : false"
                                                        @click="handleEnableDisable()"
                                                        toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                                                </div>
                                            </div>
                                
                                            <div v-if="form.is_watermask == 1">
                                                <!-- <ps-label class="text-base flex flex-row">{{ $t('watermask_title') }} </ps-label>
                                                    <ps-input  ref="watermask_title" type="text" v-model:value="form.watermask_title"  />
                                                    <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.watermask_title }}</ps-label-caption> -->
                                                <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'watermask_image_size' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory = 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-input ref="watermask_image_size" type="number"
                                                        v-model:value="form.watermask_image_size"
                                                        :placeholder="$t(coreField.placeholder)"
                                                        @keyup="coreField.mandatory == 1 ? validateEmptyInput('watermask_image_size', form.watermask_image_size) : ''"
                                                        @blur="coreField.mandatory == 1 ? validateEmptyInput('watermask_image_size', form.watermask_image_size) : ''" />
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{
                                                        errors.watermask_image_size }}</ps-label-caption>
                                                </div>
                                
                                                <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'watermask_angle' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory = 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-input ref="watermask_angle" type="number"
                                                        v-model:value="form.watermask_angle"
                                                        :placeholder="$t(coreField.placeholder)"
                                                        @keyup="coreField.mandatory == 1 ? validateEmptyInput('watermask_angle', form.watermask_angle) : ''"
                                                        @blur="coreField.mandatory == 1 ? validateEmptyInput('watermask_angle', form.watermask_angle) : ''" />
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{
                                                        errors.watermask_angle }}</ps-label-caption>
                                                </div>
                                                
                                
                                                <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'position' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory == 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-dropdown class="w-full" h="h-auto">
                                                        <template #select>
                                                            <ps-dropdown-select :placeholder="$t(coreField.placeholder)"
                                                                :showCenter="true"
                                                                :selectedValue="paddingList.filter((position) => position.value == form.position)[0].label"
                                                                @change="coreField.mandatory = 1 ? validateEmptyInput('position', form.position) : ''"
                                                                @blur="coreField.mandatory = 1 ? validateEmptyInput('position', form.position) : ''" />
                                                        </template>
                                                        <template #list>
                                                            <div class="">
                                                                <div v-for="row in paddingList" :key="row.id" class="w-56">
                                                                    <ps-label
                                                                        @click="[(form.position = row.value), coreField.mandatory = 1 ? validateEmptyInput('position', form.position) : '']"
                                                                        class="py-2 px-4 text-md hover:bg-primary-50 cursor-pointer">
                                                                        {{ row.label }}</ps-label>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </ps-dropdown>
                                                </div>
                                
                                                <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'padding' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory = 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-input ref="padding" type="number" v-model:value="form.padding"
                                                        :placeholder="$t(coreField.placeholder)"
                                                        @keyup="coreField.mandatory == 1 ? validateEmptyInput('padding', form.padding) : ''"
                                                        @blur="coreField.mandatory == 1 ? validateEmptyInput('padding', form.padding) : ''" />
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.padding
                                                    }}</ps-label-caption>
                                                </div>
                                
                                                <div class="px-4 py-3"
                                                    v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'opacity' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                    :key="index">
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory = 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-input ref="opacity" type="number" v-model:value="form.opacity"
                                                        :placeholder="$t(coreField.placeholder)"
                                                        @keyup="coreField.mandatory == 1 ? validateEmptyInput('opacity', form.opacity) : ''"
                                                        @blur="coreField.mandatory == 1 ? validateEmptyInput('opacity', form.opacity) : ''" />
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.opacity
                                                    }}</ps-label-caption>
                                                </div>
                                
                                
                                
                                                <div class="px-4 py-3">
                                                    <ps-label class="text-base mb-1">{{ $t('water_mask_background') }}</ps-label>
                                                    <ps-label
                                                        textColor="text-secondary-400 text-xs"> {{
                                                            $t('core__be_recommended_size_16_16') }}
                                                    </ps-label>
                                                    
                                                    <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.water_mask_background"
                                                        :imagePath="$page.props.uploadUrl + '/' + backend_setting.water_mask_background?.img_path"/>
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{
                                                        errors.water_mask_background }}</ps-label-caption>
                                                </div>
                                
                                                <div class="px-4 py-3">
                                                    <ps-label class="text-base mb-1">{{ $t('water_mask_preview') }}</ps-label>
                                                    <div class="container mx-auto grid-cols-4 lg:space-y-0  lg:gap-2 lg:grid  lg:grid-cols-4"
                                                        v-if="reRenderImage">
                                
                                
                                                        <div v-if="backend_setting.water_mask_background && reRenderImage && backend_setting.water_mask_background_org"
                                                            class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                            <img class="object-cointain"
                                                                v-lazy="{ src: $page.props.uploadUrl + '/' + backend_setting.water_mask_background_org?.img_path + '?timestamp=' + reRenderTime, loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                                                                alt="water_mask_background" />
                                                            <!-- <img :src="$page.props.uploadUrl + '/' + form.original_water_mask "/> -->
                                                            <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview')
                                                            }} </ps-label>
                                                        </div>
                                
                                                        <div v-else
                                                            class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                            <img v-lazy="{ src: $page.props.uploadUrl + '/' + $page.props.sysImageUrl + '/default_photo.png', loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                                                                alt="water_mask_background" />
                                                            <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview')
                                                            }} </ps-label>
                                                        </div>
                                
                                                        <div v-if="backend_setting.water_mask_background && reRenderImage && backend_setting.water_mask_background_org"
                                                            class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center content-end">
                                                            <img class="object-cointain"
                                                                v-lazy="{ src: $page.props.thumb1xUrl + '/' + backend_setting.water_mask_background_org?.img_path + '?timestamp=' + reRenderTime, loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                                                                alt="water_mask_background" width="80" />
                                                            <ps-label class="text-base grid place-items-center">{{
                                                                $t('1x_thumb_preview') }} </ps-label>
                                                        </div>
                                                        <div v-else
                                                            class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                            <img v-lazy="{ src: $page.props.thumb1xUrl + '/' + $page.props.sysImageUrl + '/default_photo.png', loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                                                                alt="water_mask_background" />
                                                            <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview')
                                                            }} </ps-label>
                                                        </div>
                                                        <div v-if="backend_setting.water_mask_background && reRenderImage && backend_setting.water_mask_background_org"
                                                            class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center content-end">
                                                            <img class="object-cointain"
                                                                v-lazy="{ src: $page.props.thumb2xUrl + '/' + backend_setting.water_mask_background_org?.img_path + '?timestamp=' + reRenderTime, loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                                                                alt="water_mask_background" width="100" />
                                                            <ps-label class="text-base grid place-items-center">{{
                                                                $t('2x_thumb_preview') }} </ps-label>
                                                        </div>
                                                        <div v-else
                                                            class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                            <img v-lazy="{ src: $page.props.thumb2xUrl + '/' + $page.props.sysImageUrl + '/default_photo.png', loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                                                                alt="water_mask_background" />
                                                            <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview')
                                                            }} </ps-label>
                                                        </div>
                                                        <div v-if="backend_setting.water_mask_background && reRenderImage && backend_setting.water_mask_background_org"
                                                            class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center content-end ">
                                                            <img class="object-cointain"
                                                                v-lazy="{ src: $page.props.thumb3xUrl + '/' + backend_setting.water_mask_background_org?.img_path + '?timestamp=' + reRenderTime, loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                                                                alt="water_mask_background" width="110" />
                                                            <ps-label class="text-base grid place-items-center">{{
                                                                $t('3x_thumb_preview') }} </ps-label>
                                                        </div>
                                                        <div v-else
                                                            class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                            <img v-lazy="{ src: $page.props.thumb3xUrl + '/' + $page.props.sysImageUrl + '/default_photo.png', loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                                                                alt="water_mask_background" />
                                                            <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview')
                                                            }} </ps-label>
                                                        </div>
                                                    </div>
                                                </div>
                                
                                
                                
                                            </div>
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- watermark_configuration_1 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_watermark_image_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                             <div class="px-4 py-3">
                                                    <ps-label class="text-base mb-1">{{ $t('core__backend_water_mask_image')
                                                    }}</ps-label>
                                                    <ps-label
                                                        textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_16_16')
                                                        }}
                                                    </ps-label>
                                                   
                                                    <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.backend_water_mask_image"
                                                        :imagePath="$page.props.uploadUrl + '/' + backend_setting.backend_water_mask_image?.img_path"/>
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{
                                                        errors.backend_water_mask_image }}</ps-label-caption>
                                                </div>
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- watermark_configuration_2 -->
                                
                                </div>
                                


                              </div>
                              <!-- End Watermark Configuration -->

                              <!-- Start User Configuration -->
                              <div v-if="title == settingColumn[8].label">
                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_user_social_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            <div class="px-4 py-3"
                                                v-for="( coreField, index) in
                                                    coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_social_info_override' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <div class="flex items-center">
                                                    <ps-checkbox-value v-model:value="form.user_social_info_override"
                                                        class="font-normal" :title="$t(coreField.placeholder)" />
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="-ms-1 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-80685/6220fdbb827d06a"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                            </div>

                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_social_info_override' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{
                                                    $t(coreField.label_name) }}</ps-label>
                                            </div>
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- user_configuration_1 -->
                                
                                </div>


                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_app_token_fb_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_app_token_fb_desc') }}</ps-label>
                                            <br>
                                            
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'app_token' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <div class="flex items-center">
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory = 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-78985/fb754eedbf26c1d"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                                <ps-input ref="app_token" type="text" v-model:value="form.app_token"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('app_token', form.app_token) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('app_token', form.app_token) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.app_token }}</ps-label-caption>
                                            </div>
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- user_configuration_2 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_user_upload_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_user_upload_desc') }}</ps-label>
                                            <br>
                                            
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'upload_setting' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <div class="flex items-center">
                                
                                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                            v-if="coreField.mandatory == 1"
                                                            class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                    <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                :href="uploadSettingDocUrl"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                
                                                <ps-dropdown class="w-full" h="h-auto">
                                                    <template #select>
                                                        <ps-dropdown-select :placeholder="$t(coreField.placeholder)"
                                                            :showCenter="true"
                                                            :selectedValue="uploadSettingList.filter((setting) => setting.value == form.upload_setting)[0].label"
                                                            @change="coreField.mandatory = 1 ? validateEmptyInput('upload_setting', form.upload_setting) : ''"
                                                            @blur="coreField.mandatory = 1 ? validateEmptyInput('upload_setting', form.upload_setting) : ''" />
                                                    </template>
                                                    <template #list>
                                                        <div class="">
                                                            <div v-for="row in uploadSettingList" :key="row.id" class="w-56">
                                                                <ps-label
                                                                    @click="[(form.upload_setting = row.value), coreField.mandatory = 1 ? validateEmptyInput('upload_setting', form.upload_setting) : '']"
                                                                    class="py-2 px-4 text-md hover:bg-primary-50 cursor-pointer">
                                                                    {{ row.label }}</ps-label>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </ps-dropdown>
                                
                                            </div>
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- user_configuration_3 -->
                                
                                </div>


                                
                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_email_verification_code_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            
                                            
                                            <div class="px-4 py-3"
                                                v-for="( coreField, index) in
                                                    coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'email_verification_enabled' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <div class="flex items-center">
                                                    <ps-checkbox-value v-model:value="form.email_verification_enabled"
                                                        class="font-normal" :title="$t(coreField.placeholder)" />
                                                    <ps-tooltip tooltiptext="absolute  bottom-full">
                                                        <template #content>
                                                            <ps-icon name="info" w="20" h="20"
                                                                class=" transition-all duration-300 text-primary-500" />
                                                        </template>
                                                        <template #tooltips>
                                                            For more details: <a target="_blank"
                                                                href="https://doc.clickup.com/24312566/p/h/q5yqp-158944/a0991286de6c1e3"
                                                                class="underline">Refer to this doc</a>
                                                        </template>
                                                    </ps-tooltip>
                                                </div>
                                            </div>
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'email_verification_enabled' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                    <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{
                                                        $t(coreField.label_name) }}</ps-label>
                                            </div>
                                            

                                        </template>

                                        

                                    </ps-accordion> <!-- user_configuration_4 -->

                                </div>


                              </div>
                              <!-- End User Configuration -->
                              
                              <!-- Start smtp configuration -->
                              <div v-if="title == settingColumn[9].label">


                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_smtp_setup_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_smtp_setup_desc') }}</ps-label>
                                            <br>
                                            
                                
                                            <!--start-->
                                                <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'sender_name' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="sender_name" type="email" v-model:value="form.sender_name"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('sender_name', form.sender_name) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('sender_name', form.sender_name) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.sender_name }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'sender_email' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="sender_email" type="email" v-model:value="form.sender_email"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmailInput('sender_email', form.sender_email) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmailInput('sender_email', form.sender_email) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.sender_email }}</ps-label-caption>
                                            </div>
                                
                                
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'receive_email' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="receive_email" type="email" v-model:value="form.receive_email"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmailInput('receive_email', form.receive_email) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmailInput('receive_email', form.receive_email) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.receive_email }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_host' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="smtp_host" type="text" v-model:value="form.smtp_host"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('smtp_host', form.smtp_host) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('smtp_host', form.smtp_host) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.smtp_host }}</ps-label-caption>
                                            </div>
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_port' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="smtp_port" type="text" v-model:value="form.smtp_port"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('smtp_port', form.smtp_port) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('smtp_port', form.smtp_port) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.smtp_port }}</ps-label-caption>
                                            </div>
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_user' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="smtp_user" type="text" v-model:value="form.smtp_user"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('smtp_user', form.smtp_user) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('smtp_user', form.smtp_user) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.smtp_user }}</ps-label-caption>
                                            </div>
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_pass' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="smtp_pass" type="password" v-model:value="form.smtp_pass"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('smtp_pass', form.smtp_pass) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('smtp_pass', form.smtp_pass) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.smtp_pass }}</ps-label-caption>
                                            </div>
                                            <!--end-->
                                
                                            <div class="px-4 py-3"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_encryption' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="smtp_encryption" type="text" v-model:value="form.smtp_encryption"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('smtp_encryption', form.smtp_encryption) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('smtp_encryption', form.smtp_encryption) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.smtp_encryption }}</ps-label-caption>
                                            </div>
                                            
                                            
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- smtp_configuration_1 -->
                                
                                </div>

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_smtp_checking_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_smtp_checking_desc') }}</ps-label>
                                            <br>
                                            
                                
                                
                                
                                            <div class="p-4 border border-secondary-100 rounded-lg">
                                                
                                
                                                <div class="">
                                                    <ps-label class="text-base mb-1">{{ $t('core__be_enter_receiver_email') }}
                                                    </ps-label>
                                                    <ps-input ref="search_item_limit" type="text"
                                                        v-model:value="smtpCheckForm.email"
                                                        :placeholder="$t('core__be_smtp_check_email_placeholder')" />
                                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.email
                                                    }}</ps-label-caption>
                                                </div>
                                                <div class="flex flex-row pt-2 justify-end">
                                
                                                    <ps-button @click="handleCancel()" textSize="text-base" type="reset"
                                                        class="me-4" colors="text-primary-500" focus="" hover="">{{
                                                            $t('core__be_btn_cancel') }}</ps-button>
                                                    <ps-button @click="checkSmtpConfiguration()"
                                                        class="transition-all duration-300 min-w-3xs me-4" padding="px-6 py-2"
                                                        rounded="rounded" hover="" focus="">
                                                        <ps-loading v-if="loadingSmtp"
                                                            theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                                            loadingSize="h-5 w-5" />
                                                        <ps-icon v-if="successSmtp" name="check" w="20" h="20"
                                                            class="me-1.5 transition-all duration-300" />
                                                        <ps-label v-if="successSmtp" class="transition-all duration-300"
                                                            textColor="text-white dark:text-secondaryDark-black">{{
                                                                $t('core__be_btn_success') }}</ps-label>
                                                        <ps-label v-if="!loadingSmtp && !successSmtp"
                                                            textColor="text-white dark:text-secondaryDark-black">
                                                            {{ $t('core__be_btn_send') }} </ps-label>
                                                    </ps-button>
                                                </div>
                                                
                                            </div>
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- smtp_configuration_2 -->
                                
                                </div>


                              </div>
                             <!-- End smtp configuration --> 


                             <!-- Start Deeplink Configuration  --> 
                             <div v-if="title == settingColumn[10].label">

                                <div class="px-4 py-0 ">

                                    <ps-accordion class="w-130 md:items-right">
                                        
                                        <template #title>
                                            <ps-label-header-5>{{ $t('core__be_deeplink_title') }}</ps-label-header-5>      
                                        </template>
                                        <template #description>
                                            
                                            
                                            <ps-label class="text-base">{{ $t('core__be_deeplink_desc') }}</ps-label>
                                            <br>
                                            
                                
                                
                                
                                            <!--start-->
                                            <div class="px-4 py-3">
                                                    <ps-label class="text-base flex flex-row">
                                                        {{ $t('deeplinkGenerator__be_default_dynamic_link') }} <span class="text-red-800 font-medium ms-1">*</span>
                                                    </ps-label>
                                                    <ps-dropdown class="w-full" h="h-auto">
                                                        <template #select>
                                                            <ps-dropdown-select placeholder="replace"
                                                                :showCenter="true"
                                                                :selectedValue="dynamicLinkOptions.filter((dynamicLink) => dynamicLink.id == form.default_dynamic_link)[0].value" />
                                                        </template>
                                                        <template #list>
                                                            <div class="">
                                                                <div v-for="row in dynamicLinkOptions" :key="row.id" class="w-56">
                                                                    <ps-label
                                                                        @click="[(form.default_dynamic_link = row.id)]"
                                                                        class="py-2 px-4 text-md hover:bg-primary-50 cursor-pointer">
                                                                        {{ row.value }}</ps-label>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </ps-dropdown>
                                                </div>
                                            <div class="px-4 py-3" v-if="shouldDisableForFirebase()"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_key' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="dyn_link_key" type="text" v-model:value="form.dyn_link_key"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_key', form.dyn_link_key) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_key', form.dyn_link_key) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.dyn_link_key }}</ps-label-caption>
                                            </div>
                                            <div class="px-4 py-3" v-if="shouldDisableForFirebase()"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_domain' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="dyn_link_domain" type="text" v-model:value="form.dyn_link_domain"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_domain', form.dyn_link_domain) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_domain', form.dyn_link_domain) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.dyn_link_domain }}</ps-label-caption>
                                            </div>
                                            <div class="px-4 py-3" v-if="shouldDisableForFirebase()"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_url' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="dyn_link_url" type="text" v-model:value="form.dyn_link_url"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_url', form.dyn_link_url) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_url', form.dyn_link_url) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.dyn_link_url }}</ps-label-caption>
                                            </div>
                                            <div class="px-4 py-3" v-if="shouldDisableForFirebase()"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_deep_url' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="dyn_link_deep_url" type="text" v-model:value="form.dyn_link_deep_url"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_deep_url', form.dyn_link_deep_url) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_deep_url', form.dyn_link_deep_url) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.dyn_link_deep_url }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3" v-if="!shouldDisableForFirebase()">
                                                <ps-label class="text-base flex flex-row">{{ $t('deeplinkGenerator__be_scheme_name') }} <span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="scheme_name" type="text"
                                                    v-model:value="form.scheme_name"
                                                    placeholder="deeplinkGenerator__be_scheme_name_placeholder"
                                                    @keyup="!shouldDisableForFirebase() ? validateEmptyInput('scheme_name', form.scheme_name) : ''"
                                                    @blur="!shouldDisableForFirebase() ? validateEmptyInput('scheme_name', form.scheme_name) : ''" />
                                                <ps-label-caption textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.scheme_name }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3" v-if="!shouldDisableForFirebase()">
                                                <ps-label class="text-base flex flex-row">{{ $t('deeplinkGenerator__be_android_package') }} <span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="android_package" type="text"
                                                    v-model:value="form.android_package"
                                                    placeholder="deeplinkGenerator__be_android_package_placeholder"
                                                    @keyup="!shouldDisableForFirebase() ? validateEmptyInput('android_package', form.android_package) : ''"
                                                    @blur="!shouldDisableForFirebase() ? validateEmptyInput('android_package', form.android_package) : ''" />
                                                <ps-label-caption textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.android_package }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3" v-if="!shouldDisableForFirebase()">
                                                <ps-label class="text-base flex flex-row">{{ $t('deeplinkGenerator__be_apple_id') }} <span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="apple_id" type="text"
                                                    v-model:value="form.apple_id"
                                                    placeholder="deeplinkGenerator__be_apple_id_placeholder"
                                                    @keyup="!shouldDisableForFirebase() ? validateEmptyInput('apple_id', form.apple_id) : ''"
                                                    @blur="!shouldDisableForFirebase() ? validateEmptyInput('apple_id', form.apple_id) : ''" />
                                                <ps-label-caption textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.apple_id }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3" v-if="shouldDisableForFirebase()"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_package_name' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="dyn_link_package_name" type="text"
                                                    v-model:value="form.dyn_link_package_name"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_package_name', form.dyn_link_package_name) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('dyn_link_package_name', form.dyn_link_package_name) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.dyn_link_package_name }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3" v-if="shouldDisableForFirebase()"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'ios_boundle_id' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="ios_boundle_id" type="text" v-model:value="form.ios_boundle_id"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('ios_boundle_id', form.ios_boundle_id) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('ios_boundle_id', form.ios_boundle_id) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.ios_boundle_id }}</ps-label-caption>
                                            </div>
                                
                                            <div class="px-4 py-3" v-if="shouldDisableForFirebase()"
                                                v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'ios_appstore_id' && coreField.enable === 1 && coreField.is_delete === 0)"
                                                :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span
                                                        v-if="coreField.mandatory = 1"
                                                        class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input ref="ios_appstore_id" type="text" v-model:value="form.ios_appstore_id"
                                                    :placeholder="$t(coreField.placeholder)"
                                                    @keyup="coreField.mandatory == 1 ? validateEmptyInput('ios_appstore_id', form.ios_appstore_id) : ''"
                                                    @blur="coreField.mandatory == 1 ? validateEmptyInput('ios_appstore_id', form.ios_appstore_id) : ''" />
                                                <ps-label-caption v-if="coreField.mandatory == 1" textColor="text-red-500 "
                                                    class="mt-2 block">{{ errors.ios_appstore_id }}</ps-label-caption>
                                            </div>
                                            <!--end-->
                                            
                                
                                        </template>
                                
                                        
                                
                                    </ps-accordion> <!-- deeplink_configuration_1 -->
                                
                                </div>

                             </div>
                             <!-- End Deeplink Configuration  --> 
                               
                                <div class="flex flex-row  px-4 py-3 justify-end mb-2.5 mt-4">
                                    <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4"
                                        colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel')
                                        }}</ps-button>
                                    <ps-button :disabled="!can.updateBackendSetting"
                                        @click="handleSubmit(this.backend_setting.id)"
                                        class="transition-all duration-300 min-w-3xs me-4" padding="px-8 py-0"
                                        rounded="rounded" hover="" focus="">
                                        <ps-loading v-if="loading"
                                            theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                            loadingSize="h-5 w-5" />
                                        <ps-icon v-if="success" name="check" w="20" h="20"
                                            class="me-1.5 transition-all duration-300" />
                                        <ps-label v-if="success" class="transition-all duration-300"
                                            textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_saved')
                                            }}</ps-label>
                                        <ps-label v-if="!loading && !success"
                                            textColor="text-white dark:text-secondaryDark-black">
                                            {{ $t('core__be_btn_save') }} </ps-label>
                                    </ps-button>
                                </div>

                               

                                

                            </div>
                            <div class="px-4">

                                <div  v-for="column in settingColumn.slice().sort((a, b) => $t(a.label).localeCompare($t(b.label)))" :key="column.id">
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
                    </div>
                </div>
            </ps-card>

            <ps-success-dialog ref="ps_success_dialog" />
            <ps-error-dialog ref="ps_error_dialog" />
            <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />

            <!-- <ps-dialog-with-input ref="ps_dialog_with_input">
                <template #body>
                    <div class="w-full text-start mt-2">
                        <ps-label class="font-light text-sm lg:text-base">{{ $t('your_email') }}</ps-label>
                        <ps-input ref="email" type="email" v-model:value="smtpCheckForm.email" :placeholder="$t('enter_your_email')"
                                @keyup="validateEmptyInput( 'email', smtpCheckForm.email )" @blur="validateEmptyInput('email', smtpCheckForm.email )" />
                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.email }}</ps-label-caption>
                    </div>
                </template>
            </ps-dialog-with-input> -->
        </div>
    </ps-layout>
    <PsColorPickerModal ref="ps_color_picker_modal" />
</template>

<script>
import { defineComponent, ref, defineAsyncComponent, reactive, onMounted, onUnmounted } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import FlashMessage from "../components/FlashMessage.vue";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import PsRadio from "@/Components/Core/Radio/PsRadio.vue";
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsSuccessDialog from '@/Components/Core/Dialog/PsSuccessDialog.vue';
import PsErrorDialog from "@/Components/Core/Dialog/PsErrorDialog.vue";
import PsDialogWithInput from "@/Components/Core/Dialog/PsDialogWithInput.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
const PsColorPickerModal = defineAsyncComponent(() => import('@/Components/Core/Modals/PsColorPickerModal.vue'));
import PsUtils from '@templateCore/utils/PsUtils';
import { trans } from 'laravel-vue-i18n';
import PsTooltip from '@/Components/Core/Tooltips/PsTooltip.vue';
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsAccordion from "@/Components/Core/Accordion/PsAccordion.vue";

export default defineComponent({
    name: "Edit",
    components: {
        FlashMessage,
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsCheckboxValue,
        PsRadioValue,
        PsRadio,
        PsLabelHeader4,
        PsLabelCaption,
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        Link,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsImageUpload,
        PsSuccessDialog,
        PsErrorDialog,
        PsDialogWithInput,
        PsDropdownSelect,
        PsDropdown,
        PsColorPickerModal,
        PsToggle,
        PsTooltip,
        PsLoadingCircleDialog,
        PsLabelHeader6,
        PsInputWithRightIcon,
        PsAccordion
    },
    layout: PsLayout,
    props: ['languages',
            'uploadSettingDocUrl',
            'firebasePrivateJsonFileName',
            'errors',
            'backend_setting',
            'commonColor',
            'status',
            'coreFieldFilterSettings',
            'can',
            'validation',
            'paddingList',
            'uploadSettingList',
            'currentSection',
            'SettingPage',
            'dynamicLinkSetting'],
    setup(props) {
        let selectedFile = ref();
        const loading = ref(false);
        const success = ref(false);
        const email = ref();
        const loadingSmtp = ref(false);
        const successSmtp = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const ps_dialog_with_input = ref();
        const ps_success_dialog = ref();
        const ps_error_dialog = ref();
        const ps_color_picker_modal = ref();
        const ps_loading_circle_dialog = ref();
        const reRenderImage = ref(true);
        const currentDynamicLink = JSON.parse(props.dynamicLinkSetting.setting);
        const dynamicLinkOptions = JSON.parse(props.dynamicLinkSetting.ref_selection).default_dynamic_link;
        let visible = ref(false);
        const importFile = ref();
        let smtpCheckForm = useForm({
            email: ''
        });
        const reRenderTime = ref(+new Date());

        if(props.firebasePrivateJsonFileName){
            selectedFile.value = props.firebasePrivateJsonFileName;
        }

        let form = useForm(
            {
                sender_name: props.backend_setting.sender_name,
                sender_email: props.backend_setting.sender_email,
                receive_email: props.backend_setting.receive_email,
                fcm_api_key: props.backend_setting.fcm_api_key,
                map_key: props.backend_setting.map_key,
                app_token: props.backend_setting.app_token,
                topics: props.backend_setting.topics,
                topics_fe: props.backend_setting.topics_fe,
                smtp_host: props.backend_setting.smtp_host,
                smtp_port: props.backend_setting.smtp_port,
                smtp_user: props.backend_setting.smtp_user,
                smtp_pass: props.backend_setting.smtp_pass,
                smtp_encryption: props.backend_setting.smtp_encryption,
                email_verification_enabled: props.backend_setting.email_verification_enabled == 1 ? true : false,
                user_social_info_override: props.backend_setting.user_social_info_override == 1 ? true : false,
                landscape_width: props.backend_setting.landscape_width,
                potrait_height: props.backend_setting.potrait_height,
                square_height: props.backend_setting.square_height,
                landscape_thumb_width: props.backend_setting.landscape_thumb_width,
                potrait_thumb_height: props.backend_setting.potrait_thumb_height,
                square_thumb_height: props.backend_setting.square_thumb_height,
                landscape_thumb2x_width: props.backend_setting.landscape_thumb2x_width,
                potrait_thumb2x_height: props.backend_setting.potrait_thumb2x_height,
                square_thumb2x_height: props.backend_setting.square_thumb2x_height,
                landscape_thumb3x_width: props.backend_setting.landscape_thumb3x_width,
                potrait_thumb3x_height: props.backend_setting.potrait_thumb3x_height,
                square_thumb3x_height: props.backend_setting.square_thumb3x_height,
                default_dynamic_link: currentDynamicLink.default_dynamic_link.id,
                scheme_name: currentDynamicLink.scheme_name,
                android_package: currentDynamicLink.android_package,
                apple_id: currentDynamicLink.apple_id,
                dyn_link_key: props.backend_setting.dyn_link_key,
                dyn_link_url: props.backend_setting.dyn_link_url,
                dyn_link_package_name: props.backend_setting.dyn_link_package_name,
                dyn_link_domain: props.backend_setting.dyn_link_domain,
                dyn_link_deep_url: props.backend_setting.dyn_link_deep_url,
                ios_boundle_id: props.backend_setting.ios_boundle_id,
                ios_appstore_id: props.backend_setting.ios_appstore_id,
                backend_version_no: props.backend_setting.backend_version_no,
                slow_moving_item_limit: props.backend_setting.slow_moving_item_limit,
                search_item_limit: props.backend_setting.search_item_limit,
                search_user_limit: props.backend_setting.search_user_limit,
                search_category_limit: props.backend_setting.search_category_limit,
                date_format: props.backend_setting.date_format,
                backend_logo: "",
                fav_icon: "",
                backend_login_image: "",
                // backend_meta_image: "",
                backend_water_mask_image: "",
                water_mask_background: "",
                backend_logo_id: props.backend_setting.backend_logo ? props.backend_setting.backend_logo.id : "",
                backend_fav_icon_id: props.backend_setting.fav_icon ? props.backend_setting.fav_icon.id : "",
                water_mark_image_id: props.backend_setting.backend_water_mask_image ? props.backend_setting.backend_water_mask_image.id : "",
                water_mark_background_id: props.backend_setting.water_mask_background ? props.backend_setting.water_mask_background.id : "",
                watermask_image_size: props.backend_setting.watermask_image_size,
                font_size: props.backend_setting.font_size,
                position: props.backend_setting.position,
                upload_setting: props.backend_setting.upload_setting,
                opacity: props.backend_setting.opacity,
                commonColor: props.commonColor,
                watermask_title: props.backend_setting.watermask_title,
                is_watermask: props.backend_setting.is_watermask,
                watermask_angle: props.backend_setting.watermask_angle,
                padding: props.backend_setting.padding,
                is_google_map: props.backend_setting.is_google_map === 1 ? "Google Map" : "Open Street Map",
                is_open_street_map: props.backend_setting.is_open_street_map,
                fe_setting: props.backend_setting.fe_setting == 1 ? true : false,
                vendor_setting: props.backend_setting.vendor_setting == 1 ? true : false,
                firebasePrivateKeyJsonFile: '',
                // original_water_mask:props.backend_setting.water_mask_background_org.img_path,
                "_method": "put"
            }
        )

        function shouldDisableForFirebase() {
            const firebaseLink = dynamicLinkOptions.find(link => link.id === 'FIREBASE');
            return form.default_dynamic_link === firebaseLink?.id;
        }

        const settingColumn = [
      
            {
                index: 0,
                label: 'core__image_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-80665/e32299b4734944b'
            },
            {
                index: 1,
                label: 'core__lang_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-83845/a164dc7b0c3046b'
            },
            {
                index: 2,
                label: 'core__configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-83845/a164dc7b0c3046b'
            },
            {
                index: 3,
                label: 'core__map_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-83305/c0d33a57bf43ab4'
            },
            {
                index: 4,
                label: 'core__date_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-83305/c0d33a57bf43ab4'
            },
            {
                index: 5,
                label: 'core__notification_configuraion',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-79285/016265d456cc1d6'
            },
            {
                index: 6,
                label: 'core__data_configuraion',
                docu: ''
            },
            {
                index: 7,
                label: 'core__watermark_configuration',
                docu: 'https://app.clickup.com/24312566/v/dc/q5yqp-24005/q5yqp-144765'
            },
            {
                index: 8,
                label: 'core__user_configuration',
                docu: ''
            },
            {
                index: 9,
                label: 'core__smtp_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-79925/32fc70aa1e15255'
            },
            {
                index: 10,
                label: 'core__deep_linking_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-81665/bb69eadab54fc7a'
            }
        ];

        const dateFormatList = [
            'YYYY-MM-DD',
            'YYYY-DD-MM',
            'DD-MM-YYYY',
            'MM-DD-YYYY',
            'DD-MM-YYYY HH:mm',
            'DD-MM-YYYY HH:mm:ss',
            'MM-DD-YYYY HH:mm',
            'MM-DD-YYYY HH:mm:ss',
            'YYYY-MM-DD HH:mm',
            'YYYY-MM-DD HH:mm:ss',
            'YYYY-DD-MM HH:mm',
            'YYYY-DD-MM HH:mm:ss',
            'YYYY/MM/DD',
            'YYYY/DD/MM',
            'DD/MM/YYYY',
            'MM/DD/YYYY',
            'DD/MM/YYYY HH:mm',
            'DD/MM/YYYY HH:mm:ss',
            'MM/DD/YYYY HH:mm',
            'MM/DD/YYYY HH:mm:ss',
            'YYYY/MM/DD HH:mm',
            'YYYY/MM/DD HH:mm:ss',
            'YYYY/DD/MM HH:mm',
            'YYYY/DD/MM HH:mm:ss',
            'dddd, MMMM Do YYYY',
            'HH:mm:ss',
            'hh:mm a',
            'YYYY-MM-DDTHH:mm:ssZ',
            'MMMM D, YYYY',
            'MMM D, YYYY',
            'D MMMM YYYY',
            'D MMM YYYY',
            'ddd, MMM D YYYY',
            'ddd, D MMM YYYY',
            'dddd, MMMM D YYYY',
            'dddd, D MMMM YYYY',
            'YYYY',
            'YYYY-MM',
        ];

        var pageIndex = ( props.SettingPage ?? 2)  ;
        if(pageIndex < 0 || pageIndex >= settingColumn.length){
            pageIndex = 2;
        }

        const title = ref(settingColumn[pageIndex].label);
        const docu = ref(settingColumn[pageIndex].docu);

        // Client Side Validation
        const { isEmail, isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
        }

        const validateEmailInput = (fieldName, fieldValue, errorMessage1 = '', errorMessage2 = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage1) : isEmail(fieldName, fieldValue, errorMessage2);
        }

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }
        function changeSection(v) {
            title.value = v.label;
            docu.value = v.docu;

            // const newUrl = `/admin/backend_setting?page=${v.index}`;
            // window.history.replaceState({}, '', newUrl)
            PsUtils.updateBrowserUrl('backend_setting', `page=${v.index}`);
        }
        function handleCancel() {
            this.$inertia.get(route('backend_setting.index'));
        }
        function handleSubmit(id) {
            // console.log('here');
            this.$inertia.post(route('backend_setting.update', id), form, {
                forceFormData: true,
                onBefore: () => { loading.value = true },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(() => {
                        success.value = false;
                        reRenderImage.value = false;
                        setTimeout(() => {
                            reRenderImage.value = true;
                        }, 200);

                    }, 1000)


                },
                onError: () => {
                    loading.value = false;;
                },
            });
        }

        function openSuccessDialog() {
            ps_success_dialog.value.openModal(trans('core__be_smtp_configuration_check'), trans('smtp_configuration_is_success'), trans('btn_back'),
                () => {
                    // router.get(route('language_string.updateAllLanguageStrings'), {
                    //     onBefore: () => {
                    //         ps_loading_circle_dialog.value.openModal('Importing','We’re processing your file at the moment. Please wait while we import the file for you.');
                    //     },
                    //     onSuccess: () => {
                    //         ps_loading_circle_dialog.value.closeModal();
                    //         //
                    //     },
                    //     onError: () => {
                    //         ps_loading_circle_dialog.value.closeModal();
                    //     },
                    // });

                },
                false);
        }

        function openErrorDialog() {
            ps_error_dialog.value.openModal(trans('core__be_smtp_configuration_check'), trans('smtp_configuration_is_fail'), trans('core__be_btn_ok'), () => { });
        }

        function checkSmtpConfiguration() {
            // console.log('there');
            // ps_dialog_with_input.value.openModal(
            //     trans('checking_email'),
            //     trans('core__comfirm_to_delete_row'),
            //     trans('core__be_btn_confirm'),
            //     trans('core__be_btn_cancel'),
            //     () => {
            this.$inertia.get(route('backend_setting.checkSmtpConfig'), smtpCheckForm, {
                onSuccess: () => {
                    visible.value = true;
                    setTimeout(() => {
                        visible.value = false;
                    }, 3000);
                },
                onError: () => {
                    visible.value = true;
                    setTimeout(() => {
                        visible.value = false;
                    }, 3000);
                },
            })

            // },
            //     () => { }
            // );
        }
        function colorPickerClicked(data) {
            // alert(data.value)
            ps_color_picker_modal.value.openModal(
                data.value,
                (selectedColor) => {
                    console.log(selectedColor)
                    let colorForm = useForm({
                        value: selectedColor,
                        "_method": "put"
                    })
                    this.$inertia.post(route("color.update", data.id), colorForm, {
                        forceFormData: true,
                        onFinish: () => {
                            this.$inertia.get(route("backend_setting.index",))
                        },
                    });
                },
                () => { }
            );
        }

        function handleEnableDisable() {
            if (form.is_watermask == 0) {
                form.is_watermask = 1;
            }
            else {
                form.is_watermask = 0;
            }
        }


        function replaceImageClicked(id, isLogo, imageName, uploadType = null) {

            let removeLabel = trans('core__be_remove_icon_label');
            let replaceLabel = trans('core__be_replace_icon_label');
            let confirmLabel = trans('core__be_are_u_sure_remove_icon');
            let uploadLabel = trans('core__be_upload_icon');
            if (isLogo) {
                removeLabel = trans('core__be_remove_logo_label');
                replaceLabel = trans('core__be_replace_logo_label');
                confirmLabel = trans('core__be_are_u_sure_remove_logo');
                uploadLabel = trans('core__be_upload_logo');
            }
            ps_action_modal.value.openModal(trans('conf_modal_label'),
                replaceLabel,
                removeLabel,
                'image',
                'trash',
                '24',
                '24',
                () => {
                    ps_image_icon_modal.value.openModal(
                        uploadLabel,
                        'cloudUpload',
                        (imageFile) => {

                            let imageForm = useForm({
                                image: imageFile,
                                uploadType: uploadType,
                                "_method": "put"
                            })

                            this.$inertia.post(route("image.replace", id), imageForm);
                        });
                },
                () => {
                    ps_danger_dialog.value.openModal(
                        trans('core__be_remove_label'),
                        confirmLabel,
                        trans('core__be_btn_confirm'),
                        trans('core__be_btn_cancel'),
                        () => {
                            this.$inertia.delete(route("image.destroy", id), {
                                onBefore: () => {
                                    loading.value = true;
                                },
                                onSuccess: () => {
                                    loading.value = false;
                                    success.value = true;
                                    setTimeout(() => {
                                        success.value = false;
                                    }, 2000);
                                },
                                onError: () => {
                                    loading.value = false;
                                },
                            });
                        },
                        () => { }
                    );
                },
                !props.validation.includes(imageName)
            );
        }

        function langRefreshSuccessDialog() {
            ps_success_dialog.value.openModal(trans('core__be_awesome_title'), trans('core__be_lang_refresh_success_desc'), trans('core__be_btn_back'),
                () => {

                }, true);
        }

        function handleLangRefresh(id) {

            this.$inertia.get(route('backend_setting.languageRefresh'), { key: "refresh", languageId: id }, {
                onBefore: () => {
                    ps_loading_circle_dialog.value.openModal(trans('core__be_updating_title'), trans('core__be_lang_refreshing_desc'));
                },
                onSuccess: () => {
                    window.location.reload();
                    ps_loading_circle_dialog.value.closeModal();
                },
                onError: () => {
                    ps_loading_circle_dialog.value.closeModal();
                }
            });
        }

        function clearSelectedFile() {
            // alert('clear')
            selectedFile.value = "";
            form.firebasePrivateKeyJsonFile = "";
        }

        function handleImport(event) {
            // alert("hge");
            let selectedFiles = event.target.files;
            selectedFile.value = selectedFiles[0].name;
            form.firebasePrivateKeyJsonFile = selectedFiles[0];
            // console.log(form.file_name);
        }

        function importClicked() {
            importFile.value.click();
        }

        onMounted(() => {
            reRenderTime.value = setInterval(() => {
                reRenderTime.value = +new Date();
            }, 60 * 100);
        })

        onUnmounted(() => clearInterval(reRenderTime.value))

        return {
            importFile,
            handleImport,
            selectedFile,
            clearSelectedFile,
            importClicked,
            ps_loading_circle_dialog,
            langRefreshSuccessDialog,
            handleLangRefresh,
            validateEmailInput,
            handleCancel,
            onlyNumber,
            form,
            settingColumn,
            title,
            docu,
            changeSection,
            handleSubmit,
            loading,
            success,
            replaceImageClicked,
            ps_danger_dialog,
            ps_image_icon_modal,
            ps_action_modal,
            checkSmtpConfiguration,
            successSmtp,
            loadingSmtp,
            ps_success_dialog,
            openSuccessDialog,
            ps_error_dialog,
            openErrorDialog,
            visible,
            ps_dialog_with_input,
            smtpCheckForm,
            validateEmptyInput,
            email,
            dateFormatList,
            colorPickerClicked,
            ps_color_picker_modal,
            reRenderImage,
            reRenderTime,
            handleEnableDisable,
            dynamicLinkOptions,
            shouldDisableForFirebase
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
                    label: trans('backend_setting_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    mounted() {
        // this.langRefreshSuccessDialog();
        if (this.status.flag == "success") {
            this.openSuccessDialog();
        }
        if (this.status.flag == "danger") {
            this.openErrorDialog();
        }
        if (this.status.flag == "langSuccess") {
            this.langRefreshSuccessDialog();
        }
        // if  (this.errors.email){
        //     this.checkSmtpConfiguration();
        // }
        
    }
})
</script>
