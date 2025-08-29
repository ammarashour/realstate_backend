<template>
    <Head :title="$t('payment__be_edit_payment')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core_vendor__order_status_info')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit()">
                    <div class="grid w-full sm:w-1/2 gap-6">
                        <div>
                            <ps-label class="text-base">{{ $t('core_vendor__order_status_name') }}<span class="text-red-500 ms-1">*</span></ps-label>
                            <ps-input ref="name" type="text" v-model:value="form.name" :placeholder="$t('core_vendor__order_status_title')"
                                @keyup="validateNameInput('name', form.name)" @blur="validateNameInput('name', form.name)" />
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.name}}</ps-label-caption>
                        </div>

                        <div>
                            <ps-label class="text-base">{{ $t('core_vendor__description') }}</ps-label>
                            <ps-textarea ref="description" rows="4" v-model:value="form.description" :placeholder="$t('core_vendor__description')" />
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.description}}</ps-label-caption>
                        </div>

                        <div>
                            <ps-label class="text-base">{{ $t('core_vendor__status') }}</ps-label>
                            <ps-checkbox-value v-model:value="form.status" class="font-normal" :title="$t('core_vendor__public')" />
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
                <!-- card body end -->
            </div>
        </ps-card>
    </ps-layout>
</template>
<script>
import { defineComponent, ref, reactive } from 'vue'
import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Create",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsLabelHeader6,
        PsCard,
        PsLoading,
        PsIcon,
        PsBreadcrumb2,
        PsLabelCaption,
        PsTextarea,
        PsCheckboxValue,
        PsDataTable
    },
    layout: PsVendorLayout,
    props: ['errors'],
    // data() {
    //     return {
    //         form: useForm({
    //             name: "",
    //             description: "",
    //             status: "",
    //         })
    //     }
    // },
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const name = ref();
        const description = ref();

        // Client Side Validation
        const { isEmpty, minLength } = useValidators();

        const validateNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
            if (fieldName == 'name') {
                name.value.isError = (props.errors.name == '') ? false : true;
            }
        };

        let form = useForm({
            name: "",
            description: "",
            status: true,
        });

        function handleSubmit() {
            this.$inertia.post(route("vendor_order_status.store"), form, {
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
                    name.value.isError = ("name" in props.errors) ? true : false;
                    loading.value = false;
                },
            });
        }

        return { description, name, validateNameInput, handleSubmit, form, loading, success };
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__vendor_my_store_module'),
                    url: route('vendor_info.index')
                },
                {
                    label: trans('core__vendor_order_status_module'),
                    url: route('vendor_order_status.index'),
                },
                {
                    label: trans('core_vendor__add_order_status'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('vendor_order_status.index'));
        }
    },
})
</script>
