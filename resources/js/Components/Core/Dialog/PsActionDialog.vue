<template>
    <ps-modal ref="psmodal" maxWidth="480px" line="hidden" :isClickOut='false' theme=" px-6 py-6 rounded-sm" class=' z-20'>
        <template #title>
            <div class="w-full text-start">
                <ps-label class="font-medium text-lg lg:text-xl"> {{title}} </ps-label>
            </div>
        </template>
        <template #body>
            <div class="w-full text-start mt-2">
                <ps-label class="font-light text-sm lg:text-base"> {{message}} </ps-label>
            </div>
        </template>
        <template #footer>
            <div class=" flex justify-end">
                <div class="flex-grow-0">
                    <ps-button  rounded="rounded" @click="actionClicked()" textSize="text-xs lg:text-sm" class=""  > {{okButton}} </ps-button>
                </div>

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
    components : {
        PsModal,
        PsLabel,
        PsButton
    },
    setup() {
        const psmodal = ref();
        const title = ref(trans('ps_success_dialog__success'));
        const message = ref(trans('ps_success_dialog__success_message'));
        const okButton = ref(trans('ps_confirm_dialog__yes'));
        let okClicked: Function;

        function actionClicked() {
            okClicked();
            psmodal.value.toggle(false);
        }

        function openModal(
                titleString,
                messageString,
                okString,
                okClickedAction: Function, ) {
            title.value = titleString;
            message.value = messageString.toString();
            okButton.value = okString;
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
        }

        return {
            psmodal,
            title,
            message,
            openModal,
            actionClicked,
            okButton,
        }
    },
})
</script>
