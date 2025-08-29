<template>
    <Head :title="$t('city_module')" />
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
        <ps-table2 :customizeDetails="customizeDetails"  :row="row" :search="search" :object="this.cities" :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow"
            :searchable="showFilter" :globalSearchPlaceholder="$t('core__be_search_city')">
            <!-- for csv file import start -->
            <template #searchLeft>

                <ps-button v-if="can.createLocationCity" @click="csvUploadClicked" rounded="rounded" class="flex flex-wrap items-center ms-3 ">
                    <ps-icon name="plus" class="me-2 font-semibold" />
                    {{ $t('core__be_import_file') }}
                </ps-button>
                <ps-csv-modal ref="ps_csv_modal" url="https://drive.google.com/file/d/1s8Ah8RY0v8n2fznEGKn3KmS4QRVqT9QQ/view?usp=sharing" isCityTownship/>

            </template>
            <!-- for csv file import start -->

            <!-- add new field start -->
            <template #button>
                <ps-button v-if="can.createLocationCity" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_city')}}</ps-button>
            </template>
            <template #responsive_button>
                <ps-button v-if="can.createLocationCity" @click="handleCreate()" rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class="me-2 font-semibold" />{{$t('core__be_add_city')}}</ps-button>
            </template>
            <!-- add new field end -->

            <template #tableActionRow="rowProps">

                <!-- For action (edit/delete) start -->
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="rowProps.row.authorization.update ? false : true"   @click="handleEdit(rowProps.row.id)" class="me-2" colors="bg-green-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-green-100"
                        focus="focus:outline-none focus:ring focus:ring-green-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="editPencil" w="16" h="16" />
                    </ps-button>
                    <ps-button :disabled="rowProps.row.authorization.delete ? false : true"  @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1.5"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
                <!-- For action (edit/delete) end -->
            </template>

            <template #tableRow="rowProps">
                <ps-toggle  :disabled="rowProps.row.authorization.update ? false : true" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false" @click="handlePublish(rowProps.row.id,rowProps.row.authorization.update)"></ps-toggle>
            </template>

        </ps-table2>
        <!-- data table end -->

    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { Link, Head, useForm } from '@inertiajs/vue3';
import PsLayout from "@/Components/PsLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
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
import { router } from '@inertiajs/vue3';

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
        PsDangerDialog,
        PsToggle,
        PsIcon,
        PsCsvModal,
        PsBannerIcon,
        Dropdown,
        PsIconButton
    },
    layout: PsLayout,
    // props: ['cities', 'status', 'cities', 'checkPermission', 'showLocationCityCols', 'showCoreAndCustomFieldArr', 'hideShowFieldForFilterArr'],
    props:{

            status:Object,
            can:Object,
            cities:Object,
            // owners:Object,
            hideShowFieldForFilterArr:Object,
            showCoreAndCustomFieldArr:Object,
            // selectedpsitm00001:{type:String,default:''},
            // selectedpsitm00003:{type:String,default:''},
            selectedOwner:{type:String,default:''},
            authUser:Object,
            uis:Object,
            sort_field:{
                    type:String,
                    default:"",

                },
            sort_order:{
                type:String,
                default:'desc',
            },
            search:String,
            customizeDetails: Object,
            customizeHeaders: Object
        },
    setup(props) {
        // For data table
       let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_owner = props.selectedOwner ? ref(props.selectedOwner) : ref('');
        // let selected_psitm00001=props.selectedpsitm00001 ? ref(props.selectedpsitm00001):ref('');
        // let selected_psitm00003=props.selectedpsitm00003 ? ref(props.selectedpsitm00003):ref('');

        const ps_danger_dialog = ref();
        const ps_csv_modal = ref();
        let visible = ref(false)

        const colFilterOptions = ref();

        const columns = ref();

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__delete'),
                trans('delete_city_msg'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route("city.destroy", id),{
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

        function csvUploadClicked() {
            ps_csv_modal.value.openModal(
                (selectedFile) => {
                    let form = useForm({
                        csvFile: selectedFile,
                        "_method": "put"
                    })
                    router.post(route('city.import.csv'), form)
                }
            );
        }

        function handleSorting(value){
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        //  function handlepsitm00003filter(value){
        //     selected_psitm00003.value = value
        //     let page=1;
        //     handleSearchingSorting(page);

        // }
         function handleSearching(value){
            search.value = value;
            let page=1;
            handleSearchingSorting(page);
        }
        function handleRow(value){
            handleSearchingSorting(1,value);
        }

        function handleSearchingSorting(page = null,row=null){
            router.get(route('city.index'),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.cities.meta.current_page,
                row:row ?? props.cities.meta.per_page,
                search:search.value,

                // item_type:selected_psitm00001.value,
                // item_condition:selected_psitm00003.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        function loadEyeFilter(value) {
            colFilterOptions.value = value;
        }

        return {
            visible,
            columns,
            ps_danger_dialog,
            confirmDeleteClicked,
            ps_csv_modal,
            csvUploadClicked,
            colFilterOptions,
            colFilterOptions,
            handleSorting,
            handleSearchingSorting,
             handleRow,
            handleSearching,
            loadEyeFilter
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
                    label: trans('city_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCreate() {
            this.$inertia.get(route("city.create"));
        },
        handleEdit(id) {
            this.$inertia.get(route('city.edit', id));
        },
        handlePublish(id,hasPermission) {
            if(hasPermission){
                this.$inertia.put(route('city.statusChange', id));
            }
        },
        FilterOptionshandle(value) {
        router.put(route('city.screenDisplayUiSetting.store'),
            {
                value,
                sort_field :this.sort_field ,
                sort_order:this.sort_order,
                row:this.cities.per_page,
                search:this.search.value,
                current_page:this.cities.current_page
            },
            {
                preserveScroll: true,
                preserveState:true,
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
                key: trans(columnFilterOption.key),
                key_id: columnFilterOption.key_id,
                label: trans(columnFilterOption.label),
                module_name: columnFilterOption.module_name
            };
        });
    },
    watch: {
        hideShowFieldForFilterArr(current, previous){
            this.loadEyeFilter(current);
        },
        showCoreAndCustomFieldArr(value){
            this.columns = value.map(column => {
                return {
                    action: column.action,
                    field: column.field,
                    label: trans(column.label),
                    sort: column.sort,
                    type: column.type,
                };
            });
        }
    },
})
</script>
