<template>
    <Head :title="$t('core__be_item_detail')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header5>{{$t('core__be_successful_deal_count_information')}}</ps-label-header5>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form>
                        <div class="grid grid-cols-1 lg:grid-cols-2 mb-4 md:mb-10 gap-6 w-full">
                            <div class="grid gap-4">
                                <div>
                                    <ps-label-header-6 class="font-semibold" textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_buyer_info_lbl') }}</ps-label-header-6>
                                </div>
                                <div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_name') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{ item[0].buyer.name }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_email') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{ item[0].buyer.email }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_contact_number') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{ item[0].buyer.user_phone }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_address') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{ item[0].buyer.user_address }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_registered_date') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{  moment(item[0].buyer.added_date).format($page.props.dateFormat) }} </ps-label>
                                    </div>
                                </div>
                            </div>
                            <div class="grid gap-4">
                                <div>
                                    <ps-label-header-6 class="font-semibold"  textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_seller_info_lbl') }}</ps-label-header-6>
                                </div>
                                <div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_name') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{ item[0].seller.name }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_email') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{ item[0].seller.email }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_contact_number') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{ item[0].seller.user_phone }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_address') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{ item[0].seller.user_address }} </ps-label>
                                    </div>
                                    <div class="grid grid-cols-3 mt-1" >
                                        <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('core__be_user_registered_date') }} </ps-label>
                                        <ps-label class="text-base-800 col-span-2"  > <span class="me-2"> :</span>{{  moment(item[0].seller.added_date).format($page.props.dateFormat) }} </ps-label>
                                    </div>
                                </div>
                            </div>
                        </div>

                         <div>
                            <ps-label-header-6 class="font-semibold mb-4" textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_item_details') }}</ps-label-header-6>
                        </div>
                        <ps-data-table margin="m-0" :rows="this.item" :columns="columns" :searchHide="true" :selectedPriceType="selectedPriceType">
                            <template #tableRow="rowProps">
                                <span v-if="rowProps.field == itmPurchasedOption + '@@name'">
                                    <ps-badge class="m-2" v-if="rowProps.row[itmPurchasedOption + '@@name']">{{ rowProps.row[itmPurchasedOption + '@@name'] }}</ps-badge>
                                </span>

                                <span v-if="rowProps.field == itmItemType + '@@name'">
                                    <ps-badge theme="text-red-500 bg-red-100" class="m-2" v-if="rowProps.row[itmItemType + '@@name']">{{ rowProps.row[itmItemType + '@@name'] }}</ps-badge>
                                </span>

                                <span v-if="rowProps.field == itmConditionOfItem + '@@name'">
                                    <ps-badge theme="text-red-500 bg-red-100" class="m-2" v-if="rowProps.row[itmConditionOfItem + '@@name']">{{ rowProps.row[itmConditionOfItem + '@@name'] }}</ps-badge>
                                </span>
                            </template>
                        </ps-data-table>

                        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 w-full mt-8">
                            <div class="grid gap-3">
                                <div>
                                    <ps-label-header-6 class="font-semibold" textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_buyer_review') }}</ps-label-header-6>
                                </div>
                                <div>
                                    <div>
                                        <ps-label class="text-xs font-semibold mb-2" textColor="text-secondary-800" > {{ $t('core__be_review') }} </ps-label>
                                        <ps-rating :grade="item[0].buyer_rating" :hasCounter="true" />
                                    </div>

                                    <hr class=" mt-2 mb-4"/>

                                    <div>
                                        <ps-label class="text-xs mb-2" textColor="text-secondary-500" > {{ $t('core__be_remark') }} </ps-label>
                                        <ps-textarea rows="4" v-model:value="item[0].buyer_remark" :placeholder="$t('core__be_remark')" />
                                    </div>
                                </div>
                            </div>
                            <div class="grid gap-3">
                                <div>
                                    <ps-label-header-6 class="font-semibold" textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_seller_review') }}</ps-label-header-6>
                                </div>
                                <div>
                                    <div>
                                        <ps-label class="text-xs font-semibold mb-2" textColor="text-secondary-800" > {{ $t('core__be_review') }} </ps-label>
                                        <ps-rating :grade="item[0].seller_rating" :hasCounter="true" />
                                    </div>

                                    <hr class=" mt-2 mb-4"/>

                                    <div>
                                        <ps-label class="text-xs mb-2" textColor="text-secondary-500" > {{ $t('core__be_remark') }} </ps-label>
                                        <ps-textarea rows="4" v-model:value="item[0].seller_remark" :placeholder="$t('core__be_remark')" />
                                    </div>
                                </div>
                            </div>
                        </div>

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
import { Head, useForm } from "@inertiajs/vue3";
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
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsDataTable from "@/Components/Core/Table/PsDataTable.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue";
import PsRating from "@/Components/Core/Rating/PsRating.vue";
import moment from 'moment';
import { trans } from 'laravel-vue-i18n';
import PsConst from '@templateCore/object/constant/ps_constants';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        CheckBox,
        DatePicker,
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
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsImageUpload,
        PsVideoUpload,
        PsLabelTitle3,
        PsDataTable,
        PsBadge,
        PsRating
    },
    layout: PsLayout,
    props: [
        'errors',
        'coreFieldFilterSettings',
        'item',
        'customizeHeaders',
        'customizeDetails',
        'itmPurchasedOption',
        'itmItemType',
        'itmDealOption',
        'selected_price_type',
    ],
    data() {
        return {
            moment: moment,
        }
    },
    setup(props) {
        const selectedPriceType = props.selected_price_type;
        let columns = []; let temp1 = []; let temp2 = [];
        if (selectedPriceType == PsConst.NO_PRICE) {
            temp1 = [
            {
                label: trans('core__be_item_name'),
                field: 'title',
                type: 'String',
            },
            {
                label: trans('core__be_category_name'),
                field: 'category_id@@name',
                // relation: 'category',
                // relationField: "name",
                type: 'String',
            },
            ];
        } else if (selectedPriceType == PsConst.PRICE_RANGE) {
            temp1 = [
            {
                label: trans('core__be_item_name'),
                field: 'title',
                type: 'String',
            },
            {
                label: trans('core__be_category_name'),
                field: 'category_id@@name',
                // relation: 'category',
                // relationField: "name",
                type: 'String',
            },
            {
                label: trans('core__be_item_price'),
                field: 'original_price',
                type: 'Integer',
            },
            ];
        }

        else {
            temp1 = [
            {
                label: trans('core__be_item_name'),
                field: 'title',
                type: 'String',
            },
            {
                label: trans('core__be_category_name'),
                field: 'category_id@@name',
                // relation: 'category',
                // relationField: "name",
                type: 'String',
            },
            {
                label: trans('core__be_item_price'),
                field: 'price',
                type: 'Integer',
            },
            ]
        }
        temp2 = [
        {
                label: trans('core__be_offer_amount'),
                field: "offer_amount",
                type: 'Integer',
            },
            {
                label: trans('core__be_purchased_option'),
                field: props.itmPurchasedOption + '@@name',
                type: "String",
            },
            {
                label: trans('core__be_item_type'),
                field: props.itmItemType + '@@name',
                type: "String",
            },
            {
                label: trans('core__be_deal_option'),
                field: props.itmDealOption + '@@name',
                type: 'String',
                action: 'Action'
            },
            {
                label: trans('core__be_deal_date'),
                field: "added_date",
                type: 'Date',
            },
        ]

        columns = [...temp1, ...temp2];
        return {
            columns,
            selectedPriceType,
            PsConst
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
                    label: trans('successful_deal_count_report_module'),
                    url: route('successful_deal_count_report.index'),
                },
                {
                    label: trans('core__be_successful_deal_count_report_detail'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('successful_deal_count_report.index'));
        },
    },
})
</script>
