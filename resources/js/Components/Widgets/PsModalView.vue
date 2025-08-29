<template>

    <ps-loading-dialog ref="psloading" />

    <ps-modal ref="psmodal" maxWidth="350px" :isClickOut='false' class=' z-20'>
        <template #title>
            <ps-label-title> {{ $t("ps_model_view__title") }}</ps-label-title>
        </template>
        <template #body>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
            <ps-label> {{ $t("ps_model_view__message") }} </ps-label>
        </template>
        <template #footer>
            <div class="flex flex-row justify-end">
                <ps-button @click="psloading.openModal()"> {{ $t("ps_model_view__save") }} </ps-button>
                <ps-button @click="psmodal.toggle(false)"> {{ $t("ps_model_view__end") }} </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script>
import { defineComponent,ref } from 'vue'
import PsModal from '../Core/Modals/PsModal.vue';
import PsLabel from '../Core/Label/PsLabel.vue';
import PsLabelTitle from '../Core/Label/PsLabelTitle.vue';
import PsButton from '../Core/Buttons/PsButton.vue';
import PsLoadingDialog from '../Core/Dialog/PsLoadingDialog.vue';

export default defineComponent({
    name : "PsModelView",
    components : {
        PsModal,
        PsLabel,
        PsLabelTitle,
        PsLoadingDialog,
        PsButton
    },
    setup() {
        const psmodal = ref();
        const psloading = ref();

        function openModal() {
            psmodal.value.toggle(true);
        }
        return {
            psmodal,
            openModal,
            psloading
        }
    },
})
</script>
