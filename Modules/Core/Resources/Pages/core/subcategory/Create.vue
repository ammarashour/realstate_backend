<template>
    <Head :title="$t('core__be_add_subcategory')" />

    <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />

    <ps-card class="w-full h-auto">
        <div class="rounded-xl">
            <!-- Title -->
            <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_subcategory_info')}}</ps-label-header-6>
            </div>

            <div class="w-full px-4 pt-6 sm:w-1/2">
                <!-- Category Selection -->
                <ps-label class="text-base mb-2">{{$t('core__category_label')}}<span class="text-red-800 font-medium ms-1">*</span> </ps-label>
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
                                    <div v-for="category in categories" :key="category.id"
                                        class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-secondary-700 cursor-pointer items-center"
                                        @click="[form.category_id = category.id]">
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
                <!-- </div> -->

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
                <ps-label class="text-base mb-2">{{ $t('core__be_subcategory_photo') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                <ps-label-title-3>{{ $t('core__be_recommended_size_400_200') }}</ps-label-title-3>
                <ps-image-upload uploadType="image" v-model:imageFile="form.sub_cat_photo" />
                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.sub_cat_photo }}</ps-label-caption>

                <!-- Category Icon -->
                <ps-label class="text-base mb-2">{{ $t('core__be_subcategory_icon') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                <ps-label-title-3>{{ $t('core__be_recommended_size_200_200') }}</ps-label-title-3>
                <ps-image-upload class="w-72" uploadType="icon" v-model:imageFile="form.sub_cat_icon" />
                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.sub_cat_icon }}</ps-label-caption>

                <!-- Publish Status -->
                <ps-label class="text-base mb-2">{{$t('core__status_label')}}</ps-label>
                <ps-checkbox-value v-model:value="form.status" :title="$t('core__publish_label')" />

                <!-- Error Message Card -->
                <ps-message-card
                    v-if="psMessageCardVisibleRef"
                    :flag = "status != null ? status.flag : ''"
                    class="mt-2 mb-6 text-white sm:mb-6 lg:mb-8"
                    ref="psMessageCardRef"
                    >{{ status.msg }}
                </ps-message-card>

                <div class="flex flex-row justify-end mb-2.5">
                    <ps-button @click="handleCancel()" type="reset" class="me-4" colors="text-primary-500" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                    <ps-submit-button
                        ref="psSubmitButton"
                        :title="$t('core__be_btn_save')"
                        :titleSuccess="$t('core__be_btn_saved')"
                        @click="handleSubmit"
                    />
                </div>
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
import { useSubCategoryStoreState } from '@templateCore/store/modules/category/SubCategoryStore';

import PsUtils from '@templateCore/utils/PsUtils';
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsInputWithRightIcon from "@/Components/Core/Input/PsInputWithRightIcon.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsSubmitButton from "@/Components/Core/Buttons/PsSubmitButton.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";

const LanguageUpdateModal = defineAsyncComponent(() => import('../components/LanguageUpdateModal.vue'));
const PsMessageCard = defineAsyncComponent(() => import('@/Components/Core/Card/PsMessageCard.vue'));

const props = defineProps({
    errors: Object,
    status: Object,
    languages: Array,
    categories: Array
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
        label: trans('core__be_add_subcategory'),
        color: "text-primary-500"
    }
]);

const psSubmitButton = ref();
const languageStringEntryModalRef = ref();
const languageStringEntryModalVisibleRef = ref(false);
const psMessageCardRef = ref();
const psMessageCardVisibleRef = ref(false);
const languageKey = "_name";
const subCategoryStore = useSubCategoryStoreState();

let form = useForm({
    name: "",
    category_id: "",
    sub_cat_photo: "",
    sub_cat_icon: "",
    status: false,
    extra_caption:[],
    images:[],
    nameForm:[],
});

// Sub Category Name Entry For Multi Languages
async function handleSubCategoryNameEntry() {
    await PsUtils.waitingComponent(languageStringEntryModalRef, languageStringEntryModalVisibleRef);

    // Set Language Strings and Open Modal
    languageStringEntryModalRef.value.setLanguageStrings(languageKey, props.languages);
    languageStringEntryModalRef.value.openModal(languageKey, null, true);
}

function languageStringSaved(defaultValue, languageValues) {
    form.name = defaultValue;
    form.nameForm = languageValues;
}

// Save Category
function handleSubmit() {
    subCategoryStore.saveSubCategoryInertia(form, (status, message) => {
        switch(status) {
            case "before":
                psSubmitButton.value.startLoading();
            break;
            case "success":
                psSubmitButton.value.endLoading(true);
            break;
            case "error":
                if(message != null && message != '')
                    showErrorMessage();
                psSubmitButton.value.endLoading(false);
            break;
        }
    })
}


async function showErrorMessage() {
    await PsUtils.waitingComponent(psMessageCardRef, psMessageCardVisibleRef);
    psMessageCardRef.value.show();
}

// Cancel Entry
function handleCancel() {
    categoryStore.navigateToCategoryIndex();
}

</script>
