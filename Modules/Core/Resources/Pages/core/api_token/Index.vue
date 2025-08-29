<template>

    <Head :title="$t('api_tokens_module')" />
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
        <ps-table2 actionColName='core__be_delete_col' :row="row" :search="search" :object="this.apiTokens" :colFilterOptions="colFilterOptions" :columns="columns" :sort_field="sort_field" :sort_order="sort_order" @FilterOptionshandle="FilterOptionshandle"
        @handleSort="handleSorting"  @handleSearch="handleSearching" @handleRow="handleRow"
        :globalSearchPlaceholder="$t('core__be_search_api_token')">

            <template #button>
                    <ps-button @click="handleCreate()"  rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class=" font-semibold" />{{$t('core__add_api_token')}}</ps-button>
            </template>
             <template #responsive_button>
                    <ps-button @click="handleCreate()"  rounded="rounded-lg" type="button" class="flex flex-wrap items-center"> <ps-icon name="plus" class=" font-semibold" />{{$t('core__add_api_token')}}</ps-button>
            </template>

            <template #tableActionRow="rowProps">
                <!-- For action (edit/delete) start -->
                <div class="flex flex-row" v-if="rowProps.field == 'action'">
                    <ps-button :disabled="rowProps.row.authorization.delete ? false : true" @click="confirmDeleteClicked(rowProps.row.id)" colors="bg-red-400 text-white" padding="p-1"
                        hover="hover:outline-none hover:ring hover:ring-red-100"
                        focus="focus:outline-none focus:ring focus:ring-red-200">
                        <ps-icon theme="text-white dark:text-primary-900" name="trash" w="16" h="16" />
                    </ps-button>
                    <ps-danger-dialog ref="ps_danger_dialog" />
                </div>
                <!-- For action (edit/delete) end -->
            </template>
            <template #tableRow="rowProps">

                <ps-text-button :disabled="rowProps.row.authorization.update ? false : true" v-if="rowProps.field == 'permission' && availablePermissions.length > 0" @click="manageApiTokenPermissions(rowProps.row)">
                    {{$t('core__be_permissions')}}
                </ps-text-button>
                <ps-label v-if="rowProps.field=='last_used_at'">{{ rowProps.row.last_used_at }}</ps-label>
                <ps-toggle v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false"
                    @click="handlePublish(rowProps.row.id)"></ps-toggle>
            </template>

         </ps-table2>

         <ps-label class="mt-4">{{ $t('core__be_api_token_note') }}</ps-label>

        <!-- API Token Permissions Modal -->
        <jet-dialog-modal :showFooter="false" :show="managingPermissionsFor" @close="managingPermissionsFor = false">
            <template #title>
                {{ $t('core__be_api_token_permissions') }}
            </template>

            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="permission in availablePermissions" :key="permission">
                        <label class="flex items-center">
                            <jet-checkbox :value="permission" v-model:checked="updateApiTokenForm.permissions"/>
                            <span class="ms-2 text-sm text-gray-600">{{ permission }}</span>
                        </label>
                    </div>
                </div>

                <div class="flex flex-row justify-end mb-2.5 mt-4">
                    <ps-button @click="managingPermissionsFor = false" colors="bg-white text-secondary-900 dark:text-white dark:bg-secondary-900" border="boder-1 border-secondary-900 dark:border-whites">
                        {{$t('core__be_cancel_btn')}}
                    </ps-button>

                    <ps-button class="ms-3" @click="updateApiToken(this.selectedToken)" :class="{ 'opacity-25': updateApiTokenForm.processing }" :disabled="updateApiTokenForm.processing">
                        {{$t('core__be_save_btn')}}
                    </ps-button>
                </div>
            </template>

        </jet-dialog-modal>
        <!-- data table end -->
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { Link, Head, useForm } from '@inertiajs/vue3';
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
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
import JetDialogModal from '@/Jetstream/DialogModal.vue'
import JetCheckbox from '@/Jetstream/Checkbox.vue'
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
        PsIconButton,
        PsTextButton,
        JetDialogModal,
        JetCheckbox
    },
    layout : PsLayout,
    props:{
        can:Object,
        status:Object,
        apiTokens:Object,
        hideShowFieldForFilterArr:Object,
        showCoreAndCustomFieldArr:Object,
        sort_field:{
                type:String,
                default:"",

            },
        sort_order:{
            type:String,
            default:'desc',
        },
        search:String,
        availablePermissions: Object,
    },
    setup(props) {
        // For data table
        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');

        const ps_danger_dialog = ref();

        let visible = ref(false)

        const colFilterOptions  = ref();
        const columns  = ref();

        function confirmDeleteClicked(id) {
            ps_danger_dialog.value.openModal(
                trans('core__be_delete_api_token'),
                trans('core__be_delete_api_token_info'),
                trans('core__be_btn_confirm'),
                trans('core__be_btn_cancel'),
                () => {
                    this.$inertia.delete(route("api_token.destroy", id),{
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

        function handleSearchingSorting(page = null,row=null){
            router.get(route('api_token.index'),
            {
                sort_field : sort_field.value,
                sort_order: sort_order.value,
                page:page ?? props.apiTokens.meta.current_page,
                row:row ?? props.apiTokens.meta.per_page,
                search:search.value,
            },
            {
                preserveScroll: true,
                preserveState:true,
            })
        }

        let updateApiTokenForm = useForm({
            permissions: []
        })
        let selectedToken = ref(null)
        function manageApiTokenPermissions(token) {
            this.updateApiTokenForm.permissions = (token.abilities)
            this.selectedToken = token
            this.managingPermissionsFor = true
        }

        const managingPermissionsFor = ref(false)
        function updateApiToken(t) {
                updateApiTokenForm.put(route('api_token.update', t), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => (this.managingPermissionsFor = false, this.selectedToken= null),
                    onError: () => { }
                })
            }

        return {
            handleRow,
            handleSearchingSorting,
            handleSearching,
            handleSorting,
            visible,
            columns, ps_danger_dialog,
            confirmDeleteClicked,
            colFilterOptions ,
            updateApiToken,
            updateApiTokenForm,
            selectedToken,
            managingPermissionsFor,
            manageApiTokenPermissions
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
                    label: trans('api_tokens_module'),
                    color: "text-primary-500"
                }
            ]

        }
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
    methods: {
        handleCreate() {
            this.$inertia.get(route("api_token.create"));
        },
        handleEdit(id){
            this.$inertia.get(route('api_token.edit',id));
        },
        FilterOptionshandle(value) {
        router.put(route('api_token.screenDisplayUiSetting.store'),
            {
                value,
                sort_field :this.sort_field ,
                sort_order:this.sort_order,
                row:this.apiTokens.per_page,
                search:this.search.value,
                current_page:this.apiTokens.current_page
            },
            {
                preserveScroll: true,
                preserveState:true,
            });

        },
    },
})
</script>
