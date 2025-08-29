<template>
    <Head :title="$t('about_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <div class="w-full mt-8 rounded-xl bg-white dark:bg-secondaryDark-black  shadow-sm">
            <div class="">
                <!-- card header start -->
                <ps-label class="text-lg px-4 py-3.5 rounded-t-xl bg-primary-50 dark:bg-primary-900 "> {{$t('core__about')}}</ps-label>
                <!-- card header end -->

                <form @submit.prevent="handleSubmit">
                    <div class="grid grid-cols-2 gap-x-20" >
                        <div class="">
                            <ps-label class="text-lg px-4 py-3 ">{{$t('core__about_info')}}</ps-label>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'about_title' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="about_title" type="text" v-model:value="form.about_title" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'about_title', form.about_title ):''" @blur="coreField.mandatory==1?validateEmptyInput('about_title', form.about_title ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.about_title }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'upload_point' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="upload_point" type="text" v-model:value="form.upload_point" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'upload_point', form.upload_point ):''" @blur="coreField.mandatory==1?validateEmptyInput('upload_point', form.upload_point ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.upload_point }}</ps-label-caption>
                            </div>


                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'about_description' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea rows="3" ref="about_description" v-model:value="form.about_description" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'about_description', form.about_description ):''" @blur="coreField.mandatory==1?validateEmptyInput('about_description', form.about_description ):''"></ps-textarea>
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.about_description }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'GDPR' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea rows="3" ref="GDPR" v-model:value="form.GDPR" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'GDPR', form.GDPR ):''" @blur="coreField.mandatory==1?validateEmptyInput('GDPR', form.GDPR ):''"></ps-textarea>
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.GDPR }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'safety_tips' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea rows="3" ref="safety_tips" v-model:value="form.safety_tips" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'safety_tips', form.safety_tips ):''" @blur="coreField.mandatory==1?validateEmptyInput('safety_tips', form.safety_tips ):''"></ps-textarea>
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.safety_tips }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'faq_pages' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea rows="3" ref="faq_pages" v-model:value="form.faq_pages" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'faq_pages', form.faq_pages ):''" @blur="coreField.mandatory==1?validateEmptyInput('faq_pages', form.faq_pages ):''"></ps-textarea>
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.faq_pages }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'terms_and_conditions' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-textarea rows="3" ref="terms_and_conditions" v-model:value="form.terms_and_conditions" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'terms_and_conditions', form.terms_and_conditions ):''" @blur="coreField.mandatory==1?validateEmptyInput('terms_and_conditions', form.terms_and_conditions ):''"></ps-textarea>
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.terms_and_conditions }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3">
                                <ps-label class="text-base">{{ $t('core__about_cover_photo') }}</ps-label>
                                <ps-label-title-3>{{ $t('core__be_recommended_size_400_200') }}</ps-label-title-3>
                                <ps-image-upload uploadType="image" v-model:imageFile="form.about_cover" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.about_cover }}</ps-label-caption>
                            </div>
                            <!-- <div class="px-4 py-3 flex flex-row">
                                <input type="checkbox" class="rounded border" value="0" v-model="form.ads_on" />
                                <ps-label class="ms-2" for="">Ads On?</ps-label>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="text-base">Ads Slot</ps-label>
                                <ps-input type="text" v-model:value="form.ads_slot" placeholder="Ads Slot"
                                        />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.ads_slot }}</ps-label-caption>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="text-base">Ads Client</ps-label>
                                <ps-input type="text" v-model:value="form.ads_client" placeholder="Ads Client"
                                        />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.ads_client}}</ps-label-caption>
                            </div>
                            <div class="px-4 py-3 flex flex-row">
                                <input type="checkbox" class="rounded border" value="0" v-model="form.analyt_on" />
                                <ps-label class="ms-2" for="">Analyt On?</ps-label>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="text-base">Analyt Track ID</ps-label>
                                <ps-input type="text" v-model:value="form.analyt_track_id" placeholder="Analyt Track ID"
                                        />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.analyt_track_id }}</ps-label-caption>
                            </div> -->
                        </div>
                        <div class="">
                            <ps-label class="text-lg px-4 py-3 ">{{ $t('core__about_contact_label') }}</ps-label>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'about_email' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="about_email" type="text" v-model:value="form.about_email" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'about_email', form.about_email ):''" @blur="coreField.mandatory==1?validateEmptyInput('about_email', form.about_email ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.about_email }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'about_phone' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="about_phone" type="text" v-model:value="form.about_phone" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'about_phone', form.about_phone ):''" @blur="coreField.mandatory==1?validateEmptyInput('about_phone', form.about_phone ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.about_phone }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'about_address' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="about_address" type="text" v-model:value="form.about_address" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'about_address', form.about_address ):''" @blur="coreField.mandatory==1?validateEmptyInput('about_address', form.about_address ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.about_address }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'about_website' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="about_website" type="text" v-model:value="form.about_website" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'about_website', form.about_website ):''" @blur="coreField.mandatory==1?validateEmptyInput('about_website', form.about_website ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.about_website }}</ps-label-caption>
                            </div>


                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'facebook' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="facebook" type="text" v-model:value="form.facebook" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'facebook', form.facebook ):''" @blur="coreField.mandatory==1?validateEmptyInput('facebook', form.facebook ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.facebook }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'google_plus' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="google_plus" type="text" v-model:value="form.google_plus" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'google_plus', form.google_plus ):''" @blur="coreField.mandatory==1?validateEmptyInput('google_plus', form.google_plus ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.google_plus }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'twitter' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="twitter" type="text" v-model:value="form.twitter" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'twitter', form.twitter ):''" @blur="coreField.mandatory==1?validateEmptyInput('twitter', form.twitter ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.twitter }}</ps-label-caption>
                            </div>

                             <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'instagram' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="instagram" type="text" v-model:value="form.instagram" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'instagram', form.instagram ):''" @blur="coreField.mandatory==1?validateEmptyInput('instagram', form.instagram ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.instagram }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'pinterest' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="pinterest" type="text" v-model:value="form.pinterest" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'pinterest', form.pinterest ):''" @blur="coreField.mandatory==1?validateEmptyInput('pinterest', form.pinterest ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.pinterest }}</ps-label-caption>
                            </div>

                            <div class="px-4 py-3" v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'youtube' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base flex flex-row">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory==1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="youtube" type="text" v-model:value="form.youtube" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'youtube', form.youtube ):''" @blur="coreField.mandatory==1?validateEmptyInput('youtube', form.youtube ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.youtube }}</ps-label-caption>
                            </div>

                        </div>
                        <div class="col-span-2 px-4 py-3 flex flex-row justify-end mb-2.5 mt-4">
                            <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                            <ps-button class="transition-all duration-300 min-w-3xs" padding="px-6 py-2" rounded="rounded" hover="" focus="" >
                                <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                            </ps-button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </ps-layout>
</template>

<script>
import { defineComponent,ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Create",
    components: {
        Head,
        PsLoading,
        PsIcon,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption,
        PsBreadcrumb2,
        PsLabelTitle3,
        PsImageUpload
    },
    layout: PsLayout,
    props: ['errors', 'status', 'coreFieldFilterSettings'],
    setup(props) {
        // Client Side Validation
        let form = useForm({
                about_title: "",
                about_cover: "",
                about_description: "",
                about_email: "",
                about_phone: "",
                about_address: "",
                about_address: "",
                facebook: "",
                google_plus: "",
                instagram: "",
                youtube: "",
                pinterest: "",
                twitter: "",
                GDPR: "",
                upload_point: "",
                safety_tips: "",
                faq_pages: "",
                terms_and_conditions: "",
            })

        const loading = ref(false);
        const success = ref(false);

        function handleCancel() {
            this.$inertia.get(route('about.index'));
        }

        function handleSubmit() {
            this.$inertia.post(route('about.store'), form, {
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
        const { isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
        }

        return { validateEmptyInput, handleCancel,form, handleSubmit,loading,success }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('about_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
