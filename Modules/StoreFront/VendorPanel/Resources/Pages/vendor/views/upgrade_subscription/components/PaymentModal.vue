<template>
    <ps-modal ref="psmodal" line="hidden" maxWidth="534px" :isClickOut='false' class='h-full' bodyHeight="max-h-72" theme="px-4 py-8 sm:p-8 rounded-lg">
        <template #title>
            <div class="flex justify-between">
                <ps-label textColor="text-xl font-semibold text-secondary-800">{{ $t('vendor_subscription__payment_methods') }}</ps-label>
                <ps-icon class="cursor-pointer dark:text-feSecondary-500" name="close" w="24" h="24" @click="psmodal.toggle(false)"/>
            </div>
        </template>
        <template #body>
            <div class="grid grid-cols-3 sm:grid-cols-3 gap-4 mt-6">
                <ps-button colors="bg-transparent dark:bg-feSecondary-50 h-16" border="border hover:shadow" hover="none" class=""  rounded="rounded-lg" @click="paymentClicked('PAYPAL')" >
                    <img v-lazy="{ src: $page.props.sysImageUrl + '/paypal.png' }" alt=""
                            class="w-full h-full object-contain bottom-0"
                                >
                </ps-button>

                <ps-button colors="bg-transparent  dark:bg-feSecondary-50 h-16" border="border hover:shadow" hover="none" class=""  rounded="rounded-lg" @click="paymentClicked('STRIPE')" >
                    <img v-lazy="{ src: $page.props.sysImageUrl + '/stripe.png' }" alt=""
                            class="w-full h-full object-contain bottom-0"
                                >
                </ps-button>
                <ps-button colors="bg-transparent dark:bg-feSecondary-50 h-16" border="border hover:shadow" hover="none" class="" rounded="rounded-lg" @click="paymentClicked('RAZOR')" >
                    <img v-lazy="{ src: $page.props.sysImageUrl + '/razorpay.png' }" alt=""
                            class="w-full h-full object-contain bottom-0"
                                >
                </ps-button>
                <ps-button colors="bg-transparent dark:bg-feSecondary-50 h-16" border="border hover:shadow" hover="none" class=""  rounded="rounded-lg" @click="paymentClicked('PAYSTACK')" >
                    <img v-lazy="{ src: $page.props.sysImageUrl + '/paystack.png' }" alt=""
                            class="w-full h-full object-contain bottom-0"
                                >
                </ps-button>
                <ps-button colors="bg-transparent dark:bg-feSecondary-50 h-16" border="border hover:shadow" hover="none" class=""  rounded="rounded-lg" @click="paymentClicked('FLUTTERWAVE')" >
                    <img v-lazy="{ src: $page.props.sysImageUrl + '/flutterwave.png' }" alt=""
                            class="w-full h-full object-contain bottom-0"
                                >
                </ps-button>
            </div>
        </template>
    </ps-modal>
    <paypal-payment-modal ref="paypal_payment_modal" />
    <stripe-payment-modal ref="stripe_payment_modal" />
    <input-email-modal ref="input_email" />

    <ps-warning-dialog ref="ps_warning_dialog" />
    <ps-error-dialog ref="ps_error_dialog" />
    <ps-success-dialog ref="ps_success_dialog"/>
</template>

<script lang='ts'>
/// Razorpay
import 'https://checkout.razorpay.com/v1/checkout.js';

// Libs
import {defineAsyncComponent, defineComponent, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { trans } from 'laravel-vue-i18n';
import PsConst from '@templateCore/object/constant/ps_constants';
import PaystackPop from '@paystack/inline-js';
import PsUtils from '@templateCore/utils/PsUtils';
import format from 'number-format.js';
import { router } from '@inertiajs/vue3';
import {useFlutterwave} from "flutterwave-vue3ts"

import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
// import PaypalPaymentModal from '@vendorPanel/vendor/components/modules/credit/PaypalPaymentModal.vue';
import PsErrorDialog from '@/Components/Core/Dialog/PsErrorDialog.vue';
import PsWarningDialog from '@/Components/Core/Dialog/PsWarningDialog.vue';
import PsSuccessDialog from "@/Components/Core/Dialog/PsSuccessDialog.vue";
const PaypalPaymentModal = defineAsyncComponent(() => import ('@vendorPanel/vendor/components/modules/credit/PaypalPaymentModal.vue'));
const StripePaymentModal = defineAsyncComponent(() => import ('@vendorPanel/vendor/components/modules/credit/StripePaymentModal.vue'));
const InputEmailModal = defineAsyncComponent(() => import ('@vendorPanel/vendor/components/modules/email/InputEmailModal.vue'));
    export default defineComponent({
        name: "PaymentModal",
        components: {
            PsModal,
            PsButton,
            PsLabel,
            PsIcon,
            PaypalPaymentModal,
            StripePaymentModal,
            InputEmailModal,
            PsErrorDialog,
            PsWarningDialog,
            PsSuccessDialog,
        },
        props:{
            appInfo: Object,
            vendor: Object,
        },
        setup(props){
            const psmodal = ref();
            const ps_warning_dialog = ref();
            const ps_error_dialog = ref();
            const ps_success_dialog = ref();
            const planId = ref();
            const salePrice = ref();
            const paypal_payment_modal = ref();
            const stripe_payment_modal = ref();
            const input_email = ref();
            const currencyShortForm = ref();
            const vendor = props.vendor;
            const transactionId = ref();

            const razorKey = ref('');
            const razorId = ref('');

            let form = useForm({
                user_id: '',
                vendor_id: '',
                subscription_plan_id: planId.value,
                payment_method: '',
                payment_method_nonce: localStorage.paymentNonce || '',
                price: salePrice.value,
                razor_id: razorId.value || '',
                transaction_id: ''
            });

            function openModal(id, price, currency) {
                planId.value = id;
                salePrice.value = price;
                currencyShortForm.value = currency;
                razorKey.value = props.appInfo.razor_key;
                psmodal.value.toggle(true);
            }

            function paymentClicked(value){
                if(props.appInfo.mobile_config_setting.is_demo_for_payment == 1){
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
                            }else if(value == 'FLUTTERWAVE'){
                                if(PsUtils.checkFlutterwaveCurrency(currencyShortForm.value)){
                                    flutterwaveClicked();
                                }else{
                                    ps_error_dialog.value.openModal('',
                                        trans('core_vp__flutterwave_currency_not_supported',{"attribute":currencyShortForm.value}),
                                        trans('core__vp_btn_ok')
                                    );
                                }
                            }
                        },
                        () => {
                            console.log("Cancel");
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
                    }else if(value == 'FLUTTERWAVE'){
                        if(PsUtils.checkFlutterwaveCurrency(currencyShortForm.value)){
                            flutterwaveClicked();
                        }else{
                            ps_error_dialog.value.openModal('',
                                trans('core_vp__flutterwave_currency_not_supported',{"attribute":currencyShortForm.value}),
                                trans('ps_confirm_dialog__ok')
                            );
                        }
                    }
                }
            }

            function doPayment(payment) {
                form.user_id = props.vendor.owner.id;
                form.vendor_id = props.vendor.id;
                form.payment_method = payment;
                form.subscription_plan_id = planId.value;
                form.payment_method_nonce = localStorage.paymentNonce || '';
                form.price = salePrice.value;
                form.razor_id = razorId.value || '';
                form.transaction_id = transactionId.value;

                router.post(route("upgrade_subscription.store", form,{
                    onBefore: () => {
                        psmodal.value.toggle(false);
                    },
                    onSuccess: () => {
                        psmodal.value.toggle(false);
                        localStorage.paymentNonce = "";
                        location.reload();
                    },
                    onError: () => {
                        psmodal.value.toggle(false);
                        ps_error_dialog.value.openModal('error','erro');
                    }
                }));
            }

            function paypalClicked() {
                psmodal.value.toggle(false);
                paypal_payment_modal.value.openModal(
                    props.vendor.owner.id,
                    () => {
                        const payment = PsConst.PAYMENT_PAYPAL_METHOD.toString();
                        doPayment(payment);
                    },
                    () => {
                        console.log("Cancel");
                    }
                );
            }

            function stripeClicked() {

                psmodal.value.toggle(false);

                stripe_payment_modal.value.openModal(
                    () => {
                        const payment = PsConst.PAYMENT_STRIPE_METHOD.toString();
                        doPayment(payment);
                    },
                    () => {
                        console.log("Cancel");
                    }
                );

            }

            async function razorClicked() {
                psmodal.value.toggle(false);
                const userInfo = props.vendor.owner;
                //for razor ui
                const options =
                {
                    "key": razorKey.value, // Enter the Key ID generated from the Dashboard
                    "amount": parseInt(salePrice.value) * 100,
                    "currency": currencyShortForm.value,
                    "name": trans('site_name'),
                    "prefill": {
                        "email": userInfo.email,
                        "name": userInfo.name,
                        "contact": userInfo.user_phone
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

            function paystackClicked() {
                psmodal.value.toggle(false);
                const userInfo = props.vendor.owner;
                let email = userInfo.email;
                if(userInfo.email == "" || userInfo.email == null){
                    input_email.value.openModal(
                        (emailStr) => {
                            email = emailStr;
                            const paystack = PaystackPop.setup({
                                key: props.appInfo.paystack_key,
                                email: email,
                                amount: parseInt(salePrice.value) * 100,
                                currency: currencyShortForm.value,
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
                    key: props.appInfo.paystack_key,
                    email: email,
                    amount: parseInt(salePrice.value) * 100,
                    currency: currencyShortForm.value,
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

            function flutterwaveClicked() {
                if(props.appInfo.flutterwave_public_key == ""){
                    ps_error_dialog.value.openModal('', trans('core_vp__update_flutterwave_public_key'), trans('core__vp_btn_ok'));
                }else{
                    const payment = PsConst.PAYMENT_FLUTTERWAVE_METHOD.toString();
                    useFlutterwave({
                        amount: salePrice.value,//amount
                        callback(data: any) {
                        //  TODO: handle callbacks
                            console.log(data);
                            transactionId.value = data.transaction_id;
                            doPayment(payment);
                        },
                        country: "US",
                        currency: currencyShortForm.value,
                        customer: {email: vendor.email, name: vendor.name, phone_number: vendor.phone},
                        customizations: {description: "Pay with "+trans('site_name'), logo: "", title: trans('site_name')},
                        meta: {
                            user_id: 1,
                            token: "jdjdjdjdjd"
                        },
                        onclose(): void {
                            // emit('isPromoteSuccessful',flutterwaveSuccess.value);
                        },
                        payment_options:  "card",
                        public_key: props.appInfo.flutterwave_public_key,
                        redirect_url: undefined,
                        tx_ref: "tx_ref_"+Date.now()
                    })
                }

            }

            return {
                psmodal,
                openModal,
                paymentClicked,
                ps_error_dialog,
                ps_warning_dialog,
                planId,
                ps_success_dialog,
                doPayment,
                paypal_payment_modal,
                stripe_payment_modal,
                input_email,
            }
        },
        methods:{
            // paypalClicked(){
            //     this.$inertia.get(route('vendor_info.index'));
            // }
        }
    })
</script>
