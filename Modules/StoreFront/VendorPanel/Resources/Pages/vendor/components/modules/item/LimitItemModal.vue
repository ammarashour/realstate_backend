<template>
    <div>
       <ps-modal ref="psmodal"  line="hidden" maxWidth="534px" :isClickOut='false' class='h-full  ' bodyHeight="max-h-72" theme="px-4 py-8 sm:p-8 rounded-lg">
        <template #title>
             <!-- Item entry title -->
            <div class="flex justify-between items-start">
                    <ps-label v-if="limitProvider.buyadList?.data != null && limitProvider.buyadList?.data[0]?.user?.remainingPost == 0 " textColor="text-lg font-semibold text-feSecondary-800 mb-2">{{ $t("limit_item_modal__quota_running_out.") }}</ps-label>
                    <div class="flex flex-wrap">
                        <ps-label class="text-lg font-semibold text-feSecondary-800 me-2"> {{ $t('limit_item_modal__limit') }} </ps-label>
                        <ps-label class="text-lg"> {{ $t('limit_item_modal__limit_post_buy') }} </ps-label>
                    </div>
                <ps-icon class="cursor-pointer dark:text-feSecondary-500" name="close" w="24" h="24" @click="psmodal.toggle(false)"/>
            </div>
        </template>
        <template #body>
            <!-- End Item entry title -->
            <!-- Start Input Field for md .. -->
            <div class="flex flex-col mt-6 sm:mt-7">
                <!-- Start Left Screen -->
                <div class="">
                    <div class="flex flex-col w-full">
                        <ps-label class="font-medium text-base">{{ $t('promote_item_modal__choose_package') }}  </ps-label>
                        <div class="flex flex-col w-full mt-6 gap-2 sm:gap-4" id="r1">
                            <ps-radio-2 class=""
                            v-for="selectData in packageprovider.packageList.data"
                            :key="selectData.packageId"
                            :value="selectData"
                            v-model:selectedValue="selected"  >
                               <template #title >
                                    <div>
                                        <ps-label class='ms-2 text-sm text-feSecondary-800'>{{selectData.paymentInfo.value}} - {{ selectData.paymentAttributes.count }} {{ $t('limit_item_modal__post') }} </ps-label>
                                    </div>
                                </template>
                                <template #price>
                                    <ps-label class='font-semibold text-base'><span class="font-light">{{ selectData.paymentAttributes?.currencySymbol }}</span> {{ formatPrice(selectData.paymentAttributes.price) }}</ps-label>
                                </template>
                            </ps-radio-2>

                        </div>

                        <!-- end Ads days -->
                     </div>
                </div>
                <!-- End Left Screen -->

            </div>
            <!-- End Input Field -->

        </template>
        <template #footer>
            <ps-label class="mt-6 sm:mt-7 font-medium text-base"> {{ $t('promote_item_modal__pay_with') }}  </ps-label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6">
                <ps-button colors="bg-feBrand-paypal text-feAchromatic-50" v-if="appInfoStore.appInfo.data?.paypalEnable == '1'" class="" padding="px-8 py-4" rounded="rounded-lg" @click="paymentClicked('PAYPAL')" >{{ $t('promote_item_modal__pay_with_paypal') }}</ps-button>
                <ps-button colors="bg-feBrand-stripe text-feAchromatic-50" v-if="appInfoStore.appInfo.data?.stripeEnable == '1'" class="" padding="px-8 py-4" rounded="rounded-lg" @click="paymentClicked('STRIPE')" >{{ $t('promote_item_modal__pay_with_stripe') }}</ps-button>
                <ps-button colors="bg-feBrand-razor text-feAchromatic-50" v-if="appInfoStore.appInfo.data?.razorEnable == '1'" class="" padding="px-8 py-4" rounded="rounded-lg" @click="paymentClicked('RAZOR')" >{{ $t('promote_item_modal__pay_with_razor') }}</ps-button>
                <ps-button colors="bg-feBrand-paystack text-feAchromatic-50" v-if="appInfoStore.appInfo.data?.payStackEnabled == '1'" class="" padding="px-8 py-4" rounded="rounded-lg" @click="paymentClicked('PAYSTACK')" >{{ $t('promote_item_modal__pay_with_stack') }}</ps-button>
                <ps-button v-if="appInfoStore.appInfo.data?.offlineEnabled == '1'" class="" padding="px-8 py-4" rounded="rounded-lg" @click="paymentClicked('OFFLINE')" >{{ $t('promote_item_modal__pay_with_offline') }}</ps-button>
            </div>
        </template>
    </ps-modal>
    <stripe-payment-modal ref="stripe_payment_modal" />

    <paypal-payment-modal ref="paypal_payment_modal" />

    <ps-warning-dialog-2 ref="ps_warning_dialog" />

    <offline-payment-modal ref="offline_payment_modal" />

    <ps-error-dialog ref="ps_error_dialog" />
    <input-email-modal ref="input_email" />
 </div>

</template>

<script lang='ts'>

/// Razorpay
import 'https://checkout.razorpay.com/v1/checkout.js';

// Libs
import {defineComponent, ref } from 'vue';

// Compone
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsRadio from '@template1/vendor/components/core/radio/PsRadio.vue';
import PsRadio2 from '@template1/vendor/components/core/radio/PsRadio2.vue';
import PsErrorDialog from '@template1/vendor/components/core/dialog/PsErrorDialog.vue';
import PsWarningDialog2 from '@template1/vendor/components/core/dialog/PsWarningDialog2.vue';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

import StripePaymentModal from '@template1/vendor/components/modules/credit/StripePaymentModal.vue';
import PaypalPaymentModal from '@template1/vendor/components/modules/credit/PaypalPaymentModal.vue';
import OfflinePaymentModal from '@template1/vendor/components/modules/credit/OfflinePaymentModal.vue';
import InputEmailModal from '@template1/vendor/components/modules/email/InputEmailModal.vue';

// Providers
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import { useUserStore } from "@templateCore/store/modules/user/UserStore";
import { usePackageStoreState } from "@templateCore/store/modules/package/PackageStore";
import ItemLimitParameterHolder from '@templateCore/object/holder/ItemLimitParameterHolder';
import AppInfoParameterHolder from '@templateCore/object/holder/AppInfoParameterHolder';
import { useLimitAdItemStoreState } from "@templateCore/store/modules/limit/LimitAdItemStore";

import PsStatus from '@templateCore/api/common/PsStatus';
import { trans } from 'laravel-vue-i18n';import PsConst from '@templateCore/object/constant/ps_constants';

import PaystackPop from '@paystack/inline-js';
import PsUtils from '@templateCore/utils/PsUtils';
export default defineComponent({
    name: "LimitItemModal",
    components: {
        PsModal,
        PsIcon,
        PsLabel,
        PsButton,
        PsErrorDialog,
        PsRadio2,
        PsRadio,
        StripePaymentModal,
        PaypalPaymentModal,
        OfflinePaymentModal,
        PsWarningDialog2,
        InputEmailModal,
    },
   setup() {
        const psmodal = ref();
        const stripe_payment_modal = ref();
        const paypal_payment_modal = ref();
        const offline_payment_modal = ref();
        const ps_error_dialog = ref();
        const input_email = ref();
        const ps_warning_dialog = ref();
        const selected = ref();

        const packageprovider = usePackageStoreState();
        const limitProvider = useLimitAdItemStoreState();

        const itemLimitParameterHolder = new ItemLimitParameterHolder();
        const appInfoStore = usePsAppInfoStoreState();
        const appInfoParameterHolder = new AppInfoParameterHolder();
        const userProvider = useUserStore();

        const psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();

        // Price Per Day
        const pricePerDay = ref(0);
        const razorKey = ref('');
        const razorId = ref('');

        async function openModal() {

            psmodal.value.toggle(true);
            await limitProvider.resetPaidAdItemList(loginUserId);
            await packageprovider.resetPackageList(loginUserId);

            selectPackageOne();

            const info = await appInfoStore.loadAppInfo(appInfoParameterHolder);

            razorKey.value = info.data.razorKey;
            if(info.status == PsStatus.SUCCESS) {
                pricePerDay.value = parseInt(info.data.oneDay, 10);
            }else {
                pricePerDay.value = 0;
            }

        }

        function selectPackageOne() {
            if(packageprovider.packageList != null
                && packageprovider.packageList.data != null
                && packageprovider.packageList.data.length > 0 ) {
                selected.value = packageprovider.packageList.data[0];
            }
        }


        function paymentClicked(value){
            if(selected.value == null
                || selected.value.paymentInfo == null ){
                    ps_error_dialog.value.openModal('', $t("select_package"));
                    return;
            }
            if(appInfoStore.appInfo.data.mobileSetting.is_demo_for_payment == 1){
                ps_warning_dialog.value.openModal(
                    trans('payment__warning_title'),
                    trans('payment__confirm_message'),
                    trans('payment__ok'),
                    trans('credit_card_modal__cancel'),
                    () => {
                        if(value == 'PAYPAL'){
                            paypalClicked();
                        }else if(value == 'STRIPE'){
                            stripeClicked();
                        }else if(value == 'RAZOR'){
                            razorClicked();
                        }else if(value == 'PAYSTACK'){
                            paystackClicked();
                        }else if(value == 'OFFLINE'){
                            offlineClicked();
                        }
                    },
                    () => {
                        PsUtils.log("Cancel");
                    });


            }else{
                if(value == 'PAYPAL'){
                    paypalClicked();
                }else if(value == 'STRIPE'){
                    stripeClicked();
                }else if(value == 'RAZOR'){
                    razorClicked();
                }else if(value == 'PAYSTACK'){
                    paystackClicked();
                }else if(value == 'OFFLINE'){
                    offlineClicked();
                }
            }


        }

        function stripeClicked() {

            psmodal.value.toggle(false);

            stripe_payment_modal.value.openModal(
                () => {
                    const payment = PsConst.PAYMENT_STRIPE_METHOD.toString();
                    doPayment(payment);
                },
                () => {
                    PsUtils.log("Cancel");
                }
            );

        }

        async function doPayment(payment) {
            itemLimitParameterHolder.userId = loginUserId;
            itemLimitParameterHolder.packageId = selected.value.paymentInfo?.id;
            itemLimitParameterHolder.paymentMethod = payment;
            itemLimitParameterHolder.paymentMethodNounce = localStorage.paymentNonce || '';
            itemLimitParameterHolder.price = selected.value.paymentAttributes.price;
            itemLimitParameterHolder.razorId = razorId.value || '';

            const limititem = await packageprovider.postPackageBought(loginUserId, itemLimitParameterHolder);
            psmodal.value.toggle(false);
            if(limititem.status == PsStatus.ERROR) {
                ps_error_dialog.value.openModal('', packageprovider.limititem.message);
            } else {
                localStorage.paymentNonce = "";
                location.reload();
            }
        }

        function paypalClicked() {
            psmodal.value.toggle(false);
            paypal_payment_modal.value.openModal(
                () => {
                    const payment = PsConst.PAYMENT_PAYPAL_METHOD.toString();
                    doPayment(payment);
                },
                () => {
                    PsUtils.log("Cancel");
                }
            );
        }

        async function razorClicked() {
            const returnData = await userProvider.loadUser(loginUserId);
            const userInfo = returnData.data;
            //for razor ui
            const options =
            {
                "key": razorKey.value, // Enter the Key ID generated from the Dashboard
                "name": "Test Company",
                "prefill": {
                    "email": userInfo.userEmail,
                    "name": userInfo.userName,
                    "contact": userInfo.userPhone
                },
                "theme": {
                    "color": "#0e9f6e"
                },
                // This handler function will handle the success payment
                "handler": async function (response) {
                    razorId.value =  response.razorpay_payment_id;
                    // Submit response.razorpay_payment_id to your server
                    doPayment(PsConst.PAYMENT_RAZOR_METHOD.toString())

                },
            };

            const rzp1 = new (window as any).Razorpay(options);
            rzp1.open();
            rzp1.on('payment.failed', function (response)
            {
                // alert(response.error);
            });

        }

        async function paystackClicked() {
            const returnData = await userProvider.loadUser(loginUserId);
            const userInfo = returnData.data;
            let email = userInfo.userEmail;
            if(userInfo.userEmail == "" || userInfo.userEmail == null){
                input_email.value.openModal(
                    (emailStr) => {
                         email = emailStr;
                        const paystack = PaystackPop.setup({
                            key: appInfoStore?.appInfo?.data.payStackKey,
                            email: email,
                            amount: selected.value.price,
                            callback: async function(response){
                                PsUtils.log(response);
                                // Payment complete! Reference: ' + response.reference;
                                doPayment(PsConst.PAYMENT_PAY_STACK_METHOD.toString());

                            },
                            onClose: function(){
                                // alert("close");
                                // user closed popup
                            }

                        });
                        paystack.openIframe();
                    },
                     () => {
                         console.log('cancel');
                    }  );
            }else{
                const paystack = PaystackPop.setup({
                key: appInfoStore?.appInfo?.data.payStackKey,
                email: email,
                amount: selected.value.paymentAttributes.price,
                callback: async function(response){
                    PsUtils.log(response);
                    // Payment complete! Reference: ' + response.reference;
                    doPayment(PsConst.PAYMENT_PAY_STACK_METHOD.toString());

                },
                onClose: function(){
                    // alert("close");
                    // user closed popup
                }

            });
            paystack.openIframe();
            }

        }

        function offlineClicked() {
            psmodal.value.toggle(false);

            offline_payment_modal.value.openModal(
                () => {
                    const payment = PsConst.PAYMENT_OFFLINE_METHOD.toString();
                    doPayment(payment);
                },
                () => {
                    PsUtils.log("Cancel");
                }
            );
        }

        function formatPrice(value) {
            if(value.toString() == '0') {
                return trans('item_price__free');
            }else {
                return PsUtils.formatPrice(appInfoStore, value);
            }
        }

        return {
            psmodal,
            openModal,
            packageprovider,
            appInfoStore,
            limitProvider,
            ps_error_dialog,
            stripe_payment_modal,
            paypal_payment_modal,
            offline_payment_modal,
            selected,
            ps_warning_dialog,
            paymentClicked,
            input_email,
            formatPrice
        }
    },
})
</script>
