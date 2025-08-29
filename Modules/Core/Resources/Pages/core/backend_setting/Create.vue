<template>
    <Head :title="$t('backend_setting_module')" />
    <ps-layout>
        <div class="">
            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
            <!-- breadcrumb end -->

            <!-- alert banner start -->
            <ps-banner-icon  v-if="visible" :visible="visible"
                             :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
                             :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
                             class="text-white mb-5 sm:mb-6 lg:mb-8"
                             iconColor="white">{{status.msg}}</ps-banner-icon>
            <!-- alert banner end -->

            <ps-card class="w-full h-auto">
                <div class="bg-background dark:bg-secondaryDark-black rounded-lg  mb-20 ">
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

                    <div >
                        <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4">
                            <div>
                                <div v-if="title == settingColumn[0].label">

                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'sender_name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="sender_name" type="email" v-model:value="form.sender_name" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'sender_name', form.sender_name ):''" @blur="coreField.mandatory==1?validateEmptyInput('sender_name', form.sender_name ):''"/>
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.sender_name }}</ps-label-caption>
                                    </div>

                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'sender_email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="sender_email" type="email" v-model:value="form.sender_email" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmailInput( 'sender_email', form.sender_email ):''" @blur="coreField.mandatory==1?validateEmailInput('sender_email', form.sender_email ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.sender_email }}</ps-label-caption>
                                    </div>


                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'receive_email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="receive_email" type="email" v-model:value="form.receive_email" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmailInput( 'receive_email', form.receive_email ):''" @blur="coreField.mandatory==1?validateEmailInput('receive_email', form.receive_email ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.receive_email }}</ps-label-caption>
                                    </div>

                                    <div class="px-4 py-3" v-for="( coreField, index) in
                                            coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'email_verification_enabled' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                            <ps-checkbox-value v-model:value="form.email_verification_enabled" class="font-normal" :title="$t(coreField.placeholder)" />
                                        </div>
                                        <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'email_verification_enabled' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{ $t(coreField.label_name) }}</ps-label>
                                    </div>
                                </div>

                                <div v-if="title == settingColumn[1].label">
                                    <!--start-->
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'fcm_api_key' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-textarea rows="4" ref="fcm_api_key" v-model:value="form.fcm_api_key" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'fcm_api_key', form.fcm_api_key ):''" @blur="coreField.mandatory==1?validateEmptyInput('fcm_api_key', form.fcm_api_key ):''"></ps-textarea>
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.fcm_api_key }}</ps-label-caption>
                                    </div>

                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'topics' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="topics" type="text" v-model:value="form.topics" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'topics', form.topics ):''" @blur="coreField.mandatory==1?validateEmptyInput('topics', form.topics ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.topics }}</ps-label-caption>
                                    </div>

                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'topics_fe' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="topics_fe" type="text" v-model:value="form.topics_fe" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'topics_fe', form.topics_fe ):''" @blur="coreField.mandatory==1?validateEmptyInput('topics_fe', form.topics_fe ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.topics_fe }}</ps-label-caption>
                                    </div>
                                    <!--end-->
                                </div>

                                <div v-if="title == settingColumn[2].label">
                                    <!--start-->
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_host' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="smtp_host" type="text" v-model:value="form.smtp_host" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'smtp_host', form.smtp_host ):''" @blur="coreField.mandatory==1?validateEmptyInput('smtp_host', form.smtp_host ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.smtp_host }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_port' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="smtp_port" type="text" v-model:value="form.smtp_port" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'smtp_port', form.smtp_port ):''" @blur="coreField.mandatory==1?validateEmptyInput('smtp_port', form.smtp_port ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.smtp_port }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_user' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="smtp_user" type="text" v-model:value="form.smtp_user" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'smtp_user', form.smtp_user ):''" @blur="coreField.mandatory==1?validateEmptyInput('smtp_user', form.smtp_user ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.smtp_user }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_pass' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="smtp_pass" type="password" v-model:value="form.smtp_pass" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'smtp_pass', form.smtp_pass ):''" @blur="coreField.mandatory==1?validateEmptyInput('smtp_pass', form.smtp_pass ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.smtp_pass }}</ps-label-caption>
                                    </div>
                                    <!--end-->

                                    <!-- <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'smtp_encryption' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="smtp_encryption" type="text" v-model:value="form.smtp_encryption" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'smtp_encryption', form.smtp_encryption ):''" @blur="coreField.mandatory==1?validateEmptyInput('smtp_encryption', form.smtp_encryption ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.smtp_encryption }}</ps-label-caption>
                                    </div> -->




                                </div>
                                <div v-if="title == settingColumn[3].label">
                                    <!--start-->
                                    <div class="px-4 py-3">
                                        <ps-label class="text-base mb-1" >{{ $t('core__backend_logo') }} </ps-label>
                                        <!-- <ps-input type="file" accept="image/*"   @input="form.backend_logo = $event.target.files"/> -->

                                        <ps-label  textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_256_256') }}
                                        </ps-label>

                                        <ps-image-upload   class="w-72" uploadType="icon" v-model:imageFile="form.backend_logo" />
                                         <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.backend_logo}}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3">
                                        <ps-label class="text-base mb-1" >{{ $t('core__backend_fav_icon') }}</ps-label>
                                        <!-- <ps-input type="file" accept="image/*"   @input="form.fav_icon = $event.target.files"/> -->
                                        <ps-label textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_16_16') }}
                                        </ps-label>

                                        <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.fav_icon" />
                                         <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.fav_icon}}</ps-label-caption>
                                    </div>
                                    <!-- Meta Image -->
                                    <!-- <div class="px-4 py-3">
                                        <ps-label class="text-base mb-1" >{{ $t('core__backend_meta_image') }}</ps-label>
                                        <ps-label textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_16_16') }}
                                        </ps-label>
                                        <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.backend_meta_image" />
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.backend_meta_image}}</ps-label-caption>
                                    </div> -->

                                    <div class="px-4 py-3">
                                        <ps-label class="text-base mb-1" >{{ $t('core__backend_water_mask_image') }}</ps-label>
                                        <ps-label textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_16_16') }}
                                        </ps-label>

                                        <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.backend_water_mask_image" />
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.backend_water_mask_image}}</ps-label-caption>
                                    </div>



                                </div>
                                <div v-if="title == settingColumn[4].label">
                                    <ps-label class="text-lg px-4 py-3" >{{ $t('core__org_image_size') }}</ps-label>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'landscape_width' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="landscape_width" type="text" v-model:value="form.landscape_width" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'landscape_width', form.landscape_width ):''" @blur="coreField.mandatory==1?validateEmptyInput('landscape_width', form.landscape_width ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.landscape_width }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'potrait_height' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="potrait_height" type="text" v-model:value="form.potrait_height" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'potrait_height', form.potrait_height ):''" @blur="coreField.mandatory==1?validateEmptyInput('potrait_height', form.potrait_height ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.potrait_height }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'square_height' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="square_height" type="text" v-model:value="form.square_height" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'square_height', form.square_height ):''" @blur="coreField.mandatory==1?validateEmptyInput('square_height', form.square_height ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.square_height }}</ps-label-caption>
                                    </div>

                                    <ps-label class="text-lg px-4 py-3" >{{ $t('core__thumb1x_img_size') }}</ps-label>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'landscape_thumb_width' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="landscape_thumb_width" type="text" v-model:value="form.landscape_thumb_width" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'landscape_thumb_width', form.landscape_thumb_width ):''" @blur="coreField.mandatory==1?validateEmptyInput('landscape_thumb_width', form.landscape_thumb_width ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.landscape_thumb_width }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'potrait_thumb_height' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="potrait_thumb_height" type="text" v-model:value="form.potrait_thumb_height" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'potrait_thumb_height', form.potrait_thumb_height ):''" @blur="coreField.mandatory==1?validateEmptyInput('potrait_thumb_height', form.potrait_thumb_height ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.potrait_thumb_height }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'square_thumb_height' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="square_thumb_height" type="text" v-model:value="form.square_thumb_height" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'square_thumb_height', form.square_thumb_height ):''" @blur="coreField.mandatory==1?validateEmptyInput('square_thumb_height', form.square_thumb_height ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.square_thumb_height }}</ps-label-caption>
                                    </div>

                                    <ps-label class="text-lg px-4 py-3" >{{ $t('core__thumb2x_img_size') }}</ps-label>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'landscape_thumb2x_width' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="landscape_thumb2x_width" type="text" v-model:value="form.landscape_thumb2x_width" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'landscape_thumb2x_width', form.landscape_thumb2x_width ):''" @blur="coreField.mandatory==1?validateEmptyInput('landscape_thumb2x_width', form.landscape_thumb2x_width ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.landscape_thumb2x_width }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'potrait_thumb2x_height' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="potrait_thumb2x_height" type="text" v-model:value="form.potrait_thumb2x_height" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'potrait_thumb2x_height', form.potrait_thumb2x_height ):''" @blur="coreField.mandatory==1?validateEmptyInput('potrait_thumb2x_height', form.potrait_thumb2x_height ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.potrait_thumb2x_height }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'square_thumb2x_height' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="square_thumb2x_height" type="text" v-model:value="form.square_thumb2x_height" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'square_thumb2x_height', form.square_thumb2x_height ):''" @blur="coreField.mandatory==1?validateEmptyInput('square_thumb2x_height', form.square_thumb2x_height ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.square_thumb2x_height }}</ps-label-caption>
                                    </div>

                                    <ps-label class="text-lg px-4 py-3" >{{ $t('core__thumb3x_img_size') }}</ps-label>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'landscape_thumb3x_width' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="landscape_thumb3x_width" type="text" v-model:value="form.landscape_thumb3x_width" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'landscape_thumb3x_width', form.landscape_thumb3x_width ):''" @blur="coreField.mandatory==1?validateEmptyInput('landscape_thumb3x_width', form.landscape_thumb3x_width ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.landscape_thumb3x_width }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'potrait_thumb3x_height' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="potrait_thumb3x_height" type="text" v-model:value="form.potrait_thumb3x_height" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'potrait_thumb3x_height', form.potrait_thumb3x_height ):''" @blur="coreField.mandatory==1?validateEmptyInput('potrait_thumb3x_height', form.potrait_thumb3x_height ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.potrait_thumb3x_height }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'square_thumb3x_height' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="square_thumb3x_height" type="text" v-model:value="form.square_thumb3x_height" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'square_thumb3x_height', form.square_thumb3x_height ):''" @blur="coreField.mandatory==1?validateEmptyInput('square_thumb3x_height', form.square_thumb3x_height ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.square_thumb3x_height }}</ps-label-caption>
                                    </div>
                                    <!--end-->
                                </div>
                                <div v-if="title == settingColumn[5].label">
                                    <!--start-->
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_key' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="dyn_link_key" type="text" v-model:value="form.dyn_link_key" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'dyn_link_key', form.dyn_link_key ):''" @blur="coreField.mandatory==1?validateEmptyInput('dyn_link_key', form.dyn_link_key ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.dyn_link_key }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_domain' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="dyn_link_domain" type="text" v-model:value="form.dyn_link_domain" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'dyn_link_domain', form.dyn_link_domain ):''" @blur="coreField.mandatory==1?validateEmptyInput('dyn_link_domain', form.dyn_link_domain ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.dyn_link_domain }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_url' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="dyn_link_url" type="text" v-model:value="form.dyn_link_url" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'dyn_link_url', form.dyn_link_url ):''" @blur="coreField.mandatory==1?validateEmptyInput('dyn_link_url', form.dyn_link_url ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.dyn_link_url }}</ps-label-caption>
                                    </div>
                                     <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_deep_url' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="dyn_link_deep_url" type="text" v-model:value="form.dyn_link_deep_url" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'dyn_link_deep_url', form.dyn_link_deep_url ):''" @blur="coreField.mandatory==1?validateEmptyInput('dyn_link_deep_url', form.dyn_link_deep_url ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.dyn_link_deep_url }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'dyn_link_package_name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="dyn_link_package_name" type="text" v-model:value="form.dyn_link_package_name" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'dyn_link_package_name', form.dyn_link_package_name ):''" @blur="coreField.mandatory==1?validateEmptyInput('dyn_link_package_name', form.dyn_link_package_name ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.dyn_link_package_name }}</ps-label-caption>
                                    </div>


                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'ios_boundle_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="ios_boundle_id" type="text" v-model:value="form.ios_boundle_id" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'ios_boundle_id', form.ios_boundle_id ):''" @blur="coreField.mandatory==1?validateEmptyInput('ios_boundle_id', form.ios_boundle_id ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.ios_boundle_id }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'ios_appstore_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="ios_appstore_id" type="text" v-model:value="form.ios_appstore_id" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'ios_appstore_id', form.ios_appstore_id ):''" @blur="coreField.mandatory==1?validateEmptyInput('ios_appstore_id', form.ios_appstore_id ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.ios_appstore_id }}</ps-label-caption>
                                    </div>
                                    <!--end-->
                                </div>
                                <div v-if="title == settingColumn[6].label">
                                    <!--start-->
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'slow_moving_item_limit' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="slow_moving_item_limit" type="text" v-model:value="form.slow_moving_item_limit" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'slow_moving_item_limit', form.slow_moving_item_limit ):''" @blur="coreField.mandatory==1?validateEmptyInput('slow_moving_item_limit', form.slow_moving_item_limit ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.slow_moving_item_limit }}</ps-label-caption>

                                        <ps-label class="ms-2 mt-1 text-sm" textColor="text-secondary-400">{{ $t('slow_moving_item_limit_info') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    </div>
                                    <!--end-->
                                </div>
                                <div v-if="title == settingColumn[7].label">
                                    <!--start-->
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'search_item_limit' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="search_item_limit" type="text" v-model:value="form.search_item_limit" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'search_item_limit', form.search_item_limit ):''" @blur="coreField.mandatory==1?validateEmptyInput('search_item_limit', form.search_item_limit ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.search_item_limit }}</ps-label-caption>
                                    </div>

                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'search_category_limit' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="search_category_limit" type="text" v-model:value="form.search_category_limit" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'search_category_limit', form.search_category_limit ):''" @blur="coreField.mandatory==1?validateEmptyInput('search_category_limit', form.search_category_limit ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.search_category_limit }}</ps-label-caption>
                                    </div>
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'search_user_limit' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input ref="search_user_limit" type="text" v-model:value="form.search_user_limit" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'search_user_limit', form.search_user_limit ):''" @blur="coreField.mandatory==1?validateEmptyInput('search_user_limit', form.search_user_limit ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.search_user_limit }}</ps-label-caption>
                                    </div>
                                </div>
                                <div v-if="title == settingColumn[8].label">

                                     <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'is_watermask' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <div class="flex justify-between">
                                            <ps-label class="text-base"> {{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                            <ps-toggle :selectedValue="form.is_watermask == 1 ? true : false"
                                                @click="handleEnableDisable()" toggleOnTheme="bg-primary-500 border-primary-500 "></ps-toggle>
                                        </div>
                                    </div>

                                        <div v-if="form.is_watermask == 1">
                                             <!-- <ps-label class="text-base flex flex-row">{{ $t('watermask_title') }} </ps-label>
                                            <ps-input  ref="watermask_title" type="text" v-model:value="form.watermask_title"  />
                                            <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.watermask_title }}</ps-label-caption> -->
                                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'watermask_image_size' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input  ref="watermask_image_size" type="number" v-model:value="form.watermask_image_size" :placeholder="$t(coreField.placeholder)"
                                                @keyup="coreField.mandatory==1?validateEmptyInput( 'watermask_image_size', form.watermask_image_size ):''" @blur="coreField.mandatory==1?validateEmptyInput('watermask_image_size', form.watermask_image_size ):''" />
                                                <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.watermask_image_size }}</ps-label-caption>
                                            </div>

                                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'watermask_angle' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input  ref="watermask_angle" type="number" v-model:value="form.watermask_angle" :placeholder="$t(coreField.placeholder)"
                                                @keyup="coreField.mandatory==1?validateEmptyInput( 'watermask_angle', form.watermask_angle ):''" @blur="coreField.mandatory==1?validateEmptyInput('watermask_angle', form.watermask_angle ):''" />
                                                <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.watermask_angle }}</ps-label-caption>
                                            </div>
                                            <!-- <ps-label class="text-base flex flex-row">{{ $t('watermask_image_angle') }} </ps-label>
                                            <ps-input ref="watermask_angle" type="text" v-model:value="form.watermask_angle"  />
                                            <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.watermask_angle }}</ps-label-caption> -->

                                            <!-- <ps-label class="text-base flex flex-row">{{$t('watermask_font_size') }} </ps-label>
                                            <ps-input ref="font_size" type="number" v-model:value="form.font_size"  />
                                            <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.font_size }}</ps-label-caption> -->

                                            <!-- <ps-label class="text-base flex flex-row">{{ "font color" }} </ps-label>
                                            <ps-input ref="watermask_ratio" type="text" v-model:value="form.watermask_ratio"  />
                                            <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.watermask_ratio }}</ps-label-caption> -->


                                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'position' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                               <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-dropdown class="w-full" h="h-auto" >
                                                    <template #select>
                                                        <ps-dropdown-select :placeholder="$t(coreField.placeholder)" :showCenter="true"
                                                        :selectedValue="paddingList.filter((position) => position.value == form.position)[0].label"
                                                         @change="coreField.mandatory=1?validateEmptyInput('position', form.position ):''"
                                                            @blur="coreField.mandatory=1?validateEmptyInput('position',form.position):''"
                                                        />
                                                    </template>
                                                    <template #list>
                                                        <div class="">
                                                            <div v-for="row in paddingList" :key="row.id" class="w-56">
                                                                <ps-label @click="[(form.position=row.value),coreField.mandatory=1?validateEmptyInput('position',form.position):'']" class="py-2 px-4 text-md hover:bg-primary-50 cursor-pointer" > {{ row.label }}</ps-label>
                                                            </div>
                                                        </div>
                                                    </template>
                                                </ps-dropdown>
                                            </div>

                                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'padding' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input  ref="padding" type="number" v-model:value="form.padding" :placeholder="$t(coreField.placeholder)"
                                                @keyup="coreField.mandatory==1?validateEmptyInput( 'padding', form.padding ):''" @blur="coreField.mandatory==1?validateEmptyInput('padding', form.padding ):''" />
                                                <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.padding }}</ps-label-caption>
                                            </div>

                                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'opacity' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                                <ps-input  ref="opacity" type="number" v-model:value="form.opacity" :placeholder="$t(coreField.placeholder)"
                                                @keyup="coreField.mandatory==1?validateEmptyInput( 'opacity', form.opacity ):''" @blur="coreField.mandatory==1?validateEmptyInput('opacity', form.opacity ):''" />
                                                <ps-label-caption  textColor="text-red-500 " class="mt-2 block">{{ errors.opacity }}</ps-label-caption>
                                            </div>



                                            <!-- <ps-label-header-4>{{$t('watermask_font_color')}}</ps-label-header-4>
                                            <div class="grid grid-cols-2">

                                                    <div class="flex flex-row p-1">
                                                        <div class="w-6 h-6 m-1 rounded shadow-sm cursor-pointer">
                                                            <div @click="colorPickerClicked(commonColor)" class="w-6 h-6 rounded shadow-sm cursor-pointer" :style="{ 'background-color': form.commonColor.value }"></div>
                                                        </div>
                                                        <ps-label class="leading-7">{{ form.commonColor.title}}</ps-label>

                                                    </div>

                                            </div> -->

                                        <div class="px-4 py-3">
                                            <ps-label class="text-base mb-1" >{{ $t('water_mask_background') }}</ps-label>
                                            <ps-label textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_16_16') }}
                                            </ps-label>

                                            <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.water_mask_background" />
                                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.water_mask_background}}</ps-label-caption>
                                        </div>

                                        <ps-label class="py-3 text-xl ">{{$t('water_mask_preview')}}</ps-label>
                                            <div class="container mx-auto grid-cols-4 lg:space-y-0  lg:gap-2 lg:grid  lg:grid-cols-4" v-if="reRenderImage">




                                                <div class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                    <img  v-lazy=" { src: $page.props.uploadUrl + '/' + $page.props.sysImageUrl+'/default_photo.png', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                                    alt="water_mask_background" />
                                                    <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview') }} </ps-label>
                                                </div>


                                                <div class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                    <img  v-lazy=" { src: $page.props.thumb1xUrl + '/' + $page.props.sysImageUrl+'/default_photo.png', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                                    alt="water_mask_background" />
                                                    <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview') }} </ps-label>
                                                </div>

                                                <div class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                    <img  v-lazy=" { src: $page.props.thumb2xUrl + '/' + $page.props.sysImageUrl+'/default_photo.png', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                                    alt="water_mask_background" />
                                                    <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview') }} </ps-label>
                                                </div>

                                                <div class="w-full dark:bg-secondary-900 bg-secondary-000 rounded hover:shadow-2xl grid place-items-center ">
                                                    <img  v-lazy=" { src: $page.props.thumb3xUrl + '/' + $page.props.sysImageUrl+'/default_photo.png', loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                                    alt="water_mask_background" />
                                                    <ps-label class="text-base grid place-items-center">{{ $t('org_img_preview') }} </ps-label>
                                                </div>
                                            </div>


                                        </div>
                                </div>
                                <div v-if="title == settingColumn[10].label">
                                    <div class="px-4 py-3">
                                        <div class="flex justify-between">
                                            <ps-radio-value v-model:value="form.is_google_map" :title="$t('is_google_map')" class="me-4" />
                                            <ps-radio-value v-model:value="form.is_google_map" :title="$t('is_open_street_map')" class="me-4" />
                                        </div>
                                    </div>
                                </div>
                                <div v-if="title == settingColumn[11].label">
                                    <!--start-->
                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'map_key' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <div class="flex items-center">
                                            <ps-label class="text-base">{{ $t(coreField.label_name) }} {{ coreField.label_name }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                            <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                <template #content>
                                                    <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                </template>
                                                <template #tooltips>
                                                    For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-77245/5ed0ce87caa866c" class="underline">Refer to this doc</a>
                                                </template>
                                            </ps-tooltip>
                                        </div>
                                        <ps-textarea rows="4" ref="map_key" v-model:value="form.map_key" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'map_key', form.map_key ):''" @blur="coreField.mandatory==1?validateEmptyInput('map_key', form.map_key ):''"></ps-textarea>
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.map_key }}</ps-label-caption>
                                    </div>


                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'app_token' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <div class="flex items-center">
                                            <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                            <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                <template #content>
                                                    <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                </template>
                                                <template #tooltips>
                                                    For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-78985/fb754eedbf26c1d" class="underline">Refer to this doc</a>
                                                </template>
                                            </ps-tooltip>
                                        </div>
                                        <ps-input ref="app_token" type="text" v-model:value="form.app_token" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'app_token', form.app_token ):''" @blur="coreField.mandatory==1?validateEmptyInput('app_token', form.app_token ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.app_token }}</ps-label-caption>
                                    </div>

                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'date_format' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <div class="flex items-center">
                                            <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                            <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                <template #content>
                                                    <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                </template>
                                                <template #tooltips>
                                                    For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-78425/f52485b24b02ab2" class="underline">Refer to this doc</a>
                                                </template>
                                            </ps-tooltip>
                                        </div>
                                        <ps-dropdown align="left" class="lg:mt-2 mt-1 w-full">
                                            <template #select>
                                                <ps-dropdown-select ref="date_format" :placeholder="$t(coreField.placeholder)" :showCenter="true"
                                                    :selectedValue="form.date_format == '' ? '': dateFormatList.filter((date) => date == form.date_format)[0]"
                                                    @change="coreField.mandatory=1?validateEmptyInput('date_format', form.date_format ):''"
                                                    @blur="coreField.mandatory=1?validateEmptyInput('date_format',form.date_format):''" />
                                            </template>
                                            <template #list>
                                                <div class="rounded-md shadow-xs w-full bg-background dark:bg-backgroundDark">
                                                    <div class="pt-2 z-30">
                                                        <div>
                                                            <div v-for="date in dateFormatList" :key="date"
                                                                class="w-96 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                                                @click="[(form.date_format = date), coreField.mandatory=1?validateEmptyInput('date_format',form.date_format):'']">
                                                                <ps-label class="ms-2" :class="date == form.date_format ? ' font-bold' : '' ">{{ date }}</ps-label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </ps-dropdown>
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.date_format }}</ps-label-caption>
                                    </div>

                                    <div class="px-4 py-3" v-for="( coreField, index) in
                                            coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_social_info_override' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                            <div class="flex items-center">
                                                <ps-checkbox-value v-model:value="form.user_social_info_override" class="font-normal" :title="$t(coreField.placeholder)" />
                                                <ps-tooltip tooltiptext="absolute z-50 bottom-full mb-2 -ms-2.5">
                                                    <template #content>
                                                        <ps-icon name="info" w="20" h="20" class="-ms-1 transition-all duration-300 text-primary-500"/>
                                                    </template>
                                                    <template #tooltips>
                                                        For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-80685/6220fdbb827d06a" class="underline">Refer to this doc</a>
                                                    </template>
                                                </ps-tooltip>
                                            </div>
                                        </div>
                                        <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_social_info_override' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{ $t(coreField.label_name) }}</ps-label>
                                    </div>

                                    <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'backend_version_no' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <div class="flex items-center">
                                            <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                            <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                                <template #content>
                                                    <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                                </template>
                                                <template #tooltips>
                                                    For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-81325/01ae928e06a86ac" class="underline">Refer to this doc</a>
                                                </template>
                                            </ps-tooltip>
                                        </div>
                                        <ps-input ref="backend_version_no" type="text" v-model:value="form.backend_version_no" :placeholder="$t(coreField.placeholder)"
                                            @keyup="coreField.mandatory==1?validateEmptyInput( 'backend_version_no', form.backend_version_no ):''" @blur="coreField.mandatory==1?validateEmptyInput('backend_version_no', form.backend_version_no ):''" />
                                        <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.backend_version_no }}</ps-label-caption>
                                    </div>
                                    <!--end-->
                                </div>

                                <div v-if="title == settingColumn[9].label">

                                    <ps-card class="w-full h-auto">
                                        <div class="rounded-xl">
                                            <!-- card body start -->

                                            <div class="mt-6">

                                                <div class="">
                                                    <div class="border border-1 rounded p-4">
                                                        <div class="h-auto">
                                                            <!-- <div class="mb-2">
                                                                <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('demo_data_deletion_desc_title')}}</ps-label-header-6>
                                                            </div> -->
                                                            <div>
                                                                <ps-label class="dark:text-white text-secondary-800 font-normal">{{$t('lang_refresh_desc')}}</ps-label>
                                                            </div>
                                                            <div v-if="can.deleteDataReset" class="flex flex-row justify-start mt-6">
                                                                <ps-button @click="handleLangRefresh()" rounded="rounded" class="flex flex-wrap items-center">
                                                                    <ps-icon name="refresh" class="me-2 font-semibold" />
                                                                    <ps-label textColor="text-white dark:text-secondary-800">{{ $t('core__be_lang_refresh') }}</ps-label>
                                                                </ps-button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <!-- card body end -->
                                        </div>
                                    </ps-card>

                                </div>


                                <div class="flex flex-row  px-4 py-3 justify-end mb-2.5 mt-4">
                                    <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                    <ps-button :disabled="!can.updateBackendSetting" @click="handleSubmit()" class="transition-all duration-300 min-w-3xs me-4" padding="px-8 py-0" rounded="rounded" hover="" focus="" >
                                        <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                        <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                        <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                        <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                                    </ps-button>
                                </div>

                            </div>
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
import { defineComponent,ref,defineAsyncComponent, reactive ,onMounted ,onUnmounted} from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head,Link, useForm } from "@inertiajs/vue3";
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

import { trans } from 'laravel-vue-i18n';
import PsTooltip from '@/Components/Core/Tooltips/PsTooltip.vue';

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
        PsLabelHeader6
    },
    layout: PsLayout,
    props: ['errors', 'commonColor','status', 'coreFieldFilterSettings','can','validation','paddingList'],
    setup(props) {
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
        let visible = ref(false)
        let smtpCheckForm = useForm({
            email: ''
        });
        const reRenderTime = ref(+new Date());
        let form = useForm(
            {
                sender_name: "",
                sender_email: "",
                receive_email: "",
                fcm_api_key: "",
                map_key: "",
                app_token: "",
                topics: "",
                topics_fe: "",
                smtp_host: "",
                smtp_port: "",
                smtp_user: "",
                smtp_pass: "",
                smtp_encryption: "",
                email_verification_enabled: false,
                user_social_info_override: false,
                landscape_width: "",
                potrait_height: "",
                square_height: "",
                landscape_thumb_width: "",
                potrait_thumb_height: "",
                square_thumb_height: "",
                landscape_thumb2x_width:  "",
                potrait_thumb2x_height: "",
                square_thumb2x_height: "",
                landscape_thumb3x_width: "",
                potrait_thumb3x_height: "",
                square_thumb3x_height: "",
                dyn_link_key: "",
                dyn_link_url: "",
                dyn_link_package_name: "",
                dyn_link_domain: "",
                dyn_link_deep_url: "",
                ios_boundle_id: "",
                ios_appstore_id: "",
                backend_version_no: "",
                slow_moving_item_limit: "",
                search_item_limit: "",
                search_user_limit: "",
                search_category_limit: "",
                date_format: "",
                backend_logo: "",
                fav_icon: "",
                backend_login_image: "",
                // backend_meta_image: "",
                backend_water_mask_image: "",
                water_mask_background: "",
                watermask_image_size: "",
                font_size: "",
                position: "",
                opacity: "",
                commonColor: props.commonColor,
                watermask_title: "",
                is_watermask: "",
                watermask_angle: "",
                padding: "",
                is_google_map: "is_open_street_map",
                is_open_street_map:false,

            }
        )

        const settingColumn = [
            {
                label: 'core__mail_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-78405/558fcb9fa90dc8c'
            },
            {
                label: 'core__notification_configuraion',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-79285/016265d456cc1d6'
            },
            {
                label: 'core__smtp_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-79925/32fc70aa1e15255'
            },
            {
                label: 'core__image_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-80665/e32299b4734944b'
            },
            {
                label: 'core__image_size_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-81305/c0a1be0b1b3bdb6'
            },
            {
                label: 'core__deep_linking_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-81665/bb69eadab54fc7a'
            },
            {
                label: 'core__slow_moving_item_limit_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-82245/3f0c9b52d40ccde'
            },
            {
                label: 'core__search_limit_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-82565/6b2501073593ff7'
            },
            {
                label: 'core__watermark_setting',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-83665/17332e1221948083'
            },
            {
                label: 'core__lang_refresh_setting',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-83845/a164dc7b0c3046b'
            },
            {
                label: 'core__default_map_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-83305/c0d33a57bf43ab4'
            },
            {
                label: 'core__other_configuration',
                docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-84065/1b5aa8867d19a63'
            },

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

        const title = ref(settingColumn[0].label);
        const docu = ref(settingColumn[0].docu);

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
        function changeSection(v){
            title.value = v.label;
            docu.value = v.docu;
        }
        function handleCancel() {
            this.$inertia.get(route('backend_setting.index'));
        }
        function handleSubmit(id) {
            // console.log('here');
            this.$inertia.post(route('backend_setting.store'), form, {
                forceFormData: true,
            onBefore: () => {loading.value = true},
            onSuccess: () => {
                loading.value = false;
                success.value = true;
                setTimeout(()=>{
                    success.value = false;
                    reRenderImage.value= false;
                     setTimeout(() => {
                        reRenderImage.value = true;
                    }, 200);

                },1000)


            },
            onError: () => {
                loading.value = false;;
            },
            });
        }

        function openSuccessDialog(){
            ps_success_dialog.value.openModal(trans('core__be_smtp_configuration_check'),trans('smtp_configuration_is_success'),trans('btn_back'),
                ()=>{
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

        function openErrorDialog(){
            ps_error_dialog.value.openModal(trans('core__be_smtp_configuration_check'), trans('smtp_configuration_is_fail'),trans('core__be_btn_ok'), ()=> {});
        }

        function checkSmtpConfiguration() {
            // console.log('there');
            // ps_dialog_with_input.value.openModal(
            //     trans('checking_email'),
            //     trans('core__comfirm_to_delete_row'),
            //     trans('core__be_btn_confirm'),
            //     trans('core__be_btn_cancel'),
            //     () => {
                    // this.$inertia.get(route('backend_setting.checkSmtpConfig'), smtpCheckForm,{
                    //     onSuccess: () => {
                    //         visible.value = true;
                    //         setTimeout(() => {
                    //             visible.value = false;
                    //         }, 3000);
                    //     },
                    //     onError: () => {
                    //         visible.value = true;
                    //         setTimeout(() => {
                    //             visible.value = false;
                    //         }, 3000);
                    //     },
                    // })

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
                    this.$inertia.post(route("color.update", data.id), colorForm,{
                        forceFormData: true,
                        onFinish: () => {
                            this.$inertia.get(route("backend_setting.index",))
                        },
                        });
                },
                () => {}
            );
        }

        function handleEnableDisable(){
            if(form.is_watermask == 0)
            {
                form.is_watermask = 1;
            }
            else{
                form.is_watermask = 0;
            }
        }


        function replaceImageClicked(id, isLogo,imageName , uploadType= null) {

            let removeLabel = trans('core__be_remove_icon_label');
            let replaceLabel = trans('core__be_replace_icon_label');
            let confirmLabel = trans('core__be_are_u_sure_remove_icon');
            let uploadLabel = trans('core__be_upload_icon');
            if(isLogo){
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
                                uploadType:uploadType,
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

        function langRefreshSuccessDialog()
        {
            ps_success_dialog.value.openModal(trans('core__be_awesome_title'),trans('core__be_lang_refresh_success_desc'),trans('core__be_btn_back'),
            ()=>{

            },true);
        }

        function handleLangRefresh()
        {

            this.$inertia.get(route('backend_setting.languageRefresh'), {key: "refresh"}, {
                onBefore: () => {
                    ps_loading_circle_dialog.value.openModal(trans('core__be_updating_title'),trans('core__be_lang_refreshing_desc'));
                },
                onSuccess: () => {
                    ps_loading_circle_dialog.value.closeModal();
                },
                onError: () => {
                    ps_loading_circle_dialog.value.closeModal();
                }
            });
        }

        onMounted(() => {
            reRenderTime.value = setInterval(() => {
                reRenderTime.value = +new Date();
            }, 60 * 100);
        })

        onUnmounted(() => clearInterval(reRenderTime.value))

        return {
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
            handleEnableDisable
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
        if (this.status.flag == "success"){
            this.openSuccessDialog();
        }
        if (this.status.flag == "danger"){
            this.openErrorDialog();
        }
        if(this.status.flag == "langSuccess"){
            this.langRefreshSuccessDialog();
        }
        // if  (this.errors.email){
        //     this.checkSmtpConfiguration();
        // }
    }
})
</script>
