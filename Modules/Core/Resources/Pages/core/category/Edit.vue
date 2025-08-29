<template>
    <Head :title="$t('core__edit_category')" />

    <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />

    <ps-card class="w-full h-auto">

        <!-- Title -->
        <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
            <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_category_info')}}</ps-label-header-6>
        </div>

        <div class="w-full px-4 pt-6 sm:w-1/2">

            <!-- Category Name -->
            <ps-label class="mb-2 text-base">{{$t('core__category_label')}}<span class="font-medium text-red-800 ms-1">*</span>
            </ps-label>
            <ps-input-with-right-icon theme="text-secondary-500" rounded="rounded" :placeholder="$t('core__category_placeholder')"
                placeholderColor="placeholder-secondary-500" ref="name" :disabled="true" v-model:value="form.name">
                <template #icon>
                    <ps-icon name="editPencil" w="20" h="20" class="cursor-pointer " @click="handleCategoryNameEntry()"/>
                </template>
            </ps-input-with-right-icon>
            <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.name }}</ps-label-caption>

            <!-- Category Order -->
            <ps-label class="mb-2 text-base">{{$t('core__category_ordering_label')}}</ps-label>
            <ps-input type="text" v-model:value="form.ordering" :placeholder="$t('core__category_ordering_placeholder')"/>
            <ps-label-caption textColor="text-red-500 "
                class="block mt-2">{{props.errors.ordering}}</ps-label-caption>

            <!-- Category Photo -->
            <ps-label class="mb-2 text-base">{{$t('core__category_photo_label')}}<span class="font-medium text-red-800 ms-1">*</span>
            </ps-label>
            <ps-label-title-3 v-if="!category.cover"> {{ $t('core__be_recommended_size_400_200') }} </ps-label-title-3>
            <div v-if="category.cover" class="flex items-end pt-4">
                <img
                    v-lazy=" { src: $page.props.uploadUrl + '/' + category.cover.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                    class="object-cover h-48 w-96" alt="category cover" />
                <ps-button type="button" @click="handleReplaceImage(category.cover.id)"
                    rounded="rounded-full" shadow="drop-shadow-2xl" class="mb-2 -ms-10"
                    colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                    <ps-icon name="pencil-btn"  w="21" h="21" />
                </ps-button>
                <ps-image-icon-modal ref="ps_image_icon_modal" />
                <ps-action-modal ref="ps_action_modal" />
                <ps-danger-dialog ref="ps_danger_dialog" />
            </div>
            <ps-image-upload v-else uploadType="image" v-model:imageFile="form.cat_photo" />
            <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.cat_photo }}</ps-label-caption>

            <!-- Category Icon -->
            <ps-label class="mb-2 text-base">{{$t('core__category_icon_label')}}<span class="font-medium text-red-800 ms-1">*</span>
            </ps-label>
            <ps-label-title-3 v-if="!category.icon"> {{ $t('core__be_recommended_size_200_200') }}
            </ps-label-title-3>
            <div v-if="category.icon" class="flex items-end pt-4">
                <img
                v-lazy=" { src: $page.props.uploadUrl + '/' + category.icon.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                    class="object-cover w-48 h-48" alt="category icon" />
                <ps-button type="button" @click="handleReplaceIcon(category.icon.id)" rounded="rounded-full"
                    shadow="drop-shadow-2xl" class="mb-2 -ms-10"
                    colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                    <ps-icon name="pencil-btn"  w="21" h="21" />
                </ps-button>
                <ps-image-icon-modal ref="ps_image_icon_modal" ser/>
                <ps-action-modal ref="ps_action_modal" />
                <ps-danger-dialog ref="ps_danger_dialog" />
            </div>
            <ps-image-upload v-else uploadType="icon" v-model:imageFile="form.cat_icon" />
            <ps-label-caption textColor="text-red-500 " class="m-2">{{ errors.cat_icon }}</ps-label-caption>

            <!-- Publish Status -->
            <ps-label class="mb-2 text-base">{{$t('core__status_label')}}</ps-label>
            <ps-checkbox-value v-model:value="form.status" class="font-normal" :title="$t('core__publish_label')" />

            <!-- Error Message Card -->
            <ps-message-card
                    v-if="psMessageCardVisibleRef"
                    :flag = "status.flag"
                    class="mt-2 mb-6 text-white sm:mb-6 lg:mb-8"
                    ref="psMessageCardRef"
                    >{{ status.msg }}
                </ps-message-card>

            <!-- Action Buttons -->
            <div class="mb-2.5 flex flex-row justify-end">
                <ps-button @click="handleCancel()" type="reset" class="me-4" colors="text-primary-500" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                <ps-submit-button
                    ref="psSubmitButton"
                    :title="$t('core__be_btn_save')"
                    :titleSuccess="$t('core__be_btn_saved')"
                    @click="handleSubmit(category.id)"
                />
            </div>
        </div>

        <language-update-modal
            v-if="languageStringEntryModalVisibleRef"
            ref="languageStringEntryModalRef"
            @onSaved="languageStringSaved"/>

    </ps-card>

</template>

<script>
import PsLayout from "@/Components/PsLayout.vue";

export default {
   layout: PsLayout
};
</script>

<script setup>
import { trans } from 'laravel-vue-i18n';
import { useCategoryStoreState } from '@templateCore/store/modules/category/CategoryStore';
import { useImageStoreState } from '../../../../../TemplateCore/store/utilities/ImageStore';
import { ref, defineAsyncComponent, computed } from 'vue'
import { Head, useForm } from "@inertiajs/vue3";
import PsUtils from '@templateCore/utils/PsUtils';

import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsSubmitButton from "@/Components/Core/Buttons/PsSubmitButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";

const LanguageUpdateModal = defineAsyncComponent(() => import('../components/LanguageUpdateModal.vue'));
const PsMessageCard = defineAsyncComponent(() => import('@/Components/Core/Card/PsMessageCard.vue'));

const props = defineProps({
    errors: Object,
    status: Object,
    category: Object,
    validation: Object,
    languages: Array,
    categoryLanguages: Array
});

const breadcrumb = computed(() => [
    {
        label: trans('core__be_dashboard_label'),
        url: route('admin.index')
    },
    {
        label: trans('category_module'),
        url: route('category.index'),
    },
    {
        label:  trans('core__edit_category'),
        color: "text-primary-500"
    }
]);

const categoryStore = useCategoryStoreState();
const imageStore = useImageStoreState();

const psSubmitButton = ref();
const languageStringEntryModalRef = ref();
const languageStringEntryModalVisibleRef = ref(false);
const psMessageCardRef = ref();
const psMessageCardVisibleRef = ref(false);
const languageKey = "_name";

const ps_action_modal = ref();
const ps_image_icon_modal = ref();
const ps_danger_dialog = ref();

let form = useForm({
    name: props.category.name,
    ordering: props.category.ordering,
    status: props.category.status,
    nameForm: null,
    cover_id: props.category.cover ? props.category.cover.id : "",
    icon_id: props.category.icon ? props.category.icon.id : "",
    "_method": "put"
});

// Update Category
function handleSubmit(id) {
    categoryStore.updateCategoryInertia(id, form, (status) => {
        switch(status) {
            case "before":
                psSubmitButton.value?.startLoading();
            break;
            case "success":
                psSubmitButton.value?.endLoading(true);
            break;
            case "error":
                showErrorMessage();
                psSubmitButton.value?.endLoading(false);
            break;
        }
    })

}

async function showErrorMessage() {
    await PsUtils.waitingComponent(psMessageCardRef, psMessageCardVisibleRef);
    psMessageCardRef.value.show();
}

// Update Images
function handleReplaceImage(id) {
    let showDeleteCover = props.validation.includes('cat_photo');
    ps_action_modal.value.openModal(trans('core__be_replace_cat_photo'),
        trans('core__be_replace_img_label'),
        trans('core__be_remove_img_label'),
        'imagePlus',
        'cross',
        '24',
        '24',
        () => {
            ps_image_icon_modal.value.openModal(
                trans('core__be_upload_photo'),
                'cloudUpload',
                (imageFile) => {
                    imageStore.replaceImageInertia(id, imageFile);
                });
        },
        () => {
            ps_danger_dialog.value.openModal(
                trans('core__be_remove_label'),
                trans('core__be_are_u_sure_remove_photo'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    imageStore.deleteImageInertia(id, (status) => {
                        switch(status) {
                            case "before":
                                psSubmitButton.value.startLoading();
                            break;
                            case "success":
                                psSubmitButton.value.endLoading(true);
                            break;
                            case "error":
                                psSubmitButton.value.endLoading(false);
                            break;
                        }
                    });
                },
                () => { }
            );
        },
        !showDeleteCover
    );
}

function handleReplaceIcon(id) {
    let showDelete = props.validation.includes('cat_icon');
    ps_action_modal.value.openModal(trans('core__be_replace_cat_icon'),
        trans('core__be_replace_icon_label'),
        trans('core__be_remove_icon_label'),
        'image',
        'trash',
        '24',
        '24',

        () => {
            ps_image_icon_modal.value.openModal(
                trans('core__be_upload_icon'),
                'cloudUpload',
                (imageFile) => {
                    imageStore.replaceImageInertia(id, imageFile);
                });
        },
        () => {

            ps_danger_dialog.value.openModal(
                trans('core__be_remove_label'),
                trans('core__be_are_u_sure_remove_icon'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    imageStore.deleteImageInertia(id, (status) => {
                        switch(status) {
                            case "before":
                                psSubmitButton.value.startLoading();
                            break;
                            case "success":
                                psSubmitButton.value.endLoading(true);
                            break;
                            case "error":
                                psSubmitButton.value.endLoading(false);
                            break;
                        }
                    });
                },
                () => { }

            );
        },
        !showDelete
    );
}

// Category Name Entry For Multi Languages
async function handleCategoryNameEntry() {
    await PsUtils.waitingComponent(languageStringEntryModalRef, languageStringEntryModalVisibleRef);

    // Set Language Strings and Open Modal
    languageStringEntryModalRef.value.setLanguageStrings(languageKey, props.languages, props.categoryLanguages);
    languageStringEntryModalRef.value.openModal(languageKey, null, true);

}

function languageStringSaved(defaultValue, languageValues) {
    form.name = defaultValue;
    form.nameForm = languageValues;
}

// Cancel Edit
function handleCancel() {
    categoryStore.navigateToCategoryIndex();
}


</script>
