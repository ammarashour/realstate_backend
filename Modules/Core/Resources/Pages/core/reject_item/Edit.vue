<template>
    <Head :title="$t('core__be_reject_item_detail')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header5>{{$t('core__be_reject_item_detail')}}</ps-label-header5>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form>
                        <div class="grid xxl:grid-cols-2 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1">
                            <div class="grid gap-6">
                                <div>
                                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_item_info_lbl') }}</ps-label-header-6>
                                </div>
                                
                                <!-- title-->
                                <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'title' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input :disabled="true" type="text" v-model:value="form.title" :placeholder="$t(coreField.placeholder)" />
                                </div>

                                <div v-if="selected_price_type == PsConst.NORMAL_PRICE">
                                    <!-- original price-->
                                    <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'original_price' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input :disabled="true" type="text" v-model:value="form.original_price" :placeholder="$t(coreField.placeholder)" />
                                    </div>

                                    <!-- sale price-->
                                    <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'price' && coreField.enable === 1 && coreField.is_delete === 0 )" :key="index">
                                        <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-input :disabled="true" type="text" v-model:value="form.price" :placeholder="$t(coreField.placeholder)" />
                                    </div>
                                
                                    <!-- for currency dropdown -->
                                    <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'currency_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                            <template #select>
                                                <ps-dropdown-select :disabled="true" :placeholder="$t(coreField.placeholder)" :showCenter="true" :selectedValue="form.currency_id" />
                                            </template>
                                        </ps-dropdown>
                                    </div>
                                    <!-- end currency -->
                                </div>
                                
                                <!-- price range -->
                                <div v-if="selected_price_type == PsConst.PRICE_RANGE">
                                    <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'original_price' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-label  class="text-base">{{ "price_range" }}
                                            <!-- <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span> -->
                                        </ps-label>
                                        <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                            <template #select>
                                                <ps-dropdown-select :disabled="true" :placeholder="$t(coreField.placeholder)" :showCenter="true" :selectedValue="form.original_price" />
                                            </template>
                                        </ps-dropdown>
                                    </div>
                                </div>

                                <!-- for category dropdown -->
                                <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'category_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                        <template #select>
                                            <ps-dropdown-select :disabled="true" :placeholder="$t(coreField.placeholder)" :showCenter="true" :selectedValue="form.category_id" />
                                        </template>
                                    </ps-dropdown>
                                </div>
                                <!-- end category -->

                                <!-- for subcategory dropdown -->
                                <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'subcategory_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                        <template #select>
                                            <ps-dropdown-select :disabled="true" :placeholder="$t(coreField.placeholder)" :showCenter="true" :selectedValue="form.subcategory_id" />
                                        </template>
                                    </ps-dropdown>
                                </div>
                                <!-- end subcategory -->

                                <!-- for location city dropdown -->
                                <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'location_city_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                        <template #select>
                                            <ps-dropdown-select :disabled="true" :placeholder="$t(coreField.placeholder)" :showCenter="true" :selectedValue="form.location_city_id" />
                                        </template>
                                    </ps-dropdown>
                                </div>
                                <!-- end location city -->

                                <!-- for location township dropdown -->
                                <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'location_township_id' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                        <template #select>
                                            <ps-dropdown-select :disabled="true" :placeholder="$t(coreField.placeholder)" :showCenter="true" :selectedValue="form.location_township_id" />
                                        </template>
                                    </ps-dropdown>
                                </div>
                                <!-- end location township -->

                                <!-- for description-->
                                <div class="md:me-6 sm:me-0" v-for="(coreField, index ) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-textarea :disabled="true"  rows="4" v-model:value="form.description" :placeholder="$t(coreField.description)" />
                                </div>

                                <!-- percent -->
                                <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'percent' && coreField.enable === 1 && coreField.is_delete === 0 )" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input :disabled="true" type="text" v-model:value="form.percent" :placeholder="$t(coreField.placeholder)" />
                                </div>

                                <!-- custom field start -->
                                <div v-for="(custom_field_header) in customizeHeaders.filter((custom) => custom.core_keys_id != 'ps-itm00009')" :key="custom_field_header.id">
                                    <!-- dropdown-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00001' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory == 1">*</span></ps-label>
                                        <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                            <template #select>
                                                <ps-dropdown-select :disabled="true" :placeholder="$t(custom_field_header.placeholder)" :showCenter="true" 
                                                    :selectedValue="this.customizeDetails.filter((detail) =>detail.id == 
                                                    this.form.product_relation[custom_field_header.core_keys_id]).length > 0? 
                                                    this.customizeDetails.filter((detail) => detail.id == this.form.product_relation[custom_field_header.core_keys_id])[0].name: ''" />
                                            </template>
                                        </ps-dropdown>
                                    </div>

                                    <!-- text-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00002' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{$t(custom_field_header.name)}}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <ps-input :disabled="true" type="text" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" />
                                    </div>

                                    <!-- radio-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00003' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0 ">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <div class="flex flex-row">
                                            <div class="me-2" v-for="detail in customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id )" :key="detail.id">
                                                <ps-radio-value :disabled="true" color="text-purple-600 border-purple-300" v-model:value="form.product_relation[custom_field_header.core_keys_id]" :title="detail.name" />
                                                <ps-label textColor="text-secondary-300 dark:text-secondary-700" :for="detail.id">{{detail.attribute}}</ps-label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- checkbox-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00004' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{$t(custom_field_header.name)}}</ps-label>
                                        <div class="flex flex-row ">
                                            <div class="me-2 flex">
                                                <input :disabled="true" type="checkbox" class="rounded border mt-1.5" value="0" v-model="form.product_relation[custom_field_header.core_keys_id]" @change="handleCustomFieldError(custom_field_header)" />
                                                <ps-label textColor="text-secondary-300 dark:text-secondary-700" class="text-base ms-2">{{$t(custom_field_header.placeholder)}}</ps-label>
                                            </div>
                                        </div>
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                    </div>

                                    <!-- datetime -->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00005' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <div>
                                            <date-picker :disabled="true" v-model:value="form.product_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" :enableTimePicker="true" :inline="false" :autoApply="false" />
                                        </div>
                                    </div>

                                    <!-- textarea -->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00006' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <ps-textarea :disabled="true" rows="4" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    </div>

                                    <!-- number-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00007' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <ps-input :disabled="true" type="number" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    </div>

                                    <!-- multi Select-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00008' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <div class="flex flex-row">
                                            <CheckBox :disabled="true"  :oldData="form.product_relation[custom_field_header.id]" @toParent="handleMultiSelect" :customizeDetails="customizeDetails" :customizeHeader="custom_field_header" />
                                        </div>
                                    </div>

                                    <!-- Image-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00009' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory ===1">*</span></ps-label>
                                        <div v-if="item.image" class="flex items-end pt-4">
                                            <img v-lazy=" { src: $page.props.uploadUrl + '/' + item.image.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" class="w-96 h-48" :alt="$t(core__be_item_photo)" />
                                            <ps-button :disabled="true" type="button" @click="replaceImageClicked(item.image.id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                                <ps-icon name="pencil-btn"  w="21" h="21" />
                                            </ps-button>
                                            <ps-image-icon-modal :disabled="true" ref="ps_image_icon_modal" />
                                            <ps-action-modal :disabled="true" ref="ps_action_modal" />
                                            <ps-danger-dialog :disabled="true" ref="ps_danger_dialog" />
                                        </div>
                                        <ps-image-upload :disabled="true" v-else uploadType="image" v-model:imageFile="form.product_relation[custom_field_header.core_keys_id]" />
                                    </div>

                                    <!-- time Only -->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00010' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <input :disabled="true" type="time" class="w-full rounded" v-model="form.product_relation[custom_field_header.core_keys_id]" />
                                        
                                    </div>

                                    <!-- date Only -->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00011' && custom_field_header.enable === 1 &&custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <div>
                                            <date-picker :disabled="true" v-model:value="form.product_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" :inline="false" :autoApply="false" />
                                        </div>
                                    </div>
                                </div>
                                <!-- /.custom field end -->
                                
                                <!-- status-->
                                <div class="md:me-6 sm:me-0" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'status' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}</ps-label>
                                    <ps-checkbox-value :disabled="true" v-model:value="form.status" class="font-normal" :title="$t(coreField.placeholder)" />
                                </div>

                                <!-- item photo -->
                                <div>
                                    <ps-label  class="text-base">{{$t('core__be_item_photo')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-label-title-3  v-if="!item.cover[0]">{{ $t('core__be_recommended_size_400_200') }}</ps-label-title-3>
                                    <div v-if="item.cover[0]" class="flex items-end pt-4">
                                        <img v-lazy=" { src: $page.props.uploadUrl + '/' + item.cover[0].img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" class="w-96 h-48" :alt="$t(core__be_item_photo)" />
                                        <ps-button :disabled="true" type="button" @click="replaceImageClicked(item.cover[0].id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                            <ps-icon name="pencil-btn"  w="21" h="21" />
                                        </ps-button>
                                        <ps-image-icon-modal :disabled="true" ref="ps_image_icon_modal" />
                                        <ps-action-modal :disabled="true" ref="ps_action_modal" />
                                        <ps-danger-dialog :disabled="true" ref="ps_danger_dialog" />
                                    </div>
                                    <ps-image-upload :disabled="true" v-else uploadType="image" v-model:imageFile="form.cover" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.cover }}</ps-label-caption>
                                </div>

                                <!-- item video -->
                                <div>
                                    <ps-label  class="text-base">{{$t('core__be_item_video')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-label-title-3  v-if="!item.video[0]">{{ $t('core__be_recommended_size_400_200') }}</ps-label-title-3>
                                    <div v-if="item.video[0]" class="flex items-end pt-4">
                                        <video class="w-96 h-48" controls>
                                            <source :src="$page.props.uploadUrl + '/' + item.video[0].img_path" type="video/mp4">
                                            <source :src="$page.props.uploadUrl + '/' + item.video[0].img_path" type="video/ogg">
                                            {{$t('core__be_item_video')}}
                                        </video>
                                        <ps-button :disabled="true" @click="replaceVideoClicked(item.video[0].id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                            <ps-icon name="pencil-btn"  w="21" h="21" />
                                        </ps-button>
                                        <ps-image-icon-modal :disabled="true" ref="ps_image_icon_modal" />
                                        <ps-action-modal :disabled="true" ref="ps_action_modal" />
                                        <ps-danger-dialog :disabled="true" ref="ps_danger_dialog" />
                                    </div>
                                    <ps-video-upload :disabled="true" v-else v-model:videoFile="form.video" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.video }}</ps-label-caption>
                                </div>

                                <!-- item icon -->
                                <div>
                                    <ps-label  class="text-base">{{$t('core__be_item_icon')}}</ps-label>
                                    <ps-label-title-3  v-if="!item.icon[0]">{{ $t('core__be_recommended_size_200_200') }}</ps-label-title-3>
                                    <div v-if="item.icon[0]" class="flex items-end pt-4">
                                        <img v-lazy=" { src: $page.props.uploadUrl + '/' + item.icon[0].img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" class="w-72 h-48" :alt="$t(core__be_item_icon)" />
                                        <ps-button :disabled="true" type="button" @click="replaceImageClicked(item.icon[0].id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                            <ps-icon name="pencil-btn"  w="21" h="21" />
                                        </ps-button>
                                        <ps-image-icon-modal :disabled="true" ref="ps_image_icon_modal" />
                                        <ps-action-modal :disabled="true" ref="ps_action_modal" />
                                        <ps-danger-dialog :disabled="true" ref="ps_danger_dialog" />
                                    </div>
                                    <ps-image-upload :disabled="true" v-else class="w-72" uploadType="icon" v-model:imageFile="form.video_icon" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.video_icon }}</ps-label-caption>
                                </div>
                            </div>
                            <div class="w-full ms-4">
                                <div class="mb-6">
                                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_item_location')}}</ps-label-header-6>
                                </div>

                                <!-- contact number -->
                                <div class="mb-6" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input :disabled="true" type="text" v-model:value="form.phone" :placeholder="$t(coreField.placeholder)"/>
                                </div>

                                <!-- custom field start -->
                                <div v-for="(custom_field_header) in customizeHeaders.filter((custom) => custom.core_keys_id == 'ps-itm00009')" :key="custom_field_header.id">
                                    <!-- dropdown-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00001' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory == 1">*</span></ps-label>
                                        <ps-dropdown :disabled="true" align="left" class="lg:mt-2 mt-1 w-full">
                                            <template #select>
                                                <ps-dropdown-select :disabled="true" :placeholder="$t(custom_field_header.placeholder)" :showCenter="true" 
                                                    :selectedValue="this.customizeDetails.filter((detail) =>detail.id == 
                                                    this.form.product_relation[custom_field_header.core_keys_id]).length > 0? 
                                                    this.customizeDetails.filter((detail) => detail.id == this.form.product_relation[custom_field_header.core_keys_id])[0].name: ''" />
                                            </template>
                                        </ps-dropdown>
                                    </div>

                                    <!-- text-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00002' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{$t(custom_field_header.name)}}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <ps-input :disabled="true" type="text" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" />
                                    </div>

                                    <!-- radio-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00003' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0 ">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <div class="flex flex-row">
                                            <div class="me-2" v-for="detail in customizeDetails.filter((customizeDetail) => customizeDetail.core_keys_id === custom_field_header.core_keys_id )" :key="detail.id">
                                                <ps-radio-value :disabled="true" color="text-purple-600 border-purple-300" v-model:value="form.product_relation[custom_field_header.core_keys_id]" :title="detail.name" />
                                                <ps-label  :for="detail.id">{{detail.attribute}}</ps-label>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- checkbox-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00004' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base mb-1">{{$t(custom_field_header.name)}}</ps-label>
                                        <div class="flex flex-row">
                                            <div class="me-2 flex">
                                                <input :disabled="true" type="checkbox" class="rounded border" value="0" v-model="form.product_relation[custom_field_header.core_keys_id]" @change="handleCustomFieldError(custom_field_header)" />
                                                <ps-label  class="text-base ms-2">{{$t(custom_field_header.placeholder)}}</ps-label>
                                            </div>
                                        </div>
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ product_relation_errors && product_relation_errors[custom_field_header.core_keys_id] }}</ps-label-caption>
                                    </div>

                                    <!-- datetime -->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00005' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <div>
                                            <date-picker :disabled="true" v-model:value="form.product_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" :enableTimePicker="true" :inline="false" :autoApply="false" />
                                        </div>
                                    </div>

                                    <!-- textarea -->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00006' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <ps-textarea :disabled="true" rows="4" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    </div>

                                    <!-- number-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00007' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <ps-input :disabled="true" type="number" class="w-full rounded" :placeholder="$t(custom_field_header.placeholder)" v-model:value="form.product_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" />
                                    </div>

                                    <!-- multi Select-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00008' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <div class="flex flex-row">
                                            <CheckBox :disabled="true"  :oldData="form.product_relation[custom_field_header.id]" @toParent="handleMultiSelect" :customizeDetails="customizeDetails" :customizeHeader="custom_field_header" />
                                        </div>
                                    </div>

                                    <!-- Image-->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00009' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory ===1">*</span></ps-label>
                                        <div v-if="item.image" class="flex items-end pt-4">
                                            <img v-lazy=" { src: $page.props.uploadUrl + '/' + item.image.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }" class="w-96 h-48" :alt="$t(core__be_item_photo)" />
                                            <ps-button :disabled="true" type="button" @click="replaceImageClicked(item.image.id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                                <ps-icon name="pencil-btn"  w="21" h="21" />
                                            </ps-button>
                                            <ps-image-icon-modal :disabled="true" ref="ps_image_icon_modal" />
                                            <ps-action-modal :disabled="true" ref="ps_action_modal" />
                                            <ps-danger-dialog :disabled="true" ref="ps_danger_dialog" />
                                        </div>
                                        <ps-image-upload :disabled="true" v-else uploadType="image" v-model:imageFile="form.product_relation[custom_field_header.core_keys_id]" />
                                    </div>

                                    <!-- time Only -->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00010' && custom_field_header.enable === 1 && custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <input :disabled="true" type="time" class="w-full rounded" v-model="form.product_relation[custom_field_header.core_keys_id]" />
                                        
                                    </div>

                                    <!-- date Only -->
                                    <div class="md:me-6 sm:me-0" v-if="custom_field_header.ui_type_id === 'uit00011' && custom_field_header.enable === 1 &&custom_field_header.is_delete === 0">
                                        <ps-label  class="text-base">{{ $t(custom_field_header.name) }}<span class="text-red-800 font-medium ms-1" v-show="custom_field_header.mandatory === 1">*</span></ps-label>
                                        <div>
                                            <date-picker :disabled="true" v-model:value="form.product_relation[custom_field_header.core_keys_id]" @keyup="handleCustomFieldError(custom_field_header)" @blur="handleCustomFieldError(custom_field_header)" :inline="false" :autoApply="false" />
                                        </div>
                                    </div>
                                </div>
                                <!-- /.custom field end -->

                                <div class="mb-6">
                                    <google-map-pin-picker :mapKey="$page.props.mapKey"  :lat="parseFloat(form.lat)" :lng="parseFloat(form.lng)"
                                        widthHeight="width: 458px; height: 500px" :onChange="updateCoordinates" />
                                </div>

                                <div class="mb-6" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'lat' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input :disabled="true" type="text" v-model:value="form.lat" :placeholder="$t(coreField.placeholder)"/>
                                </div>

                                <div class="mb-6" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) =>coreField.original_field_name === 'lng' &&coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label  class="text-base">{{ $t(coreField.label_name) }}<span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input :disabled="true" type="text" v-model:value="form.lng" :placeholder="$t(coreField.placeholder)"/>
                                </div>
                            </div>
                        </div>
                        <div class="mb-2.5 flex flex-row justify-end">
                            <ps-button type="button" @click="handleCancel()">{{ $t('core__be_btn_back') }}</ps-button>
                        </div>
                    </form>
                </div>
                <!-- card body end -->
            </div>
        </ps-card>

    </ps-layout>
</template>

<script>

import { defineComponent, ref, defineAsyncComponent } from 'vue'
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
import PsLabelHeader5 from "@/Components/Core/Label/PsLabelHeader5.vue";
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
import PsConst from '@templateCore/object/constant/ps_constants';

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
        PsLabelHeader5,
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
        PsImageUpload,
        GoogleMapPinPicker,
        PsVideoUpload,
        PsLabelTitle3,
    },
    layout: PsLayout,
    props: [
        'errors',
        'coreFieldFilterSettings',
        'item',
        'customizeHeaders', 
        'customizeDetails',
        'selected_price_type',
    ],
    setup(props) {
        function checkPriceFormat(data) {
            // alert(data);
            if (props.selected_price_type == PsConst.PRICE_RANGE) {

                const floatValue = parseFloat(data);
                const intValue = parseInt(floatValue);
                if (intValue > 5) {
                    return '$'.repeat(5)
                }
                if (intValue < 1) {
                    return '$'.repeat(1)
                }
                return '$'.repeat(intValue);
            }
            if (props.selected_price_type == PsConst.NORMAL_PRICE) {
                return data;
            }
            if (props.selected_price_type == PsConst.NO_PRICE) {
                return data;
            }
        }

        function checkPercent(data) {

            if (props.selected_price_type == PsConst.PRICE_RANGE || props.selected_price_type == PsConst.NO_PRICE ) {
                return 0;
            }
            if (props.selected_price_type == PsConst.NORMAL_PRICE){

                return data;
            }
        }

        let form = useForm(
            {
                id: props.item.id,
                title: props.item.title,
                original_price: checkPriceFormat(props.item.original_price),
                price: props.item.price,
                percent: checkPercent(props.item.percent),
                location_city_id: props.item.city?props.item.city.name:'',
                location_township_id: props.item.township?props.item.township.name:'',
                currency_id: props.item.currency?props.item.currency.currency_short_form:'',
                category_id: props.item.category?props.item.category.name:'',
                subcategory_id: props.item.subcategory?props.item.subcategory.name:'',
                lat: props.item.lat,
                lng: props.item.lng,
                ordering: props.item.ordering,
                description: props.item.description,
                search_tag: props.item.search_tag,
                status: props.item.status == 1 ? true : false,
                cover: '',
                video: '',
                video_icon: '',
                is_available: true,
                is_discount: false,
                product_relation: {},
                "_method": "put"
            }
        )

        return { form, PsConst }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('reject_item_module'),
                    url: route('reject_item.index'),
                },
                {
                    label: trans('core__be_reject_item_detail'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('reject_item.index'));
        },
    },
})
</script>
