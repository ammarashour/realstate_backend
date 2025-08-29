<template>
    <Head :title="$t('transaction_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon  v-if="visible" :visible="visible"
        :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
        :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
        class="text-white mb-5 sm:mb-6 lg:mb-8"
         iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-data-table :rows="transactions" :columns="columns" :colFilterOptions="colFilterOptions">
            <template #tableActionRow="rowProps">
                <!-- For action (edit/delete) start -->
                <div class="flex flex-row mb-2" v-if="rowProps.field == 'action'">
                    <ps-text-button @click="handleEdit(rowProps.row.id)"> Detail </ps-text-button>
                </div>
                <!-- For action (edit/delete) end -->

            </template>

            <template #tableRow="rowProps">
                <ps-label v-if="rowProps.field == 'balance_amount'" class="flex">
                    <ps-label class="mb-2 text-sm whitespace-nowrap">
                        {{ rowProps.row.currency_symbol }} {{ rowProps.row.balance_amount }}
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'payment_method'" class="flex">
                    <ps-label class="mb-2 px-1 py-0.5 text-xs font-semibold bg-yellow-50 rounded whitespace-nowrap" textColor="text-yellow-500">
                        {{ rowProps.row.payment_method }}
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'transaction_status'">
                    <ps-label class="mb-2 whitespace-nowrap dark:text-white">
                        <span class="flex flex-row" :style="{color: rowProps.row.transaction_status.color_value }">
                            <div class="w-2 h-2 my-auto rounded-full me-2" :style="{background: rowProps.row.transaction_status.color_value }"/>
                            {{ rowProps.row.transaction_status.title }}
                        </span>
                    </ps-label>
                </ps-label>

            </template>

        </ps-data-table>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
    import { defineComponent, ref, reactive  } from 'vue'
    import PsLayout from "@/Components/PsLayout.vue";
    import { Head, Link } from '@inertiajs/vue3';
    import PsInput from "@/Components/Core/Input/PsInput.vue";
    import PsLabel from "@/Components/Core/Label/PsLabel.vue";
    import PsButton from "@/Components/Core/Buttons/PsButton.vue";
    import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
    import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
    import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
    import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
    import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
    import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
    import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
    import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
    import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
    import { trans } from 'laravel-vue-i18n';

    export default defineComponent({
        name: "Index",
        components: {
            Head,
            Link,
            PsInput,
            PsLabel,
            PsTextButton,
            PsButton,
            PsTextarea,
            PsLabelHeader4,
            PsIcon,
            PsAlert,
            PsBreadcrumb2,
            PsDangerDialog,
            PsDataTable,
            PsBannerIcon
        },
        layout : PsLayout,
        props: ['transactions', 'status'],
        setup() {
            const ps_danger_dialog = ref();

            const colFilterOptions = reactive([
                {
                    label: trans('user_name_label'),
                    key: "contact_name",
                    hidden: false
                },
                {
                    label: "Amount",
                    key: "balance_amount",
                    hidden: false
                },
                {
                    label: trans('phone_label'),
                    key: "contact_phone",
                    hidden: true
                },
                {
                    label: "Item Count",
                    key: "total_item_count",
                    hidden: false
                },
                {
                    label: "Payment",
                    key: "payment_method",
                    hidden: false
                },
                {
                    label: "Transaction Status",
                    key: "transaction_status",
                    hidden: false
                },
                {
                    label: trans('owner_label'),
                    key: "added_user_id",
                    hidden: true
                },
                {
                    label: trans('added_date_label'),
                    key: "added_date",
                    hidden: true
                },
                {
                    label: trans('updated_user_label'),
                    key: "updated_user_id",
                    hidden: true
                },
                {
                    label: trans('updated_date_label'),
                    key: "updated_date",
                    hidden: true
                },
            ])

            const columns = [
                {
                    label: trans('action_label'),
                    field: "action",
                    type: 'action',
                },
                {
                    label: trans('user_name_label'),
                    field: "contact_name",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: "Amount",
                    field: "balance_amount",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: trans('phone_label'),
                    field: "contact_phone",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: "Item Count",
                    field: "total_item_count",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: "Payment",
                    field: "payment_method",
                    type: 'String',
                    action: 'Action'
                },
                {
                    label: "Transaction Status",
                    field: "transaction_status",
                     type: 'String',
                    action: 'Action'
                },
                {
                    label: trans('owner_label'),
                    field: "added_user_id",
                    relation: "owner",
                    type:'String',
                    relationField: 'name',
                },
                {
                    label: trans('added_date_label'),
                    field: "added_date",
                    type:'Date',
                },
                {
                    label: trans('updated_date_label'),
                    field: "updated_date",
                    type:'Date',
                },
                {
                    label: trans('updated_user_label'),
                    field: "updated_user_id",
                    relation: "editor",
                    type:'String',
                    relationField: 'name',
                },
            ]

            function confirmDeleteClicked(id) {
                ps_danger_dialog.value.openModal(
                    trans('core__be_transaction_history'),
                    trans('delete_transaction_history'),
                    trans('core__be_btn_confirm'),
                    trans('core__be_btn_cancel'),
                    () => {
                        this.$inertia.delete(route("transaction.destroy", id));
                    },
                    () => { }
                );
            }

            return {
                columns,
                ps_danger_dialog,
                confirmDeleteClicked,
                colFilterOptions
            }
        },
        computed: {
            breadcrumb() {

                return [
                   {
                        label: trans('core__be_dashboard_label'),
                        url: route('admin.index')
                    },
                    {
                        label: trans('transaction_history_module'),
                        color: "text-primary-500"
                    }
                ]

            }
        },
        methods: {
            handleEdit(id){
                this.$inertia.get(route('transaction.edit',id));
            },
            handlePublish(id){
                this.$inertia.put(route('transaction.statusChange',id));
            },
            handleDefault(id){
                this.$inertia.put(route('transaction.defaultChange',id));
            }
        },
    })
</script>



<style scoped>

</style>
