<template>
    <Head :title="$t('promotion_report_detail')" />
    <ps-layout>

    <!-- breadcrumb start -->
    <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
    <!-- breadcrumb end -->

    <ps-card class="w-full h-auto">
        <div class="rounded-xl">
            <!-- card header start -->
            <div class="bg-primary-50 rounded-t-xl dark:bg-primary-900 py-2.5 ps-4">
                <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('promotion_report_detail')}}</ps-label-header-6>
            </div>
            <!-- card header end -->
            <!-- card body start -->
            <div class="px-4 pt-6 dark:bg-backgroundDark">
                <form @submit.prevent="handleSubmit(paid_item.id)">
                    <div class="flex flex-col sm:flex-row justify-between ">


                        <div class=" w-full sm:w-1/2">
                            <ps-label class="text-lg font-semibold mb-2 sm:mb-4" > {{$t('core__be_promotion_information')}}</ps-label>
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" > {{ $t('core__be_start_date_label')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" >
                                     : {{ paid_item.start_timestamp ? moment(new Date(paid_item.start_timestamp * 1000)).format($page.props.dateFormat + ', h:mm a') : '' }}
                                </ps-label>
                            </div>
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" > {{ $t('core__be_end_date_label')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" >
                                     : {{ paid_item.end_timestamp ? moment(new Date(paid_item.end_timestamp * 1000)).format($page.props.dateFormat + ', h:mm a') : '' }}

                                </ps-label>
                            </div>
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" > {{ $t('core__be_amount_label')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{paid_item.amount}}</ps-label>
                            </div>
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" >{{ $t('core__be_promotion_days_label')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{paid_item.promoted_days}}</ps-label>
                            </div>
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" > {{ $t('core__be_paymend_method_label')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{paid_item.payment_method}}</ps-label>
                            </div>

                        </div>
                        <div class=" w-full sm:w-1/2">
                            <ps-label class="text-lg font-semibold mb-2 sm:mb-4 mt-4 sm:mt-0" > {{ $t('core__be_item_information_label')}}</ps-label>
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" > {{ $t('core__be_item_name_label')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{paid_item.item?.title}}</ps-label>
                            </div>
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" >  {{ $t('core__category_label')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{$t(paid_item.item?.category?.name)}}</ps-label>
                            </div>
                            <div v-if="$page.props.isSubCategoryOn == '1'" class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" >  {{ $t('core__be_subcategory')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{paid_item.item?.subcategory?.name}}</ps-label>
                            </div>
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" > {{ $t('core__be_city')}} </ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{paid_item.item?.city?.name}}</ps-label>
                            </div>
                            <!-- <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" > Township</ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{paid_item.item?.township?.name}}</ps-label>
                            </div> -->
                            <div class="w-full lg:w-3/4 grid grid-cols-2 mb-2">
                                <ps-label class="textsm: lg:text-base" textColor="text-secondary-500" > {{ $t('core__be_owner_label')}}</ps-label>
                                <ps-label class="textsm: lg:text-base" > : {{paid_item.item?.owner?.name}}</ps-label>
                            </div>

                        </div>
                    </div>
                    <div class="flex flex-row justify-end mb-2.5">
                        <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover=""
                            focus="">
                            <ps-label textColor="text-white dark:text-secondaryDark-black"> {{
                                $t('core__be_btn_back') }} </ps-label>
                        </ps-button>
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
import moment from 'moment';
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
    props: ['errors', 'paid_item'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);

        let form = useForm({
            status: props.paid_item.status,
            "_method": "put"
        })

        function handleSubmit(id) {
            // this.$inertia.post(route("paid_item.update", id), form, {
            //     onBefore: () => {
            //         loading.value = true;
            //     },
            //     onSuccess: () => {
            //         loading.value = false;
            //         success.value = true;
            //         setTimeout(() => {
            //             success.value = false;
            //         }, 2000);
            //     },
            //     onError: () => {
            //         loading.value = false;
            //     },
            // });
            this.$inertia.get(route('paid_item.index'));
        }

        return { handleSubmit, form, loading, success,  moment: moment, }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('promotion_report_module'),
                    url: route('paid_item.index'),
                },
                {
                    label: trans('promotion_report_detail'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('paid_item.index'));
        },
    },
})
</script>
