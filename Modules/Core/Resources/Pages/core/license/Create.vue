
<template>
    <Head :title="$t('backend_setting_module')" />
    <ps-layout>
        <div class="">
            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mt-4 mb-7" />
            <!-- breadcrumb end -->

            <div class="grid w-full grid-cols-2 mt-8 bg-white rounded-lg shadow-sm gap-x-20 dark:bg-secondaryDark-black">
                <ps-label class="col-span-2 text-lg px-4 py-3.5 bg-primary-50 dark:bg-primary-900">{{ title }} </ps-label>
                <div class="">
                    <form @submit.prevent="handleSubmit()">
                        <div v-if="title == settingColumn[0].label">
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Sender Name</ps-label>
                                <ps-input type="text" v-model:value="form.sender_name" placeholder="Sender Name"
                                    />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Sender Email</ps-label>
                                <ps-input type="text" v-model:value="form.sender_email" placeholder="Sender Email"
                                    @keyup="validateEmailInput('sender_email', form.sender_email)"
                                    @blur="validateEmailInput('sender_email', form.sender_email)"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.sender_email }}</ps-label-caption>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Receive Email</ps-label>
                                <ps-input type="text" v-model:value="form.receive_email" placeholder="Receive Email"
                                    @keyup="validateEmailInput('receive_email', form.receive_email)"
                                    @blur="validateEmailInput('receive_email', form.receive_email)"
                                    />
                                <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.receive_email }}</ps-label-caption>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >FCM API Key</ps-label>
                                <ps-textarea rows="3" v-model:value="form.fcm_api_key" placeholder="FCM API Key">
                                    </ps-textarea>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >App Token</ps-label>
                                <ps-input type="text" v-model:value="form.app_token" placeholder="App Token"
                                    />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Topics for Mobile</ps-label>
                                <ps-input type="text" v-model:value="form.topics" placeholder="Topics for Mobile"
                                    />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Topics for Frontend</ps-label>
                                <ps-input type="text" v-model:value="form.topics_fe" placeholder="Topics for Frontend"
                                    />
                            </div>
                        </div>

                        <div v-if="title == settingColumn[1].label">
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >SMTP Host</ps-label>
                                <ps-input type="text" v-model:value="form.smtp_host" placeholder="eg: ssl://smtp.hotmail.com"
                                    />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >SMTP Port Number</ps-label>
                                <ps-input type="text" v-model:value="form.smtp_port" placeholder="SMTP Port Number"
                                    />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >SMTP User</ps-label>
                                <ps-input type="text" v-model:value="form.smtp_user" placeholder="SMTP User"
                                    />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >SMTP Password</ps-label>
                                <ps-input type="text" v-model:value="form.smtp_pass" placeholder="SMTP Password"
                                    />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >SMTP Encryption</ps-label>
                                <ps-input type="text" v-model:value="form.smtp_encryption" placeholder="SMTP Encryption"
                                    />
                            </div>
                            <div class="flex flex-row px-4 py-3">
                                <input type="checkbox" class="border rounded" value="0" v-model="form.email_verification_enabled"/>
                                <ps-label class="ms-2" for="">Email Verification Enabled?</ps-label>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="text-sm ms-2" textColor="text-secondary-400">If this setting is "On" when user register by using email it will send verification code to user registered email.</ps-label>
                            </div>
                            <div class="flex flex-row px-4 py-3">
                                <input type="checkbox" class="border rounded" value="0" v-model="form.user_social_info_override"/>
                                <ps-label class="ms-2" for="">User Social Information Override? </ps-label>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="text-sm ms-2" textColor="text-secondary-400">If checked, the user's social information will be overried when you login with (google, facebook, phone and apple, etc...)</ps-label>
                            </div>
                        </div>

                        <div v-if="title == settingColumn[2].label">
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Backend Logo</ps-label>
                                <ps-label-title-3>{{ $t('recommended_size_200_200') }}</ps-label-title-3>
                                <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.backend_logo" />
                                <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.backend_logo }}</ps-label-caption>
                                <!-- <ps-input type="file" accept="image/*"   @input="form.backend_logo = $event.target.files"/> -->
                            </div>

                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Backend Fav Ico</ps-label>
                                <ps-label-title-3>{{ $t('recommended_size_200_200') }}</ps-label-title-3>
                                <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.fav_icon" />
                                <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.fav_icon }}</ps-label-caption>
                                <!-- <ps-input type="file" accept="image/*"   @input="form.fav_icon = $event.target.files"/> -->
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Backend Login Background Image</ps-label>
                                <ps-label-title-3>{{ $t('recommended_size_400_200') }}</ps-label-title-3>
                                <ps-image-upload uploadType="image" v-model:imageFile="form.backend_login_image" />
                                <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.backend_login_image }}</ps-label-caption>
                                <!-- <ps-input type="file" accept="image/*"   @input="form.backend_login_image = $event.target.files"/> -->
                            </div>
                        </div>

                        <div v-if="title == settingColumn[3].label">
                            <ps-label class="px-4 py-3 text-lg" >Original Image Size</ps-label>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Landscape Width</ps-label>
                                <ps-input type="text" v-model:value="form.landscape_width" placeholder="Landscape Width"
                                        @keypress="onlyNumber"/>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Potrait Height</ps-label>
                                <ps-input type="text" v-model:value="form.potrait_height" placeholder="Potrait Height"
                                        @keypress="onlyNumber"/>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Square Height</ps-label>
                                <ps-input type="text" v-model:value="form.square_height" placeholder="Square Height"
                                        @keypress="onlyNumber"/>
                            </div>

                            <ps-label class="px-4 py-3 text-lg" >Thumbnail 1x Image Size</ps-label>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Landscape Width</ps-label>
                                <ps-input type="text" v-model:value="form.landscape_thumb_width" placeholder="Landscape Width"
                                        @keypress="onlyNumber"/>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Potrait Height</ps-label>
                                <ps-input type="text" v-model:value="form.potrait_thumb_height" placeholder="Potrait Height"
                                        @keypress="onlyNumber"/>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Square Height</ps-label>
                                <ps-input type="text" v-model:value="form.square_thumb_height" placeholder="Square Height"
                                        @keypress="onlyNumber"/>
                            </div>

                            <ps-label class="px-4 py-3 text-lg" >Thumbnail 2x Image Size</ps-label>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Landscape Width</ps-label>
                                <ps-input type="text" v-model:value="form.landscape_thumb2x_width" placeholder="Landscape Width"
                                        @keypress="onlyNumber"/>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Potrait Height</ps-label>
                                <ps-input type="text" v-model:value="form.potrait_thumb2x_height" placeholder="Potrait Height"
                                        @keypress="onlyNumber"/>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Square Height</ps-label>
                                <ps-input type="text" v-model:value="form.square_thumb2x_height" placeholder="Square Height"
                                        @keypress="onlyNumber"/>
                                <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.square_thumb2x_height }}</ps-label-caption>
                            </div>

                            <ps-label class="px-4 py-3 text-lg" >Thumbnail 3x Image Size</ps-label>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Landscape Width</ps-label>
                                <ps-input type="text" v-model:value="form.landscape_thumb3x_width" placeholder="Landscape Width"
                                        @keypress="onlyNumber"/>
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Potrait Height</ps-label>
                                <ps-input type="text" v-model:value="form.potrait_thumb3x_height" placeholder="Potrait Height"
                                        @keypress="onlyNumber" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base" >Square Height</ps-label>
                                <ps-input type="text" v-model:value="form.square_thumb3x_height" placeholder="Square Height"
                                        @keypress="onlyNumber"/>
                            </div>
                        </div>
                        <div v-if="title == settingColumn[4].label">
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Dynamic link key</ps-label>
                                <ps-input type="text" v-model:value="form.dyn_link_key" placeholder="Dynamic link key" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Dynamic linking url</ps-label>
                                <ps-input type="text" v-model:value="form.dyn_link_url" placeholder="Dynamic linking url" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Dynamic linking package name</ps-label>
                                <ps-input type="text" v-model:value="form.dyn_link_package_name" placeholder="Dynamic linking package name" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Dynamic linking domain</ps-label>
                                <ps-input type="text" v-model:value="form.dyn_link_domain" placeholder="Dynamic linking domain" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Deep linking url</ps-label>
                                <ps-input type="text" v-model:value="form.dyn_link_deep_url" placeholder="Deep linking url" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Dynamic linking IOS package name</ps-label>
                                <ps-input type="text" v-model:value="form.ios_boundle_id" placeholder="Dynamic linking IOS package name" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Dynamic Linking IOS AppStore Id</ps-label>
                                <ps-input type="text" v-model:value="form.ios_appstore_id" placeholder="Dynamic Linking IOS AppStore Id" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Backend Version No.</ps-label>
                                <ps-input type="text" v-model:value="form.backend_version_no" placeholder="Backend Version No." />
                            </div>
                        </div><div v-if="title == settingColumn[5].label">
                            <div class="px-4 py-3">
                                <ps-label class="mb-1 text-base">Limited Days</ps-label>
                                <ps-input type="text" v-model:value="form.slow_moving_item_limit" placeholder="Input Limited Days" />
                                <ps-label-caption textColor="text-secondary-300" class="block mt-2">Item will be moved to Slow Moving Item Report after
                                    limited days<span style="color:red">*</span></ps-label-caption>
                            </div>
                        </div>
                        <div class="flex flex-row px-4 py-3 justify-end mb-2.5 mt-4">
                            <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('btn_cancel')}}</ps-button>
                            <ps-button class="transition-all duration-300 min-w-3xs" padding="px-6 py-2" rounded="rounded" hover="" focus="" >
                                <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_save') }}</ps-label>
                                <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('btn_save')}} </ps-label>
                            </ps-button>
                        </div>
                    </form>
                </div>
                <div class="flex flex-col pt-4">

                    <div v-for="column in settingColumn" :key="column.id">
                        <div @click="changeSection(column.label)"
                            :class="title == column.label ? 'border-l border-s-primary-500' : 'border-l border-s-secondary-300'"
                            class="px-2 py-3 cursor-pointer">
                            <ps-label :textColor="title == column.label ? 'text-primary-500 dark:text-primary-500' : 'text-secondary-800 dark:text-white'" >
                                {{ column.label }}
                            </ps-label>
                        </div>
                    </div>

                </div>
            </div>
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
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
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
        PsLabelHeader4,
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        Link,
        PsLabelCaption,
        PsLabelTitle3,
        PsImageUpload
    },
    layout: PsLayout,
    props: ['errors'],
    setup(props) {

        const loading = ref(false);
        const success = ref(false);
        let form = useForm(
            {
                sender_name: "",
                sender_email: "",
                receive_email: "",
                fcm_api_key: "",
                app_token: "",
                topics: "",
                topics_fe: "",
                smtp_host: "",
                smtp_port: "",
                smtp_user: "",
                smtp_pass: "",
                smtp_encryption: "",
                email_verification_enabled: "",
                user_social_info_override: "",
                landscape_width: "",
                potrait_height: "",
                square_height: "",
                landscape_thumb_width: "",
                potrait_thumb_height: "",
                square_thumb_height: "",
                landscape_thumb2x_width: "",
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
                backend_logo: "",
                fav_icon: "",
                backend_login_image: "",
                slow_moving_item_limit: "",
            }
        )

        const settingColumn = [
            {
                label: "Backend Setting Information",
            },
            {
                label: "SMT Configuraiton",
            },
            {
                label: "Image Section",
            },
            {
                label: "Image Configuration",
            },
            {
                label: "Deep Linking Configuration",
            },
            {
                label: "Slow Moving Item List",
            }
        ];

        const title = ref(settingColumn[0].label);

        // Client Side Validation
        const { isEmail } = useValidators();

        const validateEmailInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? '' : isEmail(fieldName, fieldValue);
        }

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }
        function changeSection(v){
            title.value = v;
        }
        function handleCancel() {
            this.$inertia.get(route('backend_setting.index'));
        }
        function handleSubmit() {
            this.$inertia.post(route('backend_setting.store'), form, {
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
            settingColumn,
            title,
            changeSection,
            handleSubmit,
            loading,
            success
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
})
</script>
