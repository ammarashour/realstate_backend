<template>
    <Head :title="$t('core__be_sign_in')" />

    <div :class="themeStore.isDarkMode ? 'dark' : '' " :dir="languageStore.getLanguageDir()">
        <!-- frontend enable -->
        <PsFrontendLayout v-if="backendSetting.fe_setting == 1" :backendSetting="backendSetting"
            :firebaseConfig="firebaseConfig" :webPushKey="webPushKey">
            <div class="flex flex-row items-center justify-center min-h-screen mb-6 bg-feAchromatic-50 dark:bg-feAchromatic-900">
                <div
                    class="flex flex-row h-auto overflow-hidden rounded-md shadow-md sm:mt-32 lg:mt-36 mt-28 w-98 lg:w-202 ">
                    <div class="flex-col items-center justify-center hidden h-auto w-100 bg-feSecondary-900 lg:flex">
                        <div class="w-65 h-50">
                            <welcome-image :baseProjectId="$page.props.project?.base_project_id" />
                            <!-- <img  src="/images/assets/welcome_image.png" width="44" height="48" class="object-cover h-48 w-44"/> -->
                        </div>
                        <ps-label class="mt-6 mb-2 text-lg font-semibold" textColor="text-feSecondary-50">
                            {{ $t('login__fe_title_best_deal') }}
                        </ps-label>
                        <ps-label class="mx-10 mb-2 text-base font-normal text-center" textColor="text-feSecondary-200">
                            {{ $t('login__fe_description_best_deal') }}
                        </ps-label>

                        <div class="flex flex-row space-x-4 text-feSecondary-50 dark:text-feSecondary-900 rtl:space-x-reverse">
                            <ps-icon name="facebookCircle" />
                            <ps-icon name="instagramCircle" />
                            <ps-icon name="twitter" />
                            <!-- <ps-icon textColor="text-fePrimary-500 dark:text-feAchromatic-50" class="" name="apple-icon"
                                h="24" w="24" /> -->
                            <ps-icon name="pinterest" />
                            <ps-icon name="linkedIn" />
                        </div>
                    </div>
                    <div class="w-full px-10 pb-10 lg:w-100 bg-feAchromatic-50 dark:bg-feSecondary-800">
                        <!-- <img v-if="$page.props.backendLogo" :src="$page.props.uploadUrl + '/' + $page.props.backendLogo.img_path" width="50" height="50" class="m-auto my-2.5"/> -->

                        <div class="flex justify-center">

                            <ps-label class="justify-center mt-6 mb-6 text-2xl font-senibold">{{ $t('core__fe_login')
                            }}</ps-label>
                            <!-- <ps-label class="mt-1 mb-6 text-base font-light" textColor="text-feSecondary-600 dark:text-feSecondary-50">{{
                                $t('login_to_your_acc') }}</ps-label> -->
                        </div>

                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <ps-label class="mb-2 text-sm" textColor="text-feSecondary-800 dark:text-feSecondary-200">{{
                                    $t('core__fe_email_username') }}</ps-label>
                                <ps-input ref="email" type="text" v-model:value="form.email"
                                    class="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                    theme="text-feSecondary-500 dark:bg-feSecondary-800"
                                    defaultBorder="border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focusr_border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400"
                                    :placeholder="$t('core__fe_email_username_placeholder')" autofocus />
                                <ps-label-caption textColor="text-feError-500 " class="block mt-2">{{ errors.email
                                }}</ps-label-caption>
                            </div>
                            <div class="mb-4">
                                <ps-label class="mb-2">{{ $t('core__fe_password') }}</ps-label>
                                <ps-input-with-right-icon v-model:value="form.password" ref="password"
                                    placeholderColor="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                    theme="bg-feAchromatic-50 dark:bg-feSecondary-800" :type="(isHide ? 'password' : 'text')"
                                    defaultBorder="border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focusr_border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400"
                                    @keyup="validateEmptyInput('password', form.password)"
                                    @blur="validateEmptyInput('password', form.password)"
                                    :placeholder="$t('core__fe_password_placeholder2')" autocomplete="current-password">
                                    <template #icon>
                                        <ps-icon viewBox="0 0 24 24" @click="isHide = !isHide" class="cursor-pointer"
                                            theme="text-feSecondary-800 dark:text-feSecondary-300"
                                            :name="isHide ? 'eyeOff' : 'eye-on'" />
                                    </template>
                                </ps-input-with-right-icon>
                                <ps-label-caption textColor="text-feError-500 " class="block mt-2">{{ errors.password
                                }}</ps-label-caption>
                            </div>

                            <div class="flex justify-between mb-4">
                                <ps-checkbox-value name="remember" v-model:checked="form.remember"
                                    color="focus:ring-2 focus:ring-fePrimary-300 hover:bg-fePrimary-500 dark:hover:bg-fePrimary-500 checked:bg-fePrimary-500 dark:checked:bg-fePrimary-500 text-fePrimary-500 dark:text-fePrimary-500"
                                    textColor="text-fePrimary-500 dark:text-fePrimary-500"
                                    :title="$t('core__fe_remember_me')" />
                                <ps-label class="cursor-pointer" textColor=" text-fePrimary-500 " v-if="canResetPassword"
                                    @click="handleReset">
                                    {{ $t('core__fe_forgot_password') }}
                                </ps-label>
                            </div>

                            <div class="block mt-4">
                                <ps-button class="w-full mb-2">
                                    <ps-loading v-if="form.processing"
                                        theme="border-2 border-t-2 border-text-8 border-t-fePrimary-800 me-1"
                                        loadingSize="h-5 w-5" />
                                    {{ $t('core__fe_btn_sign_in') }}
                                </ps-button>
                            </div>
                            <ps-label-caption textColor="text-feError-500 " class="block mt-2">{{ errors.user_is_bannded
                            }}</ps-label-caption>
                            <ps-label-caption textColor="text-feError-500 " class="block mt-2">{{ errors.user_ban
                            }}</ps-label-caption>
                            <ps-label-caption textColor="text-feError-500 " class="block mt-2">{{ errors.user_status
                            }}</ps-label-caption>

                        </form>
                        <div class="flex flex-row justify-center my-8 ">
                            <ps-label class="me-1" textColor="text-feSecondary-800 dark:text-feSecondary-500">
                                {{ $t('fe__donot_have_acc') }}
                            </ps-label>
                            <ps-label class="cursor-pointer" textColor="text-fePrimary-500" @click="singinUpClicked">
                                {{ $t('core__fe_sign_up') }}
                            </ps-label>

                        </div>
                        <div class="block">

                            <!-- <div class="mb-6">  -->
                            <div v-if="appInfoStore?.appInfo?.data?.mobileSetting.show_phone_login == 1 || appInfoStore?.appInfo?.data?.mobileSetting.show_facebook_login == 1 || appInfoStore?.appInfo?.data?.mobileSetting.show_google_login == 1 || appInfoStore?.appInfo?.data?.mobileSetting.show_apple_login == 1" class="flex flex-row justify-center mb-6 ">
                                <span
                                    class="w-full h-3 border border-t-0 border-s-0 border-e-0 border-feSecondary-200 dark:border-feSecondaryDark-grey" />
                                <ps-label class="px-2"> {{ $t("login__or") }} </ps-label>
                                <span
                                    class="w-full h-3 border border-t-0 border-s-0 border-e-0 border-feSecondary-200 dark:border-feSecondaryDark-grey" />
                            </div>

                            <!-- <ps-label> or </ps-label>
                            </div> -->


                        </div>

                        <div v-if="appInfoStore?.appInfo?.data?.mobileSetting.show_phone_login == 1 || appInfoStore?.appInfo?.data?.mobileSetting.show_facebook_login == 1 || appInfoStore?.appInfo?.data?.mobileSetting.show_google_login == 1 || appInfoStore?.appInfo?.data?.mobileSetting.show_apple_login == 1" class='flex flex-row items-start justify-start mb-6'>
                            <ps-checkbox-value title="" v-model:value="agreePrivacyPolicy"
                                color="focus:ring-2 focus:ring-fePrimary-300 hover:bg-fePrimary-500 dark:hover:bg-fePrimary-500 checked:bg-fePrimary-500 dark:checked:bg-fePrimary-500 text-fePrimary-500 dark:text-fePrimary-500"
                                textColor="text-fePrimary-500 dark:text-fePrimary-500" @click="agreePrivacyPolicyClicked" />
                            <ps-label class='text-sm me-2 ' textColor="text-feSecondary-800 dark:text-feSecondary-200">{{
                                $t("core__fe_login__check_privacy_policy")
                            }}</ps-label>

                            <ps-label textColor="text-fePrimary-500" class='text-sm '> {{ $t("login__privacy_policy")
                            }}</ps-label>

                        </div>

                        <div class="flex flex-row justify-center space-x-3 rtl:space-x-reverse">


                            <!-- phone -->
                            <div @click="phoneiconclicked"
                                v-if="appInfoStore?.appInfo?.data?.mobileSetting.show_phone_login == 1"
                                class="flex items-center justify-center w-10 h-10 rounded cursor-pointer text-feBrand-phone bg-feBrand-phone">

                                <svg class="flex flex-wrap items-center justify-center space-x-2 space-y-2 rtl:space-x-reverse text-feSecondary-50 dark:text-feAchromatic-50"
                                    width="40" height="40" viewBox="0 0 40 40" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="40" height="40" rx="4" fill="fill-current" />
                                    <mask id="mask0_3456_4766" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="8"
                                        y="8" width="24" height="24">
                                        <rect x="8" y="8" width="24" height="24" fill="white" />
                                    </mask>
                                    <g mask="url(#mask0_3456_4766)">
                                        <path
                                            d="M27.95 29C25.8 29 23.7043 28.5207 21.663 27.562C19.621 26.604 17.8127 25.3373 16.238 23.762C14.6627 22.1873 13.396 20.379 12.438 18.337C11.4793 16.2957 11 14.2 11 12.05C11 11.75 11.1 11.5 11.3 11.3C11.5 11.1 11.75 11 12.05 11H16.1C16.3333 11 16.5417 11.075 16.725 11.225C16.9083 11.375 17.0167 11.5667 17.05 11.8L17.7 15.3C17.7333 15.5333 17.7293 15.7457 17.688 15.937C17.646 16.129 17.55 16.3 17.4 16.45L15 18.9C15.7 20.1 16.575 21.225 17.625 22.275C18.675 23.325 19.8333 24.2333 21.1 25L23.45 22.65C23.6 22.5 23.796 22.3873 24.038 22.312C24.2793 22.2373 24.5167 22.2167 24.75 22.25L28.2 22.95C28.4333 23 28.625 23.1123 28.775 23.287C28.925 23.4623 29 23.6667 29 23.9V27.95C29 28.25 28.9 28.5 28.7 28.7C28.5 28.9 28.25 29 27.95 29Z"
                                            fill="white" />
                                    </g>
                                </svg>

                            </div>
                            <!-- facebook -->
                            <div @click="facebookiconclicked"
                                v-if="appInfoStore?.appInfo?.data?.mobileSetting.show_facebook_login == 1"
                                class="flex items-center justify-center w-10 h-10 border rounded cursor-pointer text-feBrand-facebook bg-feBrand-facebook">

                                <svg class="flex flex-wrap items-center justify-center space-x-2 space-y-2 rtl:space-x-reverse text-feSecondary-50 dark:text-feAchromatic-50"
                                    width="40" height="40" viewBox="0 0 40 40" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect width="40" height="40" rx="4" fill="fill-current" />
                                    <path
                                        d="M27.0703 10H12.9297C11.3144 10 10 11.3144 10 12.9297V27.0703C10 28.6856 11.3144 30 12.9297 30H18.8281V22.9297H16.4844V19.4141H18.8281V17.0312C18.8281 15.0926 20.4051 13.5156 22.3438 13.5156H25.8984V17.0312H22.3438V19.4141H25.8984L25.3125 22.9297H22.3438V30H27.0703C28.6856 30 30 28.6856 30 27.0703V12.9297C30 11.3144 28.6856 10 27.0703 10Z"
                                        fill="white" />
                                </svg>


                            </div>
                            <!-- google -->

                            <div @click="googleiconclicked"
                                v-if="appInfoStore?.appInfo?.data?.mobileSetting.show_google_login == 1"
                                class="flex items-center justify-center w-10 h-10 border rounded cursor-pointer bg-feBrand-google">
                                <svg class="flex flex-wrap items-center justify-center space-x-2 space-y-2 rtl:space-x-reverse bg-feBrand-google"
                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6.43248 14.0862L5.7363 16.6852L3.19177 16.739C2.43133 15.3286 2 13.7148 2 12C2 10.3417 2.40329 8.77793 3.11814 7.401H3.11869L5.38403 7.81632L6.37638 10.0681C6.16869 10.6736 6.05548 11.3236 6.05548 12C6.05556 12.734 6.18853 13.4374 6.43248 14.0862Z"
                                        fill="white" />
                                    <path
                                        d="M21.8263 10.1318C21.9411 10.7368 22.001 11.3615 22.001 12C22.001 12.7159 21.9257 13.4143 21.7823 14.0879C21.2955 16.3803 20.0235 18.3819 18.2614 19.7984L18.2609 19.7979L15.4075 19.6523L15.0037 17.1314C16.1729 16.4457 17.0867 15.3726 17.568 14.0879H12.2207V10.1318H17.646H21.8263Z"
                                        fill="white" />
                                    <path
                                        d="M18.2597 19.798L18.2603 19.7986C16.5465 21.176 14.3696 22.0002 11.9998 22.0002C8.19147 22.0002 4.88045 19.8717 3.19141 16.7392L6.43211 14.0864C7.27662 16.3403 9.45082 17.9447 11.9998 17.9447C13.0954 17.9447 14.1218 17.6485 15.0025 17.1315L18.2597 19.798Z"
                                        fill="white" />
                                    <path
                                        d="M18.3842 4.3021L15.1446 6.95432C14.233 6.38454 13.1555 6.0554 12.0011 6.0554C9.3945 6.0554 7.17962 7.73343 6.37742 10.0681L3.11969 7.40104H3.11914C4.78346 4.19221 8.13624 1.99988 12.0011 1.99988C14.4275 1.99988 16.6523 2.86419 18.3842 4.3021Z"
                                        fill="white" />
                                </svg>
                            </div>

                            <!-- apple -->

                            <!-- <div class="" @click="testmail"><p>test</p></div> v-if="appInfoStore?.appInfo?.data?.mobileSetting.show_apple_login == 1" -->
                            <div @click="appleiconclicked"
                            v-if="appInfoStore?.appInfo?.data?.mobileSetting.show_apple_login == 1"
                                class="flex items-center justify-center w-10 h-10 rounded cursor-pointer text-feBrand-apple bg-feBrand-apple">

                                <ps-icon textColor=""
                                    class="flex flex-wrap items-center justify-center space-x-2 space-y-2 text-white rtl:space-x-reverse"
                                    name="apple-icon" h="24" w="24" />
                            </div>

                            <privacy-modal ref="privacy_modal" />
                            <ps-error-dialog ref="ps_error_dialog" />




                        </div>

                    </div>

                </div>
            </div>
        </PsFrontendLayout>
        <!-- frontend disable -->
        <div v-else
            class="flex flex-col items-center min-h-screen pt-6 sm:justify-center sm:pt-0 dark:bg-feAchromatic-900 dark:text-feSecondary-50">



            <ps-card class="w-full py-4 mt-6 shadow-md sm:max-w-md dark:bg-feAchromatic-900" rounded="rounded">

                <div class="flex items-center justify-end px-2">

                    <!-- <ps-label >{{$t('core__be_email')}}</ps-label> -->
                    <ps-label class="text-xs font-semibold" textColor="text-gray-500">
                        {{ $t('core__be_version') }} {{ backendSetting.backend_version_no }}
                    </ps-label>
                </div>
                <div class="px-6">
                    <img v-if="$page.props.backendLogo"
                        v-lazy="{ src: $page.props.uploadUrl + '/' + $page.props.backendLogo.img_path, loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }"
                        width="50" height="50" class="m-auto my-2.5" />
                    <img v-else :src="$page.props.uploadUrl + '/no_logo.png'" width="200" height="100"
                        class="m-auto my-2.5" />
                    <ps-label-header-3 class="pt-2 text-center">{{ $t('core__be_login_to_your_account') }}</ps-label-header-3>
                    <ps-label-title class="pt-2 pb-8 m-auto text-center"
                        textColor="text-feSecondary-600 dark:text-feSecondary-50">{{ $t('core__be_welcome_back') }}</ps-label-title>

                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <ps-label>{{ $t('core__be_email') }}</ps-label>
                            <ps-input1 ref="email" type="email" v-model:value="form.email"
                                :placeholder="$t('core__be_email_placeholder')"
                                @keyup="validateEmailInput('email', form.email)"
                                @blur="validateEmailInput('email', form.email)" autofocus />
                            <ps-label-caption textColor="text-feError-500 "
                                class="block mt-2">{{ errors.email }}</ps-label-caption>
                        </div>
                        <div class="mb-4">
                            <ps-label>{{ $t('core__be_password') }}</ps-label>
                            <ps-input-with-right-icon1 v-model:value="form.password" ref="password"
                                :type="(isHide ? 'password' : 'text')" @keyup="validateEmptyInput('password', form.password)"
                                @blur="validateEmptyInput('password', form.password)"
                                :placeholder="$t('core__be_password_placeholder')" autocomplete="current-password">
                                <template #icon>
                                    <ps-icon @click="isHide = !isHide" class="cursor-pointer"
                                        :name="isHide ? 'eyeOff' : 'eye-on'" />
                                </template>
                            </ps-input-with-right-icon1>
                            <ps-label-caption textColor="text-feError-500 "
                                class="block mt-2">{{ errors.password }}</ps-label-caption>
                        </div>

                        <div class="flex justify-between mb-4">
                            <ps-checkbox-value name="remember" v-model:checked="form.remember"
                                :title="$t('core__be_remember_me')" />
                            <ps-label class="cursor-pointer" textColor="text-fePrimary-500" v-if="canResetPassword"
                                @click="handleReset">
                                {{ $t('core__be_forgot_password') }}
                            </ps-label>
                        </div>

                        <div class="block mt-4">
                            <ps-button1 class="w-full mb-2">
                                <ps-loading v-if="form.processing"
                                    theme="border-2 border-t-2 border-text-8 border-t-fePrimary-500 me-1"
                                    loadingSize="h-5 w-5" />
                                {{ $t('core__be_btn_sign_in') }}
                            </ps-button1>
                        </div>
                    </form>
                </div>

            </ps-card>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, computed, onMounted, watch } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3';
import PsLabelHeader3 from "@template1/vendor/components/core/label/PsLabelHeader3.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import useValidators from "@/Validation/Validators";
import PsInput from "@template1/vendor/components/core/input/PsInput.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsLabel from "@template1/vendor/components/core/label/PsLabel.vue";
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsLabelCaption from "@template1/vendor/components/core/label/PsLabelCaption.vue";
import PsLabelTitle from "@template1/vendor/components/core/label/PsLabelTitle.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import { trans } from 'laravel-vue-i18n';
import firebase from "firebase/app";
import 'firebase/messaging'
import { useAuthStore } from '../../store/AuthStore';
import { getExistUser } from '@/Api/psApiService.js'
import UserExistParameterHolder from '@templateCore/object/holder/UserExistParameterHolder';
import PsConst from '@templateCore/object/constant/ps_constants';
import { router } from '@inertiajs/vue3';
import WelcomeImage from "@/Components/Svgs/WelcomeImage.vue";
import PsFrontendLayout from '@template1/vendor/components/layouts/container/PsFrontendLayout.vue';
import PsTextButton from "@template1/vendor/components/core/buttons/PsTextButton.vue";
import PsStatus from '@templateCore/api/common/PsStatus';
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';

import PsInput1 from "@template1/vendor/components/core/input/PsInput.vue";
import PsInputWithRightIcon1 from "@/Components/Core/Input/PsInputWithRightIcon.vue";
// import PsLabel from "@template1/vendor/components/core/label/PsLabel.vue";
import PsButton1 from "@template1/vendor/components/core/buttons/PsButton.vue";

// import PsCheckboxValue from '@template1/vendor/components/core/checkbox/PsCheckboxValue.vue';
import PsErrorDialog from '@template1/vendor/components/core/dialog/PsErrorDialog.vue';

import PrivacyModal from '@template1/vendor/components/modules/privacy/PrivacyModal.vue';
import { useThemeStore } from '../../store/Utilities/ThemeStore';
import { useLanguageStore } from '../../store/Localization/LanguageStore';
import { useNotificationStore } from '../../store/Notification/NotificationStore';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import { useNotiStoreState } from "@templateCore/store/modules/noti/NotificationStore";

// import UserExistParameterHolder from '../../../../Modules/TemplateCore/object/holder/UserExistParameterHolder';

export default defineComponent({
    components: {
        Head,
        Link,
        PsLabelHeader3,
        PsCard,
        PsInput,
        PsInputWithRightIcon,
        PsLabel,
        PsInput1,
        PsInputWithRightIcon1,
        PsButton1,
        PsButton,
        PsIcon,
        PsCheckboxValue,
        PsLabelCaption,
        PsLabelTitle,
        PsLoading,
        getExistUser,
        PsConst,
        WelcomeImage,
        PsTextButton,
        PsErrorDialog,
        PrivacyModal,
        PsFrontendLayout
    },

    props: {
        canResetPassword: Boolean,
        status: String,
        error: String,
        errors: Object,
        backendSetting: Object,
        firebaseConfig: String,
        webPushKey: String,
        appUrl: String,
        project: Object,
        tokenForFCM: String
    },


    data() {
        return {
            form: this.$inertia.form({
                email: '',
                password: '',
                remember: false,
                deviceToken: '',
                headerToken: '',
                loginMethod: '',
                google_id: '',
                facebook_id: '',
                apple_id: '',
                profile_photo_url: '',
                name: '',
                registerMethod: '',
            })
        }
    },
    setup(props) {

        const paramHolder = new UserExistParameterHolder();

        const email = ref();
        const password = ref();
        const isHide = ref(true);

        // const store = useStore();
        // const isDarkMode = computed(() => store.getters.isDarkMode);
        // const dir = computed(() => store.getters.dir);
        const themeStore = useThemeStore();

        const authStore = useAuthStore();
        const ps_error_dialog = ref();
        const privacy_modal = ref();
        const agreePrivacyPolicy = ref(false);
        const appInfoStore = usePsAppInfoStoreState();
        const languageStore = useLanguageStore();
        const notificationStore = useNotificationStore();
        let psValueStore = PsValueStore();
        const notiStore = useNotiStoreState();
        // const appinfo =appInfoStore.loadAppInfo()

        ///start firebase noti

        // const firebase = initializeApp({
        //     apiKey: "AIzaSyCCbP35UgAsfdnJaQM4TxWDLICYR4gDi28",
        //     authDomain: "flutter-admotors.firebaseapp.com",
        //     databaseURL: "https://flutter-admotors.firebaseio.com",
        //     projectId: "flutter-admotors",
        //     storageBucket: "flutter-admotors.appspot.com",
        //     messagingSenderId: "959099436187",
        //     appId: "1:959099436187:web:915d817bd3b509b1b81a3e"
        // });

        function isAcceptPrivacy(isAccept) {
            agreePrivacyPolicy.value = isAccept;
        }

        function agreePrivacyPolicyClicked() {
            privacy_modal.value.openModal(isAcceptPrivacy);
        }

        ///end firebase noti

        onMounted(async () => {
            // for dark or light mode local storage
            // store.dispatch('loadIsDarkMode');
            themeStore.initDarkMode();

            var loading = document. getElementById("home_loading__container");
            loading.style.display = "none";
            // for rtl or ltr directory switch local storage
            // store.dispatch('loadDirectory');

            // await appInfoStore.loadAppInfo();
            if(!localStorage.deviceToken || localStorage.deviceToken == "errorToken") {
                notificationStore.initFirebase(props.firebaseConfig);
                notificationStore.requestPermission();
                notificationStore.initMessageServieWorker(
                    props.appUrl,
                    props.webPushKey,
                    psValueStore,
                    route('firebase.topicSubscribeForNoti'),
                    appInfoStore,
                    notiStore,
                    PsConst.NO_LOGIN_USER
                );
            }
        });

        // Client Side Validation
        const { isEmpty, minLength, isEmail } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 4);
            if (fieldName == 'password') {
                password.value.isError = (props.errors.password == '') ? false : true;
            }
        };

        const validateEmailInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isEmail(fieldName, fieldValue);
            if (fieldName == 'email') {
                email.value.isError = (props.errors.email == '') ? false : true;
            }
        }

        //regex for domain url



        function singinUpClicked() {
            router.get(route('register'));
        }


        return {
            singinUpClicked,
            validateEmptyInput, validateEmailInput, email, password, isHide, authStore,
            themeStore, paramHolder, agreePrivacyPolicyClicked,
            ps_error_dialog, privacy_modal, agreePrivacyPolicy,
            appInfoStore,
            languageStore
        };
    },
    methods: {
        testmail() {
            // router.post('')
            router.post(route("send.mail.testing"))
        },

        // getDir(){
        //     if(localStorage.activeLanguage != null && localStorage.activeLanguage != undefined && localStorage.activeLanguage == 'ar'){
        //         return 'rtl';
        //     }else{
        //         return 'ltr';
        //     }
        // },

        async submit() {


            this.form.deviceToken = localStorage.deviceToken;
            const currentDate = new Date();
            localStorage.headerToken = localStorage.deviceToken + currentDate.getTime();
            this.form.headerToken = localStorage.headerToken;
            this.form.loginMethod = 'normal'


            // const url = "https://www.products.panacea-soft.co/mpc-fbs-admin-dev1";
            // const domain = await this.getDomainFromURL(url);


            // this.paramHolder.google_id = user.uid;
            this.paramHolder.email = this.form.email
            this.paramHolder.loginMethod = this.form.loginMethod





            // alert(domain);

            const status = await this.authStore.signInWithEmailAndPassword(PsConst.defaultEmail, PsConst.defaultPassword);

            if (status) {



                // this.form.loginMethod = 'normal';

                // this.form
                //     .transform(data => ({
                //         ...data,
                //     }))
                //     .post(this.route('CreateLogin'), {
                //         onFinish: () => this.form.reset('password'),
                //     })
                // if (this.form.newUser == 1) {


                this.form
                    .transform(data => ({
                        ...data,
                    }))
                    .post(this.route('login'), {

                    })
                // }

            }
            // this.form
            //     .transform(data => ({
            //         ...data,
            //         remember: this.form.remember ? 'on' : ''
            //     }))
            //     .post(this.route('login'), {
            //         onFinish: () => this.form.reset('password'),
            //     })


        },
        async googleiconclicked() {
            // alert(this.agreePrivacyPolicy);
            if (this.agreePrivacyPolicy) {
                await this.googleloginclicked();

            } else {
                this.ps_error_dialog.openModal(
                    trans('login__privacy_policy'),
                    trans('login__need_to_agree_privacy'));
            }
        },

        async phoneiconclicked() {
            // alert(this.agreePrivacyPolicy);
            if (this.agreePrivacyPolicy) {
                await this.phoneloginclicked();

            } else {
                this.ps_error_dialog.openModal(
                    trans('login__privacy_policy'),
                    trans('login__need_to_agree_privacy'));
            }
        },

        async facebookiconclicked() {
            // alert(this.agreePrivacyPolicy);
            if (this.agreePrivacyPolicy) {
                await this.facebookloginclicked();

            } else {
                this.ps_error_dialog.openModal(
                    trans('login__privacy_policy'),
                    trans('login__need_to_agree_privacy'));
            }
        },

        async appleiconclicked() {
            // alert(this.agreePrivacyPolicy);
            if (this.agreePrivacyPolicy) {
                await this.appleLoginClick();

            } else {
                this.ps_error_dialog.openModal(
                    trans('login__privacy_policy'),
                    trans('login__need_to_agree_privacy'));
            }
        },

        async appleLoginClick() {
            // if(agreePrivacyPolicy.value) {
            const user = await this.authStore.loginWithAppleSignIn();

            console.log(JSON.stringify(user));

            this.form.deviceToken = localStorage.deviceToken;
            const currentDate = new Date();
            localStorage.headerToken = localStorage.deviceToken + currentDate.getTime();
            this.form.headerToken = localStorage.headerToken;
            this.form.email = user.providerData[0].email;
            this.form.password = 'admin123'
            this.form.loginMethod = 'apple';
            this.form.apple_id = user.uid
            this.form.name = user.providerData[0].displayName
            this.form.profile_photo_url = user.photoURL
            this.form.registerMethod = 'apple';



            // api for exist user
            this.paramHolder.apple_id = user.uid;
            this.paramHolder.email = user.providerData[0].email;
            this.paramHolder.loginMethod = this.form.loginMethod




            const UserLogindata = await this.authStore.existUser(this.paramHolder);

            if (UserLogindata.data.status == "success") {

                this.form.newUser = UserLogindata.data.message.user_isexisted

                if (this.form.newUser == 2) {
                    // this.form.loginMethod = 'normal';

                    this.form
                        .transform(data => ({
                            ...data,
                        }))
                        .post(this.route('CreateLogin'), {
                            onFinish: () => this.form.reset('password'),
                        })
                }
                if (this.form.newUser == 1) {


                    this.form
                        .transform(data => ({
                            ...data,
                        }))
                        .post(this.route('login'), {
                            onFinish: () => this.form.reset('password'),
                        })
                }

            }
            else {
                this.ps_error_dialog.openModal(
                    trans('login_failed'),
                    trans('facebook_login_failed'));
            }
            // this.form.newUser=UserLogindata;


            // this.form
            //         .transform(data => ({
            //             ... data,
            //         }))
            //         .post(this.route('googleLogin'), {
            //             onFinish: () => this.form.reset('password'),
            //         })


        },




        async googleloginclicked() {
            // if(agreePrivacyPolicy.value) {
            const user = await this.authStore.loginWithGoogleId();
            this.form.deviceToken = localStorage.deviceToken;
            const currentDate = new Date();
            localStorage.headerToken = localStorage.deviceToken + currentDate.getTime();
            this.form.headerToken = localStorage.headerToken;
            this.form.email = user.providerData[0].email;
            this.form.password = 'admin123'
            this.form.loginMethod = 'google';
            this.form.google_id = user.uid
            this.form.name = user.providerData[0].displayName
            this.form.profile_photo_url = user.photoURL
            this.form.registerMethod = 'google';



            // api for exist user
            this.paramHolder.google_id = user.uid;
            this.paramHolder.email = user.providerData[0].email;
            this.paramHolder.loginMethod = this.form.loginMethod




            const UserLogindata = await this.authStore.existUser(this.paramHolder);

            if (UserLogindata.data.status == "success") {

                this.form.newUser = UserLogindata.data.message.user_isexisted

                if (this.form.newUser == 2) {
                    // this.form.loginMethod = 'normal';

                    this.form
                        .transform(data => ({
                            ...data,
                        }))
                        .post(this.route('CreateLogin'), {
                            onFinish: () => this.form.reset('password'),
                        })
                }
                if (this.form.newUser == 1) {


                    this.form
                        .transform(data => ({
                            ...data,
                        }))
                        .post(this.route('login'), {
                            onFinish: () => this.form.reset('password'),
                        })
                }

            }

            // this.form.newUser=UserLogindata;


            // this.form
            //         .transform(data => ({
            //             ... data,
            //         }))
            //         .post(this.route('googleLogin'), {
            //             onFinish: () => this.form.reset('password'),
            //         })


        },

        async facebookloginclicked() {
            // if(agreePrivacyPolicy.value) {
            const user = await this.authStore.loginWithFacebookId();

            this.form.deviceToken = localStorage.deviceToken;
            const currentDate = new Date();
            localStorage.headerToken = localStorage.deviceToken + currentDate.getTime();
            this.form.headerToken = localStorage.headerToken;
            this.form.email = user.providerData[0].email;
            this.form.password = 'admin123'
            this.form.loginMethod = 'facebook';
            this.form.facebook_id = user.uid
            this.form.name = user.providerData[0].displayName
            this.form.profile_photo_url = user.photoURL
            this.form.registerMethod = 'facebook';



            // api for exist user
            this.paramHolder.facebook_id = user.uid;
            this.paramHolder.email = user.providerData[0].email;
            this.paramHolder.loginMethod = this.form.loginMethod




            const UserLogindata = await this.authStore.existUser(this.paramHolder);

            if (UserLogindata.data.status == "success") {

                this.form.newUser = UserLogindata.data.message.user_isexisted

                if (this.form.newUser == 2) {
                    // this.form.loginMethod = 'normal';

                    this.form
                        .transform(data => ({
                            ...data,
                        }))
                        .post(this.route('CreateLogin'), {
                            onFinish: () => this.form.reset('password'),
                        })
                }
                if (this.form.newUser == 1) {


                    this.form
                        .transform(data => ({
                            ...data,
                        }))
                        .post(this.route('login'), {
                            onFinish: () => this.form.reset('password'),
                        })
                }

            }
            else {
                this.ps_error_dialog.openModal(
                    trans('login_failed'),
                    trans('facebook_login_failed'));
            }
            // this.form.newUser=UserLogindata;


            // this.form
            //         .transform(data => ({
            //             ... data,
            //         }))
            //         .post(this.route('googleLogin'), {
            //             onFinish: () => this.form.reset('password'),
            //         })


        },

        phoneloginclicked() {
            router.get(route('Phonelogin'))
        },

        handleReset() {
            this.$inertia.get(route('password.request'))
        }
    },
    watch: {
        async errors(value) {
            if (this.errors.user_need_verify == "1") {
                this.paramHolder.email = this.form.email
                this.paramHolder.loginMethod = "checkVerifyEmail"
                const UserLogindata = await this.authStore.existUser(this.paramHolder);
                if (UserLogindata.data.message.user_isexisted == 1) {
                    const map = {};
                    map['user_email'] = this.form.email;
                    const returnData = await this.authStore.postResendCode(map);
                    if (returnData.status == PsStatus.SUCCESS) {

                        router.post(route('verifyEmail'), useForm({ 'email': this.form.email, 'user_id': UserLogindata.data.message.user.id, 'password': this.form.password }))
                    } else {
                        this.ps_error_dialog.openModal('', returnData.message);
                    }
                }

            }
            // alert(this.errors.user_need_verify)
        }
    }
})
</script>
