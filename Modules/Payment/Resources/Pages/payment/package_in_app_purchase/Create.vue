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
          <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('payment__be_package_iap_info') }}</ps-label-header-6>
        </div>
        <!-- card header end -->

        <!-- card body start -->
        <div class="px-4 pt-6 dark:bg-backgroundDark">
          <form @submit.prevent="handleSubmit">
            <div class="grid w-full sm:w-1/2 gap-6">
              <div>
                <ps-label class="text-base">{{ $t('payment__be_iap_prod_id') }}<span class="text-red-500 ms-1" >*</span></ps-label>
                <ps-input ref="in_app_purchase_prd_id" type="text" v-model:value="form.in_app_purchase_prd_id" :placeholder="$t('payment__be_iap_prod_id')"
                  @keyup="validateInAppPurchasePrdIdInput('in_app_purchase_prd_id', form.in_app_purchase_prd_id)"
                  @blur="validateInAppPurchasePrdIdInput('in_app_purchase_prd_id', form.in_app_purchase_prd_id)" />
                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{errors.in_app_purchase_prd_id}}</ps-label-caption>
              </div>

              <div>
                <ps-label class="text-base">{{ $t('payment__be__description')}}</ps-label>
                <ps-textarea ref="description" rows="4" v-model:value="form.description" :placeholder="$t('payment__be__description')" />
              </div>

              <div>
                <ps-label class="text-base">{{ $t('payment__be_post_count') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                <ps-input ref="count" type="text" v-model:value="form.count" :placeholder="$t('payment__be_post_count')"
                @keyup="validateEmptyInput('count', form.count)"
                @blur="validateEmptyInput('count', form.count)"
                @keypress="onlyNumber"/>
                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{errors.count}}</ps-label-caption>
              </div>
              <div>
                <ps-label class="text-base">{{ $t('payment__be_iap_price') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                <ps-input ref="price" type="text" v-model:value="form.price" :placeholder="$t('payment__be_iap_price')" @keyup="validatePriceInput('price', form.price)"
                  @blur="validatePriceInput('price', form.price)" @keypress="onlyNumberWithCustom" />
                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{ errors.price }}
                </ps-label-caption>
              </div>
              <!-- for available currency dropdown -->
              <div>
                <ps-label class="text-base">{{ $t('payment__be_currency') }}
                </ps-label>
                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                  <template #select>
                    <ps-dropdown-select :placeholder="$t('payment__be_select_currency')"
                      :selectedValue="(form.currency_id == '') ? '' : availableCurrencies.filter(currency => currency.id == form.currency_id)[0].name + '( ' + availableCurrencies.filter(currency => currency.id == form.currency_id)[0].currency_symbol + ',' +
                                                                                                                  availableCurrencies.filter(currency => currency.id == form.currency_id)[0].currency_short_form + ' )'" />
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
                          @click="[form.currency_id = currency.id]">
                          <ps-label class="ms-2" :class="currency.id == form.currency_id ? ' font-bold' : ''">
                            {{currency.name}}({{ currency.currency_symbol }}, {{currency.currency_short_form}})
                          </ps-label>
                        </div>
                      </div>
                    </div>
                  </template>
                </ps-dropdown>
              </div>
              <!-- end available currency -->
              <!-- for type dropdown -->
              <div>
                <ps-label class="text-base">{{$t('payment__be_type')}}<span class="text-red-800 font-medium ms-1">*</span>
                </ps-label>
                <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                  <template #select>
                    <ps-dropdown-select :placeholder="$t('payment__be_select_type')"
                      :selectedValue="(form.type == '') ? '' : types.filter(type => type.id == form.type)[0].name"
                      @change="validateEmptyInput('type', form.type)" @blur="validateEmptyInput('type', form.type)" />
                  </template>
                  <template #list>
                    <div class="rounded-md shadow-xs w-56 ">
                      <div class="pt-2 z-30 ">
                        <!-- <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                          @click="[form.type = '', validateEmptyInput('type', form.type)]">
                          <ps-label class="text-secondary-200">{{$t('payment__be_select_type')}}</ps-label>
                        </div> -->
                        <div v-for="type in types" :key="type.id"
                          class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                          @click="[form.type = type.id, validateEmptyInput('type', form.type)]">
                          <ps-label class="ms-2" :class="type.id == form.type ? ' font-bold' : ''">
                            {{ type.name }} </ps-label>
                        </div>
                      </div>
                    </div>
                  </template>
                </ps-dropdown>
                <ps-label-caption textColor="text-red-500 text-xs" class="mt-2 block">{{ errors.type }}</ps-label-caption>
              </div>
              <!-- end type -->
              <div>
                <ps-label class="text-base mb-2">{{$t('payment__be_status')}}</ps-label>
                <ps-checkbox-value v-model:value="form.status" :title="$t('payment__be_publish')" />
              </div>

              <div class="flex flex-row justify-end mb-2.5">
                <ps-button @click="handleCancel()" type="reset" class="me-4" colors="text-primary-500" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                    <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{ $t('core__be_btn_saved') }}</ps-label>
                    <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{ $t('core__be_btn_save') }} </ps-label>
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
import { defineComponent, ref } from "vue";
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import useValidators from "@/Validation/Validators";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import PsCard from "@/Components/Core/Card/PsCard.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsCheckboxValue from "@/Components/Core/Checkbox/PsCheckboxValue.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
  name: "Create",
  components: {
    Head,
    PsBreadcrumb2,
    PsInput,
    PsLabel,
    PsButton,
    PsLabelHeader6,
    PsCard,
    PsLoading,
    PsIcon,
    PsCheckboxValue,
    PsLabelCaption,
    PsLabelTitle3,
    PsTextarea,
    PsDropdown,
    PsDropdownSelect
  },
  layout: PsLayout,
  props: ["errors", 'availableCurrencies'],
  setup(props) {
    const loading = ref(false);
    const success = ref(false);
    const in_app_purchase_prd_id = ref();
    const count = ref();
    const price = ref();
    const type = ref();

    // Client Side Validation
    const { isEmpty, minLength } = useValidators();

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
      if (fieldName == 'type') {
        type.value.isError = (props.errors.type == '') ? false : true;
      }
    };

    const onlyNumber = ($e) => {
      let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
      if (keyCode < 48 || keyCode > 57) {
        $e.preventDefault();
      }
    }

    let form = useForm({
      in_app_purchase_prd_id: "",
      description: "",
      count: "",
      price: "",
      type: "",
      status:true,
      currency_id:""
    });

    let types = [
      {
        id: "Android",
        name: "Android",
      },
      {
        id: "IOS",
        name: "IOS",
      }
    ];

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
    function handleSubmit() {
      this.$inertia.post(route("package_in_app_purchase.store"), form, {
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
          in_app_purchase_prd_id.value.isError = (props.errors.in_app_purchase_prd_id == '') ? false : true;
          count.value.isError = (props.errors.count == '') ? false : true;
          price.value.isError = (props.errors.price == '') ? false : true;
          type.value.isError = (props.errors.type == '') ? false : true;
            loading.value = false;
        },
      });
    }

    return { count, type, price, onlyNumber, onlyNumberWithCustom, validatePriceInput, validateEmptyInput, validateInAppPurchasePrdIdInput, handleSubmit, form, loading, success, in_app_purchase_prd_id, types };
  },
  computed: {
    breadcrumb() {

      return [
        {
            label: trans('core__be_dashboard_label'),
            url: route('admin.index')
        },
        {
            label: trans('package_in_app_purchase_module'),
            url: route('package_in_app_purchase.index'),
        },
        {
            label: trans('core__be_add_package_in_app_purchase'),
            color: "text-primary-500"
        }
      ]

    }
  },
  methods: {
    handleCancel() {
      this.$inertia.get(route("package_in_app_purchase.index"));
    },
  },
});
</script>
