<template>
    <Head :title="$t('core__be_edit_subcategory')" />

    <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />

    <ps-card class="w-full h-auto">
        <!-- Title -->
        <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
            <ps-label-header-6 textColor=" text-secondary-800 dark:text-secondary-100">{{$t('core__be_subcategory_info')}}</ps-label-header-6>
        </div>

        <div class="w-full px-4 pt-6 sm:w-1/2">
            <!-- Category Selection -->
            <ps-label class="text-base mb-2">{{$t('core_be_select_category')}}<span class="text-red-800 font-medium ms-1">*</span>
            </ps-label>
            <ps-dropdown class='lg:mt-2 mt-1  w-full'>
                <template #select>
                    <ps-dropdown-select :placeholder="$t('core_be_select_category')"
                        :selectedValue="(form.category_id == '') ? '' : categories.filter(category => category.id == form.category_id)[0].name"
                        />
                </template>
                <template #list>
                    <div class="rounded-md shadow-xs w-56 ">
                        <div class="pt-2 z-30 ">
                            <div v-if="categories.length == null">
                                <ps-label class='p-2 flex' @click="route('category.index')">
                                    {{$t('core_be_create_new_category')}}</ps-label>
                            </div>
                            <div v-else>
                                <!-- <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                    @click="[form.category_id = '', validateEmptyInput('category_id', form.category_id)]">
                                    <ps-label class="text-secondary-200">{{$t('core_be_select_category')}}</ps-label>
                                </div> -->
                                <div v-for="category in categories" :key="category.id"
                                    class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                    @click="[form.category_id = category.id, validateEmptyInput('category_id', form.category_id)]">
                                    <ps-label class="ms-2"
                                        :class="category.id == form.category_id ? ' font-bold' : ''">
                                        {{ category.name }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </ps-dropdown>
            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.category_id }}</ps-label-caption>

            <!-- SubCategory Name -->
            <ps-label class="mb-2 text-base">{{$t('core__be_subcategory_label')}}<span class="text-red-500 ms-1" >*</span></ps-label>
            <ps-input-with-right-icon theme="text-secondary-500"
                rounded="rounded"
                :placeholder="$t('core__be_subcategory_placeholder')"
                placeholderColor="placeholder-secondary-500"
                :disabled="true"
                v-model:value="form.name">
                <template #icon>
                    <ps-icon name="editPencil" w="20" h="20" class=" cursor-pointer" @click="handleSubCategoryNameEntry"/>
                </template>
            </ps-input-with-right-icon>
            <ps-label-caption textColor="text-red-500 "
                class="block mt-2">{{props.errors.name}}</ps-label-caption>

            <!-- Sub Category Photo -->
            <ps-label class="mb-2 text-base">{{$t('core__be_subcategory_photo')}}<span class="font-medium text-red-800 ms-1">*</span>
            </ps-label>
            <ps-label-title-3 v-if="!subcategory.cover"> {{ $t('core__be_recommended_size_400_200') }} </ps-label-title-3>
            <div v-if="subcategory.cover" class="flex items-end pt-4">
                <img
                    v-lazy=" { src: $page.props.uploadUrl + '/' + subcategory.cover.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                    class="object-cover h-48 w-96" alt="subcategory cover" />
                <ps-button type="button" @click="handleReplaceImage(subcategory.cover.id)"
                    rounded="rounded-full" shadow="drop-shadow-2xl" class="mb-2 -ms-10"
                    colors="bg-white text-primary-500 dark:bg-secondaryDark-black" padding="p-1.5" hover="" focus="">
                    <ps-icon name="pencil-btn"  w="21" h="21" />
                </ps-button>
                <ps-image-icon-modal ref="ps_image_icon_modal" />
                <ps-action-modal ref="ps_action_modal" />
                <ps-danger-dialog ref="ps_danger_dialog" />
            </div>
            <ps-image-upload v-else uploadType="image" v-model:imageFile="form.cover" />
            <ps-label-caption textColor="text-red-500 " class="block mt-2">{{ errors.cover }}</ps-label-caption>

            <!-- Sub Category Icon -->
            <ps-label class="mb-2 text-base">{{$t('core__be_subcategory_icon')}}<span class="font-medium text-red-800 ms-1">*</span>
            </ps-label>
            <ps-label-title-3 v-if="!subcategory.icon"> {{ $t('core__be_recommended_size_200_200') }}
            </ps-label-title-3>
            <div v-if="subcategory.icon" class="flex items-end pt-4">
                <img
                v-lazy=" { src: $page.props.uploadUrl + '/' + subcategory.icon.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                    class="object-cover w-48 h-48" alt="subcategory icon" />
                <ps-button type="button" @click="handleReplaceIcon(subcategory.icon.id)" rounded="rounded-full"
                    shadow="drop-shadow-2xl" class="mb-2 -ms-10"
                    colors="bg-white text-primary-500 dark:bg-secondaryDark-black" padding="p-1.5" hover="" focus="">
                    <ps-icon name="pencil-btn"  w="21" h="21" />
                </ps-button>
                <ps-image-icon-modal ref="ps_image_icon_modal" ser/>
                <ps-action-modal ref="ps_action_modal" />
                <ps-danger-dialog ref="ps_danger_dialog" />
            </div>
            <ps-image-upload v-else uploadType="icon" v-model:imageFile="form.icon" />
            <ps-label-caption textColor="text-red-500 " class="m-2">{{ errors.icon }}</ps-label-caption>

            <!-- Error Message Card -->
            <ps-message-card
                    v-if="psMessageCardVisibleRef"
                    :flag = "status.flag"
                    class="mt-2 mb-6 text-white sm:mb-6 lg:mb-8"
                    ref="psMessageCardRef"
                    >{{ status.msg }}
                </ps-message-card>

            <!-- Publish Status -->
            <ps-label class="text-base mb-2">{{$t('core__status_label')}}</ps-label>
            <ps-checkbox-value v-model:value="form.status" :title="$t('core__publish_label')" />


            <!-- Action Buttons -->
            <div class="mb-2.5 flex flex-row justify-end">
                <ps-button @click="handleCancel()" type="reset" class="me-4" colors="text-primary-500" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                <ps-submit-button
                    ref="psSubmitButton"
                    :title="$t('core__be_btn_save')"
                    :titleSuccess="$t('core__be_btn_saved')"
                    @click="handleSubmit(subcategory.id)"
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
import { computed, defineAsyncComponent, ref } from 'vue'
import { Head, useForm } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import PsUtils from '@templateCore/utils/PsUtils';

import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsSubmitButton from "@/Components/Core/Buttons/PsSubmitButton.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";

import { useSubCategoryStoreState } from '@templateCore/store/modules/category/SubCategoryStore';
import { useImageStoreState } from '@templateCore/store/utilities/ImageStore';

const LanguageUpdateModal = defineAsyncComponent(() => import('../components/LanguageUpdateModal.vue'));
const PsMessageCard = defineAsyncComponent(() => import('@/Components/Core/Card/PsMessageCard.vue'));

const props = defineProps({
    errors: Object,
    status: Object,
    subcategory: Object,
    categories: Array,
    validation: Object,
    languages: Array,
    subcategoryLanguages: Array
});

const breadcrumb = computed(() => [
    {
        label: trans('core__be_dashboard_label'),
        url: route('admin.index')
    },
    {
        label: trans('subcategory_module'),
        url: route('subcategory.index'),
    },
    {
        label: trans('core__be_edit_subcategory'),
        color: "text-primary-500"
    }
]);

const subCategoryStore = useSubCategoryStoreState();
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
    name: props.subcategory.name,
    status: props.subcategory.status,
    nameForm: null,
    category_id: props.categories.find(element=> element.id == props.subcategory.category_id) ? props.subcategory.category_id : '',
    cover_id: props.subcategory.cover ? props.subcategory.cover.id : "",
    icon_id: props.subcategory.icon ? props.subcategory.icon.id : "",
    "_method": "put"
});

// Update Category
function handleSubmit(id) {
    subCategoryStore.updateSubCategoryInertia(id, form, (status) => {
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
    let showDeleteCover = props.validation.includes('cover');
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
    let showDelete = props.validation.includes('icon');
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
async function handleSubCategoryNameEntry() {
    await PsUtils.waitingComponent(languageStringEntryModalRef, languageStringEntryModalVisibleRef);

    // Set Language Strings and Open Modal
    languageStringEntryModalRef.value.setLanguageStrings(languageKey, props.languages, props.subcategoryLanguages);
    languageStringEntryModalRef.value.openModal(languageKey, null, true);

}

function languageStringSaved(defaultValue, languageValues) {
    form.name = defaultValue;
    form.nameForm = languageValues;
}

// Cancel Edit
function handleCancel() {
    subCategoryStore.navigateToSubCategoryIndex();
}

</script>
