<template>
    <Head :title="$t('core__fe_email_verification_title')" />
    <div :class="themeStore.isDarkMode ? 'dark' : ''">
        <div class="flex flex-row items-center justify-center min-h-screen mb-6 bg-feAchromatic-50 dark:bg-feAchromatic-900">
            <div class="flex flex-row h-auto overflow-hidden rounded-md shadow-md sm:mt-32 lg:mt-36 mt-28 w-98 lg:w-202 ">
                <div class="flex-col items-center justify-center hidden h-auto w-100 bg-feSecondary-900 lg:flex">
                    <div class="w-65 h-50">
                        <welcome-image />
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
                <div class="w-full px-4 pb-10 lg:w-100 bg-feAchromatic-50 dark:bg-feSecondary-800 sm:px-10">
                    <!-- <img v-if="$page.props.backendLogo" :src="$page.props.uploadUrl + '/' + $page.props.backendLogo.img_path" width="50" height="50" class="m-auto my-2.5"/> -->

                    <div class="flex justify-center">
                        <ps-label class="justify-center mt-6 mb-6 text-2xl font-senibold">{{ $t('core__fe_email_verification_title') }}</ps-label>
                    </div>
                    <ps-label class="mb-6 text-sm " textColor="text-feSecondary-500 ">{{
                            $t('core__fe_user_email_verification_title_description') }}</ps-label>

                    <div>
                        <div class="mb-4">
                            <ps-label class="mb-2 text-sm" textColor="text-feSecondary-800 dark:text-feSecondary-200">{{ $t('core__fe_verify_code') }}</ps-label>
                            <ps-input type="text" v-on:keyup.enter="clicked" v-model:value="code"
                                class="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                theme="text-feSecondary-500 bg-feAchromatic-50 dark:bg-feSecondary-800"
                                defaultBorder="border border-feSecondary-200 dark:border-feSecondary-400 focus:outline-none focus:ring-1 focus:ring-fePrimary-500"
                                :placeholder="$t('core__fe_verify_code_placeholder')" autofocus />
                            <!-- <ps-label-caption textColor="text-feError-500 " class="block mt-2">{{ errors.email
                            }}</ps-label-caption> -->
                        </div>

                        <div class="block mt-4 ">
                            <span
                            class="text-sm border-b cursor-pointer text-fePrimary-500 border-fePrimary-500"
                            @click="resentClicked">
                                {{ $t('core__fe_verify_resent') }}
                            </span>
                        </div>

                        <div class="block mt-6 lg:mt-56">
                            <ps-button class="w-full mb-2"  @click="clicked">
                                <ps-loading v-if="authStore.loading.value"
                                    theme="border-2 border-t-2 border-text-8 border-t-fePrimary-500 me-1"
                                    loadingSize="h-5 w-5" />
                                {{ $t('core__fe_verify_code_verify') }}
                            </ps-button>
                        </div>

                        <div class="block mt-6 mb-2 lg:mb-6">
                            <ps-feSecondary-button
                            colors="bg-feAchromatic-50 dark:bg-feSecondary-800 text-feSecondary-800 dark:text-feSecondary-50"
                            @click="loginClicked" class="w-full">
                                {{ $t('core__fe_verify_cancel') }}
                            </ps-feSecondary-button>
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <ps-loading-dialog ref="ps_loading_dialog"  :isClickOut='false'/>
    <ps-success-dialog ref="ps_success_dialog" />
    <ps-error-dialog ref="ps_error_dialog" />
</template>

<script lang="ts">
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsLabelCaption2 from '@template1/vendor/components/core/label/PsLabelCaption2.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';

import PsSecondaryButton from '@template1/vendor/components/core/buttons/PsSecondaryButton.vue';
import PsInput from '@template1/vendor/components/core/input/PsInput.vue';
import PsLoadingDialog from '@template1/vendor/components/core/dialogs/PsLoadingDialog.vue';
import PsSuccessDialog from '@template1/vendor/components/core/dialogs/PsSuccessDialog.vue';
import PsErrorDialog from '@template1/vendor/components/core/dialogs/PsErrorDialog.vue';

import { ref } from 'vue';
import { trans } from 'laravel-vue-i18n';

import { Head, Link,useForm } from '@inertiajs/vue3';
import PsLabelHeader3 from "@template1/vendor/components/core/label/PsLabelHeader3.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
// Params Holders
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabelCaption from "@template1/vendor/components/core/label/PsLabelCaption.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import WelcomeImage from "@/Components/Svgs/WelcomeImage.vue";
import PsFrontendLayout from '@template1/vendor/components/layouts/container/PsFrontendLayout.vue';

//Modules
import PsStatus from '@templateCore/api/common/PsStatus';

import PsConst from '@templateCore/object/constant/ps_constants';
// import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
import { useAuthStore } from '../../store/AuthStore';
import { router } from '@inertiajs/vue3';

// Params Holders
import UserEmailVerifyParameterHolder from '@templateCore/object/holder/UserEmailVerifyParameterHolder';
import { useThemeStore } from '../../store/Utilities/ThemeStore';

export default {
    class: "UserVerifyEmail",
    components : {
        PsLabel,
        PsLabelTitle,
        PsButton,
        PsInput,
        PsLoadingDialog,
        PsSuccessDialog,
        PsErrorDialog,
        PsLabelCaption,
        PsIcon,
        PsLabelHeader3,
        PsCard,
        Head,
        Link,
        PsLabelCaption2,
        PsLoading,
        WelcomeImage,
        PsSecondaryButton
    },
    layout: PsFrontendLayout,
    props: ['email','password','user_id','flag','error_validation','authUser'],
    setup(props) {

        // Inject Provider
        const authStore = useAuthStore();
        const holder = new UserEmailVerifyParameterHolder();
        const ps_loading_dialog = ref();
        const ps_success_dialog = ref();
        const ps_error_dialog = ref();
        // const psValueStore = PsValueStore();
        // const loginUserId = psValueStore.getLoginUserId();
        const code = ref();

        if(props.authUser != null){
            router.get(route('dashboard'))
        }

        // const store = useStore();
        // const isDarkMode = computed(() => store.getters.isDarkMode);
        // const dir = computed(() => store.getters.dir);
        const themeStore = useThemeStore();

        async function clicked() {
            if(props.flag == 'error'){
                ps_error_dialog.value.openModal(trans('ps_error_dialog__error'), props.error_validation,trans('core__fe_btn_ok'),()=>{});
            }else{
                holder.userId = props.user_id;
                holder.code = code.value;
                holder.email = '';


                ps_loading_dialog.value.closeModal();
                // forgot password
                const returnData = await authStore.postUserEmailVerify(holder);

                ps_loading_dialog.value.closeModal();
                if(returnData.status == PsStatus.SUCCESS) {
                    ps_success_dialog.value.openModal(trans('core__fe_user_email_verify_success'), trans('core__fe_user_email_verify_success_message'),trans('core__fe_user_email_veriy_continue'),()=>{
                        login();
                    });
                } else {
                    ps_error_dialog.value.openModal(trans('ps_error_dialog__error'), returnData.message,trans('core__fe_btn_ok'),()=>{});
                }
            }

        }

        async function login(){
            let form = useForm({
                email: "",
                password: "",
                deviceToken: '',
                headerToken: '',
                loginMethod: '',
            });

            form.loginMethod = 'normal';
            form.email = props.email;
            form.password = props.password;

            form.deviceToken = localStorage.deviceToken;
            form.headerToken = localStorage.headerToken;
            form.loginMethod = 'normal';

            const status = await authStore.signInWithEmailAndPassword(form.email, form.email);
            if (status) {
                form
                    .transform(data => ({
                        ...data,
                    }))
                    .post(route('login'), {
                        onFinish: () => form.reset('password'),
                    })
            }
        }

        async function resentClicked(){
            const map = {};
            map['user_email'] = props.email;


            ps_loading_dialog.value.closeModal();
            // forgot password
            const returnData = await authStore.postResendCode(map);

            ps_loading_dialog.value.closeModal();
            if(returnData.status == PsStatus.SUCCESS) {
                ps_success_dialog.value.openModal(trans('ps_success_dialog__success'), returnData.data.message,trans('core__fe_btn_ok'),()=>{

                });
            } else {
                ps_error_dialog.value.openModal(trans('ps_error_dialog__error'), returnData.message,trans('core__fe_btn_ok'),()=>{});
            }
        }

        function loginClicked() {
            // Redirect
            router.get(route('login'))
            // authStore.navigateUserLoginTo("login", router, route.query.redirect, route.query, route.params);
        }
        return{
            authStore,
            code,
            clicked,
            ps_error_dialog,
            ps_success_dialog,
            ps_loading_dialog,
            resentClicked,
            loginClicked,
            themeStore
        }
    }
}
</script>
