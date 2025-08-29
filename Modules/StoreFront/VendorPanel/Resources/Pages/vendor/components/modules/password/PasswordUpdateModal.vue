<template>
    <ps-modal ref="psmodal" theme="p-2 dark:bg-feAchromatic-50 dark:bg-feSecondary-900 border rounded-xl" maxWidth="408px" :visibleLine="false" :isClickOut='true' class="z-50" >
        <template #title>
            <ps-icon @click="closeModal()" name="close" class="text-sm text-feSecondary-400 ms-auto my-auto focus:shadow-none hover:text-purple-500 flex justify-end"  />

            <ps-label class="text-center text-xl font-semibold" > {{ $t('password_update_modal__update_password') }}</ps-label>
        </template>
        <template #body>
           <div class="flex justify-between w-68 sm:w-80 mx-auto">
                <!-- Start Left Screen -->
                <div class="flex flex-col w-full mt-5">
                    
                     <!-- for Password -->
                    <ps-label class="mt-5 mb-2  text-sm"> {{ $t('password_update_modal__old_password') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                    <ps-input-with-right-icon @keypress="old_psw_error = ''"  v-model:value="paramHolder.userOldPassword" 
                        className="dark:bg-feSecondary-900 w-full text-sm shadow-sm"
                        placeholderColor=" placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                        :type="(isHide ? 'password' : 'text')"
                        :placeholder="$t('password_update_modal__old_password_placeholder')">
                        <template #icon>
                            <ps-icon :h="24" :w="24" @click="isHide = !isHide" class="cursor-pointer"
                                theme=" text-feSecondary-800 dark:text-feSecondary-300"
                                :name="isHide ? 'eyeOff' : 'eye-on'" />
                        </template>
                    </ps-input-with-right-icon>
                    <ps-label-caption textColor="text-feError-500 "
                                class="mt-1 block">{{ old_psw_error }}</ps-label-caption>
                            
                    <!-- <ps-input class="mt-2 "  id="oldpassword" type="password" :placeholder="$t('password_update_modal__old_password')" v-model:value="paramHolder.userOldPassword" :maxlength='19' /> -->
                    <!-- for Password -->
                    <ps-label class="mt-5 mb-2 text-sm"> {{ $t('password_update_modal__password') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                    <ps-input-with-right-icon
                    className="dark:bg-feSecondary-900 w-full text-sm shadow-sm"
                     @keypress="new_psw_error = ''" v-model:value="paramHolder.userPassword" 
                        placeholderColor=" placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                        :type="(isHide1 ? 'password' : 'text')"
                        :placeholder="$t('password_update_modal__password_placeholder')">
                        <template #icon>
                            <ps-icon :h="24" :w="24" @click="isHide1 = !isHide1" class="cursor-pointer"
                                theme=" text-feSecondary-800 dark:text-feSecondary-300"
                                :name="isHide1 ? 'eyeOff' : 'eye-on'" />
                        </template>
                    </ps-input-with-right-icon>
                    <ps-label-caption textColor="text-feError-500 "
                                class="mt-1 block">{{ new_psw_error }}</ps-label-caption>

                    <!-- for Confirm Password -->
                    <ps-label class="mt-5 mb-2 text-sm "> {{ $t('password_update_modal__confirm_password') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                    <ps-input-with-right-icon 
                    className="dark:bg-feSecondary-900 w-full text-sm shadow-sm"
                    @keypress="conf_psw_error = ''" v-model:value="paramHolder.confPassword" 
                        placeholderColor=" placeholder-feSecondary-800 dark:placeholder-feSecondary-500"
                        :type="(isHide2 ? 'password' : 'text')"
                        :placeholder="$t('password_update_modal__confirm_password_placeholder')">
                        <template #icon>
                            <ps-icon :h="24" :w="24" @click="isHide2 = !isHide2" class="cursor-pointer"
                                theme=" text-feSecondary-800 dark:text-feSecondary-300"
                                :name="isHide2 ? 'eyeOff' : 'eye-on'" />
                        </template>
                    </ps-input-with-right-icon>
                    <ps-label-caption textColor="text-feError-500 "
                                class="mt-1 block">{{ conf_psw_error }}</ps-label-caption>

                    
                    
                </div>   
                <!-- End Left Screen -->

            </div>
        </template>
        <template #footer>
            <div class="flex mt-8 flex-col w-68 sm:w-80 mx-auto">
                <ps-button class="text-center" @click="submitClicked" > {{ $t('password_update_modal__update') }} </ps-button>
                <ps-secondary-button class="mt-4 mb-2 text-center" @click="closeModal()" > {{ $t('password_update_modal__cancel') }} </ps-secondary-button>

            </div>

        </template>
    </ps-modal>

    <ps-loading-dialog ref="psloading"  :isClickOut='false'/> 

    <ps-error-dialog ref="ps_error_dialog" />
    <ps-success-dialog ref="ps_success_dialog" />

</template>

<script lang='ts'>

// Libs
import { defineComponent,ref,reactive } from 'vue';

// Compone
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsLabelCaption from '@template1/vendor/components/core/label/PsLabelCaption.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsSecondaryButton from '@template1/vendor/components/core/buttons/PsSecondaryButton.vue';
import PsErrorDialog from '@template1/vendor/components/core/dialogs/PsErrorDialog.vue';
import PsSuccessDialog from '@template1/vendor/components/core/dialogs/PsSuccessDialog.vue';
import PsLoadingDialog from '@template1/vendor/components/core/dialogs/PsLoadingDialog.vue';
import PsInput from '@template1/vendor/components/core/input/PsInput.vue';
import ChangePasswordParameterHolder from '@templateCore/object/holder/ChangePasswordParameterHolder';
import { useUserStore } from '@templateCore/store/modules/user/UserStore';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import PsStatus from '@templateCore/api/common/PsStatus';
import { trans } from 'laravel-vue-i18n';
import Message from '../../../../../../../../TemplateCore/object/Message';
import { router } from '@inertiajs/vue3';
import PsIcon from "@template1/vendor/components/core/icons/PsIcon.vue";
import PsInputWithRightIcon from "@template1/vendor/components/core/input/PsInputWithRightIcon.vue";

export default defineComponent({
    name: "PasswordUpdateModal",
    components: {
        PsModal,
        PsLabel,
        PsButton,
        PsErrorDialog,
        PsLoadingDialog,
        PsInput,
        PsLabelTitle,
        PsSuccessDialog,
        PsSecondaryButton,
        PsIcon,
        PsInputWithRightIcon,
        PsLabelCaption
    },
    setup() {

        const psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();
        const isHide = ref(true);
        const isHide1 = ref(true);
        const isHide2 = ref(true);

        const psmodal = ref();
        const psloading = ref();
        const ps_error_dialog = ref();
        const ps_success_dialog = ref();

        const old_psw_error = ref('');
        const new_psw_error = ref('');
        const conf_psw_error = ref('');
        
        const paramHolder = reactive(new ChangePasswordParameterHolder());

        // Init Provider
        const userProvider = useUserStore();

        async function initData() {
            psloading.value.openModal();

            // Load User By ID List
            await userProvider.loadUser(loginUserId);
           
            psloading.value.closeModal();
        }
     
        async function openModal() {

            psmodal.value.toggle(true);
            await initData();
                        
        }

        function closeModal(){
            psmodal.value.toggle(false);
        }

        async function submitClicked() {

            // Validation
            if(paramHolder.userPassword.trim() == '') {
                new_psw_error.value = trans('password_update_modal__psw_required_error');
                // ps_error_dialog.value.openModal(trans('password_update_modal__error'), 
                // trans('password_update_modal__psw_required_error'),
                // trans('password_update_modal__ok'),
                // ()=>{});
                return;
            }

            if(paramHolder.confPassword.trim() == '') {
                conf_psw_error.value = trans('password_update_modal__confirm_psw_required_error');
                // ps_error_dialog.value.openModal(trans('password_update_modal__error'), 
                // trans('password_update_modal__confirm_psw_required_error'),
                // trans('password_update_modal__ok'),
                // ()=>{});
                return;
            }

            if(paramHolder.userOldPassword.trim() == '') {
                old_psw_error.value = trans('password_update_modal__old_psw_required_error');
                // ps_error_dialog.value.openModal(trans('password_update_modal__error'), 
                // trans('password_update_modal__old_psw_required_error'),
                // trans('password_update_modal__ok'),
                // ()=>{});
                return;
            }

            if(paramHolder.userPassword.trim() != paramHolder.confPassword.trim()) {
                conf_psw_error.value = trans('password_update_modal__psws_not_same_error');
                // ps_error_dialog.value.openModal(trans('password_update_modal__error'), 
                // trans('password_update_modal__psws_not_same_error'),
                // trans('password_update_modal__ok'),
                // ()=>{});
                return;
            }
            
            psloading.value.openModal();

            paramHolder.userId = userProvider.user?.data?.userId;

            const returnData = await userProvider.postChangePassword(paramHolder,loginUserId);
            psloading.value.closeModal();
                
            if(returnData.status == PsStatus.ERROR) {
                
                ps_error_dialog.value.openModal(trans('password_update_modal__error'), 
                returnData.message,
                trans('password_update_modal__ok'),
                ()=>{});
            }else if(returnData.status == PsStatus.SUCCESS) {
                // psValueStore.replaePassword(
                //                     paramHolder.userPassword
                // );
                ps_success_dialog.value.openModal(trans('password_update_modal__success'),
                trans('password_update_modal__psw_update_success'),trans('password_update_modal__continue'),()=>{
                    router.get(route('login'));
                });
                psmodal.value.toggle(false);
                
            }            
        }

        return {
            psmodal,
            openModal,
            submitClicked,
            psloading,
            ps_error_dialog,
            paramHolder,
            ps_success_dialog,
            closeModal,
            isHide,
            isHide1,
            isHide2,
            conf_psw_error,
            old_psw_error,
            new_psw_error
        }
    },
})
</script>
