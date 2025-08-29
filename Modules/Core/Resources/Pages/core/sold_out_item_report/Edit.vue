<template>
    <Head :title="$t('core__be_sold_out_item_report_detail')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-5>{{$t('core__be_sold_out_item_information')}}</ps-label-header-5>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 w-full">
                            <div class="grid gap-3">
                                <div>
                                    <ps-label-header-6  class="font-semibold" textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_seller_info_lbl') }}</ps-label-header-6>
                                </div>
                                <div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_name') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ item.owner.name }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_email') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ item.owner.email }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_contact_number') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ item.owner.user_phone }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_address') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ item.owner.user_address }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_registered_date') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{  moment(item.owner.added_date).format($page.props.dateFormat) }} </ps-label>
                                    </div>
                                </div>
                            </div> 
                            <div class="grid gap-6">
                                <div>
                                    <ps-label-header-6  class="font-semibold mt-8 md:mt-0" textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_item_info_lbl') }}</ps-label-header-6>
                                </div>
                                <div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_item_name') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ item.title }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_item_category') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ item['category_id@@name'] }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_item_price_title') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ checkPriceFormat(item.price) }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_item_purchased_option') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span><ps-badge v-if="item[itmPurchasedOption]">{{ item[itmPurchasedOption + '@@name'] }}</ps-badge> </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_item_type') }} </ps-label>
                                        <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span><ps-badge theme="text-red-500 bg-red-100" v-if="item[itmItemType]">{{ item[itmItemType + '@@name'] }}</ps-badge> </ps-label>
                                    </div>
                                </div>
                            </div> 
                        </div>

                        <ps-label-header-6 class="font-semibold mt-8 lg:mt-10">{{$t('core__be_buyer_details')}}</ps-label-header-6>
                        
                        <ps-data-table margin="mt-4" :rows="this.item.user_boughts" :columns="columns" :searchHide="true"></ps-data-table>

                        <div class="mb-2.5 flex flex-row justify-end mt-5">
                            <ps-button type="button" @click="handleCancel()">{{ $t('core__be_btn_back') }}</ps-button>
                        </div>
                    </form>
                </div>
                <!-- card body end -->
            </div>
        </ps-card>

    </ps-layout>
</template>

<script>
import { defineComponent, defineAsyncComponent } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, usePage } from "@inertiajs/vue3";
import PsConst from '@templateCore/object/constant/ps_constants';
import CheckBox from "../components/CheckBox.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsLabelHeader5 from "@/Components/Core/Label/PsLabelHeader5.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsVideoUpload from "@/Components/Core/Upload/PsVideoUpload.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue";
import moment from 'moment';
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
const GoogleMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/GoogleMapPinPicker.vue'));
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        CheckBox,
        DatePicker,
        PsConst,
        PsInput,
        PsRadioValue,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader6,
        PsLabelHeader5,
        PsDropdown,
        PsDropdownSelect,
        PsCard,
        PsBreadcrumb2,
        PsLabelCaption,
        PsImageUpload,
        GoogleMapPinPicker,
        PsIcon,
        PsBadge,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsImageUpload,
        GoogleMapPinPicker,
        PsVideoUpload,
        PsLabelTitle3,
        PsTable2,
        PsDataTable
    },
    layout: PsLayout,
    props: [
        'errors',
        'coreFieldFilterSettings',
        'item',
        'user_boughts',
        'customizeHeaders', 
        'customizeDetails',
        'itmItemType',
        'itmPurchasedOption'
    ],
    data() {
        return {
            moment: moment,
        }
    },
    setup(props) {
        const columns = [
            {
                label: trans('core__be_buyer_name'),
                field: 'added_user_id',
                relation: 'buyer',
                relationField: "name",
                type: 'String',
            },
            {
                label: trans('core__be_buyer_email'),
                field: 'added_user_id',
                relation: 'buyer',
                relationField: "email",
                type: 'String',
            },
            {
                label: trans('core__be_buyer_phone'),
                field: 'added_user_id',
                relation: 'buyer',
                relationField: "user_phone",
                type: 'String',
            },
            {
                label: trans('core__be_offer_amount'),
                field: "offer_amount",
                type: 'Integer',
            },
            {
                label: trans('core__be_buy_date'),
                field: "added_date",
                type: 'Date',
            },
        ]

        function checkPriceFormat(data) {
            if (usePage().props.selected_price_type == PsConst.PRICE_RANGE) {

                const floatValue = parseFloat(data);
                const intValue = parseInt(floatValue);
                if (intValue > 5) {
                    return '$'.repeat(5);
                }
                if (intValue < 1) {
                    return '$'.repeat(1);
                }
                return '$'.repeat(intValue);
            }
            if (usePage().props.selected_price_type == PsConst.NORMAL_PRICE) {
                return data;
            }
        }
        

        return { 
            columns,
            checkPriceFormat
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
                    label: trans('sold_out_item_report_module'),
                    url: route('sold_out_item_report.index'),
                },
                {
                    label: trans('core__be_sold_out_item_report_detail'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('sold_out_item_report.index'));
        },
    },
})
</script>
