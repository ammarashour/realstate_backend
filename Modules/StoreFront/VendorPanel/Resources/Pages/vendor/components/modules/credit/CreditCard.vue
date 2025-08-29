<template>

    <ps-modal ref="psmodal"  :isClickOut='false' >
        <template #title>
            <div class="flex flex-col items-center mt-8">
                <ps-label-header-4 class="font-bold"> {{ $t('credit_card__card_entry') }} </ps-label-header-4>
            </div>
        </template>
        <template #body>
            <!-- End Item entry title -->
            <!-- Start Input Field for md .. -->
            <div class="flex justify-between container w-full p-4">
                <!-- Start Left Screen -->
                <div class="md:flex flex-auto md:w-6/12 h-auto bg-feAchromatic-50 dark:bg-feAchromatic-900 border rounded-md dark:border-feAchromatic-500  pt-2">
                    <div class="flex flex-col w-full">


                        <!-- for Card Number -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__card_number') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8"  id="card-number" type="text" :placeholder="paramHolder.oldCardNo" v-model:value="paramHolder.cardNo" :onInput="onCardInput" :maxlength='19' />
                        <!-- end Card Number -->

                        <!-- for Expiry Date -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__expired_date') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" id="card-expiry" type="text" v-bind:placeholder="$t('credit_card__expired_date_placeholder')" v-model:value='paramHolder.expiryDate' :onInput="onExpiryDateInput" :maxlength='5' />
                        <!-- end Expiry Date -->

                        <!-- for CVV -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__cvv') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" id="card-cvc" type="text" v-bind:placeholder="$t('credit_card__cvv_placeholder')" v-model:value='paramHolder.cvv' :maxlength='3' />
                        <!-- end CVV -->

                        <ps-line class='mt-4' />

                        <!-- for Card Holder Name -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__card_holder_name') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" type="text" v-bind:placeholder="$t('credit_card__card_holder_name_placeholder')" v-model:value='paramHolder.nameOnCard' />
                        <!-- end Card Holder Name -->

                        <!-- for Email -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__email') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" type="email" v-bind:placeholder="$t('credit_card__email_placeholder')" v-model:value='profileParamHolder.userEmail' />
                        <!-- end Email -->

                        <!-- for Phone -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__phone') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" type="text" v-bind:placeholder="$t('credit_card__phone_placeholder')" @keypress="phoneNumber($event)" v-model:value='profileParamHolder.userPhone' />
                        <!-- end Phone -->

                        <!-- for Street -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__street') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" type="text" v-bind:placeholder="$t('credit_card__street_placeholder')" v-model:value='profileParamHolder.streetName' />
                        <!-- end Street -->

                        <!-- for City -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__city') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" type="text" v-bind:placeholder="$t('credit_card__city_placeholder')" v-model:value='profileParamHolder.city' />
                        <!-- end City -->

                        <!-- for State -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__state') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-dropdown align="left" class='mt-2 mx-8' @onClick="loadStateList()" >
                            <template #select>
                                <ps-dropdown-select :selectedValue="profileParamHolder.stateName" />
                            </template>
                            <template #list >
                                <div class="rounded-md shadow-xs w-56" >
                                    <div class="pt-2 z-30">
                                        <div v-if="userStateListProvider.userStateList.data == null" class="flex">
                                            <p class='p-2' @click="loadStateList()" >{{ $t('credit_card__loading') }} </p>
                                        </div>
                                        <div v-else>
                                            <div v-for="selectedState in userStateListProvider.userStateList.data" :key="selectedState.id"
                                            class="w-56 flex py-4 px-2 hover:bg-fePrimary-50 dark:hover:bg-fePrimary-900 cursor-pointer items-center"
                                            @click="userStateFilterClicked(selectedState)" >
                                                <ps-label class="ms-2" :class="selectedState.id==profileParamHolder.stateId ? ' font-bold' : ''"  > {{selectedState.name}} </ps-label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </template>
                            <template #loadmore>

                                <div class="mb-2 w-56">

                                    <div  v-if="userStateListProvider.userStateList.data != null
                                        && userStateListProvider.loading.value == true" class='mt-4 ms-4 flex'>
                                        <ps-label-caption  @click="loadStateList"> {{ $t('credit_card__loading') }} </ps-label-caption>
                                    </div>

                                    <ps-label class="flex mt-4 ms-4 mb-2 underline font-bold cursor-pointer"   @click="loadStateList" > {{ $t('credit_card__load_more') }} </ps-label>
                                </div>

                            </template>
                        </ps-dropdown>
                        <!-- end model -->

                        <!-- for Zip Code -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__zip_code') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8" type="text" :maxlength="5" @keypress="isNumber($event)" v-bind:placeholder="$t('credit_card__zip_code_placeholder')" v-model:value='profileParamHolder.zipCode' />
                        <!-- end Zip Code -->

                        <!-- for Apartment Number -->
                        <ps-label class="mt-4 mx-8 "> {{ $t('credit_card__apartment_number') }} <span style="font-size: 17px; color: red;">*</span> </ps-label>
                        <ps-input class="mt-2 mx-8 mb-8" type="text" v-bind:placeholder="$t('credit_card__apartment_number')" v-model:value='profileParamHolder.apartmentNo' />
                        <!-- end Apartment Number -->

                    </div>
                </div>
                <!-- End Left Screen -->

            </div>
            <!-- End Input Field -->

        </template>
        <template #footer>
            <div class="flex items-center justify-center mb-4">
                <ps-button class="text-center w-60 mx-auto  " @click="submitClicked" > {{ $t('credit_card__submit') }} </ps-button>
                <ps-button class="text-center w-60 mx-auto ms-4" theme="btn-second" @click="psmodal.toggle(false)" > {{ $t('credit_card__close') }} </ps-button>
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
import PsDropdown from '@template1/vendor/components/core/dropdown/PsDropdown.vue';
import PsDropdownSelect from '@template1/vendor/components/core/dropdown/PsDropdownSelect.vue';
import CreditCardPrameterHolder from '@templateCore/object/holder/CreditCardParameterHolder';
import ProfileUpdateViewHolder from '@templateCore/object/holder/ProfileUpdateViewHolder';
import { useUserStateStore } from '@templateCore/store/modules/user/UserStateStore';
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
    name : 'CreditCard',
    components : {
        PsModal,
        PsLabel,
        PsInput,
        PsButton,
        PsLabelHeader4,
        PsDropdown,
        PsDropdownSelect,
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
        const paramHolder = reactive(new CreditCardPrameterHolder());
        const profileParamHolder = reactive(new ProfileUpdateViewHolder());

        const date_text_field_controller = ref();
        const ps_error_dialog = ref();

        const userStateListProvider = useUserStateStore();
        const userProvider = useUserProviderState();
        const productProvider = useProductStore();

        let userData: User = new User();

        let onSuccess;


        async function initData() {
            psloading.value.openModal();

            // Load User By ID List
            await userProvider.getUser(loginUserId);
            profileParamHolder.userEmail = userProvider.user?.data?.userEmail ?? '';
            profileParamHolder.userPhone = userProvider.user?.data?.userPhone ?? '';

            profileParamHolder.city = userProvider.user?.data?.city ?? '';
            paramHolder.nameOnCard = userProvider.user?.data?.userName ?? '';

            userData = new User().fromMap(userProvider.user?.data) ?? new User();

            psloading.value.closeModal();
        }

        async function loadStateList() {
            await userStateListProvider.loadUserStateList();
        }

        function phoneNumber(evt) {
            evt = (evt) ? evt : window.event;
            const charCode = (evt.which) ? evt.which : evt.keyCode;

            if ( charCode < 42 || charCode > 57 ) {
                evt.preventDefault();
            } else {
                return true;
            }
        }

        function isNumber(evt) {
            evt = (evt) ? evt : window.event;
            const charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46 ) {
                evt.preventDefault();
            } else {
                return true;
            }
        }

        function userStateFilterClicked(value) {
            profileParamHolder.stateId = value.id;
            profileParamHolder.stateName = value.name;
        }

        async function openModal(isPostCreditCardBool, onSuccessFun?) {

            isPostCreditCard = isPostCreditCardBool;
            psmodal.value.toggle(true);
            await initData();

            onSuccess = onSuccessFun;

        }

        function onCardInput() {

            paramHolder.cardNo = paramHolder.cardNo.replace(" ", "");
            const card = paramHolder.cardNo.match(/.{1,4}/g);
            paramHolder.cardNo = card?.join(' ') ?? '';
        }

        function onExpiryDateInput() {

            paramHolder.expiryDate = paramHolder.expiryDate.replace('/', '');
            const expiryDate = paramHolder.expiryDate.match(/.{1,2}/g);
            paramHolder.expiryDate = expiryDate?.join('/') ?? '';

        }



        async function submitClicked() {
            // Validation
            if(paramHolder.cardNo.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__card_no_required_error'));
                return;
            }
            if(paramHolder.expiryDate.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__expiry_date_required_error'));
                return;
            }
            if(!paramHolder.expiryDate.includes('/')) {
                ps_error_dialog.value.openModal('',
                trans('credit_card__expiry_date_format_error'));
                return;
            }
            if(paramHolder.cvv.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__cvv_required_error'));
                return;
            }
            if(paramHolder.cvv.length != 3 ) {
                ps_error_dialog.value.openModal('',
                trans('credit_card__cvv_invalid_error'));
            }
            if(paramHolder.nameOnCard.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__name_required_error'));
                return;
            }
            if(profileParamHolder.userEmail.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__email_required_error'));
                return;
            }
            if(profileParamHolder.userPhone.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__phone_required_error'));
                return;
            }
            if(profileParamHolder.streetName.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__street_required_error'));
                return;
            }
            if(profileParamHolder.city.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__city_required_error'));
                return;
            }
            if(profileParamHolder.stateId.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__state_required_error'));
                return;
            }
            if(profileParamHolder.zipCode.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__zip_code_required_error'));
                return;
            }
            if(profileParamHolder.apartmentNo.trim() == '') {
                ps_error_dialog.value.openModal('',
                trans('credit_card__apartment_no_required_error'));
                return;
            }

            psloading.value.openModal();

            // For Profile Edit
            profileParamHolder.userId = userData.userId ?? '';
            profileParamHolder.userName = paramHolder.nameOnCard;
            profileParamHolder.userAboutMe = "-";
            profileParamHolder.deviceToken = userData.deviceToken;
            profileParamHolder.isShowEmail = userData.isShowEmail;
            profileParamHolder.isShowPhone = userData.isShowPhone;


            // For Deduct Money
            paramHolder.userId = userData.userId;
            paramHolder.stateName = profileParamHolder.streetName;
            paramHolder.city = profileParamHolder.city;
            paramHolder.stateId = profileParamHolder.stateId;
            paramHolder.zipCode = profileParamHolder.zipCode;
            paramHolder.apartmentNo = profileParamHolder.apartmentNo;
            paramHolder.paymentMethodNonce = '';

            const expiryDateArr : String[] = paramHolder.expiryDate.split('/');
            if(expiryDateArr.length > 1) {
                paramHolder.expiryMonth = expiryDateArr[0].toString();
                paramHolder.expiryYear = expiryDateArr[1].toString();
            }

            // Update Profile
            await userProvider.postProfileUpdate(profileParamHolder.toMap());

            if(isPostCreditCard) {

                const returnData = await productProvider.postCreditCard(paramHolder.toMap());
                psloading.value.closeModal();

                if(returnData.status == PsStatus.ERROR) {
                    ps_error_dialog.value.openModal('', returnData.message);
                    return;
                }else if(returnData.status == PsStatus.SUCCESS) {
                    psValueStore.replaceCreditCard(
                                        paramHolder.cardNo.toString(),
                                        paramHolder.expiryMonth.toString(),
                                        paramHolder.expiryYear.toString(),
                                        paramHolder.cvv.toString(),
                                        paramHolder.nameOnCard.toString(),
                                        true
                    );
                    psmodal.value.toggle(false);
                }
            }else {

                psValueStore.replaceCreditCard(
                                    paramHolder.cardNo.toString(),
                                    paramHolder.expiryMonth.toString(),
                                    paramHolder.expiryYear.toString(),
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
            profileParamHolder,
            userStateListProvider,
            userStateFilterClicked,
            loadStateList,
            onExpiryDateInput,
            onCardInput,
            date_text_field_controller,
            ps_error_dialog,
            submitClicked,
            phoneNumber,
            isNumber
        }
    },
})
</script>
