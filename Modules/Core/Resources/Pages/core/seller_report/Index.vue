<template>

    <Head :title="$t('seller_report_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- data table start -->
        <ps-table2 :row="row" :search="search"  :object="this.users"
                :columns="columns" :sort_field="sort_field" :sort_order="sort_order"
            :globalSearchPlaceholder="$t('core__be_search_user')"
                @FilterOptionshandle="FilterOptionshandle" @handleSort="handleSorting" @handleSearch="handleSearching"
                @handleRow="handleRow" :searchable="showFilter" :eye_filter="false">
            <!-- for csv file import start -->
            <template #searchLeft>
                <a :href="route('seller_report.csv.export')" class="font-medium transition duration-150 ease-in-out px-2 py-1.75 ms-1 rounded text-primary-500 border border-primary-500 hover:outline-none hover:ring hover:ring-blue-100 focus:outline-none focus:ring focus:ring-blue-300 opacity-100 flex items-center"><ps-icon name="fileUpload" class="mx-0.5 font-semibold" />{{ $t('core__be_export_btn') }}</a>
            </template>
            <!-- for csv file import start -->

            <template #searchRight>
                
                <!-- <ps-text-button
                            v-if="showFilter"
                            @click="handleClearFilter"
                            class="flex flex-row text-sm items-center text-red-400"
                            padding="py-1 px-4"
                        >
                            <ps-icon
                                theme="dark:text-red-400" 
                                name="cross"
                                class="me-3"
                            />
                            {{ $t('core__be_clear_filter') }}
                        </ps-text-button>
                        <ps-icon-button :colors="!showFilter ? '' : 'bg-primary-500 text-white dark:text-secondary-800'" focus="" padding="py-1 px-4"
                    hover="hover:bg-primary-500 hover:text-white" :border="!showFilter ? ' border border-secondary-200' : 'border border-primary-500'"
                    leftIcon="filter" @click="showFilter = !showFilter">{{ $t('core__be_filter') }}</ps-icon-button> -->
                     <date-picker v-if="reRenderDate" @datepick="handleDatefilter" class="rounded shadow-sm pt-0.5  focus:outline-none focus:ring-1 focus:ring-primary-500" :class="(selected_date == null || selected_date == '') ? 'w-full' :'w-full'" 
                v-model:value="selected_date" :range="true" :inline="false" :autoApply="false"/>

                    
            </template>

            <template #Filter>

                <!-- email filter -->
                <!-- <ps-dropdown align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__user_email')"
                            :selectedValue="(selected_email == '' || selected_email == 'all') ? '' : emails.filter(option => option.email == selected_email)[0].email" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleEmailfilter('all')">
                                    <ps-label class="text-gray-200">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="email in emails" :key="email.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handleEmailfilter(email.email)">
                                    <ps-label class="ms-2" :class="email.id == selected_email ? ' font-bold' : ''">
                                        {{ email.email }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown> -->

                <!-- phone filter -->
                <!-- <ps-dropdown align="" class=" h-10">
                    <template #select>
                        <ps-dropdown-select :placeholder="$t('core__user_phone')"
                            :selectedValue="(selected_phone == '' || selected_phone == 'all') ? '' : phones.filter(option => option.user_phone == selected_phone)[0].user_phone" />
                    </template>
                    <template #list>
                        <div class="rounded-md shadow-xs w-56 ">
                            <div class="pt-2 z-30  ">
                                <div class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePhonefilter('all')">
                                    <ps-label class="text-gray-200">{{$t('core__be_select_all')}}</ps-label>
                                </div>
                                <div v-for="phone in phones" :key="phone.id"
                                    class="w-56 flex py-2 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                    @click="handlePhonefilter(phone.user_phone)">
                                    <ps-label class="ms-2" :class="phone.id == selected_phone ? ' font-bold' : ''">
                                        {{ phone.user_phone }} </ps-label>
                                </div>
                            </div>
                        </div>
                    </template>
                </ps-dropdown> -->

               
            </template>

            <template #tableRow="rowProps">
                <div class="flex flex-row" v-if="rowProps.field == usrIsVerifyBlueMark ">
                    <ps-label class=" whitespace-nowrap dark:text-white">
                        <ps-label class="flex flex-row" v-if="rowProps.row[usrIsVerifyBlueMark] == 1 "
                            textColor="text-green-600">
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2" 
                                textColor="bg-green-600">
                            </ps-label>
                            {{ $t('bluemarkuser__be_applied_label') }}
                        </ps-label>

                        <ps-label class="flex flex-row" v-else
                            >
                            <ps-label class="w-2 h-2 my-auto rounded-full me-2"
                                 textColor="bg-secondary-800 dark:bg-secondary-100">
                            </ps-label>
                            {{ $t('core__be_nomal_user') }}
                        </ps-label>
                    </ps-label>
                </div>

                <div class="flex flex-row" v-if="rowProps.field == 'overall_rating'">
                    <ps-rating :grade="rowProps.row.overall_rating" :hasCounter="true" />
                </div>

                <div class="flex flex-row" v-if="rowProps.field == 'detail'">
                    <ps-text-button @click="handleDetail(rowProps.row.id)" >{{ $t('core__be_btn_detail') }}</ps-text-button>
                </div>

            </template>

        </ps-table2>
        <!-- data table end -->

    </ps-layout>
</template>

<script>
import { ref, defineComponent } from "vue";
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
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
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
        PsIcon,
        PsRating,
        PsTextButton,
        PsIconButton,
        PsDropdown,
        PsDropdownSelect,
        DatePicker
    },
    layout: PsLayout,
    //props: ['users'],
    props: {
        status: Object,
        users: Object,
        emails: Object,
        phones: Object,
        customizeHeaders: Object,
        customizeDetails: Object,
        selected_email:Object,
        selected_phone:Object,
        selected_date:Object,
        total_sold_out_items:Object,
        selectedDate: { type: String, default: '' },
        authUser: Object,
        uis: Object,
        sort_field: {
            type: String,
            default: "",
        },
        sort_order: {
            type: String,
            default: 'desc',
        },
        search: String,
        usrIsVerifyBlueMark: String,
    },
    setup(props) {
        // For data table
        const showFilter = ref(false);
        const clearFilter = ref(false);
        const reRenderDate = ref(true);

        let visible = ref(false)

        let search = props.search ? ref(props.search) : ref('');
        let sort_field = props.sort_field ? ref(props.sort_field) : ref('');
        let sort_order = props.sort_order ? ref(props.sort_order) : ref('desc');
        let selected_date = props.selected_date ? ref(props.selected_date) : ref('');
        let selected_email = props.selected_email ? ref(props.selected_email) : ref('');
        let selected_phone = props.selected_phone ? ref(props.selected_phone) : ref('');

        function handleSorting(value) {
            sort_field.value = value.field
            sort_order.value = value.sort_order
            handleSearchingSorting()
        }

        function handleDatefilter(value) {
            selected_date.value = value;
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleSearching(value) {
            search.value = value;
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleRow(value) {
            handleSearchingSorting(1, value);
        }

        function handleClearFilter() {
            selected_date.value = '';
            selected_email.value = 'all';
            selected_phone.value = 'all';
            handleSearchingSorting();

            reRenderDate.value= false;
            setTimeout(() => {
                reRenderDate.value = true;
            }, 1);
        }

        function handleEmailfilter(value) {
            selected_email.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handlePhonefilter(value) {
            selected_phone.value = value
            let page = 1;
            handleSearchingSorting(page);
        }

        function handleSearchingSorting(page = null, row = null) {
            router.get(route('seller_report.index'),
                {
                    sort_field: sort_field.value,
                    sort_order: sort_order.value,
                    page: page ?? props.users.meta.current_page,
                    row: row ?? props.users.meta.per_page,
                    search: search.value,
                    date_filter: selected_date.value,
                    email_filter: selected_email.value,
                    phone_filter: selected_phone.value,
                },
                {
                    preserveScroll: true,
                    preserveState: true,
                })
        }

        const columns = [
            {
                label: trans('core__be_user_name_label'),
                field: "name",
                type: "String"
            },
            {
                label: trans('core__be_user_email'),
                field: "email",
                type: 'String',
            },
            {
                label: trans('core__be_user_phone'),
                field: "user_phone",
                type: "String"
            },
            {
                label: trans('core__be_blue_mark'),
                field: "ps-usr00002",
                type: "String",
            },
            {
                label: trans('core__be_registered_date'),
                field: "added_date",
                type: 'Timestamp',
            },
            {
                label: trans('core__be_sold_items_count'),
                field: "purchased_item_count",
                type: 'Integer',
            },
            {
                label: trans('core__be_user_rating'),
                field: "overall_rating",
                type: "Integer"
            },
            {
                label: trans('core__be_detail_label'),
                field: "detail",
                type: 'Action',
                sort: false
            },
        ]

        //return { globalSearchFields, globalSearchPlaceholder, columns, searchOptions }

        return{
            showFilter,
            clearFilter,
            columns,
            visible,
            handleSorting,
            handleSearchingSorting,
            handleDatefilter,
            handleRow,
            handleSearching,
            handleClearFilter,
            handleEmailfilter,
            handlePhonefilter,
            selected_email,
            selected_phone,
            selected_date,
            reRenderDate
        }
    },
    methods: {
        handleDetail(id){
            this.$inertia.get(route('seller_report.show',id));
        },
        // FilterOptionshandle(value) {
        //     router.post(route('user.screenDisplayUiSetting.store'),
        //         {
        //             value,
        //             sort_field: this.sort_field,
        //             sort_order: this.sort_order,
        //             row: this.users.per_page,
        //             search: this.search.value,
        //             current_page: this.users.current_page
        //         },
        //         {
        //             preserveScroll: true,
        //             preserveState: true,
        //         });
        // },
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('seller_report_module'),
                    color: "text-primary-500"
                }
            ]
        }
    },
})
</script>