<template>
    <Head :title="$t('core__fe_register')" />
    <div :class="themeStore.isDarkMode ? 'dark' : ''">
        <div class="flex flex-row items-center justify-center min-h-screen mb-6 bg-feAchromatic-50 dark:bg-feAchromatic-900">
            <div class="flex flex-row h-auto overflow-hidden rounded-md shadow-md sm:mt-32 lg:mt-36 mt-28 w-98 lg:w-202 ">
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
                <div class="w-full px-4 pb-10 lg:w-100 bg-feAchromatic-50 dark:bg-feSecondary-800 sm:px-10">
                    <!-- <img v-if="$page.props.backendLogo" :src="$page.props.uploadUrl + '/' + $page.props.backendLogo.img_path" width="50" height="50" class="m-auto my-2.5"/> -->

                    <div class="flex justify-center">
                        <ps-label class="justify-center mt-6 mb-6 text-2xl font-senibold">{{ $t('core__fe_register_title') }}</ps-label>
                    </div>

                    <div>
                        <div class="mb-4">
                            <ps-label class="mb-2" textColor="text-feSecondary-800 dark:text-feSecondary-200">{{ $t('core__fe_name') }}</ps-label>
                            <ps-input type="text" v-model:value="form.name" @keypress="nameError = ''"
                            class="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                theme="text-feSecondary-500 dark:bg-feSecondary-800"
                                defaultBorder="border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focusr_border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400"
                                :placeholder="$t('core__fe_name_placeholder')" autofocus />
                                 <ps-label class="mt-2 text-xs" textColor=" text-feSecondary-400 dark:text-feSecondary-300">{{ $t('core__fe_name_descriotion') }}</ps-label>
                            <ps-label-caption textColor="text-feError-500 "
                                class="block mt-2">{{ nameError }}</ps-label-caption>
                        </div>
                        <div class="mb-4">
                            <ps-label class="mb-2" textColor="text-feSecondary-800 dark:text-feSecondary-200">{{ $t('core__fe_username') }}</ps-label>
                            <ps-input  type="text" v-model:value="form.username" @keypress="usernameError = ''"
                            class="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                theme="text-feSecondary-500 dark:bg-feSecondary-800"
                                defaultBorder="border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focusr_border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400"
                                :placeholder="$t('core__fe_username_placeholder')"  />
                                 <ps-label class="mt-2 text-xs" textColor=" text-feSecondary-400 dark:text-feSecondary-300">{{ $t('username__fe_valid_description') }}</ps-label>
                            <ps-label-caption textColor="text-feError-500 "
                                class="block mt-2">{{ usernameError }}</ps-label-caption>
                        </div>
                        <div class="mb-4">
                            <ps-label class="mb-2" textColor="text-feSecondary-800 dark:text-feSecondary-200">{{ $t('core__fe_email') }}</ps-label>
                            <ps-input  type="email" v-model:value="form.email" @keypress="emailError = ''"
                            class="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                theme="text-feSecondary-500 dark:bg-feSecondary-800"
                                defaultBorder="border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focusr_border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400"
                                :placeholder="$t('core__fe_email_placeholder')"  />
                            <ps-label-caption textColor="text-feError-500 "
                                class="block mt-2">{{ emailError }}</ps-label-caption>
                        </div>
                        <div class="mb-4">
                            <ps-label class="mb-2" textColor="text-feSecondary-800 dark:text-feSecondary-200">{{ $t('core__fe_password') }}</ps-label>
                            <ps-input-with-right-icon v-model:value="form.password" ref="password" @keypress="pswError = ''"
                                placeholderColor="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                theme="bg-feAchromatic-50 dark:bg-feSecondary-800"
                                defaultBorder="border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focusr_border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400"
                                :type="(isHide ? 'password' : 'text')"
                                :placeholder="$t('core__fe_password_placeholder')" autocomplete="current-password">
                                <template #icon>
                                    <ps-icon @click="isHide = !isHide" class="cursor-pointer"
                                        :name="isHide ? 'eyeOff' : 'eye-on'" />
                                </template>
                            </ps-input-with-right-icon>
                            <ps-label-caption textColor="text-feError-500 "
                                class="block mt-2">{{ pswError }}</ps-label-caption>
                        </div>
                        <div class="mb-8">
                            <ps-label class="mb-2" textColor="text-feSecondary-800 dark:text-feSecondary-200">{{ $t('core__fe_conf_password') }}</ps-label>
                            <ps-input-with-right-icon v-model:value="form.password_confirmation" ref="conf_password" @keypress="confpswError = ''"
                                placeholderColor="placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                                theme="bg-feAchromatic-50 dark:bg-feSecondary-800"
                                defaultBorder="border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focusr_border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400"
                                :type="(isHide1 ? 'password' : 'text')"
                                :placeholder="$t('core__fe_conf_password_placeholder')" autocomplete="current-password">
                                <template #icon>
                                    <ps-icon @click="isHide1 = !isHide1" class="cursor-pointer"
                                        :name="isHide1 ? 'eyeOff' : 'eye-on'" />
                                </template>
                            </ps-input-with-right-icon>
                            <ps-label-caption textColor="text-feError-500 "
                                class="block mt-2">{{ confpswError }}</ps-label-caption>
                        </div>


                        <div class='flex flex-row items-start justify-start mb-6'>
                            <ps-checkbox-value title="" class="" v-model:value="agreePrivacyPolicy"
                                color="focus:ring-2 focus:ring-fePrimary-300 hover:bg-fePrimary-500 dark:hover:bg-fePrimary-500 checked:bg-fePrimary-500 dark:checked:bg-fePrimary-500 text-fePrimary-500 dark:text-fePrimary-500"
                                textColor="text-fePrimary-500 dark:text-fePrimary-500"
                                @click="agreePrivacyPolicyClicked" />
                            <ps-label class='text-sm me-2 ' textColor="text-feSecondary-800 dark:text-feSecondary-200">{{ $t("core__fe_login__check_privacy_policy")
                            }}</ps-label>

                            <ps-label textColor="text-fePrimary-500" class='text-sm '> {{ $t("login__privacy_policy")
                            }}</ps-label>

                        </div>

                        <div class="block mt-8">
                            <ps-button class="w-full mb-2"  @click="submit">
                                <ps-loading v-if="loading"
                                    theme="border-2 border-t-2 border-text-8 border-t-fePrimary-500 me-1"
                                    loadingSize="h-5 w-5" />
                                {{ $t('core__fe_btn_register') }}
                            </ps-button>
                        </div>

                        <div class="flex flex-row justify-center my-8 ">
                            <ps-label class="me-4" textColor="text-feSecondary-800 dark:text-feSecondary-500">
                                {{ $t('core__fe_already_a_member') }}
                            </ps-label>
                            <ps-label class="cursor-pointer" textColor="text-fePrimary-500" @click="singinInClicked">
                                {{ $t('core__fe_sign_in') }}
                            </ps-label>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>
    <ps-error-dialog ref="ps_error_dialog" />
    <privacy-modal ref="privacy_modal" />
</template>

<script lang="ts">

import PsLabelCaption2 from '@template1/vendor/components/core/label/PsLabelCaption2.vue';
import PsErrorDialog from '@template1/vendor/components/core/dialogs/PsErrorDialog.vue';
import { trans } from 'laravel-vue-i18n';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabelCaption from "@template1/vendor/components/core/label/PsLabelCaption.vue";
import WelcomeImage from "@/Components/Svgs/WelcomeImage.vue";
import PsFrontendLayout from '@template1/vendor/components/layouts/container/PsFrontendLayout.vue';
import { defineComponent,onMounted,ref,computed } from "vue";
import PsLabelHeader3 from "@template1/vendor/components/core/label/PsLabelHeader3.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import useValidators from "@/Validation/Validators";
import PsInput from "@template1/vendor/components/core/input/PsInput.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsLabel from "@template1/vendor/components/core/label/PsLabel.vue";
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsLabelTitle from "@template1/vendor/components/core/label/PsLabelTitle.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import { Head, Link ,useForm} from "@inertiajs/vue3";
import firebase from "firebase/app";
import 'firebase/messaging'
import { useAuthStore } from '../../store/AuthStore';
import { getExistUser } from '@/Api/psApiService.js'
import PsConst from '@templateCore/object/constant/ps_constants';
import UserRegisterParameterHolder from '@templateCore/object/holder/UserRegisterParameterHolder.ts';
import PsStatus from '@templateCore/api/common/PsStatus';
import { router } from '@inertiajs/vue3';
import PrivacyModal from '@template1/vendor/components/modules/privacy/PrivacyModal.vue';
import axios from 'axios';
import { useThemeStore } from '../../store/Utilities/ThemeStore';
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';
import { useNotificationStore } from '../../store/Notification/NotificationStore';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import { useNotiStoreState } from "@templateCore/store/modules/noti/NotificationStore";
import { route } from 'ziggy-js';

export default defineComponent({
  components: {
    Head,
     PsLabelHeader3,
    PsCard,
    PsInput,
    PsInputWithRightIcon,
    PsLabel,
    PsButton,
    PsIcon,
    PsCheckboxValue,
    PsLabelCaption,
    PsLabelTitle,
    PsLoading,
    Link,
    PsErrorDialog,
    PsLabelCaption2,
    WelcomeImage,
    PrivacyModal

  },
  layout : PsFrontendLayout,
  props: ['backendSetting','firebaseConfig','webPushKey', 'appUrl', 'project', 'tokenForFCM'],
  setup(props){
    const authStore = useAuthStore();
    const paramHolder = new UserRegisterParameterHolder();
    const usernameError = ref('');
    const nameError = ref('');
    const pswError = ref('');
    const confpswError = ref('');
    const emailError = ref('');
    const displayNameError = ref('');
    const ps_error_dialog = ref();
    const loading = ref(false);
    const agreePrivacyPolicy = ref(false);
    //  const store = useStore();
    // const isDarkMode = computed(() => store.getters.isDarkMode);
    // const dir = computed(() => store.getters.dir);
    const themeStore = useThemeStore();
    const appInfoStore = usePsAppInfoStoreState();
    const privacy_modal = ref();
    const notificationStore = useNotificationStore();
    let psValueStore = PsValueStore();
    const notiStore = useNotiStoreState();

    const isHide = ref(true);
    const isHide1 = ref(true);

    let form = useForm({
        name: "",
        username: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
        deviceToken: '',
        headerToken: '',
        loginMethod: '',
        google_id: '',
        profile_photo_url: '',
        registerMethod: '',
    });

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


        async function submit() {

            //validation
            let errorStatus = true;

            if(form.name == '' || form.name == undefined){
                nameError.value = 'Name field is required';
                errorStatus = false;
            }

            if(form.username == '' || form.username == undefined){
                usernameError.value = 'User Name field is required';
                errorStatus = false;
            }

            if(form.password_confirmation == '' || form.password_confirmation == undefined){
                confpswError.value = 'Confirm Password field is required';
                errorStatus = false;
            }

            if(form.email == '' || form.email == undefined){
                emailError.value = 'Email field is required';
                errorStatus = false;
            }

            if(form.password == '' || form.password == undefined){
                pswError.value = 'Password field is required';
                errorStatus = false;
            }

            if(form.password !== form.password_confirmation){
                confpswError.value = 'Password and confirm password need to be same';
                errorStatus = false;
            }

            // End
            if(errorStatus != true) {
                window.scrollTo(0, 0);
                return;
            }

            if (!agreePrivacyPolicy.value) {
                ps_error_dialog.value.openModal(
                    trans('login__privacy_policy'),
                    trans('login__need_to_agree_privacy'),
                    trans('core__fe_btn_ok'),
                    ()=>{
                        agreePrivacyPolicyClicked();
                    });
                return;
            }

            loading.value = true;
            const currentDate = new Date();
            localStorage.headerToken = localStorage.deviceToken + currentDate.getTime();
            const headerToken = localStorage.headerToken;


            paramHolder.userEmail = form.email;
            paramHolder.userName = form.username;
            paramHolder.name = form.name;
            paramHolder.userPassword = form.password;
            paramHolder.name = form.name;
            paramHolder.deviceToken = localStorage.deviceToken;
            paramHolder.platformName = 'Browser';
            paramHolder.deviceInfo = 'Browser';
            paramHolder.deviceId = localStorage.deviceToken;

            const user = await authStore.registerUser(paramHolder);

            if(user.status == PsStatus.SUCCESS){
                if(user.data.needVerify == 1){
                    loading.value=false;
                    router.post(route('verifyEmail'),useForm({'email': form.email,'user_id': user.data.userId,'password': form.password}))
                }else{
                    form.loginMethod = 'normal';

                    form.deviceToken = localStorage.deviceToken;
                    form.headerToken = headerToken;
                    form.loginMethod = 'normal';

                    const status = await authStore.signInWithEmailAndPassword(form.email, form.email);
                    if (status) {
                        loading.value=false;
                        form
                            .transform(data => ({
                                ...data,
                            }))
                            .post(route('login'), {
                                onFinish: () => form.reset('password'),
                            })
                    }
                }
            }else{
                ps_error_dialog.value.openModal(trans('ps_error_dialog__error'),user.message,trans('core__fe_btn_ok'),()=>{});
            }
            loading.value=false;

        }

        function singinInClicked(){
            router.get(route('login'));
        }


        return{
            singinInClicked,
            authStore,
            paramHolder,
            usernameError,
            emailError,
            displayNameError,
            submit,
            form,
            ps_error_dialog,
            themeStore,
            nameError,
            pswError,
            confpswError,
            isHide,
            isHide1,
            loading,
            agreePrivacyPolicyClicked,
            agreePrivacyPolicy,
            privacy_modal

        }

  },
});
</script>
