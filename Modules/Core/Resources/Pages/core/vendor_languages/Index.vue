<template>
    <Head :title="$t('vendor_language_module')" />
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
        <!-- <new-ps-data-table :rows="this.languages" :columns="columns" :colFilterOptions="colFilterOptions" :globalSearchPlaceholder="globalSearchPlaceholder">
            <template #tableActionRow="rowProps">

                <div class="flex flex-row mb-2" v-if="rowProps.field == 'action'">
                    <ps-button @click="handleEdit(rowProps.row.id)" class="me-4" colors="bg-green-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>

            </template>
            <template #tableRow="rowProps">
                <div v-if="rowProps.field == 'status'" class="mb-2">
                    <ps-button v-if="rowProps.row.status == 1" @click="handlePublish(rowProps.row)" class="me-4" colors="bg-green-500 text-white" rounded="rounded-md" padding="px-2 py-1" hover="hover:outline-none hover:ring hover:ring-green-100" focus="focus:outline-none focus:ring focus:ring-green-200" >{{ $t('btn_active') }}</ps-button>
                    <ps-button v-else @click="handlePublish(rowProps.row)" colors="bg-red-500 text-white" padding="px-2 py-1" rounded="rounded-md" hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-200" > {{ $t('btn_inactive') }} </ps-button>
                </div>

                <ps-button v-if="rowProps.field == 'language_string'"  @click="handleLanguageString(rowProps.row.id)" class="text-white bg-primary-700 text-lg" padding="p-1" rounded="rounded">
                     <ps-icon name="language"  w="16" h="16" />
                </ps-button>

            </template>

        </new-ps-data-table> -->

        <ps-table2 :row="row" :search="search" :object="vendor_languages" :colFilterOptions="colFilterOptions"
            :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
            @handleRow="handleRow" :searchable="showFilter">
            <template #button>
                    <ps-button v-if="can.createLanguage" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__be_add_vendor_language') }}</ps-button>
            </template>
             <template #responsive_button>
                    <ps-button v-if="can.createLanguage" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-1 font-semibold" /> {{ $t('core__be_add_vendor_language') }}</ps-button>
            </template>
            
            <template #tableActionRow="rowProps">

                <div class="flex flex-row " v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.update" @click="handleEdit(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.delete" @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>


            </template>
            <template #tableRow="rowProps">
                <div v-if="rowProps.field == 'status'" class="">
                    <ps-button :disabled="!rowProps.row.authorizations.update" v-if="rowProps.row.status == 1" @click="handlePublish(rowProps.row)" class="me-4" colors="bg-green-500 text-white" rounded="rounded-md" padding="px-3 py-2" hover="hover:outline-none hover:ring hover:ring-green-100" focus="focus:outline-none focus:ring focus:ring-green-200" >{{ $t('btn_active') }}</ps-button>
                    <ps-button :disabled="!rowProps.row.authorizations.update" v-else @click="handlePublish(rowProps.row)" colors="bg-red-500 text-white" padding="px-3 py-2" rounded="rounded-md" hover="hover:outline-none hover:ring hover:ring-red-100" focus="focus:outline-none focus:ring focus:ring-red-200" > {{ $t('btn_inactive') }} </ps-button>
                </div>

                <ps-button v-if="rowProps.field == 'lang_string'"  @click="handleLanguageString(rowProps.row.id)" class="text-white bg-primary-700 text-lg" padding="p-1" rounded="rounded">
                     <ps-icon name="language"  w="16" h="16" />
                </ps-button>

            </template>
        </ps-table2>

    </ps-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { Head } from '@inertiajs/vue3';
import { trans, loadLanguageAsync } from 'laravel-vue-i18n';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";

import { router } from '@inertiajs/vue3';

export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsLabel,
        PsButton,
        PsTable2,
        PsAlert,
        PsBreadcrumb2,
        PsDangerDialog,
        PsToggle,
        PsIcon,
        PsBannerIcon,
        PsIconButton,

    },
    layout : PsLayout,
    // props: ['languages', 'status', 'checkPermission', 'showLanguageCols', 'showCoreAndCustomFieldArr', 'hideShowFieldForFilterArr'],
    props: {
        status: Object,
        vendor_languages:Object,
        checkPermission:Object,
        showLanguageCols:Object,
        showCoreAndCustomFieldArr:Object,
        hideShowFieldForFilterArr:Object,
        sort_field: {
            type: String,
            default: "",
        },
        sort_order: {
            type: String,
            default: 'desc',
        },
        search: String,
        can:Object,
    },
    setup(props) {
        const ps_danger_dialog = ref();


        // For data table
       let visible = ref(false)

        let sorting = "";
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        const colFilterOptions = ref();
        const columns = ref();


        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
               trans('core__be_delete_vendor_language'),
                trans('core__be_delete_vendor_language_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    router.delete(route("vendor_language.destroy", id), {
                        onSuccess: () => {
                            visible.value = true;
                            setTimeout(() => {
                                visible.value = false;
                            }, 3000);
                        },
                        onError: () => {
                            visible.value = true;
                            setTimeout(() => {
                                visible.value = false;
                            }, 3000);
                        },
                    })
                },
                () => { }
            );
        }

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }
        function handleSearching(value) {
            search.value = value;
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleRow(value) {
            handleSearchingSorting(1, value);
        }

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('vendor_language.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.vendor_languages.meta.current_page,
                    row: row ?? props.vendor_languages.meta.per_page,
                    search: search.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        return { columns, ps_danger_dialog, confirmDeleteClicked, colFilterOptions,handleSorting ,handleSearching ,handleRow ,visible}
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('vendor_language_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("vendor_language.create"));
        },
        handleEdit(id){
            this.$inertia.get(route('vendor_language.edit',id));
        },
        handlePublish(row){
            this.$inertia.put(route('vendor_language.statusChange',row.id), '', {
                onSuccess: () => {
                    loadLanguageAsync(row.symbol);

                    setTimeout(()=>{
                        window.location.reload();
                    },1000)
                }
            });
        },
        handleLanguageString(id){
            this.$inertia.get(route('vendor_language_string.index',id));
        },
        FilterOptionshandle(value) {
            router.post(route('vendor_language.screenDisplayUiSetting.store'),
                {
                    value,
                    sort_field: this.sort_field,
                    sort_order: this.sort_order,
                    row: this.vendor_languages.per_page,
                    search: this.search.value,
                    current_page: this.vendor_languages.current_page
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                });
        },
    },
    created() {
        this.columns = this.showCoreAndCustomFieldArr.map(column => {
        return {
            action: column.action,
            field: column.field,
            label: trans(column.label),
            sort: column.sort,
            type: column.type
        };
        });

        this.colFilterOptions = this.hideShowFieldForFilterArr.map(columnFilterOption => {
        return {
            hidden: columnFilterOption.hidden,
            id: columnFilterOption.id,
            key: trans(columnFilterOption.key).toLowerCase(),
            key_id: columnFilterOption.key_id,
            label: trans(columnFilterOption.label),
            module_name: columnFilterOption.module_name
        };
    });
  },
})
</script>
