<template>
    <Head :title="$t('core__be_bluemark_user_detail')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-200 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_verify_blue_mark_user')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(this.user.id)">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <!-- name-->
                            <div>
                                <ps-label class="text-base">{{ $t('user_name_label') }}</ps-label>
                                <ps-input :readonly="true" ref="name" type="text" v-model:value="form.name" :placeholder="$t('core__be_user_name')" />
                            </div>

                            <!-- email-->
                            <div>
                                <ps-label class="text-base">{{ $t('core__be_user_email') }}</ps-label>
                                <ps-input :readonly="true" ref="email" type="text" v-model:value="form.email" :placeholder="$t('core__be_user_email')" />
                            </div>
                            <!-- user_phone-->
                            <div>
                                <ps-label class="text-base">{{ $t('core__be_user_phone') }}</ps-label>
                                <ps-input :readonly="true" ref="user_phone" type="text" v-model:value="form.user_phone" :placeholder="$t('core__be_user_phone')"/>
                            </div>
                            <div>
                                <ps-label>{{ $t('bluemarkuser__be_status') }}</ps-label>
                                <ps-dropdown class="w-full" h="h-auto" >
                                    <template #select>
                                        <ps-dropdown-select
                                        :selectedValue="bluemarkStatusList.filter((status) => status.id == form.value)[0].name"
                                         />
                                    </template>
                                    <template #list>
                                        <div class="">
                                            <div v-for="row in bluemarkStatusList" :key="row.id" class="w-56">
                                                <ps-label @click="form.value=row.id" class="py-2 px-4 text-md hover:bg-primary-50 cursor-pointer" > {{ row.name }}</ps-label>
                                            </div>
                                        </div>
                                    </template>
                                </ps-dropdown>
                            </div>

                            <!-- for bluemak noter-->
                            <div>
                                <ps-label class="text-base">{{ $t('bluemarkuser__be_note') }}</ps-label>
                                <ps-textarea :disabled="true" rows="4" v-model:value="form.bluemark_note" :placeholder="$t('bluemarkuser__be_note')" />
                            </div>

                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t("core__be_btn_cancel") }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="">
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500" loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t("core__be_btn_saved") }}</span>
                                    <span v-if="!loading && !success" class="">{{ $t("core__be_btn_save") }}</span>
                                </ps-button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- card body end -->
            </div>
        </ps-card>
    </ps-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
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
import { trans } from 'laravel-vue-i18n';

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
        PsVideoUpload,
        PsLabelTitle3,
    },
    layout: PsLayout,
    props: [
        'errors',
        'coreFieldFilterSettings',
        'roles',
        'user',
        'customizeHeaders',
        'customizeDetails',
        'bluemarkStatusList',
        'blueMarkStatus',
        'blueMarkNote',
        'blueMarkStatusId'
    ],
    setup(props) {
        let form = useForm({
            name: props.user.name,
            email: props.user.email,
            user_phone: props.user.user_phone,
            value: props.blueMarkStatus,
            bluemark_note: props.blueMarkNote,
           "_method": "put"
        })

        const loading = ref(false);
        const success = ref(false);

        function handleSubmit(id) {
            this.$inertia.post(route('bluemarkuser.update', id), form, {
                forceFormData: true,
                onBefore: () => {
                    loading.value = true;
                },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    this.$inertia.get(route('bluemarkuser.index'));
                },
                onError: () => {
                    loading.value = false;
                },
            });
        }

        return {
            loading,
            success,
            form,
            handleSubmit,
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
                    label: trans('bluemark_module'),
                    url: route('bluemarkuser.index'),
                },
                {
                    label: trans('core__be_bluemark_user_detail'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
