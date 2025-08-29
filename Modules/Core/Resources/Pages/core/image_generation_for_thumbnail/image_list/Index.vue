<template>

    <Head :title="$t('image_lists_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
            :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
            class="text-white mb-4" iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- alert banner end -->

        <!-- data table start -->
        <ps-data-table :rows="this.images" :columns="columns" :globalSearchFields="globalSearchFields">

            <template #tableRow="rowProps">
                <!-- For action start -->
                <div class="flex flex-row mb-2" v-if="rowProps.field == 'action'">
                    <ps-button @click="handleThumbnail(rowProps.row.id)">
                        Thumbnail(1x,2x,3x) Generator
                    </ps-button>
                </div>
                <!-- For action end -->
            </template>

        </ps-data-table>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { Link, Head, useForm } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsCsvModal from '@/Components/Core/Modals/PsCsvModal.vue';
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
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
        PsDangerDialog,
        PsToggle,
        PsIcon,
        PsCsvModal,
        PsBannerIcon,
        Dropdown,
        PsIconButton
    },
    layout: PsLayout,
    props: ['images', 'status'],
    setup() {
        const globalSearchFields = ["img_path"];
        const columns = [
            {
                label: "Image Name",
                field: "img_path",
                type: "String",
                action: 'Action'
            },
            {
                label: trans('thumbnail_generator'),
                field: "action",
                type: 'Action',
                action: 'Action'
            },
        ]

        return { columns, globalSearchFields }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('image_lists_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },

    methods: {
        handleThumbnail(id) {
            this.$inertia.put(route('image_lists.update', id));
        },
    },
})
</script>
