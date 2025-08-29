
<template>
    <Head :title="$t('payment_setting_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="bg-background dark:bg-secondaryDark-black rounded-lg ">
                <ps-label class="col-span-2 text-lg px-4 py-3.5 bg-primary-50 dark:bg-primary-900">{{ $t(titleLabel) }} </ps-label>

                <form @submit.prevent="handleSubmit(currency_id)">
                    <div class="grid grid-cols-1 md:grid-cols-2  gap-2 mt-4 mb-20">
                        <div>
                            <div v-if="title == currency_symbol_setting">
                                <!-- for available currency dropdown -->
                                <div class="px-4 py-3">
                                    <ps-label class="text-base">{{$t('payment__pmt_currency')}}</ps-label>
                                    <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                        <template #select>
                                            <ps-dropdown-select :placeholder="$t('payment__select_pmt_currency')"
                                                :selectedValue="(currency_id == '') ? '' : availableCurrencies.filter(currency => currency.id == currency_id)[0].name + ' (' +availableCurrencies.filter(currency => currency.id == currency_id)[0].currency_symbol+ ', '+availableCurrencies.filter(currency => currency.id == currency_id)[0].currency_short_form+ ')'" />
                                        </template>
                                        <template #list>
                                            <div class="rounded-md shadow-xs ">
                                                <div class="pt-2 z-30 w-70 ">
                                                    <!-- <div class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                        @click="[currency_id = '']">
                                                        <ps-label class="text-secondary-200">{{ $t('payment__select_pmt_currency') }}</ps-label>
                                                    </div> -->
                                                    <div v-for="currency in availableCurrencies" :key="currency.id"
                                                        class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                        @click="[currency_id = currency.id]">
                                                        <ps-label class="ms-2" :class="currency.id == currency_id ? ' font-bold' : ''">
                                                            {{ currency.name + ' (' + currency.currency_symbol + ', ' + currency.currency_short_form + ' )' }}
                                                        </ps-label>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </ps-dropdown>
                                </div>
                                <!-- end available currency -->
                            </div>
                            <div v-for="payment in payments" :key="payment.id">
                                <div v-if="title == payment.name">
                                    <div class="px-4 py-3" v-if="payment.id == paypalPaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_1_paypal_payment') }}</ps-label-header-5>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == stripePaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_2_stripe_payment') }}</ps-label-header-5>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == razorPaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_3_razor_payment') }}</ps-label-header-5>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == paystackPaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_4_paystack_payment') }}</ps-label-header-5>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == flutterwavePaymentId">
                                        <ps-label-header-5 class="semibold">{{ $t('core__be_option_5_flutterwave_payment') }}</ps-label-header-5>
                                    </div>
                                    <div v-for="payment_info in payment.payment_infos" :key="payment_info.id">
                                        <div class="px-4 py-3">
                                            <ps-label class="text-base mb-1" >{{ payment_info.core_key?.name }}</ps-label>
                                            <ps-input type="text" :disabled="paymentMode == 'demo' ? true : false" v-model:value="payment_info.value" :placeholder="payment_info.core_key?.name"/>
                                        </div>
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == paypalPaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_paypal_enabled')" />
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == stripePaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_stripe_enabled')" />
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == razorPaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_razor_enabled')" />
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == paystackPaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_paystack_enabled')" />
                                    </div>
                                    <div class="px-4 py-3" v-if="payment.id == flutterwavePaymentId">
                                        <ps-checkbox-value v-model:value="payment.status" class="font-normal" :title="$t('core__be_flutterwave_enabled')" />
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row px-4 py-3 justify-end mb-2.5 mt-4">

                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                <ps-button :disabled="!can.updatePaymentSetting" class="transition-all duration-300 min-w-3xs" padding="px-6 py-1" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500" loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" class="me-1.5 transition-all duration-300" />
                                    <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_save') }}</ps-label>
                                    <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                                </ps-button>
                            </div>
                        </div>

                        <!-- setting column -->
                        <div class="flex flex-col pt-4">
                            <div @click="changeSection(currency_symbol_setting)"
                                :class="title == currency_symbol_setting ? 'border-l border-s-primary-500' : 'border-l border-s-secondary-300'"
                                class="px-2 py-3 cursor-pointer">
                                <ps-label
                                    :textColor="title == currency_symbol_setting ? 'text-primary-500 dark:text-primary-500' : 'text-secondary-800 dark:text-white'">
                                    {{ $t('payment__currency_symbol') }}
                                </ps-label>
                            </div>
                            <div v-for="payment in payments" :key="payment.id">
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
    </ps-layout>

</template>

<script>
import { defineComponent,ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
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
    layout: PsLayout,
    props: ['errors', 'payments', 'availableCurrencies', 'defaultCurrencyId',
        'paypalPaymentId',
        'stripePaymentId',
        'razorPaymentId',
        'paystackPaymentId',
        'flutterwavePaymentId','can'
],
    setup(props) {
        const paymentMode = import.meta.env.VITE_PAYMENT_MODE ;

        const loading = ref(false);
        const success = ref(false);
        const currency_id = ref(props.defaultCurrencyId);
        const currency_symbol_setting = 'currency_symbol_setting';

        const title = ref(currency_symbol_setting);
        const titleLabel = ref(currency_symbol_setting);

        function changeSection(v){
            if(v.id == props.paypalPaymentId){
                titleLabel.value = 'paypal_setting'
                title.value = v.name
            }else if(v.id == props.stripePaymentId){
                titleLabel.value = 'stripe_setting'
                title.value = v.name
            }else if(v.id == props.razorPaymentId){
                titleLabel.value = 'razor_setting'
                title.value = v.name
            }else if(v.id == props.paystackPaymentId){
                titleLabel.value = 'paystack_setting'
                title.value = v.name
            }else if(v.id == props.flutterwavePaymentId){
                titleLabel.value = 'flutterwave_setting'
                title.value = v.name
            }else{
                titleLabel.value = v;
                title.value = v
            }
        }

        function handleCancel() {
            this.$inertia.get(route('payment_setting.index'));
        }

        function handleSubmit(currency_id) {
            this.$inertia.post(route('payment_setting.store', currency_id), props.payments, {
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
            handleCancel,
            title,
            changeSection,
            handleSubmit,
            loading,
            success,
            currency_id,
            currency_symbol_setting,
            titleLabel,
            paymentMode
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
                    label: trans('payment_setting_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
