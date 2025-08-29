<template>
    <Head :title="$t('edit_transaction')" />
    <ps-layout>
        <div class="">
            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
            <!-- breadcrumb end -->
            <div class="w-full mt-8 pb-4 rounded-lg bg-white  shadow-sm">
                <ps-label class="text-lg px-4 py-2.5 bg-primary-50">Transaction Details</ps-label>
                <div class="px-4 grid grid-cols-2 gap-x-8 mt-8">
                    <div>
                        <ps-label class="font-semibold text-lg" > {{ transaction.shop.name }} </ps-label>
                        <div class="grid grid-cols-3 mt-4" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Shop {{ $t('address_label') }} </ps-label>
                            <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ transaction.address }} </ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Shop ID </ps-label>
                            <ps-label class="text-base col-span-2"  > <span class="me-2"> :</span>{{ transaction.shop_id }} </ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('email_label') }} </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.shop.email }} </ps-label>
                        </div>
                    </div>
                    <div>
                        <ps-label class="font-semibold text-lg" > Invoice </ps-label>
                        <div class="grid grid-cols-3 mt-4" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Invoice Number </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.trans_code }} </ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Date </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.created_date }} </ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Status </ps-label>
                            <div>
                                <ps-label class="text-base col-span-2"  v-for="transaction_status in transaction_statuses" :key="transaction_status.id">
                                    <div v-if="transaction_status.id == transaction.transaction_status_id" :style="{color:transaction_status.color_value }">
                                        <span class="me-2"> :</span>
                                        {{  transaction_status.title}}
                                    </div>
                                </ps-label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-x-8 mt-6 px-4">
                    <div>
                        <ps-label class="font-semibold text-lg" > Billing Address </ps-label>
                        <div class="grid grid-cols-3 mt-4" >
                            <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('name_label') }} </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.billing_first_name }} {{ transaction.billing_last_name }}</ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('address_1_label') }} </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.billing_address_1 }} </ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('address_2_label') }} </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.billing_address_2 }} </ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('email_label') }} </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.billing_email }} </ps-label>
                        </div>
                    </div>
                    <div>
                        <ps-label class="font-semibold text-lg" > Shipping Address </ps-label>
                        <div class="grid grid-cols-3 mt-4" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Name </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.shipping_first_name }} {{ transaction.shipping_last_name }}</ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('address_1_label') }} </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.shipping_address_1 }} </ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('address_2_label') }} </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.shipping_address_2 }} </ps-label>
                        </div>
                        <div class="grid grid-cols-3 mt-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > {{ $t('email_label') }} </ps-label>
                            <ps-label class="text-base col-span-2" > <span class="me-2"> :</span>{{ transaction.shipping_email }} </ps-label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full mt-8 py-4 px-4 rounded-lg bg-white  shadow-sm">
                <ps-label class="font-semibold text-lg" > Product Details </ps-label>
                <table class="w-full mt-4">
                    <thead class="">
                        <tr>
                            <th scope="col" class="bg-primary-500 py-2">
                                <ps-label textColor="text-white" class="text-sm font-semibold text-left px-4" > No </ps-label>
                            </th>
                            <th scope="col" class="bg-primary-500 py-2">
                                <ps-label textColor="text-white" class="text-sm font-semibold text-left px-4" > Product Name </ps-label>
                            </th>
                            <th scope="col" class="bg-primary-500 py-2">
                                <ps-label textColor="text-white" class="text-sm font-semibold text-left px-4" > Product Unit </ps-label>
                            </th>
                            <th scope="col" class="bg-primary-500 py-2">
                                <ps-label textColor="text-white" class="text-sm font-semibold text-left px-4" > Item Price </ps-label>
                            </th>
                            <th scope="col" class="bg-primary-500 py-2">
                                <ps-label textColor="text-white" class="text-sm font-semibold text-left px-4" > Quantity </ps-label>
                            </th>
                            <th scope="col" class="bg-primary-500 py-2">
                                <ps-label textColor="text-white" class="text-sm font-semibold text-left px-4" > Discount </ps-label>
                            </th>
                            <!-- <th scope="col" class="bg-primary-500 py-2">
                                <ps-label textColor="text-white" class="text-sm font-semibold text-left px-4" > Shipping Cost </ps-label>
                            </th> -->
                            <th scope="col" class="bg-green-500 py-2">
                                <ps-label textColor="text-white" class="text-sm font-semibold text-left ps-4 pe-8" > Amount </ps-label>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="detail in transaction.transaction_detail" :key="detail.id"
                            class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                            <td class="">
                                <ps-label class="text-sm py-3 px-4" > {{ detail.id }} </ps-label>
                            </td>
                            <td class="">
                                <ps-label class="text-sm py-3 px-4" > {{ detail.item_name }} </ps-label>
                            </td>
                            <td class="">
                                <ps-label class="text-sm py-3 px-4" > {{ detail.item_unit }} </ps-label>
                            </td>
                            <td class="">
                                <ps-label class="text-sm py-3 px-4" > {{ detail.price }} {{ transaction.currency_symbol }} </ps-label>
                            </td>
                            <td class="">
                                <ps-label class="text-sm py-3 px-4" > {{ detail.qty }} </ps-label>
                            </td>
                            <td class="">
                                <ps-label class="text-sm py-3 px-4" > -{{ detail.discount_amount }} {{ transaction.currency_symbol }} </ps-label>
                            </td>
                            <td class="">
                                <ps-label class="text-sm py-3 px-4" > {{ detail.original_price * detail.qty }} {{ transaction.currency_symbol }} </ps-label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="w-full mt-8 py-4 px-4 rounded-lg bg-white  shadow-sm">
                <div class="grid grid-cols-2 gap-x-4">
                    <div>
                        <ps-label class="font-semibold text-lg" > Payment </ps-label>
                        <div class="grid grid-cols-2 mt-2 py-2  shadow-sm" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Payment Method </ps-label>
                            <ps-label class="text-base" > <span class="me-2"> :</span>{{ transaction.payment_method }} </ps-label>
                        </div>
                        <div class="grid grid-cols-2 py-2  shadow-sm" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Overall Tax </ps-label>
                            <ps-label class="text-base" > <span class="me-2"> :</span>{{ transaction.tax_percent * 100 }} % </ps-label>
                        </div>
                        <div class="grid grid-cols-2 py-2  shadow-sm" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Shipping Tax </ps-label>
                            <ps-label class="text-base" > <span class="me-2"> :</span>{{ transaction.shipping_tax_percent }} </ps-label>
                        </div>
                        <div class="py-2" >
                            <ps-label class="text-base" textColor="text-secondary-500" > Memo </ps-label>
                            <ps-textarea rows="3" placeholder="Placeholder" :disabled="true" class="w-full rounded text-base" v-model:value="transaction.memo"> </ps-textarea>
                        </div>
                    </div>
                    <div>
                        <ps-label class="font-semibold text-lg" > Total Amount </ps-label>
                        <div class="grid grid-cols-4 mt-2 py-2 px-4 shadow-sm" >
                            <ps-label class="col-span-3" textColor="text-secondary-500" > Coupon Discount Amount (-) </ps-label>
                            <ps-label class="" > <span class="me-2"> :</span>{{ transaction.coupon_discount_amount }} {{ transaction.currency_symbol }}  </ps-label>
                        </div>
                        <div class="grid grid-cols-4 mt-2 py-2 px-4 shadow-sm" >
                            <ps-label class="col-span-3" textColor="text-secondary-500" > Item Subtotal </ps-label>
                            <ps-label class="" > <span class="me-2"> :</span>{{ transaction.sub_total_amount }} {{ transaction.currency_symbol }}  </ps-label>
                        </div>
                        <div class="grid grid-cols-4 mt-2 py-2 px-4 shadow-sm" >
                            <ps-label class="col-span-3" textColor="text-secondary-500" > Overall Tax ({{ transaction.tax_percent * 100 }}%) (+) </ps-label>
                            <ps-label class="" > <span class="me-2"> :</span>{{ transaction.tax_amount }} {{ transaction.currency_symbol }} </ps-label>
                        </div>
                        <div class="grid grid-cols-4 mt-2 py-2 px-4 shadow-sm" >
                            <ps-label class="col-span-3" textColor="text-secondary-500" > Shipping Cost ({{ transaction.shipping_method_name }}) (+) </ps-label>
                            <ps-label class="" >
                                <span class="me-2"> :</span>
                                {{ transaction.shipping_amount }} {{ transaction.currency_symbol }}
                            </ps-label>
                        </div>
                        <div class="grid grid-cols-4 mt-2 py-2 px-4 shadow-sm" >
                            <ps-label class="col-span-3" textColor="text-secondary-500" > Shipping Tax ({{ transaction.shipping_tax_percent * 100 }}%) (+)</ps-label>
                            <ps-label class="" >
                                <span class="me-2"> :</span>
                                {{ transaction.shipping_amount * transaction.shipping_tax_percent }} {{ transaction.currency_symbol }}
                            </ps-label>
                        </div>
                        <div class="grid grid-cols-4 mt-2 py-2 px-4 shadow-sm bg-primary-50" >
                            <ps-label class="col-span-3 font-semibold" textColor="text-secondary-500" > Total Balance Amount </ps-label>
                            <ps-label class="font-semibold" >
                                <span class="me-2"> :</span>
                                {{
                                    transaction.sub_total_amount + (transaction.tax_amount + transaction.shipping_amount +
                                        (transaction.shipping_amount * transaction.shipping_tax_percent))
                                }} {{ transaction.currency_symbol }}
                            </ps-label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<!--
                        <div class="w-1/2">
                            <ps-label-header-4 class="font-bold text-2xl mb-1">Transaction Detail</ps-label-header-4>
                            <div><u>Customer Information</u></div>
                            <div class="py-1">Name: {{transaction.contact_name}}</div>
                            <div class="py-1">{{ $t('email_label') }}: {{transaction.contact_email}}</div>
                            <div class="py-1">{{ $t('phone_label') }}: {{transaction.contact_phone}}</div>
                            <div class="py-1">{{ $t('address_label') }}: {{transaction.contact_address}}</div>
                        </div>
                        <div class="w-1/2">
                                <div class="ms-24">Date: {{transaction.created_date}}</div>
                                <div class="ms-24">Invoice: {{transaction.trans_code}}</div>
                            <div class="mt-6 flex justify-center">
                                <form @submit.prevent="handleSubmit(this.transaction.id)">
                                    <div>
                                        <ps-label class="block"><ps-label
                                                class="text-red-800 font-medium me-1">*</ps-label>Transation
                                            Status</ps-label>
                                        <select type="text" class="w-full rounded flex-1"
                                            v-model="form.transaction_status_id">
                                            <option value="">Please select transaction status</option>
                                            <option v-for="transaction_status in transaction_statuses"
                                                :value="transaction_status.id" :key="transaction_status.id">
                                                {{ transaction_status.title }}
                                            </option>
                                        </select>
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.transaction_status_id}}</ps-label-caption>
                                    </div>
                                    <div>
                                        <ps-label class="block">Payment Status</ps-label>
                                        <select type="text" class="w-full rounded flex-1"
                                            v-model="form.payment_status_id">
                                            <option value="">Please select payment status</option>
                                            <option v-for="payment_status in payment_statuses"
                                                :value="payment_status.id" :key="payment_status.id">
                                                {{ payment_status.title }}
                                            </option>
                                        </select>
                                        <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.payment_status_id}}</ps-label-caption>
                                    </div>
                                    <div>
                                        <ps-button
                                            >Update</ps-button>
                                    </div>
                                </form>
                            </div>

                        </div> -->

                    <!-- <div class="flex flex-row justify-content-between mx-5 mb-5">
                        <div class="w-1/2">
                            <div class="py-2">Payment Method : {{ transaction.payment_method }}</div>
                            <div class="py-2">Memo : {{ transaction.memo }}</div>
                        </div>
                        <div class="w-1/2">
                            <div class="py-2 border border-x-0 border-b-1 border-t-0">Coupon Discount Amount(-): {{
                                    transaction.coupon_discount_amount
                            }} {{ transaction.currency_symbol }}</div>
                            <div class="py-2 border border-x-0 border-b-1 border-t-0">Item Sub Total: {{
                                    transaction.sub_total_amount
                            }} {{ transaction.currency_symbol }}</div>
                            <div class="py-2 border border-x-0 border-b-1 border-t-0">Overall
                                Tax({{ transaction.tax_percent * 100 }}%) (+): {{ transaction.tax_amount }}
                                {{ transaction.currency_symbol }}</div>
                            <div class="py-2 border border-x-0 border-b-1 border-t-0">Shipping
                                Cost({{ transaction.shipping_method_name }}) (+): {{ transaction.shipping_amount }}
                                {{ transaction.currency_symbol }}</div>
                            <div class="py-2 border border-x-0 border-b-1 border-t-0">Shipping
                                Tax({{ transaction.shipping_tax_percent * 100 }}%) (+): {{ transaction.shipping_amount *
                                        transaction.shipping_tax_percent
                                }} {{ transaction.currency_symbol }}</div>
                            <div class="py-2 border border-x-0 border-b-1 border-t-0">Total Balance Amount: {{
                                    transaction.sub_total_amount + (transaction.tax_amount + transaction.shipping_amount +
                                        (transaction.shipping_amount * transaction.shipping_tax_percent))
                            }}$</div>
                        </div>
                    </div> -->
    </ps-layout>
</template>

<script>
import { defineComponent } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption,
        PsBreadcrumb2
    },
    layout: PsLayout,
    props: ['errors', 'transaction', 'transaction_statuses', 'payment_statuses'],
    data() {
        return {
            form: useForm(
                {
                    transaction_status_id: this.transaction.transaction_status_id,
                    payment_status_id: this.transaction.payment_status_id,
                    "_method": "put"
                }
            )
        }
    },
    methods: {
        handleSubmit(id) {
            this.$inertia.post(route('transaction.update', id), this.form,{forceFormData: true});
        },
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('transaction_history_module'),
                    url: route('transaction.index'),
                },
                {
                    label: "Transaction Detail",
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
