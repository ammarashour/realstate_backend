<template>
    <Head :title="$t('frontend_setting_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="bg-background dark:bg-secondaryDark-black rounded-lg ">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100"> {{ $t(title) }}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <form @submit.prevent="handleSubmit()">
                    <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4">
                        <div>
                            <div class="" v-if="title == settingColumn[0].label">
                                 <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'google_playstore_url' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="google_playstore_url"  v-model:value="form.google_playstore_url" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'google_playstore_url', form.google_playstore_url ):''" @blur="coreField.mandatory==1?validateEmptyInput('google_playstore_url', form.google_playstore_url ):''"/>
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.google_playstore_url }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'app_store_url' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="app_store_url"  v-model:value="form.app_store_url" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'app_store_url', form.app_store_url ):''" @blur="coreField.mandatory==1?validateEmptyInput('app_store_url', form.app_store_url ):''"/>
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.app_store_url }}</ps-label-caption>
                                </div>

                                 <div class="px-4 pt-3" v-for="( coreField, index) in
                                        coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'google_setting' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-checkbox-value v-model:value="form.google_setting" class="font-normal" :title="$t(coreField.placeholder)" />
                                    </div>
                                    <div class="px-4 pb-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'google_setting' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{ $t(coreField.label_name) }}</ps-label>
                                </div>

                                <div class="px-4 pt-3" v-for="( coreField, index) in
                                        coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'app_store_setting' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <ps-checkbox-value v-model:value="form.app_store_setting" class="font-normal" :title="$t(coreField.placeholder)" />
                                    </div>
                                    <div class="px-4 pb-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'app_store_setting' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{ $t(coreField.label_name) }}</ps-label>
                                </div>

                            </div>
                            <!-- Start image setting -->
                            <div class="" v-if="title == settingColumn[1].label">
                                <div class="px-4 py-3">
                                    <ps-label class="text-base mb-1" >{{ $t('core__frontend_logo') }} </ps-label>

                                    <ps-label  textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_256_256') }}
                                    </ps-label>

                                    <ps-image-upload   class="w-72" uploadType="icon" v-model:imageFile="form.frontend_logo" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.frontend_logo}}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3">
                                    <ps-label class="text-base mb-1" >{{ $t('core__frontend_icon') }} </ps-label>

                                    <ps-label  textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_256_256') }}
                                    </ps-label>

                                    <ps-image-upload  class="w-72" uploadType="icon" v-model:imageFile="form.frontend_icon" />
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.frontend_icon}}</ps-label-caption>
                                </div>
                                <div class="px-4 py-3">
                                    <ps-label class="text-base mb-1" >{{ $t('core__frontend_banner') }} </ps-label>

                                    <ps-label  textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_2048_1000') }}
                                    </ps-label>

                                    <ps-image-upload  class="w-72" uploadType="icon" v-model:imageFile="form.frontend_banner" />
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.frontend_banner}}</ps-label-caption>
                                </div>
                            </div>
                            <!-- End image setting -->
                            <!-- Start firebase setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[2].label">
                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'firebase_web_push_key_pair' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <div class="flex items-center">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-104264/bbfc1813c4065fb" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>

                                    <ps-textarea rows="4" ref="firebase_web_push_key_pair" v-model:value="form.firebase_web_push_key_pair" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'firebase_web_push_key_pair', form.firebase_web_push_key_pair ):''" @blur="coreField.mandatory==1?validateEmptyInput('firebase_web_push_key_pair', form.firebase_web_push_key_pair ):''"></ps-textarea>
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.firebase_web_push_key_pair }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'firebase_config' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <div class="flex items-center">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-104284/357a0267a31ba26" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-textarea rows="4" ref="firebase_config" v-model:value="form.firebase_config" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'firebase_config', form.firebase_config ):''" @blur="coreField.mandatory==1?validateEmptyInput('firebase_config', form.firebase_config ):''"></ps-textarea>
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.firebase_config }}</ps-label-caption>

                                    <ps-label-caption v-if="!isJson" textColor="text-red-500 " class="mt-2 block">{{ $t('core_be__need_json_string') }}</ps-label-caption>

                                </div>

                            </div>
                            <!-- End firebase setting -->
                            <!-- Start Fe Lang Refresh -->
                            <div class="grid grid-rows gap-6 ms-4" v-if="title == settingColumn[3].label">
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
                                                            <ps-label class="dark:text-white text-secondary-800 font-normal">{{$t('fe_lang_refresh_desc')}}</ps-label>
                                                        </div>
                                                        <div class="flex flex-row justify-start mt-6">
                                                            <ps-button type="button" @click="handleFeLangRefresh()" rounded="rounded" class="flex flex-wrap items-center">
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
                            <!-- End Fe Lang Refresh -->

                            <!-- Start frontend meta configuration -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[4].label">
                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'frontend_meta_title' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <div class="flex items-center">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    </div>
                                    <ps-input ref="frontend_meta_title" v-model:value="form.frontend_meta_title" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'frontend_meta_title', form.frontend_meta_title ):''" @blur="coreField.mandatory==1?validateEmptyInput('frontend_meta_title', form.frontend_meta_title ):''"/>
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.frontend_meta_title }}</ps-label-caption>
                                </div>


                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'frontend_meta_description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <div class="flex items-center">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    </div>
                                    <ps-textarea rows="4" ref="frontend_meta_description" v-model:value="form.frontend_meta_description" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'frontend_meta_description', form.frontend_meta_description ):''" @blur="coreField.mandatory==1?validateEmptyInput('frontend_meta_description', form.frontend_meta_description ):''"></ps-textarea>
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.frontend_meta_description }}</ps-label-caption>


                                </div>

                                <div class="px-4 py-3">
                                    <ps-label class="text-base mb-1" >{{ $t('core__backend_meta_image') }} </ps-label>

                                    <ps-label v-if="!frontend_setting.frontend_meta_image[0]" textColor="text-secondary-400 text-xs"> {{ $t('core__be_recommended_size_256_256') }}
                                    </ps-label>
                                    <div v-if="frontend_setting.frontend_meta_image[0]" class="flex items-end pt-4">
                                        <img
                                        v-lazy=" { src: $page.props.uploadUrl + '/' + frontend_setting.frontend_meta_img[0].img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                            width="200" height="200"
                                            class="w-48 h-48" alt="frontend_setting" />
                                        <ps-button type="button" @click="replaceImageClicked(frontend_setting.frontend_meta_image[0].id, true,'frontend_logo')" rounded="rounded-full"
                                            shadow="drop-shadow-2xl" class="-ms-10 mb-2"
                                            colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                            <ps-icon name="pencil-btn" w="21" h="21" />
                                        </ps-button>
                                        <ps-image-icon-modal ref="ps_image_icon_modal" />
                                        <ps-action-modal ref="ps_action_modal" />
                                        <ps-danger-dialog ref="ps_danger_dialog" />
                                    </div>
                                    <ps-image-upload v-else  class="w-72" uploadType="icon" v-model:imageFile="form.frontend_meta_image" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.frontend_meta_image}}</ps-label-caption>
                                </div>
                            </div>
                            <!-- End frontend meta configuration -->

                            <!-- Start other setting -->
                            <div class="grid grid-rows gap-6 ms-4 me-18" v-if="title == settingColumn[4].label">
                                 <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'copyright' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <div class="flex items-center">
                                        <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                        <ps-tooltip tooltiptext="absolute z-50 bottom-full ms-0.5">
                                            <template #content>
                                                <ps-icon name="info" w="20" h="20" class="mt-2 ms-2.5 transition-all duration-300 text-primary-500" />
                                            </template>
                                            <template #tooltips>
                                                For more details: <a target="_blank" href="https://doc.clickup.com/24312566/p/h/q5yqp-104304/017317d54feeea7" class="underline">Refer to this doc</a>
                                            </template>
                                        </ps-tooltip>
                                    </div>
                                    <ps-input ref="copyright" v-model:value="form.copyright" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'copyright', form.copyright ):''" @blur="coreField.mandatory==1?validateEmptyInput('copyright', form.copyright ):''"/>
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.copyright }}</ps-label-caption>
                                </div>

                                <div class="px-4 pt-3" v-for="( coreField, index) in
                                        coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'enable_notification' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                        <div class="flex items-center">
                                            <ps-checkbox-value v-model:value="form.enable_notification" class="font-normal" :title="$t(coreField.placeholder)" />
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
                                    <div class="px-4 pb-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'enable_notification' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="ms-2 text-xs" textColor="text-secondary-400">{{ $t(coreField.label_name) }}</ps-label>
                                </div>

                            </div>
                            <!-- End other setting -->

                            <div class="flex flex-row justify-end mt-6 mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
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
        <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
        <ps-success-dialog ref="ps_success_dialog" />

    </ps-layout>
</template>

<script>
    import { defineComponent, ref, reactive } from 'vue'
    import PsLayout from "@/Components/PsLayout.vue";
    import { Head, useForm } from "@inertiajs/vue3";
    import useValidators from '@/Validation/Validators'
    import PsInput from "@/Components/Core/Input/PsInput.vue";
    import PsLabel from "@/Components/Core/Label/PsLabel.vue";
    import PsButton from "@/Components/Core/Buttons/PsButton.vue";
    import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
    import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
    import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
    import PsCheckbox from '@/Components/Core/Checkbox/PsCheckbox.vue';
    import PsCheckboxValue from '@/Components/Core/Checkbox/PsCheckboxValue.vue';
    import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
    import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
    import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";

    import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
    import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
    import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
    import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
    import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
    import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
    import PsTooltip from '@/Components/Core/Tooltips/PsTooltip.vue';
    import PsTooltips from "@/Components/Core/Tooltips/PsTooltips.vue";
    import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
    import PsSuccessDialog from '@/Components/Core/Dialog/PsSuccessDialog.vue';

    import { trans } from 'laravel-vue-i18n';

    export default defineComponent({
        name: "Edit",
        components: {
            PsIcon,
            PsLoading,
            PsActionModal,
            PsImageIconModal,
            PsImageUpload,
            PsDangerDialog,
            Head,
            PsInput,
            PsLabel,
            PsButton,
            PsTextarea,
            PsLabelHeader4,
            PsLabelCaption,
            PsCheckbox,
            PsCheckboxValue,
            PsBreadcrumb2,
            PsDropdown,
            PsDropdownSelect,
            PsTooltips,
            PsTooltip,
            PsLoadingCircleDialog,
            PsSuccessDialog
        },
        layout: PsLayout,
        props: ['errors', 'status', 'coreFieldFilterSettings','can','validation','available_languages'],
        setup(props) {
            const loading = ref(false);
            const success = ref(false);

            const ps_action_modal = ref();
            const ps_image_icon_modal = ref();
            const ps_danger_dialog = ref();
            let visible = ref(false)
            const ps_loading_circle_dialog = ref();

            // Client Side Validation
            const { isEmpty } = useValidators();
            const checkedSelectedList = ref([] )
            const ps_success_dialog = ref();


            const validateEmptyInput = (fieldName, fieldValue) => {
                props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
            }

            const settingColumn = [
                 {
                    label: "core__play_store_and_app_store_confirmation",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-103385/acf8f8bf8d16041'
                },
                {
                    label: "core__fe_image_configuration",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-104164/5004d88c52cdd68'
                },
                {
                    label: "core__fe_firebase_configuration",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-104205/c36a4f43476753e'
                },
                {
                    label: "core__lang_refresh_setting",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-105624/6b43f6796ba146b'
                },
                {
                    label: "core__fe_meta_configuration",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-159065/f9af8c08a4bee9d'
                },
                {
                    label: "core__fe_other_information",
                    docu: 'https://doc.clickup.com/24312566/p/h/q5yqp-104225/ff73ab8afbc24dd'
                }
            ]

            const title = ref(settingColumn[0].label);
            const docu = ref(settingColumn[0].docu);

            function changeSection(v){
                title.value = v.label;
                docu.value = v.docu;
            }

            const onlyNumber = ($e) => {
                let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
                if (keyCode < 48 || keyCode > 57) {
                    $e.preventDefault();
                }
            }
            const form = reactive(useForm(
                {
                    map_key: "",
                    is_enable_video_setting: false,
                    show_user_profile: false,
                    no_filter_with_location_on_map:false,
                    price_format: "",
                    enable_notification: false,
                    fcm_server_key: "",
                    firebase_web_push_key_pair: "",
                    firebase_config: "",
                    ad_client: "",
                    ad_slot: "",
                    is_ads_on: false,
                    copyright: "",
                    google_playstore_url: "",
                    google_setting: false,
                    app_store_url: "",
                    app_store_setting: false,
                    banner_src: "",
                    google_map: false,
                    open_street_map: false,
                    mile: "",
                    default_language: "",
                    promote_first_choice_day: "",
                    // selected_language: checkedSelectedList,
                    selected_language: [],
                    promote_second_choice_day: "",
                    promote_third_choice_day: "",
                    promote_fourth_choice_day: "",
                    gps_enable: false,
                    show_main_menu: false,
                    show_special_collections: false,
                    show_featured_items: false,
                    show_best_choice_slider: false,
                    frontend_version_no: "",
                    frontend_logo: "",
                    frontend_icon: "",
                    frontend_banner: "",
                    frontend_meta_img: "",
                }
            ))

            function handleSubmit(id) {
                // console.log('here');
                this.$inertia.post(route('frontend_setting.store'), form, {
                    forceFormData: true,
                onBefore: () => {loading.value = true},
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(()=>{
                        success.value = false;
                    },1000)


                },
                onError: () => {
                    loading.value = false;;
                },
                });
            }

            function replaceImageClicked(id, isLogo,imageName , uploadType= null) {

                let removeLabel = trans('core__be_remove_icon_label');
                let replaceLabel = trans('core__be_replace_icon_label');
                let confirmLabel = trans('core__be_are_u_sure_remove_icon');
                let uploadLabel = trans('core__be_upload_icon');
                if(imageName == 'frontend_logo'){
                    removeLabel = trans('core__be_remove_logo_label');
                    replaceLabel = trans('core__be_replace_logo_label');
                    confirmLabel = trans('core__be_are_u_sure_remove_logo');
                    uploadLabel = trans('core__be_upload_logo');
                }
                if(imageName == 'frontend_banner'){
                    removeLabel = trans('core__be_remove_banner_label');
                    replaceLabel = trans('core__be_replace_banner_label');
                    confirmLabel = trans('core__be_are_u_sure_remove_banner');
                    uploadLabel = trans('core__be_upload_banner');
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

            function handleFeLangRefresh()
            {

                this.$inertia.get(route('frontend_setting.languageRefresh'), {key: "refresh"}, {
                    onBefore: () => {
                        ps_loading_circle_dialog.value.openModal(trans('core__be_updating_title'),trans('core__be_lang_refreshing_desc'));
                    },
                    onSuccess: () => {
                        // ps_loading_circle_dialog.value.closeModal();
                    },
                    onError: () => {
                        ps_loading_circle_dialog.value.closeModal();
                    }
                });
            }

            function langRefreshSuccessDialog()
            {
                ps_success_dialog.value.openModal(trans('core__be_awesome_title'),trans('core__be_lang_refresh_success_desc'),trans('core__be_btn_back'),
                ()=>{

                },true);
            }


            return {
                handleSubmit,
                validateEmptyInput,
                onlyNumber,
                form ,
                checkedSelectedList,
                title,
                docu,
                settingColumn,
                changeSection,
                loading,
                success,
                replaceImageClicked,
                ps_danger_dialog,
                ps_image_icon_modal,
                ps_action_modal,
                handleFeLangRefresh,
                ps_loading_circle_dialog,
                langRefreshSuccessDialog,
                ps_success_dialog
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
                        label: trans('frontend_setting_module'),
                        color: "text-primary-500"
                    }
                ]
            }
        },
        created() {
            // let i = 0;
            // let selected_language = (this.frontend_setting.selected_language)
            // for (let language in this.available_languages) {
            //     for (let k in selected_language) {
            //         if (selected_language[k] == this.available_languages[language].language_code) {
            //             this.form.selected_language[i] = this.available_languages[language];
            //             i = i + 1;
            //             continue
            //         }
            //     }
            // }
        },
        mounted() {
            if(this.status.flag == "langSuccess"){
                this.langRefreshSuccessDialog();
            }
        }
    })
</script>
