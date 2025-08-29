<template>
    <div class="w-full">
        <ps-draggable
            v-if="previewImage.data != '' || imagePath == null"
            :disabled="disabled"
            class="mt-4 px-2.5 h-52 rounded-lg"
            :class="previewImage.data[0] ? 'border-b-0' : ''"
        >
            <ps-icon
                name="small-cloud"
                :theme="
                    disabled
                        ? 'text-secondary-300 dark:text-secondary-700'
                        : 'text-secondary-500'
                "
            />
            <!-- <ps-label :textColor="disabled?'text-secondary-300 dark:text-secondary-700':'text-secondary-500'">{{ $t("ps_image_upload__drag_drop") }}</ps-label> -->
            <ps-label
                v-if="previewImage.data != ''"
                :textColor="
                    disabled
                        ? 'text-secondary-300 dark:text-secondary-700 mb-2'
                        : 'text-secondary-500  mb-2'
                "
                >{{ previewImage.data.length }}
                {{ $t("ps_image_upload_file_is_chosen_text") }}</ps-label
            >
            <ps-label
                v-else
                :textColor="
                    disabled
                        ? 'text-secondary-300 dark:text-secondary-700 mb-2'
                        : 'text-secondary-500  mb-2'
                "
                >{{ $t("ps_image_upload_no_file_is_chosen_text") }}</ps-label
            >
            <!-- <ps-label class="mt-2 text-secondary-500" :textColor="disabled?'text-secondary-300 dark:text-secondary-700':'text-secondary-500'">{{ $t('core__be_or') }}</ps-label> -->
            <input
                type="file"
                accept="image/*"
                ref="image"
                style="display: none"
                @change="onImageSelected($event)"
            />
            <ps-button
                v-if="disabled == false"
                type="button"
                @click="imageClick()"
                class="px-2 py-1 mt-2 w-26"
                rounded="rounded"
                >{{ $t("ps_image_upload__choose_files") }}</ps-button
            >
            <ps-button
                v-else
                type="button"
                :disabled="true"
                colors="bg-secondary-600 text-white"
                class="px-2 py-1 mt-2 w-26"
                rounded="rounded"
                >{{ $t("ps_image_upload__choose_files") }}</ps-button
            >
        </ps-draggable>
        <div
            v-if="previewImage.data != ''"
            class="flex flex-row items-center h-16 bg-primary-50 dark:bg-indigo-900 dark:rounded-b-lg"
        >
            <div class="w-20">
                <img
                    alt="Placeholder"
                    class="flex items-center justify-center object-cover w-32 h-16 bg-transparent dark:rounded"
                    width="68px"
                    height="62px"
                    :src="getImageUrl()"
                />
            </div>
            <div class="flex justify-between w-full mx-4">
                <p class="mt-2 flex-rows">
                    <ps-label class="font-bold">{{ imageName }}</ps-label>
                    <ps-label-title-3 class="dark:text-gray-500"
                        >{{ $t("ps_image_upload__image_size") }}
                        {{ imageSize }}</ps-label-title-3
                    >
                </p>
                <ps-icon-1
                    v-if="cancelAble"
                    @click="cancel()"
                    w="16"
                    h="16"
                    class="cursor-pointer"
                    name="cross"
                    theme="#EF4444"
                />
            </div>
        </div>

        <div v-else-if="imagePath != null" class="flex items-end pt-4">
            <img
                v-lazy="{
                    src: imagePath,
                    loading: $page.props.sysImageUrl + '/loading_gif.gif',
                    error: $page.props.sysImageUrl + '/default_photo.png',
                }"
                width="400"
                height="200"
                class="h-48 w-auto"
                alt="blog cover"
            />
            <input
                type="file"
                accept="image/*"
                ref="image"
                style="display: none"
                @change="onImageSelected($event)"
            />
            <ps-button
                type="button"
                @click="imageClick()"
                rounded="rounded-full"
                shadow="drop-shadow-2xl"
                class="mb-2 -ms-10"
                colors="bg-white text-primary-500 dark:bg-secondaryDark-black"
                border="border border-1 dark:border-secondary-700 border-secondary-300"
                padding="p-1.5"
            >
                <ps-icon name="pencil-btn" />
            </ps-button>
            <ps-image-icon-modal ref="ps_image_icon_modal" />
        </div>


    </div>
</template>

<script lang="ts">
import { reactive, ref, watch } from "vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsIcon1 from "@/Components/Core/Icons/PsIcon1.vue";
import PsDraggable from "@/Components/Core/Draggable/PsDraggable.vue";
export default {
    name: "PsImageUpload",
    props: {
        imageFile: {
            type: Object,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        imagePath: {
            type: String,
            default: null,
        },
        cancelAble: {
            type: Boolean,
            default : true
        }
    },
    components: {
        PsButton,
        PsLabel,
        PsLabelTitle3,
        PsIcon,
        PsDraggable,
        PsIcon1,
    },
    setup(props, { emit }) {
        const previewImage = reactive({
            data: [] as any,
        });
        watch(()=>props.imagePath, ()=>{
            previewImage.data = '';
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
            for (let i = 0; i < selectedFiles.length; i++) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    previewImage.data.push(
                        e.target
                            ? e.target.result
                                ? e.target.result.toString()
                                : ""
                            : ""
                    );
                };
                reader.readAsDataURL(selectedFiles[i]);
                selectedFile1 = selectedFiles[i];
                imageName.value = selectedFile1.name;
                imageSize.value = selectedFile1.size * (1 / 8) * (1 / 1000);
                emit("update:imageFile", selectedFile1);
            }
        }

        function cancel() {
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
            cancel,
        };
    },
};
</script>
