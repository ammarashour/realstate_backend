<template>
    <Head :title="$t('privacy_policy_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <div class="w-full mt-8 rounded-lg bg-white dark:bg-secondaryDark-black  shadow-sm">
            <!-- card header start -->
            <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('core__be_privacy_policy')}}</ps-label-header-6>
            </div>
            <!-- card header end -->
            <form @submit.prevent="handleSubmit">
                <div class="px-4 py-3">
                    <ps-label class="text-base">
                        {{$t('core__be_content')}}
                    </ps-label>
                    <editor class="dark:bg-secondaryDark:black" v-model="form.content" url="privacy_policy/ckupload" />
                </div>

                <div class="px-4 py-3">
                    <ps-label class="text-base mb-1">{{$t('core__be_privacy_policy_url')}}</ps-label>
                    <ps-link-1 target="_blank" :url="privacyurl" textColor="text-blue-500"> {{privacyurl}} </ps-link-1><br>
                </div>

                <div class=" flex flex-row px-4 py-3 justify-end mb-2.5 mt-4">
                    <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                    <ps-button :disabled="!privacy_policy.authorizations.update" class="transition-all duration-300 min-w-3xs" padding="px-6 py-2" rounded="rounded" hover="" focus="" >
                        <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                        <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                        <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('core__be_btn_save')}} </ps-label>
                    </ps-button>
                </div>

            </form>

        </div>
    </ps-layout>
</template>

<script>
import { defineComponent,ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import Editor from "@/Components/Core/Editor/Editor.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsLabelHeader6 from "@/Components/Core/Label/PsLabelHeader6.vue";
import { trans } from 'laravel-vue-i18n';
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';

export default defineComponent({
    name: "Create",
    components: {
        Head,
        Editor,
        PsIcon,
        PsLoading,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsLabelCaption,
        PsBreadcrumb2,
        PsLabelHeader6,
        PsLink1
    },
    layout: PsLayout,
    props: ['errors','privacy_policy', "domain", "dir"],
    setup(props) {
        // Client Side Validation
        let form = useForm({
                // content: props.privacy_policy?.content,
                content: props.privacy_policy.content,
                "_method": "put"
            })
        const loading = ref(false);
        const success = ref(false);
        const { isEmpty } = useValidators();
        // console.log(props.domain);

        let appDomain = props.domain;

        let subFolder = props.dir;

        let appUrl = "";
        if (!subFolder) {
            appUrl = appDomain;
        } else {
            appUrl = appDomain + subFolder + '/';
        }

        const privacyurl = appUrl + 'app_content/privacy';
        // const privacyurl = process.env.APP_URL + '/app_content/privacy';


        const validateEmptyInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? isEmpty(fieldName, fieldValue) : '';
        }
        function handleSubmit() {

            // this.$inertia.get(route('privacycontent.index'));
            this.$inertia.post(route('privacy_policy.update'), form, {
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

        return { validateEmptyInput,form ,handleSubmit,loading,success,privacyurl}
    },
    computed: {
        breadcrumb() {

            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('privacy_policy_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
