<template>
    <ps-modal ref="psmodal" line="hidden" maxWidth="480px" bodyHeight="574px" :isClickOut='false' theme="px-4 pt-4 pb-8 shadow-xl rounded-lg bg-white dark:bg-secondaryDark-black" >
        <template #title>
            <div class="hidden sm:flex justify-end ">
                <ps-icon @click="cancel()" name="cross" theme="text-secondary-400" />
            </div>
            <ps-icon class="mx-auto" name="cloud" w="79" h="75" theme="text-secondary-300 dark:text-secondary-600" />
            <ps-label-header-4 class="text-center font-normal text-2xl mt-6 mb-4"> {{ $t("ps_json_modal__title") }} </ps-label-header-4>

        </template>

        <template #body class="overflow-hidden">
            <div class="mx-2.5">
                <ps-draggable class=" mt-4 px-2.5 py-8" @drop.prevent="drop">
                    <!-- <ps-label textColor="">{{ $t("ps_csv_modal__drag_drop") }}</ps-label>
                    <span class="mt-2">{{ $t("ps_csv_modal__or") }}</span> -->
                    <ps-label v-if="fileName" textColor="mb-2">1 {{ $t("image_upload__file_is_choosen") }}</ps-label>
                    <ps-label v-else textColor="mb-2" >{{ $t("ps_image_upload__no_file_is_choosen") }}</ps-label>
                    <input type="file" accept=".json" ref="image" style="display: none;"  @change="onCsvSelected($event)">
                    <ps-button @click="defaultClick()" class="w-26 h-7 py-1 px-2 mt-2">{{ $t("ps_csv_modal__choose_files") }}</ps-button>
                    <span class="file-info" v-if="fileName">{{ $t("ps_csv_modal__file_name") }}: {{ fileName }}</span>
                    <!-- <ps-label textColor="text-secondary-600 mt-3">{{ $t("ps_csv_modal__follow_setps") }} : <br>
                    {{ $t("ps_csv_modal__follow_setp_1") }}<br>
                    {{ $t("ps_csv_modal__follow_setp_2") }} : <ps-link-1 :url="url" textColor="text-blue-500"> {{ $t('sample_download_here') }} </ps-link-1><br>
                    {{ $t("ps_csv_modal__follow_setp_3") }}</ps-label> -->
                </ps-draggable>
            </div>
        </template>

        <template #footer>
            <ps-button class="mx-auto mt-2" @click="actionClicked()"> {{ $t("ps_csv_modal__confirm_button") }} </ps-button>
        </template>
    </ps-modal>
</template>


<script lang="ts">
//Libs
import {ref} from 'vue';
import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsLabel from '@/Components/Core/Label/PsLabel.vue';
import PsLabelHeader4 from '@/Components/Core/Label/PsLabelHeader4.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsIcon from '@/Components/Core/Icons/PsIcon.vue';
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
import PsDraggable from '@/Components/Core/Draggable/PsDraggable.vue';

export default {
    name : "PsJsonModal",
    components : {
        PsLabelHeader4,
        PsLabel,
        PsLink1,
        PsButton,
        PsModal,
        PsIcon,
        PsDraggable
    },
    props: {
        url : {
            type: String,
            default: 'https://bit.ly/3G4MvHj',
        },
    },
    setup(props) {
        const psmodal = ref();
        const image = ref();

        let okClicked: Function;
        let cancelClicked: Function;
        let selectedFile;

        function cancel(){
            psmodal.value.toggle(false);
        }

        function openModal(okClickedAction: Function,
                cancelClickedAction: Function) {
            psmodal.value.toggle(true);
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
        }

        function defaultClick() {

            image.value.click();

        }

        let dropzoneFile = ref("");
        const drop = (e) => {
            dropzoneFile.value = e.dataTransfer.files[0];
        };

        // const selectedFile = () => {
        // dropzoneFile.value = document.querySelector(".dropzoneFile").files[0];
        // };
        let fileName = ref();
        function onCsvSelected(event) {
            const selectedFiles = event.target.files;
            selectedFile = selectedFiles[0];
            fileName.value = selectedFile.name;
        }

        function actionClicked(status) {

            okClicked(selectedFile);

            psmodal.value.toggle(false);
        }

        async function clicked() {
            psmodal.value.toggle(false);
        }

        return{
            psmodal,
            openModal,
            clicked,
            cancel,
            okClicked,
            cancelClicked,
            defaultClick,
            image,
            dropzoneFile,
            drop,
            onCsvSelected,
            actionClicked,
            fileName
        }
    }
}
</script>
