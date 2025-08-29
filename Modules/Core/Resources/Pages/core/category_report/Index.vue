<template>

    <Head :title="$t('category_report_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <!-- data table start -->
         <ps-table2 :row="row" :search="search" :object="this.categories" :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        :eye_filter="false" @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow">
            <!-- for csv file import start -->
            <template #searchLeft>
                <div >
                    <a :href="route('category_report.csv.export')" class="font-medium transition duration-150 ease-in-out px-4 py-1.75 ms-1 rounded text-primary-500 border border-primary-500 hover:outline-none hover:ring hover:ring-blue-100 focus:outline-none focus:ring focus:ring-blue-300 opacity-100 flex items-center"><ps-icon name="fileUpload" class="me-2 font-semibold" />{{ $t('core__be_export_btn') }}</a>
                </div>
            </template>
            <!-- for csv file import start -->

            <template #searchRight>
                <date-picker  class="rounded shadow-sm pt-0.5  focus:outline-none focus:ring-1 focus:ring-primary-500" :class="(selected_date == null || selected_date == '') ? 'w-48' :'w-64'" v-model:value="selected_date"  @datepick="dateFilter" :range="true" :inline="false" :autoApply="false"/>
                <!-- <date-picker @datepick="handleDatefilter" class="rounded shadow-sm pt-0.5 border border-secondary-200 dark:border-secondary-200 focus:outline-none focus:ring-1 focus:ring-primary-500" :class="(selected_date == null || selected_date == '') ? 'w-48' :'w-64'" v-model:value="selected_date" :range="true" :inline="false" :autoApply="false"/> -->

            </template>

            <template #tableRow="rowProps">

                <div class="flex flex-row mb-2" v-if="rowProps.field == 'overall_rating'">
                    <ps-rating :grade="rowProps.row.overall_rating" :hasCounter="true" />
                </div>

                <div class="flex flex-row mb-2" v-if="rowProps.field == 'detail'">

                    <ps-text-button  @click="handleDetail(rowProps.row.id)" >{{ $t('core__be_btn_detail') }}</ps-text-button>
                </div>

            </template>

        </ps-table2>
        <!-- data table end -->

    </ps-layout>
</template>

<script>
import { defineComponent,ref } from 'vue'
import { Link, Head } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsRating from "@/Components/Core/Rating/PsRating.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import { trans } from 'laravel-vue-i18n';
import { router } from '@inertiajs/vue3';
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";

export default defineComponent({
    name: "Index",
    components: {
        Link,
        Head,
        PsLabel,
        PsButton,
        PsTable2,
        PsAlert,
        PsBreadcrumb2,
        PsIcon,
        PsRating,
        PsTextButton,
        DatePicker,
        PsIconButton
    },
    layout: PsLayout,
    props:{
            can:Object,
            status:Object,
            categories:Object,
            sort_field:{
                    type:String,
                    default:"",

                },
            sort_order:{
                type:String,
                default:'desc',
            },
            search:String,
        },
    setup(props) {
        // For data table
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_date = props.selectedDate ? ref(props.selectedDate) : ref('');


        const searchOptions = [
            {
                placeholder: trans('select_date'),
                field: "added_date",
                filterType: "date_range"
            },
        ]

        const columns = [
            {
                label: trans('core__be_categories_label'),
                field: "name",
                type: "String"
            },
            {
                label: trans('core__be_engagement_label'),
                field: "category_touch_count",
                type: 'Integer',
            },
            {
                label: trans('core__be_date_label'),
                field: "added_date",
                type: 'Date',
            },
            {
                label: trans('core__be_detail_label'),
                field: "detail",
                type: 'Action',
                sort: false
            },
        ]

        function handleSorting(value){
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }
        function handleSearching(value){
            search.value = value;
            let page=1;
            handleSearchingSorting(page);
        }
        function handleRow(value){
            handleSearchingSorting(1,value);
        }

        function handleDatefilter(value) {
            selected_date.value = value
            let page = props.users.meta.current_page;
            handleSearchingSorting(page);
        }

        function handleSearchingSorting(page = null,row=null){
            router.get(route('category_report.index'),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.categories.meta.current_page,
                row:row ?? props.categories.meta.per_page,
                date_filter:selected_date.value,
                search:search.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        function dateFilter(value) {
            selected_date.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        return {
            dateFilter,
            handleRow,
            handleSearchingSorting,
            handleSearching,
            handleSorting,
            columns,
            searchOptions,
            selected_date,
            handleDatefilter
        }
    },
    methods: {
        handleDetail(id){
            this.$inertia.get(route('category_report.show',id));
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
                    label: trans('category_report_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
})
</script>



<style scoped>
</style>
