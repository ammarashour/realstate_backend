<template>
    <ps-modal ref="psmodal" line="hidden" maxWidth="480px" bodyHeight="438px" :isClickOut='false' class="shadow-xl rounded-lg" >
        <template #title>
            <div class="hidden sm:flex justify-end ">
                <ps-icon-1 @click="cancel()" name="cross" theme="#9CA3AF" />
            </div>
            <ps-icon-1 class="mx-auto" :name="icon" w="79" h="75" theme="#D1D5DB" />
            <ps-label-header-4 class="text-center font-normal text-2xl mt-6 mb-4"> {{ title }} </ps-label-header-4>

        </template>

        <template #body class="overflow-hidden">
            <div class="mx-2.5">
                <ps-draggable :disabled="disabled" class=" mt-4 px-2.5 h-52 rounded-lg " :class="previewImage.data[0] ? 'border-b-0' : ''">
                    <!-- <span>{{ $t("ps_image_icon_modal__drag_drop") }}</span>
                    <span class="mt-2">{{$t('ps_image_icon_modal__or')}}</span> -->
                    <span v-if="previewImage.data !=''" textColor="mb-2">{{previewImage.data.length}} {{ $t("ps_image_upload__file_is_choosen") }}</span>
                    <ps-label v-else >{{ $t("ps_image_upload__no_file_is_choosen") }}</ps-label>
                    <input type="file" accept=".jpg,.jpeg,.png" ref="image" style="display: none;" @change="onImageSelected($event)">
                    <ps-button :disabled="disabled" type="button" @click="imageClick()" class="w-26 h-12 py-1 px-2 mt-2">{{$t('ps_image_icon_modal__choose_files')}}</ps-button>
                </ps-draggable>
                <div v-if="previewImage.data !=''" class="flex flex-row items-center bg-primary-50 h-16">
                    <div class="w-20">
                        <img alt="Placeholder" class="bg-transparent w-20 h-16 flex items-center justify-center object-cover" width="68px" height="62px" :src="getImageUrl()" >
                    </div>
                    <p class="ms-4 mt-2">
                        <ps-label class="font-bold">{{ imageName }}</ps-label><br/>
                        <ps-label-title-3>{{$t('ps_image_icon_modal__image_size')}}{{ imageSize }}</ps-label-title-3>
                    </p>
                </div>
            </div>
        </template>

        <template #footer>
            <ps-button class="mx-auto mt-2" @click="actionClicked()"> {{ $t("ps_csv_modal__confirm_button") }} </ps-button>
        </template>
    </ps-modal>
</template>


<script lang="ts">
//Libs
import {reactive,ref} from 'vue';
import PsModal from '@/Components/Core/Modals/PsModal.vue';
import PsLabelHeader4 from '@/Components/Core/Label/PsLabelHeader4.vue';
import PsLabel from '@/Components/Core/Label/PsLabel.vue';
import PsButton from '@/Components/Core/Buttons/PsButton.vue';
import PsIcon1 from '@/Components/Core/Icons/PsIcon1.vue';
import PsDraggable from '@/Components/Core/Draggable/PsDraggable.vue';
import { trans } from 'laravel-vue-i18n';

export default {
    name : "PsImageIconModal",
    components : {
        PsLabelHeader4,
        PsButton,
        PsModal,
        PsIcon1,
        PsLabel,
        PsDraggable
    },
    props: {
        'disabled': { type: Boolean, default: false },
    },
    setup() {
        const title = ref(trans('ps_csv_modal__title'));
        let icon = ref('cloud');
        const psmodal = ref();
        const previewImage = reactive({
            data : [] as any
        });
        const image = ref();
        let selectedFile1;

        function imageClick() {
            image.value.click();
        }

        function getImageUrl() {
            return previewImage.data[0];
        }
        let imageName = ref();
        let imageSize = ref();
        function onImageSelected(event) {
            const selectedFiles = event.target.files;
            previewImage.data = [];
            for(let i=0; i<selectedFiles.length; i++) {
                const reader = new FileReader
                reader.onload = e => {
                    previewImage.data.push(e.target ? e.target.result ? e.target.result.toString() : '' : '')
                }
                reader.readAsDataURL(selectedFiles[i])
                selectedFile1 = selectedFiles[i];
                imageName.value = selectedFile1.name;
                imageSize.value = selectedFile1.size *(2 / 20) * (1 / 1024);

            }
        }

        let okClicked: Function;
        let cancelClicked: Function;

        function openModal(
                titleString,
                iconString,
                okClickedAction: Function,
                cancelClickedAction: Function) {
            psmodal.value.toggle(true);
            title.value = titleString;
            icon.value = iconString;
            okClicked = okClickedAction;
            cancelClicked= cancelClickedAction;
        }

        function defaultClick() {

            image.value.click();

        }

        function actionClicked(status) {

            okClicked(selectedFile1);

            psmodal.value.toggle(false);
        }

        async function clicked() {
            psmodal.value.toggle(false);
        }
        function cancel(){
            psmodal.value.toggle(false);
        }

        return{
            title,
            icon,
            psmodal,
            openModal,
            clicked,
            okClicked,
            actionClicked,
            cancelClicked,
            imageClick,
            image,
            onImageSelected,
            previewImage,
            getImageUrl,
            imageName,
            imageSize,
            cancel
        }
    }
}
</script>
