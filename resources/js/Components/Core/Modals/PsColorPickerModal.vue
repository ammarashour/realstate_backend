<template>
    <ps-modal ref="psmodal" line="hidden" maxWidth="300px" bodyHeight="438px" :isClickOut='false' theme="shadow rounded px-6 py-4" >
        <template #title>
            <div class="flex flex-row justify-between mb-2">
                <ps-label> {{ color }} </ps-label>
                <ps-icon-1 @click="cancel()" name="cross" theme="#9CA3AF" class="cursor-pointer"/>
            </div>
        </template>

        <template #body class="overflow-hidden">
            <div v-if="dataReady" class="">
                <ps-color-picker v-model:color="color"/>
            </div>
        </template>

        <template #footer>
            <div class="flex flex-row justify-end mt-6">
                <ps-button @click="cancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t("core__be_btn_cancel") }}</ps-button>
                <ps-button @click="actionClicked('yes')" class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="">
                    {{ $t("core__be_btn_save") }}
                </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script lang="ts">
//Libs
import {reactive,ref} from 'vue';
import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsLabelHeader4 from '@/Components/Core/Label/PsLabelHeader4.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsIcon1 from '@/Components/Core/Icons/PsIcon1.vue';
import PsColorPicker from '@/Components/Core/Picker/PsColorPicker.vue';
import { trans } from 'laravel-vue-i18n';
import PsLabel from '@/Components/Core/Label/PsLabel.vue';

export default {
    name : "PsImageIconModal",
    components : {
        PsLabelHeader4,
        PsButton,
        PsModal,
        PsIcon1,
        PsColorPicker,
        PsLabel
    },
    setup() {
        const color = ref('#000000');
        const dataReady = ref(false);
        let icon = ref('cloud');
        const psmodal = ref();

        let okClicked: Function;
        let cancelClicked: Function;

        function colorPickerClicked() {
            color.value.click();
        }

        function openModal(colorString,okClickedAction: Function,cancelClickedAction: Function) {
            dataReady.value = false;
            psmodal.value.toggle(true);
            color.value = colorString;
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
            dataReady.value = true;
        }

        function actionClicked(status) {
            if(status == 'yes') {
                okClicked(color.value);
            }else {
                cancelClicked();
            }
            psmodal.value.toggle(false);
            dataReady.value = false;
        }

        async function clicked() {
            psmodal.value.toggle(false);
        }
        function cancel(){
            psmodal.value.toggle(false);
            dataReady.value = false;
        }

        function close() {
            psmodal.value.toggle(false);
            dataReady.value = false;
        }

        return{
            color,
            icon,
            psmodal,
            openModal,
            clicked,
            actionClicked,
            cancelClicked,
            okClicked,
            cancel,
            close,
            colorPickerClicked,
            dataReady
        }
    }
}
</script>
