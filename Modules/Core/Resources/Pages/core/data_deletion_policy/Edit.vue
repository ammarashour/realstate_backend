<template>

    <Head :title="$t('edit_data_deletion_policy')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_data_deletion_policy')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleSubmit(data_deletion_policy.id)">
                            <div class="px-4 py-3">
                                <ps-label class="text-base mb-1">{{$t('core__be_content')}}</ps-label>
                                <!-- <editor v-model:vlaue="form.content" placeholder="Content"></editor> -->
                                <editor class="dark:bg-secondaryDark:black" v-model="form.content" url="data_deletion_policy/ckupload" />
                            </div>
                            <div class="px-4 py-3">
                                <ps-label class="text-base mb-1">{{$t('core__be_data_deletion_policy_url')}} : </ps-label>
                                <ps-link-1 target="_blank" :url="datadeletionurl" textColor="text-blue-500"> {{datadeletionurl}} </ps-link-1><br>
                            </div>

                            <div class="flex flex-row  px-4 py-3 justify-end mb-2.5 mt-4">
                                <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4"
                                    colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                <ps-button :disabled="!data_deletion_policy.authorizations.update" class="transition-all duration-300 min-w-3xs" padding="px-6 py-2"
                                    rounded="rounded" hover="" focus="">
                                    <ps-loading v-if="loading"
                                        theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                        loadingSize="h-5 w-5" />
                                    <ps-icon v-if="success" name="check" w="20" h="20"
                                        class="me-1.5 transition-all duration-300" />
                                    <ps-label v-if="success" class="transition-all duration-300"
                                        textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                    <ps-label v-if="!loading && !success"
                                        textColor="text-white dark:text-secondaryDark-black"> {{$t('core__be_btn_save')}} </ps-label>
                                </ps-button>
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
import { Head, Link, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import Editor from "@/Components/Core/Editor/Editor.vue";
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption,
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        Editor,
        PsLink1,
        Link,
    },
    layout: PsLayout,
    props: ['errors', 'data_deletion_policy', 'status', 'domain', 'dir'],
    setup(props) {

        const loading = ref(false);
        const success = ref(false);
        let form = useForm(
            {
                content: props.data_deletion_policy.content,
                "_method": "put"
            }
        )

        let appDomain = props.domain;

        let subFolder = props.dir;

        let appUrl = "";
        if (!subFolder) {
            appUrl = appDomain;
        } else {
            appUrl = appDomain + subFolder + '/';
        }

         const datadeletionurl = appUrl + 'app_content/datadelection';

        // Client Side Validation
        const { isEmail } = useValidators();

        const validateEmailInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? '' : isEmail(fieldName, fieldValue);
        }

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }
        function handleCancel() {
            this.$inertia.get(route('data_deletion_policy.index'));
        }
        function handleSubmit(id) {
            this.$inertia.post(route('data_deletion_policy.update', id), form, {
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
            });
        }
        return {
            validateEmailInput,
            handleCancel,
            onlyNumber,
            form,
            handleSubmit,
            loading,
            success,
            datadeletionurl
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
                    label: trans('data_deletion_policy_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
