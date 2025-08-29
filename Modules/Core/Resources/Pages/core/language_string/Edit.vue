<template>
    <Head :title="$t('edit_language_string')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('edit_language_string')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(this.language_string.id)">
                        <div class="grid w-1/2 gap-6">
                            <div>{{ $t('core__be_selected_language') }} : <span class="text-primary-500"> {{ language.name }}</span></div>
                            <div>
                                <ps-label class="text-base mb-1">{{ $t('core__be_key_label') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input :disabled="true" type="text" v-model:value="form.key" placeholder="Key"
                                    @keyup="validateEmptyInput('key', form.key)"
                                    @blur="validateEmptyInput('key', form.key)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.key }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base mb-1">{{ $t('core__be_value_label') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input type="text" v-model:value="form.value" placeholder="Value"
                                    @keyup="validateEmptyInput('value', form.value)"
                                    @blur="validateEmptyInput('value', form.value)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.value }}</ps-label-caption>
                            </div>
                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
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
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
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
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors', 'language_string', 'language'],
    data() {
        return {
            form: useForm(
                {
                    key: this.language_string.key,
                    value: this.language_string.value,
                    language_id: this.language.id,
                    "_method": "put"
                }
            )
        }
    },
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const key = ref();
        const value = ref();

        // Client Side Validation
        const { isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
            if(fieldName == 'key'){
                key.value.isError = (!Boolean(props.errors.key)) ? false: true;
            }
            if(fieldName == 'value'){
                value.value.isError = (!Boolean(props.errors.value)) ? false: true;
            }
        }

        let form = useForm(
            {
                key: props.language_string.key,
                value: props.language_string.value,
                language_id: props.language.id,
                id: props.language_string.id,
                "_method": "put"
            }
        )

        function handleSubmit(id) {
            this.$inertia.post(route('language_string.update', [props.language, id]), form, {
                forceFormData: true,
                onBefore: () => {loading.value = true},
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(()=>{
                        success.value = false;
                        window.location.reload();
                    },2000)
                },
                onError: () => {
                    loading.value = false;
                    key.value.isError = (!Boolean(props.errors.key)) ? false: true;
                    value.value.isError = (!Boolean(props.errors.value)) ? false: true;
                },
            });
        }
        return { validateEmptyInput,handleSubmit, loading, success, value, key, form }
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('language_module'),
                    url: route('language.index'),
                },
                {
                    label: trans('language_string_module'),
                    url: route('language_string.index', this.language.id),
                },
                {
                    label: "Edit Language String",
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('language_string.index', this.language.id));
        },
    },
})
</script>
