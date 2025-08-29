<template>
    <div class="border-dashed border-2 border-secondary-200 dark:border-secondary-800 rounded p-2 flex gap-2 items-center">
        <input type="file" accept="image/*" ref="image" style="display: none;" @change="onImageSelected($event)">
        <ps-button :disabled="disabled"  @click="imageClick()" colors="bg-transparent" border="border border-secondary-300 dark:border-secondary-800" shadow="shadow" rounded="rounded">
            <ps-icon name="fileUpload2" w="24" h="24"/>
            <ps-label class="ms-2">
                {{ $t('core_be__upload_files') }}</ps-label>
        </ps-button>
        <ps-label :textColor="disabled ? disabledTheme : defaultTheme">
            {{ imageName ? imageName : title }}
        </ps-label>
    </div>
</template>

<script lang="ts">
import { ref, defineComponent, reactive } from "vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";

    export default defineComponent({
        name: "PsImageUpload2",
        components: {
            PsButton,
            PsIcon,
            PsLabel
        },
        props: {
            imageFile: {
                type: Object
            },
            title: {
                type: String,
                default: ""
            },
            placeholder: String,
            disabled: {
                type: Boolean,
                default: false
            },
            disabledTheme: {
                type: String,
                default: "text-secondary-300 dark:text-secondary-700 placeholder-secondary-300 dark:placeholder-secondary-700"
            },
            defaultTheme: {
                type: String,
                default: "text-secondary-800 dark:text-secondary-100 placeholder-secondary-800 dark:placeholder-secondary-100"
            },
        },
        setup(props , { emit }) {
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
                console.log(selectedFiles);

                previewImage.data = [];
                for(let i=0; i<selectedFiles.length; i++) {
                    const reader = new FileReader
                    reader.onload = e => {
                        previewImage.data.push(e.target ? e.target.result ? e.target.result.toString() : '' : '')
                    }
                    reader.readAsDataURL(selectedFiles[i])
                    selectedFile1 = selectedFiles[i];
                    imageName.value = selectedFile1.name;
                    imageSize.value = selectedFile1.size * (1  / 8 ) * (1 / 1000);
                    emit("update:imageFile", selectedFile1);
                }
            }

            function cancel(){
                previewImage.data = [];
            }

            return {
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
    })
</script>
