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
                            <div>

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
                                    <ps-label-title-3 v-if="!landing_page.landing_logo[0]"> {{ $t('core__be_recommended_size_200_200') }}
                                    </ps-label-title-3>
                                    <div v-if="landing_page.landing_logo[0]" class="flex items-end pt-4">
                                        <img
                                        v-lazy=" { src: $page.props.uploadUrl + '/' + landing_page.landing_logo[0].img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"

                                            class="w-56 h-56" alt="landing_page logo" />
                                        <ps-button type="button" @click="replaceLogoClicked(landing_page.landing_logo[0].id)" rounded="rounded-full"
                                            shadow="drop-shadow-2xl" class="-ms-10 mb-2"
                                            colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                            <ps-icon name="pencil-btn"  w="21" h="21" />
                                        </ps-button>

                                    </div>
                                    <ps-image-upload class="w-56" v-else uploadType="icon" v-model:imageFile="form.logo" />
                                    <ps-label-caption textColor="text-red-500 " class="m-2">{{ errors.logo }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'landing-cover' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                    <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                    <ps-label-title-3 v-if="!landing_page.landing_cover[0]"> {{ $t('core__be_recommended_size_630_660') }} </ps-label-title-3>
                                    <div v-if="landing_page.landing_cover[0]" class="flex items-end pt-4">
                                        <img
                                        v-lazy=" { src: $page.props.uploadUrl + '/' + landing_page.landing_cover[0].img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"

                                            class="w-96 h-96" alt="landing_page cover" />
                                        <ps-button type="button" @click="replaceImageClicked(landing_page.landing_cover[0].id)"
                                            rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2"
                                            colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                            <ps-icon name="pencil-btn"  w="21" h="21" />
                                        </ps-button>

                                    </div>
                                    <ps-image-upload v-else uploadType="image" v-model:imageFile="form.cover" />
                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.cover }}</ps-label-caption>
                                </div>

                                <div class="px-4 py-3">
                                    <ps-label class="text-base flex flex-row">{{$t('core_fe__default_landing_page_color')}}</ps-label>
                                    <div class="grid grid-cols-2">
                                        <div>
                                            <div class="flex flex-row p-1">
                                                <div class="w-6 h-6 m-1 rounded shadow-sm cursor-pointer">
                                                    <div @click="colorPickerClicked(current_color)" class="w-6 h-6 rounded shadow-sm cursor-pointer" :style="{ 'background-color': current_color }"></div>
                                                </div>
                                                <ps-label class="leading-7">{{ current_color }}</ps-label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-row  px-4 py-3 justify-end mb-2.5 mt-4">

                                    <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                    <ps-button :disabled="!checkEditPermission" @click="handleSubmit()" class="transition-all duration-300 min-w-3xs me-4" padding="px-8 py-0" rounded="rounded" hover="" focus="" >
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
        <ps-image-icon-modal ref="ps_image_icon_modal" />
        <ps-action-modal ref="ps_action_modal" />
        <ps-danger-dialog ref="ps_danger_dialog" />
        <PsColorPickerModal ref="ps_color_picker_modal" />
    </ps-layout>
</template>

<script>
import { defineComponent,ref,defineAsyncComponent, reactive } from 'vue'
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
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import { trans } from 'laravel-vue-i18n';


const PsColorPickerModal = defineAsyncComponent(() => import('@/Components/Core/Modals/PsColorPickerModal.vue'));

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
        PsLabelTitle3,
        PsColorPickerModal
    },
    layout: PsLayout,
    props: ['errors', 'status', 'coreFieldFilterSettings','landing_page','checkEditPermission','validation'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const email = ref();
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const ps_color_picker_modal = ref();
        const current_color = ref(props.landing_page.default_landing_page_color);

        let visible = ref(false)

        let form = useForm(
            {
                title: props.landing_page.title,
                gps_link: props.landing_page.gps_link,
                ios_link: props.landing_page.ios_link,
                description:props.landing_page.description,
                yt_link: props.landing_page.yt_link,
                fb_link: props.landing_page.fb_link,
                tw_link: props.landing_page.tw_link,
                default_landing_page_color: props.landing_page.default_landing_page_color,
                cover: '',
                logo: '',
                "_method": "put"
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
            this.$inertia.post(route('landing_page.update',props.landing_page.id), form, {
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

        function colorPickerClicked(data) {
            ps_color_picker_modal.value.openModal(
                data,
                (selectedColor) => {
                    current_color.value = selectedColor;
                    form.default_landing_page_color = current_color.value;
                },
                () => {}
            );
        }

        function replaceImageClicked(id) {
            ps_action_modal.value.openModal(trans('conf_modal_label'),
                trans('core__be_replace_img_label'),
                trans('core__be_remove_img_label'),
                'imagePlus',
                'cross',
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
                },
                !props.validation.includes('cover')
            );
        }

        function replaceLogoClicked(id) {
            ps_action_modal.value.openModal(trans('conf_modal_label'),
                trans('core__be_replace_logo_label'),
                trans('core__be_remove_logo_label'),
                'image',
                'trash',
                '24',
                '24',
                () => {
                    ps_image_icon_modal.value.openModal(
                        trans('core__be_upload_icon'),
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
                        trans('core__be_are_u_sure_remove_logo'),
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
                !props.validation.includes('logo')
            );
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
            email,
            replaceImageClicked,
            replaceLogoClicked,
            ps_danger_dialog,
            colorPickerClicked,
            ps_color_picker_modal,
            current_color,
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
