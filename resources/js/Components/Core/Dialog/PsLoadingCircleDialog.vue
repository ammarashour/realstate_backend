<template>
    <ps-modal ref="psmodal" maxWidth="480px" :isClickOut='false' class="z-50" >
        <template #title>
             <div class="flex flex-row justify-center items-center text-center">
                <ps-loading theme="border-6 border-t-6 border-secondary-200 border-t-primary-500"  loadingSize="h-20 w-20" />
            </div>

        </template>
        <template #body>
            <div class="mt-4 flex flex-col justify-center items-center text-center">
                <ps-label class="text-2xl mb-4"> {{ title }} </ps-label>
                <ps-label class="text-base"> {{ message }} </ps-label>
            </div>
        </template>
    </ps-modal>
</template>

<script lang="ts">
import { defineComponent,ref } from 'vue';
import PsModal from '../Modals/PsModal.vue';
import PsLabel from '../Label/PsLabel.vue';
import PsLoading from "../Loading/PsLoading.vue";
import { trans } from 'laravel-vue-i18n';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";

export default defineComponent({
    components : {
        PsModal,
        PsLabel,
        PsLoading,
        PsButton
    },
    setup() {
        const psmodal = ref();
        const title = ref(trans('ps_loading_dialog__please_wait'));
        const message = ref(trans('ps_loading_dialog__loading'));

        function openModal(titleStr,messageStr) {
            title.value = titleStr;
            message.value = messageStr;
            psmodal.value.toggle(true);
        }

        function closeModal() {
            psmodal.value.toggle(false);
        }

        function setMessage(messageStr) {
            message.value = messageStr;
        }
        return {
            psmodal,
            openModal,
            closeModal,
            message,
            setMessage,
            title
        }
    },
})
</script>
