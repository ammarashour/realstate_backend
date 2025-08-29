<template>
    <ps-modal ref="psmodal" maxWidth="350px" :isClickOut='false' class=' z-20'>
        <template #title>
            <ps-label-title> {{title}} </ps-label-title>
        </template>
        <template #body>
            <div v-if="dataReady">

                <ps-label class="ms-1"> {{ $t('user_phone_login_verification_modal__please_input_code') }} </ps-label>
                <ps-input class='my-3 ms-1 w-5/6' v-model:value="verificationCode" />
            </div>
        </template>
        <template #footer>
            <div class="flex flex-row justify-end">
                <ps-button v-if="isConfirmed" @click="actionClicked('yes')"> {{okButton}} </ps-button>
                <ps-button v-else  @click="actionClicked('confirm')">{{ $t('user_phone_login_verification_modal__confirm') }} </ps-button>
                <ps-button @click="actionClicked('no')" theme="input-second" class="ms-4"> {{cancelButton}} </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script lang='ts'>
import { defineComponent,ref } from 'vue';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsLabelTitle from '@template1/vendor/components/core/label/PsLabelTitle.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';

import PsInput from '@template1/vendor/components/core/input/PsInput.vue';

export default defineComponent({
    name : "UserPhoneLoginVerificationModal",
    components : {
        PsModal,
        PsLabel,
        PsLabelTitle,
        PsButton,
        PsInput
    },
    setup() {
        const psmodal = ref();
        const title = ref("Confirm");
        const dataReady = ref(false);
        const message = ref("Are you confirm ?");
        const okButton = ref("Yes");
        const cancelButton = ref("No");
        const isConfirmed = ref(false);
        const verificationCode = ref('');
        let okClicked: Function;
        let cancelClicked: Function;

        function actionClicked(status) {
            if(verificationCode.value != '') {
                if(status == 'yes') {
                    okClicked(verificationCode.value);
                    psmodal.value.toggle(false);
                } else if(status == 'confirm') {
                    isConfirmed.value = true;
                }else {
                    cancelClicked();
                    psmodal.value.toggle(false);
                    dataReady.value = false;
                }
            }else {
                if(status == 'no') {
                    psmodal.value.toggle(false);
                    dataReady.value = false;
                }
            }

        }

        function openModal(
                titleString,
                messageString,
                okString,
                cancelString,
                okClickedAction: Function,
                cancelClickedAction: Function) {
            title.value = titleString;
            message.value = messageString.toString();
            okButton.value = okString;
            cancelButton.value = cancelString;
            dataReady.value = true;
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
            isConfirmed.value = false;
        }

        return {
            psmodal,
            title,
            message,
            openModal,
            actionClicked,
            okButton,
            cancelButton,
            verificationCode,
            isConfirmed,
            dataReady
        }
    }
})
</script>
