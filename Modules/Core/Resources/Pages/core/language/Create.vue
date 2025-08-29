<template>
    <Head :title="$t('core__be_add_language')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto ">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="rounded-t-xl bg-primary-50 dark:bg-primary-900 py-2.5 ps-4">
                    <ps-label-header6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_language_info')}}</ps-label-header6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit()">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label>{{ $t('core__be_symbol_label') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input type="text" ref="symbol" v-model:value="form.symbol" :placeholder="$t('core__be_symbol_label')"
                                    @keyup="validateEmptyInput('symbol', form.symbol)"
                                    @blur="validateEmptyInput('symbol', form.symbol)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.symbol }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label>{{ $t('core__be_language_name_label') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input type="text" ref="name" v-model:value="form.name" :placeholder="$t('core__be_language_name_label')"
                                    @keyup="validateEmptyInput('name', form.name)"
                                    @blur="validateEmptyInput('name', form.name)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                            </div>
                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                                     <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500" loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t("core__be_btn_saved") }}</span>
                                    <span v-if="!loading && !success" class="">{{ $t("core__be_btn_save") }}</span>
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
        PsLabelCaption
    },
    layout: PsLayout,
    props: ['errors'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const symbol = ref();
        const name = ref();

        // Client Side Validation
        const { isEmpty } = useValidators();

        // const validateEmptyInput = (fieldName, fieldValue) => {
        //     props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
        //     if(fieldName == 'symbol'){
        //         symbol.value.isError = (props.errors.symbol == '') ? false: true;
        //     }
        //     if(fieldName == 'name'){
        //         name.value.isError = (props.errors.name == '') ? false: true;
        //     }
        // }
          const validateEmptyInput = (fieldName, fieldValue, errorMessage = "") => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : "";
        };

        let form = useForm({
                symbol: "",
                name: "",
            })


        function handleSubmit() {
            this.$inertia.post(route('language.store'), form, {
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
                // alert("here")
                loading.value = false;
                symbol.value.isError = (!Boolean(props.errors.symbol)) ? false: true;
                name.value.isError = (!Boolean(props.errors.name)) ? false: true;
            },
            });
        }

        return { validateEmptyInput, handleSubmit, loading, success, symbol, name, form }
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
                    label: trans('core__be_add_language'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('language.index'));
        },
    },
})
</script>
