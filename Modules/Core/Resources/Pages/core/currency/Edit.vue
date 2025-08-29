<template>
    <Head :title="$t('core__edit_currency')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__currency_info')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(this.currency.id)">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label class="text-base mb-2">{{$t('core__currency_short_form')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="currency_short_form" type="text" v-model:value="form.currency_short_form" :placeholder="$t('core__currency_short_form_placeholder')"
                                    @keyup="validateCurerncyShortFormInput('currency_short_form', form.currency_short_form)"
                                    @blur="validateCurerncyShortFormInput('currency_short_form', form.currency_short_form)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.currency_short_form }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base mb-2">{{ $t('core__currency_symbol') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="currency_symbol" type="text" v-model:value="form.currency_symbol" :placeholder="$t('core__currency_symbol_placeholder')"
                                    @keyup="validateCurerncySymbolInput('currency_symbol', form.currency_symbol)"
                                    @blur="validateCurerncySymbolInput('currency_symbol', form.currency_symbol)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.currency_symbol }}</ps-label-caption>
                            </div>
                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-5 py-2" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t('core__be_btn_saved') }}</span>
                                    <span v-if="!loading && !success" class="" > {{ $t('core__be_btn_save') }} </span>
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
        PsBreadcrumb2,
        PsInput,
        PsLabel,
        PsButton,
        PsLabelHeader6,
        PsCard,
        PsLoading,
        PsIcon,
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors', 'currency'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const currency_short_form = ref();
        const currency_symbol = ref();

        // Client Side Validation
        const { isEmpty, minLength} = useValidators();

        const validateCurerncyShortFormInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
            if(fieldName == 'currency_short_form'){
                currency_short_form.value.isError = (!Boolean(props.errors.currency_short_form)) ? false: true;
            }
        }


        const validateCurerncySymbolInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
            if(fieldName == 'currency_symbol'){
                currency_symbol.value.isError = (props.errors.currency_symbol == '') ? false: true;
            }
        }

        let form = useForm(
                {
                    currency_short_form: props.currency.currency_short_form,
                    currency_symbol: props.currency.currency_symbol,
                    "_method": "put"
                }
            )

        function handleSubmit(id) {
            this.$inertia.post(route('currency.update', id), form, {
                forceFormData: true,
            onBefore: () => {loading.value = true},
            onSuccess: () => {
                loading.value = false;
                success.value = true;
                setTimeout(()=>{
                    this.$inertia.get(route('currency.index'));
                },500)
            },
            onError: () => {
                currency_short_form.value.isError = (!Boolean(props.errors.currency_short_form)) ? false: true;
                currency_symbol.value.isError = (props.errors.currency_symbol == '') ? false: true;
                loading.value = false;;
            },
            });
        }

        return { validateCurerncyShortFormInput, validateCurerncySymbolInput, handleSubmit, form,currency_symbol,
            currency_short_form,
            loading,
            success }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('currency_module'),
                    url: route('currency.index'),
                },
                {
                    label: trans('core__edit_currency'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('currency.index'));
        },
    },
})
</script>
