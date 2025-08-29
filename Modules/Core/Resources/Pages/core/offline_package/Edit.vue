<template>

    <Head :title="$t('core__be_edit_offline_package')" />
<ps-layout>

    <!-- breadcrumb start -->
    <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
    <!-- breadcrumb end -->

    <ps-card class="w-full h-auto  mb-20">
        <div class="rounded-xl">
            <!-- card header start -->
            <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4">
                <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_package_bought_information') }}</ps-label-header-6>
            </div>
            <!-- card header end -->

            <!-- card body start -->
            <div class="px-4 pt-6 dark:bg-backgroundDark">
                <form @submit.prevent="handleSubmit(transaction.id)">
                    <div class="grid w-full sm:w-1/2 gap-6">
                        <div>
                            <ps-label class="text-base">{{ $t('core__be_bought_user_label') }}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                            <ps-input type="text" v-model:value="this.transaction.user.name" :placeholder="$t('core__be_bought_user_placeholder')"/>
                        </div>

                        <!-- for pkg dropdown -->
                        <div>
                            <ps-label class="text-base">{{ $t('core__be_select_package') }}<span class="text-red-800 font-medium ms-1">*</span>
                            </ps-label>{{transaction.package.value}}
                            <!-- <ps-dropdown :disabled="true" align="left" class='lg:mt-2 mt-1  w-full'>
                                <template #select>
                                    <ps-dropdown-select :disabled="true" placeholder="Select Package"
                                        :selectedValue="(form.package_id == '') ? '' : packages.filter(pkg => pkg.id == form.package_id)[0].title"
                                        @change="validateEmptyInput('package_id', form.package_id)"
                                        @blur="validateEmptyInput('package_id', form.package_id)" />
                                </template>
                                <template #list>
                                    <div class="rounded-md shadow-xs w-56 ">
                                        <div class="pt-2 z-30 ">
                                            <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                @click="[form.package_id = '']">
                                                <ps-label class="text-secondary-200">Select Package</ps-label>
                                            </div>
                                            <div v-for="pkg in packages" :key="pkg.id"
                                                class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                @click="[form.package_id = pkg.id]">
                                                <ps-label class="ms-2" :class="pkg.id == form.package_id ? ' font-bold' : ''">
                                                    {{ pkg.title }} </ps-label>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </ps-dropdown> -->
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.package_id }}</ps-label-caption>
                        </div>
                        <!-- end pkg -->

                        <!-- for type dropdown -->
                        <div>
                            <ps-label class="text-base">{{ $t('core__be_payment_status') }}<span class="text-red-800 font-medium ms-1">*</span>
                            </ps-label>
                            <ps-dropdown align="left" class='lg:mt-2 mt-1  w-full'>
                                <template #select>
                                    <ps-dropdown-select :placeholder="$t('core__be_select_payment_status')"
                                        :selectedValue="(form.status == '') ? '' : types.filter(type => type.id == form.status)[0].name"
                                        @change="validateEmptyInput('payment_status', form.status)" @blur="validateEmptyInput('payment_status', form.status)" />
                                </template>
                                <template #list>
                                    <div class="rounded-md shadow-xs w-56 ">
                                        <div class="pt-2 z-30 ">
                                            <div class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                @click="[form.status = '', validateEmptyInput('payment_status', form.status)]">
                                                <ps-label class="text-secondary-200">{{$t('core__be_select_payment_status')}}</ps-label>
                                            </div>
                                            <div v-for="type in types" :key="type.id"
                                                class="w-56 flex py-4 px-2 hover:bg-primary-000 dark:hover:bg-primary-900 cursor-pointer items-center"
                                                @click="[form.status = type.id, validateEmptyInput('payment_status', form.status)]">
                                                <ps-label class="ms-2" :class="type.id == form.status ? ' font-bold' : ''">
                                                    {{ type.name }} </ps-label>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </ps-dropdown>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.payment_status }}</ps-label-caption>
                        </div>
                        <!-- end type -->

                        <div class="flex flex-row justify-end mb-2.5">
                            <ps-button @click="handleCancel()" type="reset" class="me-4" colors="text-primary-500" hover="">{{
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
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
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
        PsDropdownSelect,
        DatePicker
    },
    layout: PsLayout,
    props: ['errors', 'transaction', 'packages'],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const date_range = ref();
        const payment_status = ref();

        // Client Side Validation
        const { isEmpty } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';

            if (fieldName == 'payment_status') {
                payment_status.value.isError = (props.errors.payment_status == '') ? false : true;
            }
        };

        let form = useForm({
            package_id: props.transaction.package_id,
            // package_id:  props.packages.find(element=> element.id == props.transaction.package_id) ? props.transaction.package_id : '',
            user_id: props.transaction.user_id,
            status: props.transaction.status == "0" ? '' : String(props.transaction.status),
            payment_method: 'offline',
            "_method": "put"
        })

        function handleSubmit(id) {
            this.$inertia.post(route("offline_package.update", id), form, {
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
                    date_range.value.isError = (props.errors.date_range == '') ? false : true;
                    payment_status.value.isError = (props.errors.payment_status == '') ? false : true;
                    loading.value = false;
                },
            });
        }

        let types = [
            {
                id: '1',
                name: "Approved",
            },
            {
                id: '2',
                name: "Rejected",
            }
        ];

        return { types, date_range, payment_status, validateEmptyInput, handleSubmit, ps_action_modal, form, loading, success, ps_danger_dialog, ps_image_icon_modal }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('offline_package_module'),
                    url: route('offline_package.index'),
                },
                {
                    label: trans('core__be_edit_offline_package'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('offline_package.index'));
        },
    },
})
</script>
