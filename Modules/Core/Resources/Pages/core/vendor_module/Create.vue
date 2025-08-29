<template>
    <Head :title="$t('create_vendor_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__vendor_module_info')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit()">
                        <div class="grid w-1/2 gap-6">

                            <!-- module_name-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'title' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="title" type="text" v-model:value="form.title" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'title', form.title ):''" @blur="coreField.mandatory==1?validateEmptyInput('title', form.title ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.title }}</ps-label-caption>
                            </div>

                            <!-- lang_key-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'lang_key' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="lang_key" type="text" v-model:value="form.lang_key" :placeholder="$t(coreField.placeholder)"
                                          @keyup="coreField.mandatory==1?validateEmptyInput( 'lang_key', form.lang_key ):''" @blur="coreField.mandatory==1?validateEmptyInput('lang_key', form.lang_key ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.lang_key }}</ps-label-caption>
                            </div>

                             <!-- route_name-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'route_name' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }} <span v-if="coreField.mandatory=1" class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="route_name" type="text" v-model:value="form.route_name" :placeholder="$t(coreField.placeholder)"
                                    @keyup="coreField.mandatory==1?validateEmptyInput( 'route_name', form.route_name ):''" @blur="coreField.mandatory==1?validateEmptyInput('route_name', form.route_name ):''" />
                                <ps-label-caption v-if="coreField.mandatory==1" textColor="text-red-500 " class="mt-2 block">{{ errors.route_name }}</ps-label-caption>
                            </div>


                            <!-- status-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'status' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }}</ps-label>
                                <ps-checkbox-value v-model:value="form.status" class="font-normal" :title="$t(coreField.placeholder)" />
                            </div>

                            <!-- is_not_from_sidebar-->
                            <div v-for="(coreField, index) in coreFieldFilterSettings.filter((coreField) => coreField.original_field_name === 'is_not_from_sidebar' && coreField.enable === 1 && coreField.is_delete === 0)" :key="index">
                                <ps-label class="text-base">{{ $t(coreField.label_name) }}</ps-label>
                                <ps-checkbox-value v-model:value="form.is_not_from_sidebar" class="font-normal" :title="$t(coreField.placeholder)" />
                            </div>


                            <div class="mb-2.5 flex flex-row justify-end">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4"
                                    colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-5 py-2"
                                    rounded="rounded" hover="" focus="">
                                    <ps-loading v-if="loading"
                                        theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                        loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" viewBox="0 0 18 14" w="14" h="10"
                                        class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t('core__be_btn_saved') }}</span>
                                    <span v-if="!loading && !success" class="" > {{ $t('core__be_btn_save') }} </span>
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
import { defineComponent,ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Create",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption,
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        PsDropdown,
        PsDropdownSelect,
        PsCheckboxValue
    },
    layout: PsLayout,
    props: ['errors','coreFieldFilterSettings'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        let form = useForm(
                {
                    title: "",
                    lang_key: "",
                    route_name: "",
                    status: true,
                    is_not_from_sidebar: false

                }
            )
        // Client Side Validation
        const { isEmpty, minLength} = useValidators();

        const validateModuleNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
        }

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
        }

        // for only number input validate at keypress
        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }
        function handleSubmit() {
            this.$inertia.post(route('vendor_module_registering.store'), form, {
                forceFormData: true,
            onBefore: () => {loading.value = true},
            onSuccess: () => {
                loading.value = false;
                success.value = true;
                setTimeout(()=>{
                    success.value = false;
                },2000)
            },
            onError: () => {
                loading.value = false;;
            },
            })
        }

        return { validateModuleNameInput, validateEmptyInput, onlyNumber,form,handleSubmit,loading,success }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('vendor_module_registering_module'),
                    url: route('vendor_module_registering.index'),
                },
                {
                    label: trans('create_vendor_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('vendor_module_registering.index'));
        },
    }
})
</script>
