<template>

    <Head :title="$t('edit_notification')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 text-secondary-800 py-2.5 ps-4">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('core__be_user_info') }}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(user.id)">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label class="text-base">{{ $t('profile_photo') }}</ps-label>
                                <div v-if="user.user_cover_photo" class="flex items-end pt-4">
                                    <img 
                                    v-lazy=" { src: $page.props.uploadUrl + '/' + user.user_cover_photo, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }"
                                     width="200" height="200"
                                        class="" alt="Profile photo" @error="'/images/assets/default_profile.png'" />
                                    <ps-button type="button" @click="replaceImageClicked(user.user_cover_photo)" rounded="rounded-full"
                                        shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500"
                                        padding="p-1.5" hover="" focus="">
                                        <ps-icon name="editPencil" w="19" h="19" />
                                    </ps-button>
                                    <ps-image-icon-modal ref="ps_image_icon_modal" />
                                    <ps-action-modal ref="ps_action_modal" />
                                    <ps-danger-dialog ref="ps_danger_dialog" />
                                </div>
                                <input v-else type="file" accept="image/*"
                                    @input="form.user_cover_photo = $event.target.files[0]" />
                            </div>
                            <div>
                                <ps-label class="text-base">
                                    {{ $t('user_name_label') }}
                                </ps-label>
                                <ps-input type="text" v-model:value="form.name" :placeholder="$t('user_name_label')"
                                    @keyup="validateEmptyInput('name', form.name)"
                                    @blur="validateEmptyInput('name', form.name)" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}
                                </ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">
                                    {{ $t('email_label') }}
                                </ps-label>
                                <ps-input type="text" v-model:value="form.email" :placeholder="$t('email_label')"
                                    @keyup="validateEmailInput('email', form.email)"
                                    @blur="validateEmailInput('email', form.email)" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.email }}
                                </ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">{{ $t('password_label') }}</ps-label>
                                <ps-input ref="input_password" type="password" v-model:value="form.password" :placeholder="$t('password_label')"
                                @keyup="validateEmptyInput('password', form.password)"
                                @blur="validateEmptyInput('password', form.password)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{errors.password}}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">{{ $t('conf_password_label') }}</ps-label>
                                <ps-input ref="input_confirm_password" type="password" v-model:value="form.conf_password" :placeholder="$t('conf_password_label')"
                                @keyup="validateEmptyInput('conf_password', form.conf_password)"
                                @blur="validateEmptyInput('conf_password', form.conf_password)"/>
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.conf_password }}</ps-label-caption>
                            </div>
                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-5 py-2" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                    <span v-if="success" class="transition-all duration-300">{{ $t('core__be_btn_saved') }}</span>
                                    <span v-if="!loading && !success" class="" > {{ $t('core__be_btn_save') }} </span>
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
import { defineComponent, onMounted, ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import CheckBox from "../components/CheckBox.vue";
import RoleCheckbox from "../components/RoleCheckbox.vue";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        Link,
        CheckBox,
        RoleCheckbox,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsIcon,
        PsLabelCaption,
        PsLoading,
        PsBreadcrumb2,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog
    },
    layout: PsLayout,
    props: ['errors', 'user', 'custom_field_headers',
    ],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const input_name = ref(false);
        const input_email = ref(false);
        const input_password = ref(false);
        const input_confirm_password = ref(false);

        // Client Side Validation
        let form = useForm(
            {
                name: props.user.name,
                email: props.user.email,
                user_address: props.user.user_address,
                user_about_me: props.user.user_about_me,
                user_cover_photo: "",
                is_show_email: props.user.is_show_email == 1 ? true : false,
                is_show_phone: props.user.is_show_phone == 1 ? true : false,
                custom_fields: [],
                permissions: '',
                "_method": "put"
            }
        )

        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        const { isEmpty, isNum, isEmail } = useValidators();

        const validateEmptyInput = (fieldName, fieldValue, errorMessage = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage) : '';
            if(fieldName == 'name'){
                    input_name.value.isError = true;
                }
                if(fieldName == 'password'){
                    input_password.value.isError = true;
                }
                if(fieldName == 'conf_password'){
                    input_confirm_password.value.isError = true;
                }
        }

        const validateEmailInput = (fieldName, fieldValue, errorMessage1 = '', errorMessage2 = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage1) : isEmail(fieldName, fieldValue, errorMessage2);

                if(fieldName == 'email'){
                    input_email.value.isError = true;
                }
        }

        const validateNumberInput = (fieldName, fieldValue, errorMessage1 = '', errorMessage2 = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage1) : isNum(fieldName, fieldValue, errorMessage2);
        }

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }
        function handleSubmit(id) {
            this.$inertia.post(route('registered_user.update', id), form, {
                forceFormData: true,
                onBefore: () => { loading.value = true },
                onSuccess: () => {
                    loading.value = false;
                    success.value = true;
                    setTimeout(() => {
                        success.value = false;
                    }, 2000)
                },
                onError: () => {
                    
                    loading.value = false;;
                },
            })
        }
        function handleMultiSelect({ data, id }) {
            form.custom_fields[id] = data.toString();
        }
        function handleUserMultiSelect({ data }) {
            form.permissions = data.toString();
        }
        onMounted(() => {
            props.user_custom_fields.map((value, index) => {
                let data = value.data;
                form.custom_fields[value.custom_field_header_id] = data === '1' ? true : (data === '0' ? false : data)
            });
        })
        function replaceImageClicked(id) {
            ps_action_modal.value.openModal(trans('conf_modal_label'),
                trans('core__be_replace_img_label'),
                trans('core__be_remove_img_label'),
                'image',
                'trash',
                '24',
                '24',
                () => {
                    ps_image_icon_modal.value.openModal();
                },
                () => {
                    ps_danger_dialog.value.openModal(
                        trans('core__be_remove_label'),
                        trans('core__be_are_u_sure_remove_photo'),
                        trans('core__be_btn_confirm'),
                        trans('core__be_btn_cancel'),
                        () => {
                            this.$inertia.delete(route("image.destroy", id), {
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
                                },
                            });
                        },
                        () => { }
                    );
                }
            );
        }

        return {
            validateEmptyInput,
            validateEmailInput,
            validateNumberInput,
            onlyNumber,
            form,
            handleSubmit,
            handleMultiSelect,
            handleUserMultiSelect,
            loading,
            success,
            replaceImageClicked,
            ps_danger_dialog,
            ps_image_icon_modal,
            ps_action_modal,
            input_name,
            input_email,
            input_password,
            input_confirm_password,
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
                    label: trans('profile_label'),
                    url: route('registered_user.index'),
                },
                {
                    label: "Edit Profile",
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
