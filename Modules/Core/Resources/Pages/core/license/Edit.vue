<template>
    <Head :title="$t('license_module')" />
    <ps-layout>
        <div class="">
            <!-- breadcrumb start -->
            <ps-breadcrumb-2 :items="breadcrumb" class="mt-4 mb-7" />
            <!-- breadcrumb end -->

            <div class="w-full grid grid-cols-2 gap-x-20 mt-8 rounded-lg bg-whitedark:bg-secondaryDark-black  shadow-sm">
                <ps-label class="col-span-2 text-lg px-4 py-3.5 bg-primary-50 dark:bg-primary-900">
                Edit License
                </ps-label>
                <div class="">
                    <form @submit.prevent="handleSubmit(project.id)">
                        <div class="px-4 py-3">
                            <ps-label class="text-base mb-1" >Backend Url</ps-label>
                            <ps-input type="text" v-model:value="form.project_url" placeholder="Backend Url"
                            />
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.project_url }}</ps-label-caption>

                        </div>
                        <div class="px-4 py-3">
                            <ps-label class="text-base mb-1" >Purchased Code</ps-label>
                            <ps-input type="text" v-model:value="form.project_code" placeholder="Purchased Code"/>
                            <ps-label-caption textColor="text-red-500 " class="mt-2 block">{{ errors.project_code }}</ps-label-caption>
                        </div>


                        <div class="flex flex-row  px-4 py-3 justify-end mb-2.5 mt-4">
                            <ps-button @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('btn_cancel')}}</ps-button>
                            <ps-button class="transition-all duration-300 min-w-3xs" padding="px-6 py-2" rounded="rounded" hover="" focus="" >
                                <ps-loading v-if="loading" theme="border-2 border-t-2 border-text-8 border-t-primary-500"  loadingSize="h-5 w-5" />
                                <ps-icon v-if="success" name="check" w="20" h="20" class="me-1.5 transition-all duration-300" />
                                <ps-label v-if="success" class="transition-all duration-300" textColor="text-white dark:text-secondaryDark-black">{{$t('btn_saved')}}</ps-label>
                                <ps-label v-if="!loading && !success" textColor="text-white dark:text-secondaryDark-black" > {{$t('btn_save')}} </ps-label>
                            </ps-button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </ps-layout>
</template>

<script>
import { defineComponent,ref, reactive } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import { Head,Link, useForm } from "@inertiajs/vue3";
import FlashMessage from "../components/FlashMessage.vue";
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
import PsActionModal from '@/Components/Core/Modals/PsActionModal.vue';
import PsImageIconModal from '@/Components/Core/Modals/PsImageIconModal.vue';
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsImageUpload from "@/Components/Core/Upload/PsImageUpload.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Edit",
    components: {
        FlashMessage,
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
        Link,
        PsActionModal,
        PsImageIconModal,
        PsDangerDialog,
        PsImageUpload
    },
    layout: PsLayout,
    props: ['errors', 'project', 'status'],
    setup(props) {

        const loading = ref(false);
        const success = ref(false);
        const ps_action_modal = ref();
        const ps_image_icon_modal = ref();
        const ps_danger_dialog = ref();
        let form = useForm(
            {
                project_url: props.project.project_url,
                project_code: props.project.project_code,

                "_method": "put"
            }
        )




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
        function changeSection(v){
            title.value = v;
        }
        function handleCancel() {
            this.$inertia.get(route('license.index'));
        }
        function handleSubmit(id) {
            this.$inertia.post(route('license.update', id), form, {
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

        function replaceImageClicked(id) {
            ps_action_modal.value.openModal(trans('conf_modal_label'),
                trans('replace_img_label'),
                trans('remove_img_label'),
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
                                "_method": "put"
                            })

                            this.$inertia.post(route("image.replace", id), imageForm);
                        });
                },
                () => {
                    ps_danger_dialog.value.openModal(
                        trans('remove_label'),
                        trans('are_u_sure_remove_photo'),
                        trans('btn_confirm'),
                        trans('btn_cancel'),
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
            validateEmailInput,
            handleCancel,
            onlyNumber,
            form,
            changeSection,
            handleSubmit,
            loading,
            success,
            replaceImageClicked,
            ps_danger_dialog,
            ps_image_icon_modal,
            ps_action_modal
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
                    label: trans('license_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
})
</script>
