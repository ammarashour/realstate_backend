<template>
    <Head :title="$t('landing_page_module_entry')" />
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
                <div class="bg-background dark:bg-secondaryDark-black rounded-lg ">
                    <!-- card header start -->
                    <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-lg">
                        <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100"> {{ $t('core__be_landing_page_info') }} </ps-label-header-6>
                    </div>
                    <!-- card header end -->
                    
                    <div >
                        <div class="w-full sm:w-1/2">
                            <div v-if="coreFieldFilterSettings">

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'title' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="title" type="text" v-model:value="form.title" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'title', form.title ):''" @blur="coreField.mandatory==1?validateEmptyInput('title', form.title ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.title }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-textarea rows="4" ref="description" v-model:value="form.description" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'description', form.description ):''" @blur="coreField.mandatory==1?validateEmptyInput('description', form.description ):''"></ps-textarea>
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.description }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'gps_link' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="gps_link" type="text" v-model:value="form.gps_link" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'gps_link', form.gps_link ):''" @blur="coreField.mandatory==1?validateEmptyInput('gps_link', form.gps_link ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.gps_link }}</ps-label-caption>
                                </div>


                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'ios_link' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="ios_link" type="text" v-model:value="form.ios_link" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'ios_link', form.ios_link ):''" @blur="coreField.mandatory==1?validateEmptyInput('ios_link', form.ios_link ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.ios_link }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'yt_link' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="yt_link" type="text" v-model:value="form.yt_link" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'yt_link', form.yt_link ):''" @blur="coreField.mandatory==1?validateEmptyInput('yt_link', form.yt_link ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.yt_link }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'fb_link' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="fb_link" type="text" v-model:value="form.fb_link" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'fb_link', form.fb_link ):''" @blur="coreField.mandatory==1?validateEmptyInput('fb_link', form.fb_link ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.fb_link }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'tw_link' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-input ref="tw_link" type="text" v-model:value="form.tw_link" :placeholder="$t(coreField.placeholder)"
                                        @keyup="coreField.mandatory==1?validateEmptyInput( 'tw_link', form.tw_link ):''" @blur="coreField.mandatory==1?validateEmptyInput('tw_link', form.tw_link ):''" />
                                    <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.tw_link }}</ps-label-caption>
                                </div>

                                 <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'landing-icon' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-label-title-3>{{ $t('core__be_recommended_size_200_200') }}</ps-label-title-3>
                                    <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.logo" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.logo }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'landing-cover' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-label-title-3>{{ $t('core__be_recommended_size_400_200') }}</ps-label-title-3>
                                    <ps-image-upload uploadType="image" v-model:imageFile="form.cover" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.cover }}</ps-label-caption>
                                </div>

                                <!-- <div>
                                    <ps-label class="text-base mb-2">{{$t('core__landing_logo_label')}}<span class="text-red-500 ms-1">*</span></ps-label>
                                    
                                </div> -->
                            
                                <div class="flex flex-row  px-4 py-3 justify-end mb-2.5 mt-4">
                                    <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                    <ps-button @click="handleSubmit()" class="transition-all duration-300 min-w-3xs me-4" padding="px-8 py-0" rounded="rounded" hover="" focus="" >
                                        <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                        <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                        <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                        <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                                    </ps-button>
                                </div>                               
                            </div>
                        </div>
                    </div>
                </div>
            </ps-card>

        </div>
    </ps-layout>
</template>

<script>
import { defineComponent,ref, reactive } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head,Link, useForm } from "@inertiajs/vue3";
import FlashMessage from "../components/FlashMessage.vue";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";


import { trans } from 'laravel-vue-i18n';

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

    },
    layout: PsLayout,
    props: ['errors', 'status', 'coreFieldFilterSettings'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const email = ref();
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        let visible = ref(false)

        let form = useForm(
            {
                title: "",
                gps_link: "",
                ios_link: "",
                description: "",
                yt_link: "",
                fb_link: "",
                tw_link: "",
                logo: "",
                cover: "",
            }
        )


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
 
        function handleCancel() {
            this.$inertia.get(route('admin.index'));
        }
        function handleSubmit() {
            // console.log('here');
            this.$inertia.post(route('landing_page.store'), form, {
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
            });
        }


        return {
            validateEmailInput,
            handleCancel,
            onlyNumber,
            form,
            handleSubmit,
            loading,
            success,
            ps_image_icon_modal,
            ps_action_modal,
            visible,
            validateEmptyInput,
            email
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
                    label: trans('landing_page_module_entry'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
