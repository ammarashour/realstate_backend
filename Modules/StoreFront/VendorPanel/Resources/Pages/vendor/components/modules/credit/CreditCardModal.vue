<template>
    
    <ps-modal ref="psmodal"  :isClickOut='false' > 
        <!-- maxWidth="350px" -->
        <template #title>
            <!-- <ps-label-title> Title </ps-label-title> -->
             <!-- Item entry title -->
            <div class="flex flex-col items-center mt-8">
                <ps-label-header-4 class="font-bold"> {{ $t('credit_card_modal__card_entry') }} </ps-label-header-4>
            </div>
        </template>
        <template #body>           
            <!-- End Item entry title -->
            <!-- Start Input Field for md .. -->
            <div class="flex justify-between container w-full p-4">
                <!-- Start Left Screen -->
                <div class="md:flex flex-auto md:w-6/12 h-auto bg-feAchromatic-50 dark:bg-feAchromatic-900 border rounded-md dark:border-feAchromatic-500  pt-2">
                    <div class="flex flex-col w-full">
                    
                        
                        <!-- for Old Card Number -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card_modal__card_number') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8"  id="card-number" type="text" :placeholder="paramHolder1.oldCardNo" v-model:value="paramHolder.oldCardNo" :onInput="onOldCardInput" :maxlength='19' />
                        <!-- end Card Number -->

                        <!-- for Expiry Date -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card_modal__expired_date') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" id="card-expiry" type="text" :placeholder="paramHolder1.oldExpiryDate" v-model:value='paramHolder.oldExpiryDate' :onInput="onOldExpiryDateInput" :maxlength='5' />                         
                        <!-- end Expiry Date -->


                        <ps-line class='mt-4' />

                        <!-- for New Card Number -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card_modal__new_card_number') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8"  id="card-number" type="text" v-bind:placeholder="$t('credit_card_modal__new_number_placeholder')" v-model:value="paramHolder.newCardNo" :onInput="onNewCardInput" :maxlength='19' />
                        <!-- end Card Number -->

                        <!-- for New Expiry Date -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card_modal__new_expired_date') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" id="card-expiry" type="text" v-bind:placeholder="$t('credit_card_modal__new_expired_date_placeholder')" v-model:value='paramHolder.newExpiryDate' :onInput="onNewExpiryDateInput" :maxlength='5' />                         
                        <!-- <div id="card-expiry" class="mt-2 mx-8" /> -->
                        <!-- end Expiry Date -->


                        <!-- for New CVV -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card_modal__new_cvv') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" id="card-cvc" type="text" v-bind:placeholder="$t('credit_card_modal__new_cvv_placeholder')" v-model:value='paramHolder.cvv' :maxlength='3' />
                        <!-- end CVV -->


                        <!-- for New Card Holder Name -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card_modal__new_card_holder_name') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8 mb-8" type="text" v-bind:placeholder="$t('credit_card_modal__new_card_holder_name_placeholder')" v-model:value='paramHolder.nameOnCard' />
                        <!-- end Card Holder Name -->

                        
                    </div>   
                </div>
                <!-- End Left Screen -->

            </div>
            <!-- End Input Field -->
          
        </template>
        <template #footer>             
            <div class="flex items-center justify-center mb-4">
                <ps-button class="text-center w-60 mx-auto  " @click="submitClicked" > {{ $t('credit_card_modal__save') }} </ps-button>
                <ps-button class="text-center w-60 mx-auto ms-4" theme="btn-second" @click="psmodal.toggle(false)" > {{ $t('credit_card_modal__cancel') }} </ps-button>                
            </div>
        </template>
    </ps-modal>
    
    <ps-loading-dialog ref="psloading"  :isClickOut='false'/> 

    <ps-error-dialog ref="ps_error_dialog" />

</template>

<script lang='ts'>
import { defineComponent,ref,reactive } from 'vue';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabelHeader4 from '@template1/vendor/components/core/label/PsLabelHeader4.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsInput from '@template1/vendor/components/core/input/PsInput.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import UpdateCreditCardPrameterHolder from '@templateCore/object/holder/UpdateCreditCardParameterHolder';
import ProfileUpdateViewHolder from '@templateCore/object/holder/ProfileUpdateViewHolder';
import PsLoadingDialog from '@template1/vendor/components/core/dialog/PsLoadingDialog.vue';
import { useUserProviderState } from '@templateCore/store/modules/user/UserProvider';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import PsErrorDialog from '@template1/vendor/components/core/dialog/PsErrorDialog.vue';
import User from '@templateCore/object/User';
import { useProductStore } from '@templateCore/store/modules/item/ProductStore';
import PsLine from '@template1/vendor/components/core/line/PsLine.vue';
import PsStatus from '@templateCore/api/common/PsStatus';
import { trans } from 'laravel-vue-i18n';
export default defineComponent({
    name : 'CreditCardModal',
    components : {
        PsModal,
        PsLabel,
        PsInput,
        PsButton,
        PsLabelHeader4,
        PsLoadingDialog,
        PsErrorDialog,
        PsLine
    },
    setup() {
        
        // This need to "true" only for bidding. 
        // It need to be "false" at item entry and others
        let isPostCreditCard = true; 
        const psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();

        const psmodal = ref();    
        const psloading = ref();  
        const paramHolder = reactive(new UpdateCreditCardPrameterHolder());
        const paramHolder1 = reactive(new UpdateCreditCardPrameterHolder());
        const profileParamHolder = reactive(new ProfileUpdateViewHolder());

        const date_text_field_controller = ref();
        const ps_error_dialog = ref();

        const userProvider = useUserProviderState();
        const productProvider = useProductStore();

        let userData: User = new User();

        let onSuccess;
        
        
        async function initData() {
            psloading.value.openModal();

            // Load User By ID List
            await userProvider.getUser(loginUserId);
         
            userData = new User().fromMap(userProvider.user?.data) ?? new User();

            psloading.value.closeModal();
        }

        async function openModal(isPostCreditCardBool, onSuccessFun?) {

            isPostCreditCard = isPostCreditCardBool;
            psmodal.value.toggle(true);
            await initData();
          
            onSuccess = onSuccessFun;
                        
        }

        function onOldCardInput() {
            
            paramHolder.oldCardNo = paramHolder.oldCardNo.replace(' ', '');
            const card = paramHolder.oldCardNo.match(/.{1,4}/g);
            paramHolder.oldCardNo = card?.join(' ') ?? '';
        }

        function onNewCardInput() {
            
            paramHolder.newCardNo = paramHolder.newCardNo.replace(' ', '');
            const card = paramHolder.newCardNo.match(/.{1,4}/g);
            paramHolder.newCardNo = card?.join(' ') ?? '';
        }

        function onOldExpiryDateInput() {
            
            paramHolder.oldExpiryDate = paramHolder.oldExpiryDate.replace('/', '');
            const oldExpiryDate = paramHolder.oldExpiryDate.match(/.{1,2}/g);
            paramHolder.oldExpiryDate = oldExpiryDate?.join('/') ?? '';
           
        }

        function onNewExpiryDateInput() {
            
            paramHolder.newExpiryDate = paramHolder.newExpiryDate.replace('/', '');
            const newExpiryDate = paramHolder.newExpiryDate.match(/.{1,2}/g);
            paramHolder.newExpiryDate = newExpiryDate?.join('/') ?? '';
           
        }

        async function submitClicked() {
            
            // Validation
            if(paramHolder.oldCardNo.trim() == '') {
                ps_error_dialog.value.openModal('', 
                trans('credit_card_modal__old_card_no_required_error'));
                return;
            }
            if(paramHolder.newCardNo.trim() == '') {
                ps_error_dialog.value.openModal('', 
                trans('credit_card_modal__new_card_no_required_error'));
                return;
            }
            if(paramHolder.oldExpiryDate.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card_modal__old_expiry_date_required_error'));
                return;
            }
            if(!paramHolder.oldExpiryDate.includes('/')) {
                ps_error_dialog.value.openModal('',
                trans('credit_card_modal__old_expiry_date_invalid_error'));
                return;
            }
             if(paramHolder.newExpiryDate.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card_modal__new_expiry_date_required_error'));
                return;
            }
            if(!paramHolder.newExpiryDate.includes('/')) {
                ps_error_dialog.value.openModal('', 
                trans('credit_card_modal__new_expiry_date_invalid_error'));
                return;
            }
            if(paramHolder.cvv.trim() == '') {
                ps_error_dialog.value.openModal('', 
                trans('credit_card_modal__cvv_required_error'));
                return;
            }
            if(paramHolder.cvv.length != 3 ) {
                ps_error_dialog.value.openModal('', 
                trans('credit_card_modal__cvv_invalid_error'));
            }
            if(paramHolder.nameOnCard.trim() == '') {
                ps_error_dialog.value.openModal('', 
                trans('credit_card_modal__name_required_error'));
                return;
            }
           
            psloading.value.openModal();

            // For Profile Edit

            // For Deduct Money
            paramHolder.userId = userData.userId;

            // for old exp date (validation)

            const expiryDateArr : String[] = paramHolder.oldExpiryDate.split('/');
            if(expiryDateArr.length > 1) {
                paramHolder.oldMonth = expiryDateArr[0].toString();
                paramHolder.oldYear = expiryDateArr[1].toString();
            }

            // for new exp date (update)

            const NewExpiryDateArr : String[] = paramHolder.newExpiryDate.split('/');
            if(NewExpiryDateArr.length > 1) {
                paramHolder.month = NewExpiryDateArr[0].toString();
                paramHolder.year = NewExpiryDateArr[1].toString();
            }

            // Update Profile
            if(isPostCreditCard) {
                
                const returnData = await productProvider.updateCreditCard(paramHolder.toMap());
                psloading.value.closeModal();

                if(returnData.status == PsStatus.ERROR) {
                    ps_error_dialog.value.openModal('', returnData.message);
                    return;
                }else if(returnData.status == PsStatus.SUCCESS) {
                    psValueStore.replaceCreditCard(
                                        paramHolder.newCardNo.toString(),
                                        paramHolder.month.toString(),
                                        paramHolder.year.toString(),
                                        paramHolder.cvv.toString(),
                                        paramHolder.nameOnCard.toString(),
                                        true
                    );
                    psmodal.value.toggle(false);
                }
            }else {

                psValueStore.replaceCreditCard(
                                    paramHolder.newCardNo.toString(),
                                    paramHolder.month.toString(),
                                    paramHolder.year.toString(),
                                    paramHolder.cvv.toString(),
                                    paramHolder.nameOnCard.toString(),
                                    false
                );
                psloading.value.closeModal();
                psmodal.value.toggle(false);
            }

            if(onSuccess != null) {
                onSuccess();
            }
            
        }

        return {
            psmodal,
            psloading,
            openModal,
            paramHolder,
            paramHolder1,
            profileParamHolder,
            onOldExpiryDateInput,
            onOldCardInput,
            date_text_field_controller,
            ps_error_dialog,
            submitClicked,
            onNewCardInput,
            onNewExpiryDateInput
        }
    },
})
</script>
