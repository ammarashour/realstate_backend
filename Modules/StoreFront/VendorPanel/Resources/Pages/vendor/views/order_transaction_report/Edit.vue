

<template>
    <Head :title="$t('core__vendor_transaction_reports_module')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="px-4">
                    <ps-label-header5><b>{{$t('core_transaction_report_detail')}}</b></ps-label-header5>
                </div>
                <!-- card header end -->
                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <div class="grid xxl:grid-cols-2 xl:grid-cols-2 lg:grid-cols-2 md:grid-cols-2 sm:grid-cols-1">
                        <div class="grid gap-6" style="height: max-content;">
                            <ps-label textColor="text-secondary-800 dark:text-secondary-100">
                                <b>{{ $t('core_transaction_information') }}</b>
                            </ps-label>

                            <table class="w-full">
                                    <tr>
                                        <td>
                                            <ps-label class="col-6">
                                            {{ $t('order_transaction_report__transaction_date')}}
                                            : </ps-label>
                                        </td>
                                        <td>
                                            <ps-label class="col-6">
                                                {{
                                                    new Date(order_transaction['order']['order_date']).toLocaleDateString('en-US', {
                                                      month: 'long',
                                                      day: '2-digit',
                                                      year: 'numeric'
                                                    })
                                                  }}
                                            </ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td>
                                            <ps-label class="col-6">
                                            {{ $t('order_transaction_report__payment_method')}}
                                            : </ps-label>
                                        </td>
                                        <td>
                                            <ps-label class="col-6">
                                                {{ order_transaction['payment_method']}}
                                            </ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td>
                                            <ps-label>
                                                {{ $t('order_transaction_report__payment_status')}} :
                                            </ps-label>
                                        </td>
                                        <td>
                                            <ps-badge :bgColorCode="'#FAFAFA'"
                                            :textColorCode="order_transaction['vendor_payment_status']['colour']"
                                            theme="" >
                                                {{ order_transaction['vendor_payment_status']['name']}}
                                            </ps-badge>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td>
                                            <ps-label> {{ $t('core_vendor__total_order_amount')}} :</ps-label>
                                        </td>
                                        <td><ps-label>{{ order_transaction['currency']['currency_symbol'] }}
                                            {{ order_transaction['payment_amount'] }} </ps-label></td>
                                    </tr>
                            </table>
                        </div>


                        <div class="grid gap-6" style="height: max-content;">
                            <ps-label textColor="text-secondary-800 dark:text-secondary-100">
                                <b>{{ $t('core_vendor__order_information') }}</b>
                            </ps-label>
                            <table class="w-full ">
                                    <tr>
                                        <td>
                                            <ps-label>{{ $t('order_transaction_report__order_id')}}:</ps-label>
                                        </td>
                                        <td>
                                            <ps-label>{{ order_transaction['order']['order_code']}}</ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td>
                                            <ps-label>{{ $t('core_vendor__order_date')}}:</ps-label>
                                        </td>
                                        <td>
                                            <ps-label>
                                                {{
                                                    new Date(order_transaction['order']['order_date']).toLocaleDateString('en-US', {
                                                        month: 'long',
                                                        day: '2-digit',
                                                        year: 'numeric'
                                                    })
                                                }}
                                            </ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td>
                                            <ps-label>{{ $t('core_vendor_order_status')}}:</ps-label>
                                        </td>
                                        <td>
                                            <ps-label>{{ order_status['name'] }}</ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td>
                                            <ps-label>{{ $t('core_vendor__customer_name')}}:</ps-label>
                                        </td>
                                        <td>
                                            <ps-label>{{ order_transaction['owner']['name'] }}</ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td >
                                            <ps-label>{{ $t('core_vendor__customer_email')}}:</ps-label>
                                        </td>
                                        <td>
                                            <ps-label>{{ order_transaction['owner']['email'] }}</ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <ps-label>{{ $t('core_vendor__shipping_address')}}:</ps-label>
                                        </td>
                                        <td>
                                            <ps-label>
                                                {{ user_address['shipping_first_name'] }} {{ user_address['shipping_last_name'] }}<br/>
                                                {{ user_address['shipping_phone_no'] }}<br/>
                                                {{ user_address['shipping_email'] }}<br/>
                                                {{ user_address['shipping_address'] }}<br/>
                                                {{ user_address['shipping_country'] }}, {{ user_address['shipping_state'] }}, {{ user_address['shipping_city'] }}, {{ user_address['shipping_postal_code'] }}
                                            </ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td style="vertical-align: top;">
                                            <ps-label>{{ $t('core_vendor__billing_address')}}:</ps-label>
                                        </td>
                                        <td>
                                            <ps-label>
                                                {{ user_address['billing_first_name'] }} {{ user_address['billing_last_name'] }}<br/>
                                                {{ user_address['billing_phone_no'] }}<br/>
                                                {{ user_address['billing_email'] }}<br/>
                                                {{ user_address['billing_address'] }}<br/>
                                                {{ user_address['billing_country'] }}, {{ user_address['billing_state'] }}, {{ user_address['billing_city'] }}, {{ user_address['billing_postal_code'] }}
                                            </ps-label>
                                        </td>
                                    </tr><br/>
                                    <tr>
                                        <td></td>
                                        <td style="text-align: right;">
                                            <ps-button @click="handleCancel()" style="margin-left: auto;">Back </ps-button>
                                        </td>
                                    </tr>
                            </table>
                        </div>

                    </div>
                </div>
                <!-- card body end -->


            </div>
        </ps-card>

    </ps-layout>
</template>

<script>
import { defineComponent, defineAsyncComponent } from 'vue'
import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsConst from '@templateCore/object/constant/ps_constants';
import CheckBox from "../components/CheckBox.vue";
import PsRadioValue from "@/Components/Core/Radio/PsRadioValue.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
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
const GoogleMapPinPicker = defineAsyncComponent(() => import('@/Components/Core/Map/GoogleMapPinPicker.vue'));
import { trans } from 'laravel-vue-i18n';
import moment from 'moment';
import PsBadge from "@/Components/Core/Badge/PsBadge.vue"

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
        PsLabelHeader4,
        PsLabelHeader5,
        PsDropdown,
        PsDropdownSelect,
        PsCard,
        PsBreadcrumb2,
        PsLabelCaption,
        PsImageUpload,
        GoogleMapPinPicker,
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsImageUpload,
        GoogleMapPinPicker,
        PsVideoUpload,
        PsLabelTitle3,
        moment: moment,
        PsBadge

    },
    layout: PsVendorLayout,
    props: [
        'errors',
        'order_transaction',
        'order_status',
        'user_address'
    ],
    setup(props) {
        function checkPriceFormat(data) {
            if (props.selected_price_type == PsConst.PRICE_RANGE) {

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
            if (props.selected_price_type == PsConst.NORMAL_PRICE) {
                return data;
            }
            if (props.selected_price_type == PsConst.NO_PRICE) {
                return data;
            }
        }

        let form = useForm(
            {
                id: props.order_transaction.id,
                title: props.order_transaction.title,
                product_relation: {},
                "_method": "put"
            }
        )

        if(props.selected_price_type == PsConst.PRICE_RANGE){
            var priceRange = checkPriceFormat(form.price);
        }

        return { form , checkPriceFormat , priceRange, PsConst}
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__vendor_my_store_module'),
                    url: route('vendor_info.index')
                },
                {
                    label: trans('core__vendor_order_transaction_module'),
                    url: route('order_transaction_report.index')
                },
                {
                    label: trans('core_transaction_detail'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('order_transaction_report.index'));
        },
    },
})
</script>
