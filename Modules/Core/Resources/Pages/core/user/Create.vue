<template>
    <Head :user_about_me="$t('core__be_create_user')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_user_info')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit()">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <!-- name-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="name" type="text" v-model:value="form.name" required :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'name', form.name ):''" @blur="coreField.mandatory==1?validateEmptyInput('name', form.name ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                            </div>

                            <!-- email-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="email" type="email" v-model:value="form.email" :placeholder="$t(coreField.placeholder)"
                                   required @keyup="coreField.mandatory==1?validateEmptyInput( 'email', form.email ):''" @blur="coreField.mandatory==1?validateEmptyInput('email', form.email ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.email }}</ps-label-caption>
                            </div>

                            <!-- username-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'username' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="username" type="text" required v-model:value="form.username" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'username', form.username ):''" @blur="coreField.mandatory==1?validateEmptyInput('username', form.username ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.username }}</ps-label-caption>
                            </div>

                            <!-- for role dropdown -->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'role_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-dropdown align="left" class="lg:mt-2 mt-1 w-full">
                                    <template #select>
                                        <ps-dropdown-select ref="role" :placeholder="$t(coreField.placeholder)" :showCenter="true" required
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

                            <!-- password -->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'password' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="password" type="password" v-model:value="form.password" :placeholder="$t(coreField.placeholder)"
                                required  @keyup="coreField.mandatory==1?validateEmptyInput( 'password', form.password ):''" @blur="coreField.mandatory==1?validateEmptyInput('password', form.password ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.password }}</ps-label-caption>
                            </div>

                            <!-- confirm password-->
                            <div>
                                <ps-label class="text-base">{{ $t('core__be_user_conf_password') }} <span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="conf_password" type="password" v-model:value="form.conf_password" :placeholder="$t('core__be_user_conf_password')"
                                required @click="validateEmptyInput( 'conf_password', form.conf_password )" @blur="validateEmptyInput('conf_password', form.conf_password )" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.conf_password }}</ps-label-caption>
                            </div>

                            <!-- user_phone-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                 <div class="flex flex-row " >
                                    <ps-dropdown @on-click="loadMore" horizontalAlign="left" class="me-2 h-10"  >
                                        
                                        <template #select>

                                            <ps-dropdown-select  :showCenter="true" :selectedValue="countryCode" />

                                        </template>
                                        <template #list>
                                            <div class="rounded-md shadow-xs w-56 ">
                                                <div class="pt-2 z-30 ">
                                                    <div v-if="is_loading">
                                                        <ps-label class='p-2 flex'>{{
                                                            $t("item_entry__loading")
                                                        }} </ps-label>
                                                    </div>
                                                    <div v-else>
                                                        <div v-for="selectData in phoneList"
                                                            :key="selectData.id"
                                                            class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                            @click="phoneFilterClicked(selectData)">

                                                            <ps-label class="ms-2"
                                                                :class="selectData.country_code == countryCode ? ' font-bold' : ''">
                                                                {{ selectData.country_code }} </ps-label>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template #loadmore>

                                            <div class="mb-2 w-56" @click="loadMore(true)" v-if="phone_loadmore_visible">

                                                <ps-label class="flex mt-4 ms-4 mb-2 underline font-bold cursor-pointer"
                                                    > 
                                                        {{is_loading ? $t('core__be_loading') :$t('core__be_load_more') }} </ps-label>
                                            </div>

                                        </template>
                                        <template #filter>
                                            <div class="w-56">
                                                <ps-input-with-right-icon class="rounded-xl flex-1 "
                                                    v-model:value="phoneSearch"
                                                    v-bind:placeholder="$t('phone_code_by_country')">
                                                    <template #icon>
                                                        <ps-icon textColor="text-secondary-400 dark:text-secondaryDark-grey"
                                                            name="search" class='cursor-pointer'
                                                            />
                                                    </template>
                                                </ps-input-with-right-icon>
                                            </div>
                                        </template>

                                    </ps-dropdown>
                                
                                    <ps-input ref="user_phone" type="tel" required v-model:value="tempPhone" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'user_phone', tempPhone ):''" @blur="coreField.mandatory==1?validateEmptyInput('user_phone', tempPhone ):''" />
                                </div>
                                <!-- <ps-input ref="user_phone" type="text" v-model:value="form.user_phone" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'user_phone', form.user_phone ):''" @blur="coreField.mandatory==1?validateEmptyInput('user_phone', form.user_phone ):''" /> -->
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.user_phone }}</ps-label-caption>
                            </div>

                            <!-- for user_about_me-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_about_me' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="user_about_me" type="text" v-model:value="form.user_about_me" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'user_about_me', form.user_about_me ):''" @blur="coreField.mandatory==1?validateEmptyInput('user_about_me', form.user_about_me ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.user_about_me }}</ps-label-caption>
                            </div>

                            <!-- for user_address-->
                            <div v-for="( coreField, index ) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'user_address' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea rows="4" v-model:value="form.user_address" :placeholder="$t(coreField.user_address)" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.user_address }}</ps-label-caption>
                            </div>

                            <!-- user cover photo -->
                            <div>
                                <ps-label class="text-base">{{$t('core__be_user_cover_photo')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-label-title-3 v-if="!user.user_cover_photo">{{ $t('core__be_recommended_size_200_200') }}</ps-label-title-3>
                                <!-- <div v-if="user.user_cover_photo" class="flex items-end pt-4">
                                    <img :src="$page.props.uploadUrl + '/' + user.user_cover_photo" class="w-48 h-48" :alt="$t(core__be_user_cover_photo)" />
                                    <ps-button type="button" @click="replaceImageClicked(user.id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                        <ps-icon name="pencil-btn"  w="21" h="21" />
                                    </ps-button>
                                    <ps-image-icon-modal ref="ps_image_icon_modal" />
                                    <ps-action-modal ref="ps_action_modal" />
                                    <ps-danger-dialog ref="ps_danger_dialog" />
                                </div> -->
                                <ps-image-upload uploadType="image" class="w-48" v-model:imageFile="form.user_cover_photo" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.user_cover_photo }}</ps-label-caption>
                            </div>

                            <ps-label class="text-base">{{ $t('core__be_show_phone_email') }}</ps-label>
                            <!-- is_show_phone-->
                            <div v-for="( coreField, index) in
                                coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'is_show_phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-checkbox-value v-model:value="form.is_show_phone" class="font-normal" :title="$t(coreField.placeholder)" />
                            </div>
                            <!-- is_show_email-->
                            <div v-for="( coreField, index) in
                                coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'is_show_email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-checkbox-value v-model:value="form.is_show_email" class="font-normal" :title="$t(coreField.placeholder)" />
                            </div>

                            <!-- custom field start -->
                            <div v-for="(custom_field_header) in customizeHeaders" :key="custom_field_header.id">
                                <!-- dropdown-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00001' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory == 1">*</span></ps-label>
                                    <ps-dropdown align="left" class="lg:mt-2 mt-1 w-full">
                                        <template #select>
                                            <ps-dropdown-select ref="detail" :placeholder="$t(custom_field_header.placeholder)" :showCenter="true"
                                                :selectedValue="this.customizeDetails.filter((detail) =>detail.id ===
                                                this.form.user_relation[custom_field_header.core_keys_id]).length > 0?
                                                this.customizeDetails.filter((detail) => detail.id === this.form.user_relation[custom_field_header.core_keys_id])[0].name: ''"
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
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- text-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00002' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label>{{$t(custom_field_header.name)}}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <ps-input type="text" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.user_relation[custom_field_header.core_keys_id]"
                                        @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- radio-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00003' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0 ">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <div class="flex flex-row">
                                        <div class="me-2" v-for="detail in customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id )" :key="detail.id">
                                            <ps-radio-value color="text-purple-600 border-purple-300" v-model:value="form.user_relation[custom_field_header.core_keys_id]" :title="detail.name" />
                                            <ps-label :for="detail.id">{{detail.attribute}}</ps-label>
                                        </div>
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- checkbox-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00004' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <div class="flex flex-row">
                                        <div class="me-2 flex">
                                            <input type="checkbox" class="rounded border" value="0" v-model="form.user_relation[custom_field_header.core_keys_id]" @change="handleCustomFieldError(custom_field_header)" />
                                            <ps-label class="ms-2">{{$t(custom_field_header.name)}}</ps-label>
                                        </div>
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- datetime -->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00005' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <div>
                                        <date-picker v-model:value="form.user_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" :enableTimePicker="true" :inline="false" :autoApply="false" />
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- textarea -->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00006' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <ps-textarea rows="4" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.user_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- number-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00007' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <ps-input type="number" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.user_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- multi Select-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00008' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <div class="flex flex-row">
                                        <CheckBox :oldData="form.user_relation[custom_field_header.core_keys_id]" @toParent="handleMultiSelect" :customizeDetails="customizeDetails" :customizeHeader="custom_field_header" />
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- Image-->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00009' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory ===1">*</span></ps-label>
                                    <ps-image-upload uploadType="image" v-model:imageFile="form.user_relation[custom_field_header.core_keys_id]" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- time Only -->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00010' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <input type="time" class="w-full rounded" v-model="form.user_relation[custom_field_header.core_keys_id]" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>

                                <!-- date Only -->
                                <div class="mb-4" v-if="custom_field_header.ui_type_id === 'uit00011' && custom_field_header.enable === 1 &&custom_field_header.is_delete === 0">
                                    <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                    <div>
                                        <date-picker v-model:value="form.user_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" :inline="false" :autoApply="false" />
                                    </div>
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                </div>
                            </div>
                            <!-- /.custom field end -->

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t("core__be_btn_cancel") }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="">
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500" loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t("core__be_btn_saved") }}</span>
                                    <span v-if="!loading && !success" class="">{{ $t("core__be_btn_save") }}</span>
                                </ps-button>
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

import { defineComponent, ref, watch, defineAsyncComponent } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
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
const GoogleMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/GoogleMapPinPicker.vue'));
import { trans } from 'laravel-vue-i18n';
import { getPhoneCountryCodes } from '@/Api/psApiService.js'

export default defineComponent({
    name: "Create",
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
        GoogleMapPinPicker,
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsVideoUpload,
        PsLabelTitle3,
        PsInputWithRightIcon
    },
    layout: PsLayout,
    props: [
        'errors',
        'coreFieldFilterSettings',
        'roles',
        'user',
        'customizeHeaders',
        'customizeDetails',
        'authUser'
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

        const tempPhone = ref("");
        const phone = ref("");

        const phoneSearch = ref('');
        const countryCode = ref('+1');
        const phone_loadmore_visible = ref(false);
        const is_loading = ref(false);
        let phoneList = ref([]);

        phone.value = countryCode.value+"-"+tempPhone.value;

        // Client Side Validation
        let form = useForm(
                {
                    name: '',
                    email: '',
                    user_phone : phone.value,
                    password : '',
                    role_id: '',
                    conf_password : '',
                    user_address: '',
                    user_about_me: '',
                    user_cover_photo: "",
                    is_show_email: false,
                    is_show_phone: false,
                    user_relation: {},
                    username:''
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
        function handleSubmit() {
            if(tempPhone.value == ''){
                phone.value = '';
            }else{
                phone.value = countryCode.value+"-"+tempPhone.value;
            }
            

            form.user_phone = phone.value;


            this.$inertia.post(route('user.store'), form,  {
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
                                "_method": "post"
                            })

                            this.$inertia.post(route("user.image.replace", id), imageForm);
                        });
                },
                () => {
                    ps_danger_dialog.value.openModal(
                        trans('core__be_remove_label'),
                        trans('core__be_are_u_sure_remove_photo'),
                        trans('core__be_btn_confirm'),
                        trans('core__be_btn_cancel'),
                        () => {
                            this.$inertia.delete(route("user.image.delete", id), {
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

        watch(phoneSearch,_.debounce((current,previous)=>{
            let offset= 0;
            phoneList.value = [];
            loadPhone(offset);

        },500))

        function loadMore(loadmore = null){
            
            let offset = phoneList.value ? phoneList.value.length : 0 ;
            if(offset == 0 || loadmore == true){

                loadPhone(offset);
            }
        }

        function loadPhone(offset){
            
             is_loading.value = true
            getPhoneCountryCodes(offset,phoneSearch.value,props.authUser.id).then(response => {

                if(response.data.length > 0){
                    response.data.forEach(element =>{
                        phoneList.value.push(element);
                    });
                    phone_loadmore_visible.value = true;
                }else{
                    phone_loadmore_visible.value = false;
                }
                    is_loading.value=false;
            });
        }

        function phoneFilterClicked(value) {
            countryCode.value = value.country_code
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
            is_show_email,
             phoneSearch,
            countryCode,
            phone_loadmore_visible,
            is_loading,
            phoneList,
            tempPhone,
            phoneFilterClicked,
            loadPhone,
            loadMore,
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
                    label: trans('user_module'),
                    url: route('user.index'),
                },
                {
                    label: trans('core__be_create_user'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('user.index'));
        },
    },
})
</script>
