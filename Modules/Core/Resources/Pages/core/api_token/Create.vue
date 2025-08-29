<template>
    <Head :title="$t('core__create_api_token')" />
    <ps-layout>
      <!-- breadcrumb start -->
      <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
      <!-- breadcrumb end -->

      <ps-card class="w-full h-auto">
        <div class="rounded-xl">
          <!-- card header start -->
          <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
              <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__create_api_token')}}</ps-label-header-6>
          </div>
          <!-- card header end -->

          <!-- card body start -->
          <div class="px-4 pt-6 dark:bg-backgroundDark">
            <form @submit.prevent="handleSubmit">
              <div class="grid w-full sm:w-1/2 gap-6">
                <div><ps-label class="mt-0.5 mb-1.5">{{ $t('core__be_api_token_authenticate') }}</ps-label></div>
                <div>
                  <ps-label class="text-base mb-2">{{$t('core__api_token_label')}}<span class="text-red-500 ms-1" >*</span></ps-label>
                  <ps-input ref="name" type="text" v-model:value="form.name" :placeholder="$t('core__api_token_placeholder')"
                    @keyup="validateNameInput('name', form.name)"
                    @blur="validateNameInput('name', form.name)" />
                  <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.name}}</ps-label-caption>
                </div>

                <div>
                  <ps-label class="text-base mb-2">{{$t('core__be_permissions')}}</ps-label>

                  <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="permission in availablePermissions.filter(permission => permission == userMobileTokenKey)" :key="permission">
                            <label class="flex items-center">
                                <jet-checkbox :value="permission" v-model:checked="form.permissions" disabled/>
                                <span class="ms-2 text-sm text-gray-600">{{ permission }}</span>
                            </label>
                        </div>
                    </div>
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

        <!-- Token Value Modal -->
        <jet-dialog-modal :show="displayingToken" @close="displayingToken = false">
            <template #title>
                {{$t('core__be_api_token')}}
            </template>

            <template #content>
                <div>
                    {{ $t('core__be_api_token_copy_one') }}
                </div>

                <div class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 mb-4" v-if="$page.props.jetstream.flash.token">
                    {{ $page.props.jetstream.flash.token }}
                </div>
            </template>

            <template #footer>
                <ps-button @click="displayingToken = false">
                    {{ $t('core__be_close_btn') }}
                </ps-button>
            </template>
        </jet-dialog-modal>

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
  import JetCheckbox from '@/Jetstream/Checkbox.vue'
  import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
  import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
  import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
    import JetDialogModal from '@/Jetstream/DialogModal.vue'
  import { trans } from 'laravel-vue-i18n';
import PsConst from '@templateCore/object/constant/ps_constants';

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
      JetCheckbox,
      PsLabelCaption,
      PsLabelTitle3,
      PsImageUpload,
      JetDialogModal
    },
    layout: PsLayout,
    props: ["errors", "availablePermissions"],
    setup(props) {
      const loading = ref(false);
      const success = ref(false);
      const displayingToken = ref(false);
      const name = ref();

      // Client Side Validation
      const { isEmpty, minLength } = useValidators();

      const validateNameInput = (fieldName, fieldValue) => {
          props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
          if(fieldName == 'name'){
              name.value.isError = (props.errors.name == '') ? false: true;
          }
      };

      const userMobileTokenKey = PsConst.USER_MOBILE_TOKEN;

      let form = useForm({
        name: "",
        permissions: [userMobileTokenKey],
      });

      function handleSubmit() {
        this.$inertia.post(route("api_token.store"), form, {
            forceFormData: true,
          onBefore: () => {
            loading.value = true;
          },
          onSuccess: () => {
            this.displayingToken = true
            loading.value = false;
            success.value = true;
            setTimeout(() => {
              success.value = false;
            }, 2000);
          },
          onError: () => {
              name.value.isError = (props.errors.name == '') ? false: true;
              loading.value = false;
          },
        });
      }

      return { validateNameInput, handleSubmit, form, loading, success, name, displayingToken, userMobileTokenKey };
    },
    computed: {
      breadcrumb() {

        return [
          {
              label: trans('core__be_dashboard_label'),
              url: route('admin.index')
          },
          {
            label: trans('api_tokens_module'),
              url: route('api_token.index'),
          },
          {
              label: trans('core__create_api_token'),
              color: "text-primary-500"
          }
        ]

      }
    },
    methods: {
      handleCancel() {
        this.$inertia.get(route("api_token.index"));
      },
    },
  });
  </script>
