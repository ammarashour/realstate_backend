<template>
    <ps-modal ref="psmodal" maxWidth="450px" line="hidden" :isClickOut='false' theme=" px-6 py-7 rounded-lg shadow-xl" class=' z-20'>
        <template #title>
            <ps-icon @click="close()" name="close" class="text-sm text-secondary-400 ms-auto my-auto focus:shadow-none hover:text-purple-500 flex justify-end"  />
            <div class="flex flex-col text-center justify-center items-center text-gray-300">
                <ps-icon class="flex-grow-0" name="checkCircle" w="86" h="86" />
                <ps-label class="font-medium text-xl lg:text-2xl mt-3"> {{title}} </ps-label>
            </div>
        </template>
        <template #body>
            <div class="w-full text-center mt-4">
                <ps-label class="font-normal text-secondary-700 text-sm"> {{message}} </ps-label>
            </div>
        </template>
        <template #footer>
            <div class=" flex flex-row justify-center mt-6">
                <ps-button rounded="rounded" @click="actionClicked('no')" textSize="text-xs lg:text-sm" class=" me-3" border="border border-gray-200" colors="bg-none" hover="hover:outline-none hover:ring hover:ring-gray-100" > {{cancelButton}} </ps-button>
                <ps-button rounded="rounded" @click="actionClicked('yes')" textSize="text-xs lg:text-sm" class=""  > {{okButton}} </ps-button>
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
    name : "PsConfirmDialog",
    components : {
        PsModal,
        PsLabel,
        PsButton,
        PsIcon
    },
    setup() {
        const psmodal = ref();
        const title = ref(trans('ps_confirm_dialog__confirm'));
        const message = ref(trans('ps_confirm_dialog__are_you_confirm'));
        const okButton = ref(trans('ps_confirm_dialog__yes'));
        const cancelButton = ref(trans('ps_confirm_dialog__no'));
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
                cancelClickedAction: Function) {
            title.value = titleString;
            message.value = messageString.toString();
            okButton.value = okString;
            cancelButton.value = cancelString;
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
        }

        function close() {
            psmodal.value.toggle(false);
        }

        return {
            psmodal,
            title,
            message,
            openModal,
            actionClicked,
            okButton,
            cancelButton,
            close
        }
    },
})
</script>
