<template>

    <ps-modal ref="psmodal"  line="hidden"  :isClickOut='false' >
        <template #body>
            <div id="dropin-container"></div>
            <div class="flex justify-center mx-auto">
                <ps-button id="submit-button">{{ $t('paypal_credit_card_modal__submit_payment') }}</ps-button>
                <ps-button theme="btn-second" class="text-center mx-4" @click="actionClicked('no')" > {{ $t('paypal_credit_card_modal__close') }} </ps-button>
            </div>
        </template>
    </ps-modal>
    <!-- <ps-loading-dialog ref="psloading"  :isClickOut='false'/> -->

    <ps-error-dialog ref="ps_error_dialog" />
</template>

<script lang='ts'>
import { defineComponent, ref} from 'vue';

import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';

import { useTokenStoreState } from '@templateCore/store/modules/token/TokenStore';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import PsErrorDialog from '@/Components/Core/Dialog/PsErrorDialog.vue';
import { trans } from 'laravel-vue-i18n';
import * as dropins from 'braintree-web-drop-in';
import { router } from '@inertiajs/vue3';

export default defineComponent({
    name : 'PaypalPaymentModal',
    components : {
        PsModal,
        PsButton,
        PsErrorDialog,
    },
     setup() {
        const psmodal = ref();
        const ps_error_dialog = ref();
        const tokenProvider = useTokenStoreState();
        const psValueStore = PsValueStore();
        // const loginUserId = psValueStore.getLoginUserId();
        const loginUserId = ref();

        let okClicked: Function;
        let cancelClicked: Function;

        function actionClicked(status) {
            if(status == 'yes') {
                okClicked();
            }else {
                cancelClicked();
            }
            psmodal.value.toggle(false);
        }

        async function openModal(
                userId: String,
                okClickedAction: Function,
                cancelClickedAction: Function) {

                psmodal.value.toggle(true);
                loginUserId.value = userId;
                okClicked = okClickedAction;
                cancelClicked= cancelClickedAction;
                //for braintree paypal dropin
                const returnData = await tokenProvider.loadToken(loginUserId.value);
                console.log(returnData)
                const button = document.querySelector('#submit-button');
                const dropin = dropins;

                dropin.create({
                    authorization: returnData.data.message,
                    container: '#dropin-container',
                    paypal: {
                        flow: 'vault',
                    }
                }, function (createErr, instance) {
                    button?.addEventListener('click', function () {
                        if (instance.isPaymentMethodRequestable()) {
                            setTimeout(function() {
                                instance.requestPaymentMethod(async function (err, payload) {
                                    console.log(payload);
                                    if(err) {
                                        ps_error_dialog.value.openModal('',
                                        trans('paypal_credit_card_modal__error_paid_ad'));
                                        return;
                                    }
                                    // Submit payload.nonce to your server
                                    localStorage.paymentNonce = payload.nonce;
                                    actionClicked('yes');

                                });
                            }, 200);
                        }

                    });
                });
        }

        return {
            psmodal,
            ps_error_dialog,
            openModal,
            actionClicked,
        }
    },
})
</script>
