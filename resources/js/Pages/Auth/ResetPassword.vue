<template>
    <Head :title="$t('core__fe_password_reset_title')" />
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
                        <ps-label class="justify-center mt-6 mb-8 text-2xl font-senibold">{{ $t('core__fe_password_reset_title') }}</ps-label>
                    </div>
                    <ps-label class="mb-12 text-sm " textColor="text-feSecondary-500 ">{{
                            $t('core__fe_password_reset_description') }}</ps-label>

                    <div>
                         <div class="mb-4">
                            <ps-label class="mb-2">{{ $t('core__fe_password_reset_password') }}</ps-label>
                            <ps-input-with-right-icon v-model:value="paramHolder.userPassword" ref="password"
                                placeholderColor="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                theme="bg-feAchromatic-50 dark:bg-feSecondary-800" :type="(isHide ? 'password' : 'text')"
                                :placeholder="$t('core__fe_password_reset_password_placeholder')" >
                                <template #icon>
                                    <ps-icon viewBox="0 0 24 24" @click="isHide = !isHide" class="cursor-pointer"
                                        theme="text-feSecondary-800 dark:text-feSecondary-300"
                                        :name="isHide ? 'eyeOff' : 'eye-on'" />
                                </template>
                            </ps-input-with-right-icon>

                        </div>

                        <div class="mb-4">
                            <ps-label class="mb-2">{{ $t('core__fe_password_reset_conf_password') }}</ps-label>
                            <ps-input-with-right-icon v-model:value="paramHolder.confPassword" ref="password"
                                placeholderColor="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                theme="bg-feAchromatic-50 dark:bg-feSecondary-800" :type="(isHide1 ? 'password' : 'text')"
                                :placeholder="$t('core__fe_password_reset_conf_password_placeholder')" >
                                <template #icon>
                                    <ps-icon viewBox="0 0 24 24" @click="isHide1 = !isHide1" class="cursor-pointer"
                                        theme="text-feSecondary-800 dark:text-feSecondary-300"
                                        :name="isHide1 ? 'eyeOff' : 'eye-on'" />
                                </template>
                            </ps-input-with-right-icon>

                        </div>

                        <div class="block mt-12">
                            <ps-button class="w-full mb-2"  @click="submitClicked">
                                <ps-loading v-if="authStore.loading.value"
                                    theme="border-2 border-t-2 border-text-8 border-t-fePrimary-500 me-1"
                                    loadingSize="h-5 w-5" />
                                {{ $t('core__fe_password_reset_submit') }}
                            </ps-button>
                        </div>

                        <div class="block mt-6 mb-2 lg:mb-44">
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
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import { ref,reactive , computed} from 'vue';
import { trans } from 'laravel-vue-i18n';
import { Head, Link } from '@inertiajs/vue3';
import PsLabelHeader3 from "@template1/vendor/components/core/label/PsLabelHeader3.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
// Params Holders
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabelCaption from "@template1/vendor/components/core/label/PsLabelCaption.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import WelcomeImage from "@/Components/Svgs/WelcomeImage.vue";
import PsFrontendLayout from '@template1/vendor/components/layouts/container/PsFrontendLayout.vue';
import ResetPasswordParameterHolder from '@templateCore/object/holder/ResetPasswordParameterHolder';
import PsStatus from '@templateCore/api/common/PsStatus';
import PsConst from '@templateCore/object/constant/ps_constants';
// import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
import { router } from '@inertiajs/vue3';

import { useAuthStore } from '../../store/AuthStore';
import { useThemeStore } from '../../store/Utilities/ThemeStore';

    export default {
        components: {
            Head,
            PsCard,
            PsInput,
            PsLabel,
            PsLabelHeader3,
            PsButton,
            PsLabelCaption,
            PsErrorDialog,
            PsLoadingDialog,
            PsSuccessDialog,
            PsLabelTitle,
            PsIcon,
            Link,
            PsLabelCaption2,
            PsLoading,
            WelcomeImage,
            PsSecondaryButton,
            PsInputWithRightIcon
        },
         layout: PsFrontendLayout,
        props: ['id','code' , 'authUser'],
        setup(props) {

            // Client Side Validation
           const paramHolder = reactive(new ResetPasswordParameterHolder());
           paramHolder.userId = props.id;
           paramHolder.code = props.code;

           const isHide1 =ref(true);
           const isHide =ref(true);

        //    const store = useStore();
        //     const isDarkMode = computed(() => store.getters.isDarkMode);
        //     const dir = computed(() => store.getters.dir);
        const themeStore = useThemeStore();

           const ps_loading_dialog = ref();
            const ps_error_dialog = ref();
            const ps_success_dialog = ref();


            // Init Provider
            const authStore = useAuthStore();

            async function submitClicked(){
                // Validation
            if(paramHolder.userPassword.trim() == '') {
                ps_error_dialog.value.openModal(trans('ps_error_dialog__error'),
                trans('core__fe_verify_psw_required_error'),trans('core__fe_btn_ok'),()=>{});
                return;
            }

            if(paramHolder.confPassword.trim() == '') {
                ps_error_dialog.value.openModal(trans('ps_error_dialog__error'),
                trans('core__fe_verify_confirm_psw_required_error'),trans('core__fe_btn_ok'),()=>{});
                return;
            }

            if(paramHolder.userPassword.trim() != paramHolder.confPassword.trim()) {
                ps_error_dialog.value.openModal(trans('ps_error_dialog__error'),
                trans('core__fe_verify_psws_mot_same_error'),trans('core__fe_btn_ok'),()=>{});
                return;
            }

            ps_loading_dialog.value.closeModal();

            const returnData = await authStore.postResetPassword(paramHolder);
            ps_loading_dialog.value.closeModal();

            if(returnData.status == PsStatus.ERROR) {

                ps_error_dialog.value.openModal(trans('ps_error_dialog__error'),
                returnData.message,trans('core__fe_btn_ok'),()=>{});
            }else if(returnData.status == PsStatus.SUCCESS) {
                // psValueStore.replaePassword(
                //                     paramHolder.userPassword
                // );
                ps_success_dialog.value.openModal(trans('core__fe_verify_login_success'),
                trans('core__fe_verify_psw_update_success'),trans('core__fe_verify_continue'),()=>{
                    router.get(route('login'));
                });

            }
            }

            function loginClicked() {
                // Redirect
                router.get(route('login'))
                // authStore.navigateUserLoginTo("login", router, route.query.redirect, route.query, route.params);
            }

            return {
                loginClicked,
                paramHolder,
                submitClicked,
                authStore,
                ps_loading_dialog,
                ps_error_dialog,
                ps_success_dialog,
                themeStore,
                isHide,
                isHide1
            }
        },
    }
</script>
