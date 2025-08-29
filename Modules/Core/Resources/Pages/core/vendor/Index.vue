<template>
    <Head :title="$t('item_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />

        <!-- alert banner start -->
       <ps-banner-icon
            v-if="psBannerIconVisibleRef"
            :flag = "status.flag"
            class="mb-5 text-white sm:mb-6 lg:mb-8"
            ref="psBannerIconRef"
            >{{ status.msg }}
        </ps-banner-icon>

        <!-- data table start -->
        <ps-table2 :object="this.vendorList" :columns="columns" :colFilterOptions="colFilterOptions" :eye_filter="false"
                @handleRow="handleRow" @handleSort="handleSorting" @handleSearch="handleSearching"
                :sort_field="sort_field" :sort_order="sort_order">

            <template #tableActionRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="!rowProps.row.authorizations.delete"
                        @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
            </template>

            <template #tableRow="rowProps">
                <div class="flex flex-row mb-2" v-if="rowProps.field == 'is_unlimited'">
                    <ps-toggle v-if="rowProps.field == 'is_unlimited'" :selectedValue="rowProps.row.is_unlimited == 1 ? true : false"
                        @click="handleUnlimitedStatus(rowProps.row.id)"></ps-toggle>
                </div>

                <div class="flex flex-row mb-2" v-if="rowProps.field == 'detail'">
                    <ps-text-button @click="handleDetail(rowProps.row.id)">{{ $t('core__be_btn_detail') }}</ps-text-button>
                </div>

                <ps-label v-if="rowProps.field == 'total_products'">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row">{{ rowProps.row.product_count }}</ps-label>
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 0">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-yellow-500">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-yellow-500"></ps-label> {{ $t('core__be_inactive') }}
                        </ps-label>
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 1">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-green-600">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-green-600"> </ps-label> {{$t('core__be_active')}}
                        </ps-label>
                    </ps-label>
                </ps-label>

                <ps-label v-if="rowProps.field == 'status' && rowProps.row.status == 2">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" textColor="text-red-500">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" textColor="bg-red-500"> </ps-label> {{$t('core__be_terminated')}}
                        </ps-label>
                    </ps-label>
                </ps-label>
            </template>
        </ps-table2>

    </ps-layout>
</template>

<script>
import { ref, defineComponent, watch, onMounted, defineAsyncComponent } from "vue";
import { Head, router } from "@inertiajs/vue3";
import { trans } from 'laravel-vue-i18n';
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import { useTableColumnAndFilterStoreState } from '@templateCore/store/utilities/TableColumnAndFilterStore';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import { useVendorStore } from '@templateCore/store/modules/vendor/VendorStore';
import PsUtils from '@templateCore/utils/PsUtils';
const PsBannerIcon = defineAsyncComponent(() => import('@/Components/Core/Banners/PsBannerIcon.vue'));

    export default defineComponent({
        name: "Index",
        components: {
            Head,
            PsBreadcrumb2,
            PsTable2,
            PsIcon,
            PsButton,
            PsTextButton,
            PsLabel,
            PsDangerDialog,
            PsBannerIcon,
            PsToggle
        },
        layout: PsLayout,
        props: {
            vendorList : Object,
            showVendorCols:Object,
            showCoreAndCustomFieldArr:Object,
            hideShowFieldForFilterArr:Object,
            can : Object,
            status: Object,
            sort_field: {
                type: String,
                default: "",
            },
            sort_order: {
                type: String,
                default: 'desc',
            },
            search: String,
        },
        setup(props){
            console.log(props.vendorList);
            let visible = ref(false);
            const colFilterOptions = ref();
            const ps_danger_dialog = ref();
            let search = props.search ? ref(props.search) : ref('');
            let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
            let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
            const columns = ref();
            const vendorStore = useVendorStore('be-vendor-list');
            const tableColumnAndFilterStore = useTableColumnAndFilterStoreState('vendor');
            const psBannerIconRef = ref();
            const psBannerIconVisibleRef = ref(false);

            function handleSorting(value) {
                sort_field.value = value.field
                sort_order.value = value.sort_order
                handleSearchingSorting();
            }

            function handleSearching(value) {
                search.value = value;
                let page = 1;
                handleSearchingSorting(page);
            }

            function confirmDeleteClicked(id) {
                ps_danger_dialog.value.openModal(
                    trans('core__be_delete_vendor'),
                    trans('core__be_delete_vendor_info'),
                    trans('core__be_btn_confirm'),
                    trans('core__be_btn_cancel'),
                    () => {
                        router.delete(route("vendor.destroy", id), {
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

            function handleRow(value) {
                handleSearchingSorting(1, value);
            }

            function handleSearchingSorting(page = null, row = null) {
                router.get(route('vendor.index'),
                    {
                        sort_field: sort_field.value,
                        sort_order: sort_order.value,
                        page: page ?? props.vendorList.meta.current_page,
                        row: row ?? props.vendorList.meta.per_page,
                        search: search.value,
                    },
                    {
                        preserveScroll: true,
                        preserveState: true,
                    })
            }

            function handleUnlimitedStatus(id) {
                vendorStore.toggleIsUnlimitedInertia(id, async (status, message) => {
                    if(status != 'before') {
                        await PsUtils.waitingComponent(psBannerIconRef, psBannerIconVisibleRef);
                        psBannerIconRef.value.show();
                    }
                })
            }

            onMounted(() => {
                columns.value = tableColumnAndFilterStore.tableColumnsMapping(props.showCoreAndCustomFieldArr);
            });

            return {
                visible,
                ps_danger_dialog,
                columns,
                colFilterOptions,
                sort_field,
                sort_order,
                psBannerIconRef,
                psBannerIconVisibleRef,
                handleSorting,
                handleSearching,
                handleSearchingSorting,
                confirmDeleteClicked,
                handleRow,
                handleUnlimitedStatus
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
                        label: trans('vendor_list_module'),
                        color: "text-primary-500"
                    }
                ]
            },
        },
        methods: {
            handleDetail(id) {
                this.$inertia.get(route('vendor.show', id));
            },
        },
    })
</script>
