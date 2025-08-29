<template>

    <Head :title="$t('edit_package_in_app_purchase')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('payment__be_vendor_sp_info') }}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(vendorSubscriptionPlan.id)">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label class="text-base">{{$t('payment__be_iap_prod_id')}}<span
                                        class="text-red-500 ms-1">*</span></ps-label>
                                <ps-input ref="in_app_purchase_prd_id" type="text"
                                    v-model:value="form.in_app_purchase_prd_id" :placeholder="$t('payment__be_iap_prod_id')"
                                    @keyup="validateInAppPurchasePrdIdInput('in_app_purchase_prd_id', form.in_app_purchase_prd_id)"
                                    @blur="validateInAppPurchasePrdIdInput('in_app_purchase_prd_id', form.in_app_purchase_prd_id)" />
                                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">
                                    {{errors.in_app_purchase_prd_id}}
                                </ps-label-caption>
                            </div>

                            <!-- Title -->
                            <div>
                                <ps-label class="text-base">{{ $t('payment__be_vendor_sp_title')}}<span class="text-red-500 ms-1" >*</span></ps-label>
                                <ps-input ref="title" type="text" v-model:value="form.title" :placeholder="$t('payment__be_vendor_sp_title')"
                                @keyup="validateInAppPurchasePrdIdInput('title', form.title)"
                                @blur="validateInAppPurchasePrdIdInput('title', form.title)" />
                                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{errors.title}}</ps-label-caption>
                            </div>

                            <!-- Duration -->
                            <div>
                                <ps-label class="text-base">{{$t('payment__be_vendor_sp_duration')}}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                <template #select>
                                    <ps-dropdown-select :placeholder="$t('payment__be_select_duration')"
                                    :selectedValue="(form.duration == '') ? '' : durations.filter(duration => duration.id == form.duration)[0].name"
                                    @change="validateEmptyInput('duration', form.duration)" @blur="validateEmptyInput('duration', form.duration)" />
                                </template>
                                <template #list>
                                    <div class="rounded-md shadow-xs w-56 ">
                                    <div class="pt-2 z-30 ">
                                        <!-- <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="[form.duration = '', validateEmptyInput('duration', form.duration)]">
                                        <ps-label class="text-secondary-200">{{$t('payment__be_select_duration')}}</ps-label>
                                        </div> -->
                                        <div v-for="duration in durations" :key="duration.id"
                                        class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="[form.duration = duration.id, validateEmptyInput('duration', form.duration)]">
                                        <ps-label class="ms-2" :class="duration.id == form.duration ? ' font-bold' : ''">
                                            {{ duration.name }} </ps-label>
                                        </div>
                                    </div>
                                    </div>
                                </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{ errors.duration }}</ps-label-caption>
                            </div>

                            <!-- Sale Price -->
                            <div>
                                <ps-label class="text-base">{{ $t('payment__be_vendor_sp_sale_price') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="sale_price" type="text" v-model:value="form.sale_price" :placeholder="$t('payment__be_vendor_sp_sale_price')"
                                @keyup="validateEmptyInput('sale_price', form.sale_price)"
                                @blur="validateEmptyInput('sale_price', form.sale_price)"
                                @keypress="onlyNumber"/>
                                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{errors.sale_price}}</ps-label-caption>
                            </div>

                            <!-- Discount Price -->
                            <div>
                                <ps-label class="text-base">{{ $t('payment__be_vendor_sp_discount_price') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-input ref="discount_price" type="text" v-model:value="form.discount_price" :placeholder="$t('payment__be_vendor_sp_discount_price')" @keyup="validateEmptyInput('discount_price', form.discount_price)"
                                @blur="validateEmptyInput('discount_price', form.discount_price)" @keypress="onlyNumberWithCustom" />
                                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{ errors.discount_price }}
                                </ps-label-caption>
                            </div>

                            <!-- Currency -->
                            <div>
                                <ps-label class="text-base">{{ $t('payment__be_currency') }}<span class="text-red-800 font-medium ms-1">*</span>
                                </ps-label>
                                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                <template #select>
                                    <ps-dropdown-select :placeholder="$t('payment__be_select_currency')"
                                    :selectedValue="(form.currency_id == '') ? '' : availableCurrencies.filter(currency => currency.id == form.currency_id)[0].name + '( ' + availableCurrencies.filter(currency => currency.id == form.currency_id)[0].currency_symbol + ',' +
                                        availableCurrencies.filter(currency => currency.id == form.currency_id)[0].currency_short_form + ' )'"
                                        @change="validateEmptyInput('currency_id', form.currency_id)" @blur="validateEmptyInput('currency_id', form.currency_id)"/>
                                </template>
                                <template #list>
                                    <div class="rounded-md shadow-xs ">
                                    <div class="pt-2 z-30 ">
                                        <!-- <div class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="[form.currency_id = '']">
                                        <ps-label class="text-secondary-200">{{$t('payment__be_select_currency')}}</ps-label>
                                        </div> -->
                                        <div v-for="currency in availableCurrencies" :key="currency.id"
                                        class="flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                        @click="[form.currency_id = currency.id, validateEmptyInput('currency_id', form.currency_id)]">
                                        <ps-label class="ms-2" :class="currency.id == form.currency_id ? ' font-bold' : ''">
                                            {{currency.name}}({{ currency.currency_symbol }}, {{currency.currency_short_form}})
                                        </ps-label>
                                        </div>
                                    </div>
                                    </div>
                                </template>
                                </ps-dropdown>
                                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{ errors.currency_id }}</ps-label-caption>
                            </div>

                            <!-- Is Most Popular Plan -->
                            <div>
                                <ps-label class="text-base mb-2">{{$t('payment__be_vendor_sp_is_most_popular_plan')}}</ps-label>
                                <ps-checkbox-value v-model:value="form.is_most_popular_plan" :title="$t('payment__be_vendor_sp_is_most_popular')" />
                            </div>

                            <!-- Status -->
                            <div>
                                <ps-label class="text-base mb-2">{{$t('payment__be_status')}}</ps-label>
                                <ps-checkbox-value v-model:value="form.status" :title="$t('payment__be_publish')" />
                            </div>

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" type="reset" class="me-4" colors="text-primary-500"
                                    hover="">{{
                                    $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2"
                                    rounded="rounded" hover="" focus="">
                                    <ps-loading v-if="loading"
                                        theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                        loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20"
                                        class="me-1.5 transition-all duration-300" />
                                    <ps-label v-if="success" class="transition-all duration-300"
                                        textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_saved') }}
                                    </ps-label>
                                    <ps-label v-if="!loading && !success"
                                        textColor="text-white dark:text-secondaryDark-black"> {{
                                        $t('core__be_btn_save') }} </ps-label>
                                </ps-button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </ps-card>
    </ps-layout>
</template>

<script>
import { defineComponent, ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsBreadcrumb2,
        PsImageUpload,
        PsInput,
        PsLabel,
        PsButton,
        PsLabelHeader6,
        PsCard,
        PsIcon,
        PsLoading,
        PsCheckboxValue,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsLabelCaption,
        PsLabelTitle3,
        PsTextarea,
        PsDropdown,
        PsDropdownSelect
    },
    layout: PsLayout,
    props: ['errors', 'vendorSubscriptionPlan', 'duration_attribute', 'sale_price_attribute', 'discount_price_attribute', 'status_attribute', 'currency_attribute', 'availableCurrencies', 'is_most_popular_plan_attribute'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const in_app_purchase_prd_id = ref();
        const count = ref();
        const price = ref();
        const duration = ref();

        let form = useForm({
            in_app_purchase_prd_id: props.vendorSubscriptionPlan.core_key.description,
            title: props.vendorSubscriptionPlan.value,
            duration: props.duration_attribute,
            sale_price: props.sale_price_attribute,
            discount_price: props.discount_price_attribute,
            status: props.status_attribute == 1? true:false,
            is_most_popular_plan: props.is_most_popular_plan_attribute == 1? true:false,
            currency_id: props.currency_attribute,
            core_keys_id: props.vendorSubscriptionPlan.core_keys_id,
            "_method": "put"
        })


        // Client Side Validation
        const { isEmpty, minLength, isPrice } = useValidators();

        const validateInAppPurchasePrdIdInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
            if (fieldName == 'in_app_purchase_prd_id') {
                in_app_purchase_prd_id.value.isError = (props.errors.in_app_purchase_prd_id == '') ? false : true;
            }
        };

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
            if (fieldName == 'count') {
                count.value.isError = (props.errors.count == '') ? false : true;
            }
            if (fieldName == 'duration') {
                duration.value.isError = (props.errors.duration == '') ? false : true;
            }
        }

        const validatePriceInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : isPrice(fieldName, fieldValue);

            if (fieldName == 'price') {
                price.value.isError = (props.errors.price == '') ? false : true;
            }
        }

        // for custom number ps-input validate at keypress
        const onlyNumberWithCustom = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46 && keyCode !== 45 && keyCode !== 44) { // 46 is dot, 45 is dash, 44 is comma
                $e.preventDefault();
            }
        }

        function handleSubmit(id) {
            this.$inertia.post(route("vendor_subscription_plan.update", id), form, {
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
                    loading.value = false;
                    in_app_purchase_prd_id.value.isError = (props.errors.in_app_purchase_prd_id == '') ? false : true;
                    count.value.isError = (props.errors.count == '') ? false : true;
                    price.value.isError = (props.errors.price == '') ? false : true;
                    type.value.isError = (props.errors.type == '') ? false : true;
                },
            });
        }

        let durations = [
            {
                id: "1",
                name: "One Month",
            },
            {
                id: "2",
                name: "Two Months",
            },
            {
                id: "3",
                name: "Three Months",
            },
            {
                id: "6",
                name: "Six Months",
            },
            {
                id: "12",
                name: "One Year",
            },
        ];

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }

        return {
            durations, count, price, onlyNumber, onlyNumberWithCustom, duration,
            validatePriceInput, validateEmptyInput, validateInAppPurchasePrdIdInput, handleSubmit,
            ps_action_modal, form, loading, success, ps_danger_dialog, ps_image_icon_modal
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
                    label: trans('vendor_subscription_plan_module'),
                    url: route('vendor_subscription_plan.index'),
                },
                {
                    label: "core__be_edit_vendor_subscription_plan",
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('vendor_subscription_plan.index'));
        },
    },
})
</script>
