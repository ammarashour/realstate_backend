<template>
    <Head :title="$t('core__be_banned_user_detail')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6   ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_user_info')}}</ps-label-header-6>
                </div>
               
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(user.id)">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <!-- name-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input :disabled="true" ref="name" type="text" v-model:value="form.name" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'name', form.name ):''" @blur="coreField.mandatory==1?validateEmptyInput('name', form.name ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                            </div>

                            <!-- email-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input :disabled="true" ref="email" type="text" v-model:value="form.email" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'email', form.email ):''" @blur="coreField.mandatory==1?validateEmptyInput('email', form.email ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.email }}</ps-label-caption>
                            </div>

                            <!-- for role dropdown -->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'role_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                    <template #select>
                                        <ps-dropdown-select :disabled="true" ref="role" :placeholder="$t(coreField.placeholder)" :showCenter="true"
                                            :selectedValue="form.role_id == '' ? '': roles.filter((role) => role.id == form.role_id)[0].name"
                                            @change="coreField.mandatory=1?validateEmptyInput('role_id', form.role_id ):''"
                                            @blur="coreField.mandatory=1?validateEmptyInput('role_id',form.role_id):''" />
                                    </template>
                                    <template #list>
                                        <div class="rounded-md shadow-xs w-full bg-background dark:bg-backgroundDark">
                                            <div class="pt-2 z-30">
                                                <div v-if="roles.length == null">
                                                    <ps-label class="p-2 flex" @click="route('role.index')">{{$t('core__be_add_new_role')}}</ps-label>
                                                </div>
                                                <div v-else>
                                                    <div v-for="role in roles" :key="role.id"
                                                        class="w-96 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                                        @click="[(form.role_id = role.id), coreField.mandatory=1?validateEmptyInput('role_id',form.role_id):'']">
                                                        <ps-label class="ms-2" :class="role.id == form.role_id ? ' font-bold' : '' ">{{ role.name }}</ps-label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{errors.role_id}}</ps-label-caption>
                            </div>
                            <!-- end role -->

                            <!-- user_phone-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input :disabled="true" ref="user_phone" type="text" v-model:value="form.user_phone" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'user_phone', form.user_phone ):''" @blur="coreField.mandatory==1?validateEmptyInput('user_phone', form.user_phone ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.user_phone }}</ps-label-caption>
                            </div>

                            <!-- for user_about_me-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_about_me' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input :disabled="true" ref="user_about_me" type="text" v-model:value="form.user_about_me" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'user_about_me', form.user_about_me ):''" @blur="coreField.mandatory==1?validateEmptyInput('user_about_me', form.user_about_me ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.user_about_me }}</ps-label-caption>
                            </div>

                            <!-- for user_address-->
                            <div v-for="( coreField, index ) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_address' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea :disabled="true" rows="4" v-model:value="form.user_address" :placeholder="$t(coreField.user_address)" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.user_address }}</ps-label-caption>
                            </div>

                            <!-- user cover photo -->
                            <div>
                                <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{$t('core__be_user_cover_photo')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-label-title-3 v-if="!user.user_cover_photo">{{ $t('core__be_recommended_size_200_200') }}</ps-label-title-3>
                                <div v-if="user.user_cover_photo" class="flex items-end pt-4">
                                    <img
                                    v-lazy=" { src: $page.props.uploadUrl + '/' + user.user_cover_photo, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                     class="w-48 h-48" :alt="$t(core__be_user_cover_photo)" />
                                    <ps-button :disabled="true" type="button" @click="replaceImageClicked(user.user_cover_photo.id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                        <ps-icon name="pencil-btn"  w="21" h="21" />
                                    </ps-button>
                                    <ps-image-icon-modal ref="ps_image_icon_modal" />
                                    <ps-action-modal ref="ps_action_modal" />
                                    <ps-danger-dialog ref="ps_danger_dialog" />
                                </div>
                                <ps-image-upload :disabled="true" class="w-48" v-else uploadType="image" v-model:imageFile="form.user_cover_photo" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.user_cover_photo }}</ps-label-caption>
                            </div>

                            <ps-label class="text-base"  textColor="text-secondary-800 dark:text-secondary-700">{{ $t('core__be_show_phone_email') }}</ps-label>
                            <!-- is_show_phone-->
                            <div v-for="( coreField, index) in
                                coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'is_show_phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-checkbox-value :disabled="true" v-model:value="form.is_show_phone" class="font-normal" :title="$t(coreField.placeholder)" />
                            </div>
                            <!-- is_show_email-->
                            <div v-for="( coreField, index) in
                                coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'is_show_email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-checkbox-value :disabled="true" v-model:value="form.is_show_email" class="font-normal" :title="$t(coreField.placeholder)" />
                            </div>

                            <!-- custom field start -->
                            <div v-for="(custom_field_header) in customizeHeaders" :key="custom_field_header.id">
                                <!-- dropdown-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00001' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory == 1">*</span></ps-label>
                                    <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                        <template #select>
                                            <ps-dropdown-select :disabled="true" ref="detail" :placeholder="$t(custom_field_header.placeholder)" :showCenter="true"
                                                :selectedValue="this.customizeDetails.filter((detail) =>detail.id ==
                                                this.form.user_relation[custom_field_header.core_keys_id]).length > 0?
                                                this.customizeDetails.filter((detail) => detail.id == this.form.user_relation[custom_field_header.core_keys_id])[0].name: ''"
                                                @change="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                        </template>
                                        <template #list>
                                            <div class="rounded-md shadow-xs w-full bg-background dark:bg-backgroundDark">
                                                <div class="pt-2 z-30">
                                                    <div v-if="customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id).length === 0">
                                                        <ps-label class="p-2 flex" @click="route('currency.index')">{{$t('core__be_create_new')}} {{ $t(custom_field_header.name)}}</ps-label>
                                                    </div>
                                                    <div v-else>
                                                        <div v-for="detail in customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id)" :key="detail.id"
                                                            class="w-96 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                                            @click="[(form.user_relation[custom_field_header.core_keys_id] = detail.id), validateEmptyInput('currency_id',form.user_relation[custom_field_header.core_keys_id])]">
                                                            <ps-label class="ms-2" :class="detail.id == form.user_relation[custom_field_header.core_keys_id]? ' font-bold': ''">{{detail.name}}</ps-label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </ps-dropdown>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- text-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00002' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label textColor="text-secondary-800 dark:text-secondary-700">{{$t(custom_field_header.name)}}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <ps-input :disabled="true" type="text" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.user_relation[custom_field_header.core_keys_id]"
                                        @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- radio-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00003' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0 ">
                                    <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <div class="flex flex-row">
                                        <div class="me-2" v-for="detail in customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id )" :key="detail.id">
                                            <ps-radio-value :disabled="true" color="text-purple-600 border-purple-300" v-model:value="form.user_relation[custom_field_header.core_keys_id]" :title="detail.name" />
                                            <ps-label :for="detail.id">{{detail.attribute}}</ps-label>
                                        </div>
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- checkbox-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00004' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <div class="flex flex-row">
                                        <div class="me-2 flex">
                                            <input :disabled="true" type="checkbox" class="rounded border" value="0" v-model="form.user_relation[custom_field_header.core_keys_id]" @change="handleCustomFieldError(custom_field_header)" />
                                            <ps-label class="ms-2" textColor="text-secondary-800 dark:text-secondary-700">{{$t(custom_field_header.name)}}</ps-label>
                                        </div>
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- datetime -->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00005' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <div>
                                        <date-picker :disabled="true" v-model:value="form.user_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" :enableTimePicker="true" :inline="false" :autoApply="false" />
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- textarea -->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00006' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <ps-textarea :disabled="true" rows="4" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.user_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- number-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00007' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <ps-input :disabled="true" type="number" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.user_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- multi Select-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00008' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <div class="flex flex-row">
                                        <CheckBox :disabled="true" :oldData="form.user_relation[custom_field_header.core_keys_id]" @toParent="handleMultiSelect" :customizeDetails="customizeDetails" :customizeHeader="custom_field_header" />
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- Image-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00009' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory ===1">*</span></ps-label>
                                    <div v-if="user.image" class="flex items-end pt-4">
                                        <img
                                        v-lazy=" { src: $page.props.uploadUrl + '/' + user.image.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                        class="w-96 h-48" :alt="$t(core__be_user_photo)" />
                                        <ps-button type="button" @click="replaceImageClicked(user.image.id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                            <ps-icon name="pencil-btn"  w="21" h="21" />
                                        </ps-button>
                                        <ps-image-icon-modal ref="ps_image_icon_modal" />
                                        <ps-action-modal ref="ps_action_modal" />
                                        <ps-danger-dialog ref="ps_danger_dialog" />
                                    </div>
                                    <ps-image-upload v-else uploadType="image" v-model:imageFile="form.user_relation[custom_field_header.core_keys_id]" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- time Only -->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00010' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <input type="time" class="w-full rounded" v-model="form.user_relation[custom_field_header.core_keys_id]" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- date Only -->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00011' && custom_field_header.enable === 1 &&custom_field_header.is_delete === 0">
                                    <ps-label class="text-base" textColor="text-secondary-800 dark:text-secondary-700">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <div>
                                        <date-picker :disabled="true" v-model:value="form.user_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" :inline="false" :autoApply="false" />
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ user_relation_errors && user_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>
                            </div>
                            <!-- /.custom field end -->
                            <div class="mb-2.5 flex flex-row justify-end">
                                <ps-button type="button" @click="handleCancel()">{{ $t('core__be_btn_back') }}</ps-button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- card body end -->
            </div>
        </ps-card>
    </ps-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import CheckBox from "../components/CheckBox.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsVideoUpload from "@/Components/Core/Upload/PsVideoUpload.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        CheckBox,
        DatePicker,
        PsInput,
        PsRadioValue,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader6,
        PsDropdown,
        PsDropdownSelect,
        PsCard,
        PsBreadcrumb2,
        PsLabelCaption,
        PsImageUpload,
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsVideoUpload,
        PsLabelTitle3,
    },
    layout: PsLayout,
    props: [
        'errors',
        'coreFieldFilterSettings',
        'roles',
        'user',
        'customizeHeaders',
        'customizeDetails'
    ],
    setup(props) {

        const name = ref();
        const email = ref();
        const user_address = ref();
        const user_about_me = ref();
        const role_id = ref();
        const is_show_phone = ref();
        const is_show_email = ref();
        const password = ref();
        const conf_password = ref();

        // Client Side Validation
        let form = useForm(
                {
                    id: props.user.id,
                    name: props.user.name,
                    email: props.user.email,
                    user_phone : props.user.user_phone,
                    // role_id: props.user.role_id,
                    role_id:  props.roles.find(element=> element.id == props.user.role_id) ? props.user.role_id : '',
                    password: props.user.password,
                    conf_password: props.user.conf_password,
                    user_address: props.user.user_address,
                    user_about_me: props.user.user_about_me,
                    user_cover_photo: "",
                    is_show_email: props.user.is_show_email == 1 ? true : false,
                    is_show_phone: props.user.is_show_phone == 1 ? true : false,
                    user_relation: {},
                    "_method": "put"
                }
            )

        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const { isEmpty, isNum, isEmail } = useValidators();

        const loading = ref(false);
        const success = ref(false);

        const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
        }

        const validateEmailInput = (fieldName, fieldValue, errorMessage1 = '', errorMessage2 = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage1) : isEmail(fieldName, fieldValue, errorMessage2);
        }

        const validateNumberInput = (fieldName, fieldValue, errorMessage1 = '', errorMessage2 = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage1) : isNum(fieldName, fieldValue, errorMessage2);
        }

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }
        function handleSubmit(id) {
            this.$inertia.post(route('user.update', id), form,  {
                forceFormData: true,
            onBefore: () => {loading.value = true},
            onSuccess: () => {
                loading.value = false;
                success.value = true;
                setTimeout(()=>{
                    success.value = false;
                },2000)
            },
            onError: () => {
                loading.value = false;;
            },
            })
        }

        function replaceImageClicked(id) {
            ps_action_modal.value.openModal(trans('conf_modal_label'),
                trans('core__be_replace_img_label'),
                trans('core__be_remove_img_label'),
                'image',
                'trash',
                '24',
                '24',
                () => {
                    ps_image_icon_modal.value.openModal(
                        trans('core__be_upload_photo'),
                        'cloudUpload',
                        (imageFile) => {
                            let imageForm = useForm({
                                image: imageFile,
                                "_method": "put"
                            })

                            this.$inertia.post(route("image.replace", id), imageForm);
                        });
                },
                () => {
                    ps_danger_dialog.value.openModal(
                        trans('core__be_remove_label'),
                        trans('core__be_are_u_sure_remove_photo'),
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
                }
            );
        }

        function handleCustomFieldError(custom_field_header){
            custom_field_header.mandatory === 1 ? validateEmptyInput(custom_field_header.name, form.user_relation[custom_field_header.core_keys_id]) : ''
        }
        return {
            validateEmptyInput,
            validateEmailInput,
            validateNumberInput,
            handleCustomFieldError,
            onlyNumber,
            form,
            handleSubmit,
            loading,
            success,
            replaceImageClicked,
            ps_danger_dialog,
            ps_image_icon_modal,
            ps_action_modal,
            name,
            email,
            role_id,
            user_address,
            user_about_me,
            password,
            conf_password,
            is_show_phone,
            is_show_email
        }
    },

    created() {
        this.customizeHeaders.map((customizeHeader, index) => {
            this.user.user_relation.map((value) => {
                if  (customizeHeader.core_keys_id === value.core_keys_id){
                    let data = value.value;
                    this.form.user_relation[customizeHeader.core_keys_id] = data === '1' ? true : (data === '0' ? false : data);
                }
            });
        })
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('banned_user_module'),
                    url: route('banned_user.index'),
                },
                {
                    label: trans('core__be_banned_user_detail'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('banned_user.index'));
        },
    },
})
</script>
