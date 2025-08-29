<template>
    <Head :title="$t('core_vendor__edit_order')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-label class="text-xl font-semibold mb-4" >{{ $t('core_vendor__order_information') }}</ps-label>

        <!-- status overview start -->
        <ps-label class="lg:text-lg lg:font-medium bg-primary-50 px-4 py-2 rounded-t" >{{ $t('core_vendor__status_overview') }}</ps-label>
        <form @submit.prevent="handleSubmit(order.id)" class="border rounded-b border-t-0 p-4 mb-4">
            <div class="flex gap-6">
                <div class="w-full">
                    <ps-label class="lg:text-base mb-2 lg:font-medium">{{ $t('core_vendor__order_status') }}</ps-label>
                    <ps-dropdown @on-click="loadMore" horizontalAlign="left" class="me-0 w-full"  >
                        <template #select>
                            <ps-dropdown-select h="h-10" rounded="rounded-s"  :showCenter="true" :selectedValue="orderStatuses.filter((status)=>status.id == form.order_status_id)[0].name" />
                        </template>
                        <template #list>
                            <div class="rounded-md shadow-xs w-56 ">
                                <div class="pt-2 z-30 ">
                                    <div>
                                        <div v-for="selectData in orderStatuses" :key="selectData.id"
                                            class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                            @click="form.order_status_id = selectData.id">

                                            <ps-label class="ms-2"
                                                :class="selectData.id == order.order_status_id ? ' font-bold' : ''">
                                                {{ selectData.name }} </ps-label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
                <div class="w-full">
                    <ps-label class="lg:text-base mb-2 lg:font-medium">{{ $t('core_vendor__payment_status') }}</ps-label>
                    <ps-dropdown @on-click="loadMore" horizontalAlign="left" class="me-0 w-full"  >
                        <template #select>
                            <ps-dropdown-select h="h-10" rounded="rounded-s"  :showCenter="true" :selectedValue="paymentStatuses.filter((status)=>status.id == form.payment_status_id)[0].name" />
                        </template>
                        <template #list>
                            <div class="rounded-md shadow-xs w-56 ">
                                <div class="pt-2 z-30 ">
                                    <div>
                                        <div v-for="selectData in paymentStatuses" :key="selectData.id"
                                            class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                            @click="form.payment_status_id = selectData.id">

                                            <ps-label class="ms-2"
                                                :class="selectData.id == order.vendor_payment_status_id ? ' font-bold' : ''">
                                                {{ selectData.name }} </ps-label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </ps-dropdown>
                </div>
            </div>
            <div class="flex flex-row justify-end mt-6">
                <ps-button @click="handleCancel()" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{
                    $t('core__be_btn_cancel') }}</ps-button>
                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover=""
                    focus="">
                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                        loadingSize="h-5 w-5" />
                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                    <ps-label v-if="success" class="transition-all duration-300"
                        textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_saved') }}</ps-label>
                    <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black"> {{
                        $t('core__be_btn_save') }} </ps-label>
                </ps-button>
            </div>
        </form>

        <!-- order summery start -->
        <ps-label class="lg:text-lg lg:font-medium bg-primary-50 px-4 py-2 rounded-t" >{{ $t('core_vendor__order_summary') }}</ps-label>
        <div class="border rounded-b border-t-0 p-4 mb-4">
            <div class="grid grid-cols-4 gap-1">
                <div class="">
                    <ps-label textColor="lg:text-secondary-500 mb-2"> {{ $t("core_vendor__order_id") }} <span class="float-end">:</span> </ps-label>
                    <ps-label textColor="lg:text-secondary-500 mb-2"> {{ $t("core_vendor__order_date") }} <span class="float-end">:</span> </ps-label>
                    <ps-label textColor="lg:text-secondary-500 mb-2"> {{ $t("core_vendor__customer_name") }} <span class="float-end">:</span> </ps-label>
                    <ps-label textColor="lg:text-secondary-500 mb-2"> {{ $t("core_vendor__customer_email") }} <span class="float-end">:</span> </ps-label>
                    <ps-label textColor="lg:text-secondary-500 mb-2"> {{ $t("core_vendor__shipping_address") }} <span class="float-end">:</span> </ps-label>
                </div>
                <div class="">
                    <ps-label class="mb-2">{{ order.order_code }}</ps-label>
                    <ps-label class="mb-2">{{ order.order_date }}</ps-label>
                    <ps-label class="mb-2">{{ order.owner.name }}</ps-label>
                    <ps-label class="mb-2">{{ order.owner.email }}</ps-label>
                    <div>
                        <ps-label >{{ order?.shipping_and_billing?.shipping_first_name+' '+order?.shipping_and_billing?.shipping_last_name }}</ps-label>
                        <ps-label >{{ order?.shipping_and_billing?.shipping_phone_no }}</ps-label>
                        <ps-label >{{ order?.shipping_and_billing?.shipping_email }}</ps-label>
                        <ps-label >{{ order?.shipping_and_billing?.shipping_address }}</ps-label>
                        <ps-label >{{ order?.shipping_and_billing?.shipping_country+', '+order?.shipping_and_billing?.shipping_state+', '+order?.shipping_and_billing?.shipping_city+', '+order?.shipping_and_billing?.shipping_postal_code}}</ps-label>
                    </div>
                </div>
                <div class="">
                    <ps-label textColor="lg:text-secondary-500 mb-2"> {{ $t("core_vendor__payment_method") }} <span class="float-end">:</span> </ps-label>
                    <ps-label textColor="lg:text-secondary-500 mb-2"> {{ $t("core_vendor__total_order_amount") }} <span class="float-end">:</span> </ps-label>
                    <ps-label textColor="lg:text-secondary-500 mb-2"> {{ $t("core_vendor__billing_address") }} <span class="float-end">:</span> </ps-label>
                </div>
                <div class="">
                    <ps-label class="mb-2">{{ order.vendor_transaction?.payment_method ? order.vendor_transaction?.payment_method : 'N.A' }}</ps-label>
                    <ps-label class="mb-2">{{ order.vendor_transaction?.currency?.currency_symbol }}{{ order.total_price }}</ps-label>
                    <div>
                        <ps-label>{{ order?.shipping_and_billing?.billing_first_name+' '+order?.shipping_and_billing?.billing_last_name }}</ps-label>
                        <ps-label>{{ order?.shipping_and_billing?.billing_phone_no }}</ps-label>
                        <ps-label>{{ order?.shipping_and_billing?.billing_email }}</ps-label>
                        <ps-label>{{ order?.shipping_and_billing?.billing_address }}</ps-label>
                        <ps-label>{{ order?.shipping_and_billing?.billing_country+', '+order?.shipping_and_billing?.billing_state+', '+order?.shipping_and_billing?.billing_city+', '+order?.shipping_and_billing?.billing_postal_code}}</ps-label>
                    </div>
                </div>
            </div>
        </div>

        <!-- order table start -->
        <ps-label class="lg:text-lg lg:font-medium bg-primary-50 px-4 py-2 rounded-t" >{{ order.vendor.name }}</ps-label>
        <div class="border rounded-b border-t-0 mb-4">
            <table class="w-full">
                <thead class="border-b-2">
                    <tr>
                        <th class="text-start ps-4"><ps-label textColor="font-semibold text-base" class="my-2">#</ps-label></th>
                        <th class="text-start"><ps-label textColor="font-semibold text-base">{{ $t('core_vendor__product_info') }}</ps-label></th>
                        <th class="text-start"><ps-label textColor="font-semibold text-base">{{ $t('core_vendor__quantity') }}</ps-label></th>
                        <th class="text-start"><ps-label textColor="font-semibold text-base">{{ $t('core_vendor__unit_price') }}</ps-label></th>
                        <th class="text-start"><ps-label textColor="font-semibold text-base">{{ $t('core_vendor__discount') }}</ps-label></th>
                        <th class="text-start"><ps-label textColor="font-semibold text-base">{{ $t('core_vendor__total_price') }}</ps-label></th>
                    </tr>
                </thead>
                <tbody class="border-b-2">
                    <tr v-for="(row, index) in order.order_items" :key="index">
                        <td class="h-11 ps-4 items-center justify-center">
                            <ps-label textColor="font-semibold text-base">{{index + 1}}</ps-label>
                        </td>
                        <td class="py-4">
                            <div class="flex items-center gap-2">
                                <img class="w-20 h-20 rounded object-cover" width="24px" height="24px"
                                v-lazy="{ src: $page.props.thumb2xUrl + '/' + row.item?.cover[0]?.img_path, loading: $page.props.sysImageUrl + '/loading_gif.gif', error: $page.props.sysImageUrl + '/default_photo.png' }" >
                                <ps-label>{{ row.item?.title }}</ps-label>
                            </div>
                        </td>
                        <td class="">
                            <ps-label textColor="font-semibold text-base">{{ row.quantity }}</ps-label>
                        </td>
                        <td class="">
                            <ps-label textColor="font-semibold text-base">{{ order.vendor_transaction?.currency?.currency_symbol }}{{ row.sale_price }}</ps-label>
                        </td>
                        <td class="">
                            <ps-label textColor="font-semibold text-base">{{ order.vendor_transaction?.currency?.currency_symbol }}{{ row.discount_price }}</ps-label>
                        </td>
                        <td class="">
                            <ps-label textColor="font-semibold text-base">{{ order.vendor_transaction?.currency?.currency_symbol }}{{ row.sub_total }}</ps-label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="flex justify-end p-4">
                <div class="w-96 grid grid-cols-2">
                    <ps-label class="lg:text-lg p-1">{{$t('core_vendor__subtotal')}}</ps-label>
                    <ps-label class="lg:text-lg p-1 text-end">{{ order.vendor_transaction?.currency?.currency_symbol }}{{order.sub_total}}</ps-label>
                    <ps-label class="lg:text-lg p-1">{{$t('core_vendor__discount')}}</ps-label>
                    <ps-label class="lg:text-lg p-1 text-end" textColor="text-red-500">-{{ order.vendor_transaction?.currency?.currency_symbol }}{{order.discount_price}}</ps-label>
                    <ps-label class="lg:text-lg p-1">{{$t('core_vendor__delivery_charges')}}</ps-label>
                    <ps-label class="lg:text-lg p-1 text-end">{{ order.vendor_transaction?.currency?.currency_symbol }}{{order.delivery_fee}}</ps-label>
                    <span class="col-span-2 border-b-2"></span>
                    <ps-label textColor="font-semibold" class="lg:text-lg p-1">{{$t('core_vendor__total')}}</ps-label>
                    <ps-label textColor="font-semibold" class="lg:text-lg p-1 text-end">{{ order.vendor_transaction?.currency?.currency_symbol }}{{order.total_price}}</ps-label>
                </div>
            </div>
        </div>
        <ps-button @click="handleCancel()" class="float-right" padding="px-7 py-2" rounded="rounded" hover="" key="" focus="">
            <ps-label textColor="text-white dark:text-secondaryDark-black">{{$t("core_vendor__back")}}</ps-label>
        </ps-button>
    </ps-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import { Link, Head, useForm, router } from '@inertiajs/vue3';
import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsAlert from "@/Components/Core/Alert/PsAlert.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsBadge from "@/Components/Core/Badge/PsBadge.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import { trans } from 'laravel-vue-i18n';


    export default defineComponent({
        name: "Edit",
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
            PsLoading,
            PsIcon,
            PsBannerIcon,
            PsIconButton,
            PsBadge,
            PsDropdown,
            PsDropdownSelect,
        },
        layout: PsVendorLayout,
        props: {
            order: Object,
            paymentStatuses: Object,
            orderStatuses: Object,
        },
        setup(props) {
            console.log(props);
            const orderItems = ref();
            const loading = ref(false);
            const success = ref(false);
            const columns = [
                {
                    label: trans('core_vendor__product_info'),
                    field: 'order_code',
                    type: 'String',
                },
                {
                    label: trans('core_vendor__quantity'),
                    field: 'quantity',
                    type: 'String',
                },
                {
                    label: trans('core_vendor__unit_price'),
                    field: 'sale_price',
                    type: 'String',
                },
                {
                    label: trans('core_vendor__discount'),
                    field: 'discount_price',
                    type: 'String',
                },
                {
                    label: trans('core_vendor__total_price'),
                    field: 'order_code',
                    type: 'String',
                },
            ];

            let form = useForm({
                order_status_id : props.order.order_status_id,
                payment_status_id : props.order.vendor_payment_status_id,
                "_method": "put"
            });

            function handleSubmit(id) {
                this.$inertia.post(route("vendor_order_list.update", id), form, {
                    forceFormData: true,
                    onBefore: () => {
                        loading.value = true;
                    },
                    onSuccess: () => {
                        loading.value = false;
                        success.value = true;
                        setTimeout(() => {
                            success.value = false;
                        }, 2000);
                    },
                    onError: () => {
                        name.value.isError = ("name" in props.errors) ? true : false;
                        loading.value = false;
                    },
                });
            }

            function handleCancel(){
                this.$inertia.get(route("vendor_order_list.index"));
            }

            return {
                loading,
                success,
                columns,
                orderItems,
                form,
                handleSubmit,
                handleCancel,
            }
        },
        computed: {
            breadcrumb() {
                return [
                    {
                        label: trans('core__vendor_my_store_module'),
                        url: route('vendor_info.index')
                    },
                    {
                        label: trans('core__vendor_order_list_module'),
                        url: route('vendor_order_list.index')
                    },
                    {
                        label: trans('core_vendor__edit_order'),
                        color: "text-primary-500"
                    }
                ]

            }
        },
    })
</script>
