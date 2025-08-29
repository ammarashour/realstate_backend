<template>

    <Head :title="$t('user_profile')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->
        <ps-card class="w-full h-auto">
            <div class="bg-background dark:bg-secondaryDark-black rounded-xl ">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 rounded-t-xl py-2.5 ps-4">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{ $t('profile_label') }}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(user.id)">
                        <div class="grid w-full sm:w-1/2 gap-6">
                           
                            <!-- user cover photo -->
                            <div>
                                <ps-label class="text-base">{{$t('core__be_user_cover_photo')}}<span class="text-red-800 font-medium ms-1">*</span></ps-label>
                                <ps-label-title-3 v-if="!user.user_cover_photo">{{ $t('core__be_recommended_size_200_200') }}</ps-label-title-3>
                                <div v-if="user.user_cover_photo" class="flex items-end pt-4">
                                    <img 
                                    v-lazy=" { src: $page.props.uploadUrl + '/' + user.user_cover_photo, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }"
                                     class="w-48 h-48" :alt="$t(core__be_user_cover_photo)" />
                                    <ps-button type="button" @click="replaceImageClicked(user.id)" rounded="rounded-full" shadow="drop-shadow-2xl" class="-ms-10 mb-2" colors="bg-white text-primary-500 dark:bg-secondaryDark-black" border="border border-1 dark:border-secondary-700 border-secondary-300" padding="p-1.5" hover="" focus="">
                                        <ps-icon name="pencil-btn"  w="21" h="21" />
                                    </ps-button>
                                    <ps-image-icon-modal ref="ps_image_icon_modal" />
                                    <ps-action-modal ref="ps_action_modal" />
                                    <ps-danger-dialog ref="ps_danger_dialog" />
                                </div>
                                <ps-image-upload v-else uploadType="image" class="w-48" v-model:imageFile="form.user_cover_photo" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.user_cover_photo }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">
                                    {{ $t('core__be_user_name_label') }}
                                </ps-label>
                                <ps-input ref="username" type="text" v-model:value="form.username" :placeholder="$t('core__be_user_name_label')"
                                    @keyup="validateEmptyInput('name', form.username)"
                                    @blur="validateEmptyInput('name', form.username)" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.username }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">
                                    {{ $t('core__be_name') }}
                                </ps-label>
                                <ps-input ref="name" type="text" v-model:value="form.name" :placeholder="$t('core__be_name')"
                                    @keyup="validateEmptyInput('name', form.name)"
                                    @blur="validateEmptyInput('name', form.name)" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.name }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">
                                    {{ $t('core__be_user_email') }}
                                </ps-label>
                                <ps-input ref="email" type="text" v-model:value="form.email" :placeholder="$t('core__be_user_email')"
                                    @keyup="validateEmailInput('email', form.email)"
                                    @blur="validateEmailInput('email', form.email)" />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.email }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">{{ $t('core__be_user_password') }}</ps-label>
                                <ps-input ref="password" type="password" v-model:value="form.password" :placeholder="$t('core__be_user_password')"
                                />
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.password }}</ps-label-caption>
                            </div>
                            <div>
                                <ps-label class="text-base">{{ $t('core__be_user_conf_password') }}</ps-label>
                                <ps-input ref="password_confirmation" type="password" v-model:value="form.password_confirmation" :placeholder="$t('core__be_user_conf_password')"
                                />
                                
                                <!-- <ps-label-caption v-if="checkpassword" textColor="text-red-500 " class="mt-2 block">{{ $t('core__be_password_not_same') }}</ps-label-caption> -->
                                <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.password_confirmation }}</ps-label-caption>
                            </div>
                            <div class="flex flex-row justify-end mb-2.5">
                                <ps-button textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{ $t('core__be_btn_cancel') }}</ps-button>
                                <ps-button class="transition-all duration-300 min-w-3xs" padding="px-8 py-0" rounded="rounded" hover="" focus="" >
                                    <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-4 w-4" />
                                    <ps-icon v-if="success" name="check" w="14" h="14" class="me-1.5 transition-all duration-300" />
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
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
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
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
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
        PsDangerDialog,
        PsImageUpload,
        PsLabelTitle3
    },
    layout: PsLayout,
    props: ['errors', 'user' ],
    setup(props) {
        const loading = ref(false);
        const success = ref(false);
        const name = ref(false);
        const email = ref(false);
        const password = ref(false);
        const password_confirmation = ref(false);
        // const checkpassword = ref(false);

        // Client Side Validation
        let form = useForm(
            {
                id: props.user.id,
                username: props.user.username,
                name: props.user.name,
                email: props.user.email,
                user_cover_photo: "",
                password: "",
                password_confirmation: "",
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
                name.value.isError = (props.errors.name == '') ? false: true;
            }
            if(fieldName == 'password'){
                password.value.isError = (props.errors.password == '') ? false: true;
            }
            if(fieldName == 'password_confirmation'){
                password_confirmation.value.isError = (props.errors.password_confirmation == '') ? false: true;
            }
        }

        const validateEmailInput = (fieldName, fieldValue, errorMessage1 = '', errorMessage2 = '') => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue, errorMessage1) : isEmail(fieldName, fieldValue, errorMessage2);

                if(fieldName == 'email'){
                    email.value.isError = (props.errors.email == '') ? false: true;
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
            // if(form.password === form.conf_password){
                // checkpassword.value = false;
                this.$inertia.post(route('user.profile.update', id), form, {
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
                        name.value.isError = (props.errors.name == '') ? false: true;
                        email.value.isError = (props.errors.email == '') ? false: true;
                        password.value.isError = (props.errors.password == '') ? false: true;
                        password_confirmation.value.isError = (props.errors.password_confirmation == '') ? false: true;
                        loading.value = false;;
                    },
                })
            // }else{

            //     checkpassword.value = true;
            // }
            
        }
        
        function replaceImageClicked(id) {
            ps_action_modal.value.openModal(trans('conf_modal_label'),
                trans('core__be_replace_img_label'),
                trans('core__be_remove_img_label'),
                'image',
                'trash',
                '24',
                '24',
                () => {
                    ps_image_icon_modal.value.openModal(
                        trans('core__be_upload_photo'),
                        'cloudUpload',
                        (imageFile) => {
                            let imageForm = useForm({
                                image: imageFile,
                                "_method": "post"
                            })

                            this.$inertia.post(route("user.image.replace", id), imageForm);
                        });
                },
                () => {
                    ps_danger_dialog.value.openModal(
                        trans('core__be_remove_label'),
                        trans('core__be_are_u_sure_remove_photo'),
                        trans('core__be_btn_confirm'),
                        trans('core__be_btn_cancel'),
                        () => {
                            this.$inertia.delete(route("user.image.delete", id), {
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
            loading,
            success,
            replaceImageClicked,
            ps_danger_dialog,
            ps_image_icon_modal,
            ps_action_modal,
            name,
            email,
            password,
            password_confirmation,
            // checkpassword
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
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
