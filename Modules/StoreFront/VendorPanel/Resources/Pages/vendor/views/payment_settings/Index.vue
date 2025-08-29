
<template>
    <Head :title="$t('payment_setting_module')" />

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->


        <ps-card class="w-full h-auto">
            <div class="bg-background dark:bg-secondaryDark-black rounded-lg ">
                <ps-label class="col-span-2 text-lg px-4 py-3.5 bg-primary-50 dark:bg-primary-900">{{ $t(titleLabel) }} </ps-label>

                <form @submit.prevent="handleSubmit(vendorId)">
                    <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4 mb-20">
                        <div>
                            <div v-for="payment in vendorPaymentsArr" :key="payment.id">
                                <div v-if="currentPaymentId == payment.payment_id">
                                    <div class="px-4 py-3" v-if="payment.payment_id == paypalPaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_1_paypal_payment') }}</ps-label-header-5>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == stripePaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_2_stripe_payment') }}</ps-label-header-5>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == razorPaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_3_razor_payment') }}</ps-label-header-5>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == paystackPaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_4_paystack_payment') }}</ps-label-header-5>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == flutterwavePaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_5_flutterwave_payment') }}</ps-label-header-5>
                                    </div>
                                    <div v-for="(payment_relation, index) in payment.payment_relation" :key="index">
                                        <div class="px-4 py-3">
                                            <ps-label class="text-base mb-1" >{{ payment_relation.core_key?.name }}</ps-label>
                                            <ps-input type="text" v-model:value="payment_relation.value" :placeholder="payment_relation.core_key?.name"/>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == paypalPaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_paypal_enabled')" />
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == stripePaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_stripe_enabled')" />
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == razorPaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_razor_enabled')" />
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == paystackPaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_paystack_enabled')" />
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.payment_id == flutterwavePaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_flutterwave_enabled')" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row px-4 py-3 justify-end mb-2.5 mt-4">

                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-6 py-1" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500" loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" class="me-1.5 transition-all duration-300" />
                                    <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_save') }}</ps-label>
                                    <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                                </ps-button>
                            </div>
                        </div>

                        <!-- setting column -->
                        <div class="flex flex-col pt-4">
                            <div v-for="payment in vendorPaymentsArr" :key="payment.id">
                                <div @click="changeSection(payment)"
                                    :class="title == payment.name ? 'border-l border-s-primary-500' : 'border-l border-s-secondary-300'"
                                    class="px-2 py-3 cursor-pointer">
                                    <ps-label :textColor="title == payment.name ? 'text-primary-500 dark:text-primary-500' : 'text-secondary-800 dark:text-white'" >
                                        {{ payment.name }}
                                    </ps-label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </ps-card>

</template>

<script>
import { defineComponent,ref } from 'vue'
import PsVendorLayout from "@vendorPanel/vendor/components/layouts/container/PsVendorLayout.vue";
import { Head,Link } from "@inertiajs/vue3";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelHeader5 from "@/Components/Core/Label/PsLabelHeader5.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsLabelHeader4,
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        Link,
        PsLabelCaption,
        PsLabelTitle3,
        PsDropdown,
        PsDropdownSelect,
        PsCheckboxValue
    },
    layout: PsVendorLayout,
    props: ['errors', 'vendorPayments', 'vendorId',
        'paypalPaymentId',
        'stripePaymentId',
        'razorPaymentId',
        'flutterwavePaymentId',
        'paystackPaymentId','can'
],
    setup(props) {
        const vendorPaymentsArr = ref();
        const vendorPaymentsRelations = ref();
        const paymentMode = import.meta.env.VITE_PAYMENT_MODE ;
        const loading = ref(false);
        const success = ref(false);

        const title = ref(props.vendorPayments[0].name);
        const titleLabel = ref('paypal_setting');
        const currentPaymentId = ref(props.vendorPayments[0].payment_id);
console.log(props.vendorPayments);
        // vendorPayments.value.map(vp=>{
        //     let a = {
        //         id: vp.id,
        //         name: vp.name,
        //         description: vp.description,
        //     }
        // });

        function changeSection(v){
            if(v.payment_id == props.paypalPaymentId){
                titleLabel.value = 'paypal_setting'
                title.value = v.name
                currentPaymentId.value = v.payment_id
            }else if(v.payment_id == props.stripePaymentId){
                titleLabel.value = 'stripe_setting'
                title.value = v.name
                currentPaymentId.value = v.payment_id
            }else if(v.payment_id== props.razorPaymentId){
                titleLabel.value = 'razor_setting'
                title.value = v.name
                currentPaymentId.value = v.payment_id
            }else if(v.payment_id == props.paystackPaymentId){
                titleLabel.value = 'paystack_setting'
                title.value = v.name
                currentPaymentId.value = v.payment_id
            }else if(v.payment_id == props.flutterwavePaymentId){
                titleLabel.value = 'flutterwave_setting'
                title.value = v.name
                currentPaymentId.value = v.payment_id
            }else{
                titleLabel.value = v;
                title.value = v
                currentPaymentId.value = v
            }
        }

        function handleCancel() {
            this.$inertia.get(route('vendor_payment_setting.index'));
        }

        function handleSubmit(id) {
            this.$inertia.post(route('vendor_payment_setting.store',id), vendorPaymentsArr.value, {
                forceFormData: true,
            onBefore: () => {loading.value = true},
            onSuccess: () => {
                loading.value = false;
                success.value = true;
                setTimeout(()=>{
                    success.value = false;
                },2000)
            },
            onError: () => {
                loading.value = false;;
            },
            });
        }

        return {
            currentPaymentId,
            vendorPaymentsArr,vendorPaymentsRelations,
            handleCancel,
            title,
            changeSection,
            handleSubmit,
            loading,
            success,
            titleLabel,
            paymentMode
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
                    label: trans('core__vendor_payment_settings_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    created() {
        this.vendorPaymentsArr = this.vendorPayments.map(vp=>{

            let relation = vp.payment_relation.map(rel=>{
                return {
                    id: rel.vendor_payment_infos[0].id,
                    core_keys_id: rel.core_keys_id,
                    payment_id: rel.payment_id,
                    core_key: rel.core_key,
                    value: rel.vendor_payment_infos[0]?rel.vendor_payment_infos[0].value:''
                }
            })
            return {
                id: vp.id,
                payment_id: vp.payment_id,
                name: vp.name,
                description: vp.description,
                status: vp.status,
                payment_relation: relation
            }
        })
        console.log(this.vendorPaymentsArr)
    }
})
</script>
