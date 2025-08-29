<template>
    <ps-modal ref="psmodal"  :isClickOut='false' >
        <template #title>

             <!-- Item entry title -->
            <div class="flex flex-col items-center mt-8">
                <ps-label-header-4 class="font-bold"> {{ $t('stripe_credit_card_modal__card_entry') }} </ps-label-header-4>
            </div>
        </template>
        <template #body>
            <StripeElement :element="cardElement" @change="event = $event" />
        </template>
        <template #footer>
            <div class="flex items-center justify-center mb-4">
                <ps-button class="text-center w-60 mx-auto  " @click="registerCard">{{ $t('stripe_credit_card_modal__save') }}</ps-button>
                <ps-button class="text-center w-60 mx-auto ms-4" theme="btn-second" @click="actionClicked('no')" >  {{ $t('stripe_credit_card_modal__cancel') }} </ps-button>
            </div>
        </template>
        <div v-if="event && event.error">{{ event.error.message }}</div>
    </ps-modal>

    <ps-loading-dialog ref="psloading"  :isClickOut='false'/>

    <ps-error-dialog ref="ps_error_dialog" />

</template>
<script lang='ts'>
import { defineComponent, ref } from 'vue';
import { useStripe, StripeElement } from 'vue-use-stripe';
import '@stripe/stripe-js';
import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsLabelHeader4 from '@/Components/Core/Label/PsLabelHeader4.vue';
import PsErrorDialog from '@/Components/Core/Dialog/PsErrorDialog.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsLoadingDialog from '@/Components/Core/Dialog/PsLoadingDialog.vue';
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';

export default defineComponent({
    components: {
        StripeElement,
        PsModal,
        PsLabelHeader4,
        PsButton,
        PsLoadingDialog,
        PsErrorDialog
    },
    setup() {
        const psmodal = ref();
        const psValueStore = PsValueStore();

        let okClicked: Function;
        let cancelClicked: Function;
        function actionClicked(status) {
            if(status == 'no') {
                cancelClicked();
            }
            psmodal.value.toggle(false);
        }

        function openModal( okClickedAction: Function, cancelClickedAction: Function){
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
        }

        //for stripe payment
        const event = ref();
        const stripe_publisbed_key = psValueStore.getPublishedKey();
        const {
            stripe,
            elements: [cardElement],
        } = useStripe({
            key: stripe_publisbed_key || '',
            elements: [{ type: 'card', options: {} }],
        });

        const registerCard = () => {
            if (event.value != null &&
                event.value?.complete != null &&
                event.value?.complete!) {
                stripe.value?.elements().create('card');

                stripe.value?.createToken(cardElement.value).then(function(result) {
                    // Handle result.error or result.token
                    localStorage.paymentNonce = result.token?.id;
                    okClicked();
                    psmodal.value.toggle(false);
                });

            }
        }

        return {
            psmodal,
            openModal,
            actionClicked,
            event,
            cardElement,
            registerCard,
        }
    },
})
</script>
