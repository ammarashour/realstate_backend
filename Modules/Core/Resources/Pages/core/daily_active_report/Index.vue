<template>

    <Head :title="$t('daily_active_user_report_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- data table start -->
        <ps-data-table :rows="this.users" :columns="columns" :globalSearchFields="globalSearchFields"
            :searchOptions="searchOptions" :globalSearchPlaceholder="globalSearchPlaceholder"
            >
            <!-- for csv file import start -->
            <template #searchLeft>
                <a :href="route('daily_active_user_report.csv.export')" class="font-medium transition duration-150 ease-in-out px-2 py-1.75 ms-1 rounded text-primary-500 border border-primary-500 hover:outline-none hover:ring hover:ring-blue-100 focus:outline-none focus:ring focus:ring-blue-300 opacity-100 flex items-center"><ps-icon name="fileUpload" class="mx-0.5 font-semibold" />{{ $t('core__be_export_btn') }}</a>
            </template>
            <!-- for csv file import start -->

            <template #tableRow="rowProps">
                <div class="flex flex-row mb-2" v-if="rowProps.field == 'detail'">
                    <ps-text-button @click="handleDetail(rowProps.row.id)" >{{ $t('core__be_btn_detail') }}</ps-text-button>
                </div>

            </template>

        </ps-data-table>
        <!-- data table end -->

    </ps-layout>
</template>

<script>
import { defineComponent } from 'vue'
import { Link, Head } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsRating from "@/Components/Core/Rating/PsRating.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Index",
    components: {
        Link,
        Head,
        PsLabel,
        PsButton,
        PsDataTable,
        PsAlert,
        PsBreadcrumb2,
        PsIcon,
        PsRating,
        PsTextButton
    },
    layout: PsLayout,
    props: ['users'],
    setup() {
        // For data table
        const globalSearchFields = ["name"];
        const globalSearchPlaceholder = trans('search_user_name');

        const searchOptions = [
            {
                placeholder: trans('select_date'),
                field: "added_date",
                filterType: "date_range"
            },
        ]

        const columns = [
            {
                label: trans('user_name_label'),
                field: "name",
                type: "String"
            },
            {
                label: trans('email_label'),
                field: "email",
                type: 'String',
            },
            {
                label: trans('phone_label'),
                field: "user_phone",
                type: "String"
            },
            {
                label: trans('registered_date_label'),
                field: "added_date",
                type: 'Date',
            },
            {
                label: trans('detail_label'),
                field: "detail",
                type: 'Action',
                sort: false
            },
        ]

        return { globalSearchFields, globalSearchPlaceholder, columns, searchOptions }
    },
    methods: {
        handleDetail(id){
            this.$inertia.get(route('daily_active_user_report.show',id));
        },
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('daily_active_user_report_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
})
</script>



<style scoped>
</style>
