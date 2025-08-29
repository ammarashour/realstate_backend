<template>
    <ps-modal ref="psmodal" maxWidth="480px" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg shadow-xl" class=' z-50 h-56 bg-white'>
        <template #title>
            <div class="w-full text-start">
                <ps-label class="text-lg font-semibold"> {{title}} </ps-label>
            </div>
        </template>
        <template #body>
            <div class="w-full text-start mt-2">
                <ps-label class="font-light text-sm lg:text-base"> {{message}} </ps-label>

                <ps-label v-if="warningString" class="mt-3 font-light text-sm lg:text-base">
                    <span class="text-red-800 font-medium me-1">*</span>
                    {{warningString}}
                    <span class="text-red-800 font-medium ms-1">*</span>
                </ps-label>

            </div>
        </template>
        <template #footer>
            <div class=" flex flex-row justify-end">
                <ps-button v-if="showOkBtnFirst" rounded="rounded" @click="actionClicked('yes')" textSize="text-xs lg:text-sm" class="me-4"  > {{okButton}} </ps-button>
                <ps-button rounded="rounded" @click="actionClicked('no')" textSize="text-xs lg:text-sm" class="" border="border border-gray-200" colors="bg-white" hover="hover:outline-none hover:ring hover:ring-gray-100" > {{cancelButton}} </ps-button>
                <ps-button v-if="!showOkBtnFirst" rounded="rounded" @click="actionClicked('yes')" textSize="text-xs lg:text-sm" class="ms-4"  > {{okButton}} </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script lang="ts">
import { defineComponent,ref } from 'vue';
import PsModal from '../Modals/PsModal.vue';
import PsLabel from '../Label/PsLabel.vue';
import PsButton from '../Buttons/PsButton.vue';
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name : "PsConfirmDialog",
    components : {
        PsModal,
        PsLabel,
        PsButton
    },
    props : {
        showOkBtnFirst : {
            type: Boolean,
            default: false
        }
    },
    setup() {
        const psmodal = ref();
        const title = ref(trans('ps_confirm_dialog__confirm'));
        const message = ref(trans('ps_confirm_dialog__are_you_confirm'));
        const okButton = ref(trans('ps_confirm_dialog__yes'));
        const cancelButton = ref(trans('ps_confirm_dialog__no'));
        const warningString = ref(null);
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

        function openModal(
                titleString,
                messageString,
                okString,
                cancelString,
                okClickedAction: Function,
                cancelClickedAction: Function,
                warningStr = null) {
            title.value = titleString;
            message.value = messageString.toString();
            okButton.value = okString;
            cancelButton.value = cancelString;
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
            warningString.value = warningStr;
        }

        return {
            psmodal,
            title,
            message,
            openModal,
            actionClicked,
            okButton,
            cancelButton,
            warningString
        }
    },
})
</script>
