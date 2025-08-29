<template>

    <Head :title="$t('core__be_category_detail')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_category_info') }}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form>
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__category_label')}}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-input :disabled="true" type="text" v-model:value="form.name" :placeholder="$t('core__category_placeholder')"
                                />
                            </div>

                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__category_photo_label')}}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <div v-if="category.cover" class="flex items-end pt-4">
                                    <img
                                    v-lazy=" { src: $page.props.uploadUrl + '/' + category.cover.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                     class="w-96 h-48" alt="category cover" />
                                </div>
                                <ps-image-upload v-else uploadType="image" :disabled="true" />
                            </div>

                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__category_icon_label')}}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <div v-if="category.icon" class="flex items-end pt-4">
                                    <img
                                    v-lazy=" { src: $page.props.uploadUrl + '/' + category.icon.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                     class="w-48 h-48" alt="category icon" />
                                </div>
                                <ps-image-upload v-else class="w-72" uploadType="icon" :disabled="true" />
                            </div>

                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__status_label')}}</ps-label>
                                <ps-checkbox-value :disabled="true" v-model:value="form.status" class="font-normal" :title="$t('core__publish_label')" />
                            </div>

                            <div class="mb-2.5 flex flex-row justify-end">
                                <ps-button type="button" @click="handleCancel()">{{ $t('core__be_btn_back') }}</ps-button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- card body end -->
            </div>
        </ps-card>

    </ps-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import RoleCheckbox from "../components/RoleCheckbox.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsBreadcrumb2,
        PsInput,
        PsLabel,
        PsButton,
        PsLabelHeader6,
        PsCard,
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsImageUpload,
        PsDangerDialog,
        PsLabelCaption,
        PsLabelTitle3,
        PsTextarea,
        RoleCheckbox
    },
    layout: PsLayout,
    props: ['errors', 'category'],
    setup(props) {

        // Client Side Validation
        const { isEmpty, minLength } = useValidators();

        const validateNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
            if(fieldName == 'name'){
                name.value.isError = (props.errors.name == '') ? false: true;
            }
        }

        let form = useForm({
            name: trans(props.category.name),
            status: props.category.status == 1 ? true : false,
        })

        setTimeout(() => {
            form.name = trans(props.category.name)
        }, 3000);

        function handleUserMultiSelect({data}) {
            form.permissions = data.toString();
        }

        return { handleUserMultiSelect, form }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('category_report_module'),
                    url: route('category_report.index'),
                },
                {
                    label: trans('core__be_category_report_detail'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('category_report.index'));
        },
    },
})
</script>
