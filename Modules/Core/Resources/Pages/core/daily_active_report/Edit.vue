<template>

    <Head :title="$t('daily_active_user_report_detail')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_user_info') }}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form>
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label>{{ $t('user_name_label') }}</ps-label>
                                <ps-input :disabled="true" type="text" v-model:value="form.name" :placeholder="$t('user_name_label')"/>
                            </div>
                            <div>
                                <ps-label>{{ $t('email_label') }}</ps-label>
                                <ps-input :disabled="true" type="text" v-model:value="form.email" :placeholder="$t('email_label')"/>
                            </div>
                            <div>
                                <ps-label>{{ $t('phone_label') }}</ps-label>
                                <ps-input :disabled="true" type="text" v-model:value="form.user_phone" :placeholder="$t('phone_label')"/>
                            </div>
                            <div>
                                <ps-label>{{ $t('address_label') }}</ps-label>
                                <ps-textarea rows="3" v-model:value="form.user_address" :placeholder="$t('address_label')"></ps-textarea>
                            </div>
                            <div>
                                <ps-label class="mb-1">{{ $t('user_role_label') }}{{user.role_id}}</ps-label>
                                <div class="grid grid-cols-2 lg:grid-cols-3 gap-y-2">
                                    <RoleCheckbox :oldData ="user.role_id" @toParent="handleUserMultiSelect"
                                    :permissions="roles"/>
                                    <RoleCheckbox v-if="!user.role_id"
                                    @toParent="handleUserMultiSelect" :permissions="permissions"/>
                                </div>
                            </div>
                            <div>
                                <ps-label>{{ $t('about_me') }}</ps-label>
                                <ps-textarea :disabled="true" rows="3" v-model:value="form.user_about_me" :placeholder="$t('about_me')"></ps-textarea>
                            </div>
                            <div class="mb-2.5 flex flex-row justify-end">
                                <ps-button type="button" @click="handleCancel()">{{ $t('core__be_btn_back') }}</ps-button>
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
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import RoleCheckbox from "../components/RoleCheckbox.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsBreadcrumb2,
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
        RoleCheckbox
    },
    layout: PsLayout,
    props: ['errors', 'user'],
    setup(props) {

        // Client Side Validation
        const { isEmpty, minLength } = useValidators();

        const validateNameInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : minLength(fieldName, fieldValue, 3);
            if(fieldName == 'name'){
                name.value.isError = (props.errors.name == '') ? false: true;
            }
        }

        let form = useForm({
            name: props.user.name,
            email: props.user.email,
            user_phone: props.user.user_phone,
            user_address: props.user.user_address,
            user_about_me: props.user.user_about_me,
            permissions: '',
        })

        function handleUserMultiSelect({data}) {
            form.permissions = data.toString();
        }

        return { handleUserMultiSelect, form }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('daily_active_user_report_module'),
                    url: route('daily_active_user_report.index'),
                },
                {
                    label: "Daily Active User Detail",
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('daily_active_user_report.index'));
        },
    },
})
</script>
