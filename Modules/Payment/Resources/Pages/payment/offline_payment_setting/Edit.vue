<template>
    <Head :title="$t('payment__be_edit_offline_payment_setting')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('payment__be_offline_payment_info') }}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(offlinePayment.id)">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label class="text-base">{{$t('payment__be_offline_pmt_title')}}<span class="text-red-500 ms-1">*</span></ps-label>
                                <ps-input ref="title" type="text" v-model:value="form.title" :placeholder="$t('payment__be_offline_pmt_title')"
                                    @keyup="validateEmptyInput('title', form.title)" @blur="validateEmptyInput('title', form.title)" />
                                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{errors.title}}</ps-label-caption>
                            </div>

                            <div>
                                <ps-label class="text-base">{{$t('payment__be_offline_pmt_desc')}}<span class="text-red-500 ms-1" >*</span></ps-label>
                                <ps-textarea ref="description" rows="4" v-model:value="form.description"
                                    :placeholder="$t('payment__be_offline_pmt_desc')" @keyup="validateEmptyInput('description', form.description)"
                                    @blur="validateEmptyInput('description', form.description)" />
                                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{errors.description}}</ps-label-caption>
                            </div>

                            <div>
                                <ps-label>{{$t('payment__be_offline_pmt_icon')}}</ps-label>
                                <ps-label-title-3>{{ $t("core__be_recommended_size_200_200")}}</ps-label-title-3>
                                <div v-if="offlinePayment.offline_icon" class="flex items-end pt-4">
                                    <img
                                    v-lazy=" { src: $page.props.uploadUrl + '/' + offlinePayment.offline_icon.img_path, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_photo.png' }"
                                     class="w-48 h-48" alt="offline payment icon" />
                                    <ps-button type="button" @click="replaceImageClicked(offlinePayment.offline_icon.id)" rounded="rounded-full" shadow="drop-shadow-2xl"
                                        class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                        <ps-icon name="pencil-btn" w="21" h="21" />
                                    </ps-button>
                                    <ps-image-icon-modal ref="ps_image_icon_modal" />
                                    <ps-action-modal ref="ps_action_modal" />
                                    <ps-danger-dialog ref="ps_danger_dialog" />
                                </div>
                                <ps-image-upload v-else uploadType="icon" v-model:imageFile="form.icon" />
                                <ps-label-caption textColor="text-red-500 block">{{ errors.icon }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base mb-2">{{$t('payment__be_status')}}</ps-label>
                                <ps-checkbox-value v-model:value="form.status" title="Publish" />
                            </div>

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" type="reset" class="me-4" colors="text-primary-500" hover="">{{
                                $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover=""
                                    focus="">
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                        loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <ps-label v-if="success" class="transition-all duration-300"
                                        textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_saved') }}</ps-label>
                                    <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black"> {{
                                    $t('core__be_btn_save') }} </ps-label>
                                </ps-button>
                            </div>

                        </div>
                    </form>
                </div>
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
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import { trans } from 'laravel-vue-i18n';


export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsBreadcrumb2,
        PsImageUpload,
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
        PsDangerDialog,
        PsLabelCaption,
        PsLabelTitle3,
        PsTextarea,
        PsDropdown,
        PsDropdownSelect
    },
    layout: PsLayout,
    props: ['errors', 'offlinePayment'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const title = ref();
        const description = ref();

        // Client Side Validation
        const { isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
            if (fieldName == 'title') {
                title.value.isError = (props.errors.title == '') ? false : true;
            }
            if (fieldName == 'description') {
                description.value.isError = (props.errors.description == '') ? false : true;
            }
        };

        let form = useForm({
            title: props.offlinePayment.core_key.name,
            description: props.offlinePayment.value,
            core_keys_id: props.offlinePayment.core_keys_id,
            icon:"",
            status: props.offlinePayment.status_attribute.attribute_value == 1? true:false,
            "_method": "put"
        })

        function handleSubmit(id) {
            this.$inertia.post(route("offline_payment_setting.update", id), form, {
                forceFormData: true,
                onBefore: () => {
                    loading.value = true;
                },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(() => {
                        success.value = false;
                    }, 2000);
                },
                onError: () => {
                    title.value.isError = (props.errors.title == '') ? false : true;
                    description.value.isError = (props.errors.description == '') ? false : true;
                    loading.value = false;
                },
            });
        }

        function replaceImageClicked(id) {
            ps_action_modal.value.openModal(trans('conf_modal_label'),
                trans('core__be_replace_icon_label'),
                trans('core__be_remove_icon_label'),
                'image',
                'trash',
                '24',
                '24',
                () => {
                    ps_image_icon_modal.value.openModal(
                        trans('core__be_upload_photo'),
                        'cloudUpload',
                        (imageFile) => {
                            let imageForm = useForm({
                                image: imageFile,
                                "_method": "put"
                            })

                            this.$inertia.post(route("image.replace", id), imageForm);
                        });
                },
                () => {
                    ps_danger_dialog.value.openModal(
                        trans('core__be_remove_icon_label'),
                        trans('core__be_are_u_sure_remove_icon'),
                        trans('core__be_btn_confirm'),
                        trans('core__be_btn_cancel'),
                        () => {
                            this.$inertia.delete(route("image.destroy", id), {
                                onBefore: () => {
                                    loading.value = true;
                                },
                                onSuccess: () => {
                                    loading.value = false;
                                    success.value = true;
                                    setTimeout(() => {
                                        success.value = false;
                                    }, 2000);
                                },
                                onError: () => {
                                    loading.value = false;
                                },
                            });
                        },
                        () => { }
                    );
                }
            );
        }

        return { validateEmptyInput, handleSubmit, ps_action_modal, form, loading, success, ps_danger_dialog, ps_image_icon_modal, replaceImageClicked }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('offline_payment_setting_module'),
                    url: route('offline_payment_setting.index'),
                },
                {
                    label: trans('payment__be_edit_offline_payment_setting'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('offline_payment_setting.index'));
        },
    },
})
</script>
