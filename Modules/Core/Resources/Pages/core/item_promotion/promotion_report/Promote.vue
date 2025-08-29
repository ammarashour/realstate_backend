<template>
    <Head :title="$t('core__be_promote_item')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header5>Promote Item</ps-label-header5>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label>Date<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <date-picker
                                    class="rounded shadow-sm pt-0.5  focus:outline-none focus:ring-1 focus:ring-primary-500"
                                    v-model:value="form.date_range" :range="true"
                                    :inline="false" :autoApply="false"
                                    @blur="validateEmptyInput"
                                    @change="validateEmptyInput"/>

                                    <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.date_range}}</ps-label-caption>
                            </div>

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus=""
                                    hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-5 py-2" rounded="rounded" hover="" focus="">
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                        loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t('core__be_btn_saved') }}</span>
                                    <span v-if="!loading && !success" class=""> {{ $t('core__be_btn_save') }} </span>
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

import { defineComponent, ref, defineAsyncComponent, computed } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import useValidators from '@/Validation/Validators'
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsLabelHeader5 from "@/Components/Core/Label/PsLabelHeader5.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsLabel,
        PsButton,
        PsLabelHeader6,
        PsLabelHeader5,
        PsCard,
        PsBreadcrumb2,
        PsLabelCaption,
        PsLoading,
        PsLabelTitle3,
        DatePicker
    },
    layout: PsLayout,
    props: ['errors', 'item_id'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const date_range = ref();

        let form = useForm(
            {
                item_id: props.item_id,
                date_range: '',
            }
        )

        // Client Side Validation
        const { isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
            if (fieldName == 'date_range') {
                date_range.value.isError = (props.errors.date_range == '') ? false : true;
            }
        }

        function handleSubmit() {
            this.$inertia.post(route('paid_item.store'), form, {
                forceFormData: true,
                onBefore: () => { loading.value = true },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(() => {
                        success.value = false;
                    }, 2000)
                },
                onError: () => {
                    date_range.value.isError = (props.errors.date_range == '') ? false : true;
                    loading.value = false;;
                },
            });
        }

        function handleCancel() {
            this.$inertia.get(route('item.edit', props.item_id));
        }

        const breadcrumb = computed(() => [
            {
                label: trans('core__be_dashboard_label'),
                url: route('admin.index')
            },
            {
                label: trans('item_module'),
                url: route('item.edit', props.item_id),
            },
            {
                label: trans('core__be_promote_item'),
                color: "text-primary-500"
            }
        ]);

        return { validateEmptyInput, handleSubmit, handleCancel, success, loading, date_range, form, breadcrumb }
    }
})
</script>
