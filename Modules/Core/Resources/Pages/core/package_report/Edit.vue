<template>

    <Head :title="$t('package_detail')" />
<ps-layout>

    <!-- breadcrumb start -->
    <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
    <!-- breadcrumb end -->

    <ps-card class="w-full h-auto">
        <div class="rounded-lg ">
            <!-- card header start -->
            <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4">
                <ps-label-header6>{{$t('package_detail')}}</ps-label-header6>
            </div>
            <!-- card header end -->

            <!-- card body start -->
            <div class="px-4 pt-6 dark:bg-backgroundDark">
                <form @submit.prevent="handleSubmit(transaction.id)" >
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <ps-label-header6>{{$t('package_info')}}</ps-label-header6>
                            <div class="grid grid-cols-2 gap-3 mt-6">
                                <ps-label textColor="text-secondary-400">{{$t('package_name')}}</ps-label>
                                <ps-label> : {{transaction.package.value}}</ps-label>

                                <ps-label textColor="text-secondary-400">{{$t('package_price')}}</ps-label>
                                <ps-label> : {{transaction.price}}</ps-label>

                                <ps-label textColor="text-secondary-400">{{$t('package_posts')}}</ps-label>
                                <ps-label> : {{post_count}}</ps-label>

                                <ps-label textColor="text-secondary-400">{{$t('package_payment')}}</ps-label>
                                <ps-label> : {{transaction.payment_method}}</ps-label>
                            </div>
                        </div>
                        <div>
                            <ps-label-header6>{{$t('package_transition_info')}}</ps-label-header6>
                            <div class="grid grid-cols-2 gap-3 mt-6">
                                <ps-label textColor="text-secondary-400">{{$t('package_user_name')}}</ps-label>
                                <ps-label> : {{transaction.user.name}}</ps-label>

                                <ps-label textColor="text-secondary-400">{{$t('package_amount')}}</ps-label>
                                <ps-label> : {{transaction.price}}</ps-label>

                                <ps-label textColor="text-secondary-400">{{$t('package_payment')}}</ps-label>
                                <ps-label> : {{transaction.payment_method}}</ps-label>

                                <ps-label textColor="text-secondary-400">{{$t('package_date')}}</ps-label>
                                <ps-label> : {{moment(transaction.added_date).format($page.props.dateFormat)}}</ps-label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2.5 flex flex-row justify-end">
                        <ps-button type="button" @click="handleCancel()">{{ $t('core_be_btn_back') }}</ps-button>
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
import moment from 'moment';

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
    data(){
        return{
            moment: moment,
        }
    },
    layout: PsLayout,
    props: ['errors', 'transaction', 'packages'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const date_range = ref();
        const payment_status = ref();
        let post_count_filter = ref(props.transaction.user.user_relation.filter((field) => field.core_keys_id === 'ps-usr00004'));
        let post_count = ref(0);
        if(Object.keys(post_count_filter.value).length > 0){
           post_count.value = post_count_filter.value[0].value;
        }

        // Client Side Validation
        const { isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';

            if (fieldName == 'payment_status') {
                payment_status.value.isError = (props.errors.payment_status == '') ? false : true;
            }
        };

        let form = useForm({
            package_id: props.transaction.package_id,
            user_id: props.transaction.user_id,
            status: String(props.transaction.status),
            payment_method: 'offline',
            "_method": "put"
        })

        function handleSubmit(id) {
            this.$inertia.post(route("package_report.update", id), form, {
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

        let types = [
            {
                id: '0',
                name: "Waiting For Approval",
            },
            {
                id: '1',
                name: "Approved",
            },
            {
                id: '2',
                name: "Rejected",
            }
        ];

        return { types, date_range, payment_status, validateEmptyInput, handleSubmit, ps_action_modal, form, loading, success, ps_danger_dialog, ps_image_icon_modal,post_count_filter,post_count }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('package_report_module'),
                    url: route('package_report.index'),
                },
                {
                    label: trans('package_report_detail'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('package_report.index'));
        },
    },
})
</script>
