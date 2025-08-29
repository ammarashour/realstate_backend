<template>
    <ps-modal ref="psmodal" maxWidth="480px" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-lg shadow-xl" class=' z-20'>
        <template #title>
            <div class="flex flex-auto items-center ">
            <ps-icon name="alert-triangle"  class="text-yellow-500 me-2.5" />
            <ps-label class="text-lg font-semibold">
                {{title}}
            </ps-label>
            </div>
        </template>
        <template #body>
            <div class="w-full text-start mt-2">
                <ps-label class="font-light text-sm lg:text-base"> {{message}} </ps-label>
            </div>
        </template>
        <template #footer>
            <div class=" flex flex-row justify-end">
                <ps-button rounded="rounded" v-if="haveCancleStr" @click="actionClicked('no')" textSize="text-xs lg:text-sm" class=" me-2" border="border border-gray-200" colors="bg-none" hover="hover:outline-none hover:ring hover:ring-gray-100" > {{cancelButton}} </ps-button>
                <ps-button rounded="rounded" @click="actionClicked('yes')" textSize="text-xs lg:text-sm" class="" colors="bg-blue-500 text-white"  > {{okButton}} </ps-button>
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
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

export default defineComponent({
    name: "PsWarningDialog",
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsIcon
    },
    setup() {
        const psmodal = ref();
        const title = ref(trans('ps_warning_dialog__warning'));
        const message = ref(trans('ps_warning_dialog__warning_message'));

        const okButton = ref(trans('ps_confirm_dialog__yes'));
        const cancelButton = ref(trans('ps_confirm_dialog__no'));
        const haveCancleStr = ref(false);
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

        function openModal(titleStr,
                messageStr,
                okString,
                cancelString = null,
                okClickedAction: Function,
                cancelClickedAction: Function) {
             if(titleStr != null && titleStr.trim() != '') {
                title.value = titleStr;
            }

            if(messageStr != null && messageStr.trim() != '') {
                message.value = messageStr;
            }
            if(cancelString != null && cancelString.trim() != '') {
                haveCancleStr.value = true;
                cancelButton.value = cancelString;
            }

            if(okString != null && okString.trim() != '') {
                okButton.value = okString;
            }
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;

        }

        function closeModal() {
            psmodal.value.toggle(false);
        }
        return {
            psmodal,
            openModal,
            closeModal,
            title,
            actionClicked,
            okButton,
            cancelButton,
            haveCancleStr,
            message
        }
    },
})
</script>
