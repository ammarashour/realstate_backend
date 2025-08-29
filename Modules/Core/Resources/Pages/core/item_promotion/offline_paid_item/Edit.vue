<template>

    <Head :title="$t('itemPromotion__be_edit_sponsored_item')" />
<ps-layout>

    <!-- breadcrumb start -->
    <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
    <!-- breadcrumb end -->

    <ps-card class="w-full h-auto mb-20">
        <div class="rounded-xl">
            <!-- card header start -->
            <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4">
                <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('itemPromotion__be_sponsored_item_info') }}</ps-label-header-6>
            </div>
            <!-- card header end -->

            <!-- card body start -->
            <div class="px-4 pt-6 dark:bg-backgroundDark">
                <form @submit.prevent="handleSubmit(paid_item.id)">
                    <div class="grid w-full sm:w-1/2 gap-6">
                        <div>
                            <ps-label>{{ $t('itemPromotion__be_date') }}<span class="text-red-800 font-medium ms-1">*</span>
                            </ps-label>
                            <date-picker
                                class="rounded shadow-sm pt-0.5 border border-secondary-200 dark:border-secondary-200 focus:outline-none focus:ring-1 focus:ring-primary-500"
                                v-model:value="form.date_range" :range="true" :inline="false" :autoApply="false" @blur="validateEmptyInput"
                                @change="validateEmptyInput1" @datepick="datepick" enableTimePicker/>

                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.date_range}}</ps-label-caption>
                        </div>
                        <!-- for type dropdown -->
                        <div>
                            <ps-label class="text-base">{{ $t('itemPromotion__be_payment_status') }}<span class="text-red-800 font-medium ms-1">*</span>
                            </ps-label>
                            <ps-dropdown align="left" class='lg:mt-2 mt-1 w-full' h="h-auto">
                                <template #select>
                                    <ps-dropdown-select :placeholder="$t('itemPromotion__be_select_payment_status')"
                                        :selectedValue="types.filter(type => type.id == form.payment_status)[0].name"
                                        @change="validateEmptyInput('payment_status', form.payment_status)" @blur="validateEmptyInput('payment_status', form.payment_status)" />
                                </template>
                                <template #list>
                                    <div class="rounded-md shadow-xs w-56 ">
                                        <div class="pt-2 z-30 ">
                                            <div v-for="type in types.filter(type => type.id != form.payment_status )" :key="type.id"
                                                class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                @click="[form.payment_status = type.id, validateEmptyInput('payment_status', form.payment_status)]">
                                                <ps-label class="ms-2" :class="type.id == form.payment_status ? ' font-bold' : ''">
                                                    {{ type.name }} </ps-label>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </ps-dropdown>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.payment_status }}</ps-label-caption>
                        </div>
                        <!-- end type -->

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
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
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
        PsDropdownSelect,
        DatePicker
    },
    layout: PsLayout,
    props: ['errors', 'paid_item', 'types'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const date_range = ref();
        const payment_status = ref();

        // Client Side Validation
        const { isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
            if (fieldName == 'date_range') {
                date_range.value.isError = (props.errors.date_range == '') ? false : true;
            }
            if (fieldName == 'payment_status') {
                payment_status.value.isError = (props.errors.payment_status == '') ? false : true;
            }
        };

        let form = useForm({
            date_range: [props.paid_item.start_date, props.paid_item.end_date],
            payment_status: props.paid_item.status,
            amount: props.paid_item.amount,
            payment_method: 'offline',
            "_method": "put"
        })

        function handleSubmit(id) {
            this.$inertia.post(route("offline_paid_item.update", id), form, {
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
                    date_range.value.isError = (props.errors.date_range == '') ? false : true;
                    payment_status.value.isError = (props.errors.payment_status == '') ? false : true;
                    loading.value = false;
                },
            });
        }

        function datepick(value){
            console.log(value)
        }

        return { date_range, payment_status, datepick, validateEmptyInput, handleSubmit, ps_action_modal, form, loading, success, ps_danger_dialog, ps_image_icon_modal }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('offline_paid_item_module'),
                    url: route('offline_paid_item.index'),
                },
                {
                    label: trans('itemPromotion__be_edit_sponsored_item'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('offline_paid_item.index'));
        },
    },
})
</script>
