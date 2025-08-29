<template>

    <Head :title="$t('fvourite_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- data table start -->
        <ps-data-table :rows="this.favourites" :columns="columns" :searchRightHide="true">

        </ps-data-table>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent } from 'vue'
import { Head } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsBreadcrumb2,
        PsDataTable,
    },
    layout : PsLayout,
    props: ['favourites'],
    setup() {
        // For data table
        const columns = [
            {
                label: 'Product Name',
                field: "item_id",
                relation: "item",
                type:'String',
                relationField: 'title',
            },
            {
                label: trans('user_name_label'),
                field: "user_id",
                relation: "user",
                type:'String',
                relationField: 'name',
            },
        ]
        return { columns }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('favourite_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
})
</script>
