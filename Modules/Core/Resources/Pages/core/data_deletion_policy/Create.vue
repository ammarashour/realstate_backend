
<template>
    <Head :title="$t('create_data_deletion_policy')" /><ps-layout>
    
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
                    <form @submit.prevent="handleSubmit">
                        <div class="px-4 py-3">
                            <ps-label class="text-base mb-1">{{$t('core__be_content')}}</ps-label>
                            <editor class="dark:bg-secondaryDark-black" />
                        </div>   
                         
                        <div class="flex flex-row  px-4 py-3 justify-end mb-2.5 mt-4">
                            <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4"
                                colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                            <ps-button class="transition-all duration-300 min-w-3xs" padding="px-6 py-2" rounded="rounded"
                                hover="" focus="">
                                <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"
                                    loadingSize="h-5 w-5" />
                                <ps-icon v-if="success" name="check" w="20" h="20"
                                    class="me-1.5 transition-all duration-300" />
                                <ps-label v-if="success" class="transition-all duration-300"
                                    textColor="text-white dark:text-secondaryDark-black">{{$t('core__be_btn_saved')}}</ps-label>
                                <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black">
                                    {{$t('core__be_btn_save')}} </ps-label>
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
import { defineComponent,ref, reactive } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head,Link, useForm } from "@inertiajs/vue3";
import useValidators from '@/Validation/Validators'
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsLoading from "@/Components/Core/Loading/PsLoading.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsLabelCaption from "@/Components/Core/Label/PsLabelCaption.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import Editor from "@/Components/Core/Editor/Editor.vue";
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
        PsIcon,
        PsLoading,
        PsBreadcrumb2,
        Link,
        PsLabelCaption,
        PsLabelTitle3,
        PsImageUpload,
        Editor,
    },
    layout: PsLayout,
    props: ['errors'],
    setup(props) {

        const loading = ref(false);
        const success = ref(false);
        let form = useForm(
            {
                content: "",
            }
        )

        // Client Side Validation
        const { isEmail } = useValidators();

        const validateContentInput = (fieldName, fieldValue) => {
            props.errors[fieldName] = !fieldValue ? '' : isEmail(fieldName, fieldValue);
        }

        const onlyNumber = ($e) => {
            let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
            if (keyCode < 48 || keyCode > 57) {
                $e.preventDefault();
            }
        }
        function changeSection(v){
            title.value = v;
        }
        function handleCancel() { 
            this.$inertia.get(route('data_deletion_policy.index'));
        }
        function handleSubmit() {
            this.$inertia.post(route('data_deletion_policy.store'), form, {
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

        return {
            validateContentInput,
            handleCancel,
            onlyNumber,
            form,
            handleSubmit,
            loading,
            success
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
