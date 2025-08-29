<template>

    <Head :title="$t('core__contact_us_message_detail')" />
    <ps-layout>

        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 rounded-t-xl py-2.5 ps-4">
                    <ps-label-header6>{{$t('core__contact_us_message_detail')}}</ps-label-header6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                     <div class="bg-secondary-50 dark:bg-secondary-900 rounded py-3 px-4 mb-2 text-base w-full shadow-sm">
                        <div class="flex w-full justify-between">
                             <span class="text-sm flex flex-row">
                                <img 
                                v-lazy=" { src: $page.props.uploadUrl + '/' + form.owner?.user_cover_photo, loading: $page.props.sysImageUrl+'/loading_gif.gif', error: $page.props.sysImageUrl+'/default_profile.png' }"
                                 width="20" height="20"
                                        class="mx-2 inline-block h-8 w-8 rounded-full ring-2 ring-white object-cover" alt="Profile photo" @error="defaultProfileImg" />
                                <!-- <img v-else :src="defaultProfileImg" width="20" height="20"
                                        class="mx-2 inline-block h-8 w-8 rounded-full ring-2 ring-white object-cover" alt="Profile photo" @error="defaultProfileImg" /> -->
                                <ps-label class="font-semibold">{{form.contact_name}}</ps-label>
                            </span>
                            <ps-label class="text-xxs sm:text-xs font-semibold">{{moment(contact.added_date).format($page.props.dateFormat)}}</ps-label>
                        </div>
                        <span class=" text-sm ">
                            <ps-label-caption-3 class="ms-12">by : {{form.contact_email}}</ps-label-caption-3>
                            <ps-label class="ms-12 mt-0.5">{{$t('core__be_contact_number')}} : {{form.contact_phone}}</ps-label>
                        </span>
                        <div class="ms-12 mt-4">
                            <ps-label class="text-xs sm:text-sm lg:text-lg">{{form.contact_message}}</ps-label>
                        </div>
                            <!-- </template> -->

                        <div class="mb-2.5 flex flex-row justify-end">
                            <ps-button type="button" @click="handleCancel()">{{ $t('core__be_btn_back') }}</ps-button>
                        </div>
                    </div>


                    <!-- <form>
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label>{{ $t('name_label') }}</ps-label>
                                <ps-input :disabled="true" type="text" v-model:value="form.contact_name"
                                    :placeholder="$t('name_label')" />
                            </div>
                            <div>
                                <ps-label>{{ $t('email_label') }}</ps-label>
                                <ps-input :disabled="true" type="text" v-model:value="form.contact_email"
                                    :placeholder="$t('email_label')" />
                            </div>
                            <div>
                                <ps-label>{{ $t('phone_label') }}</ps-label>
                                <ps-input :disabled="true" type="text" v-model:value="form.contact_phone"
                                    :placeholder="$t('phone_label')" />
                            </div>
                            <div>
                                <ps-label>{{ $t('message_label') }}</ps-label>
                                <ps-textarea :disabled="true" rows="5" v-model:value="form.contact_message"
                                    :placeholder="$t('message_label')"></ps-textarea>
                            </div>
                            <div class="mb-2.5 flex flex-row justify-end">
                                <ps-button type="button" @click="handleCancel()">{{ $t('core__be_btn_back') }}</ps-button>
                            </div>
                        </div>
                    </form> -->
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
import PsLabelCaption3 from "@/Components/Core/Label/PsLabelCaption3.vue";
import PsLabelTitle3 from "@/Components/Core/Label/PsLabelTitle3.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import { trans } from 'laravel-vue-i18n';
import moment from 'moment';

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
        PsLabelCaption3,
        PsLabelTitle3,
        PsTextarea,
    },
    layout: PsLayout,
    props: ['errors', 'contact', 'defaultProfileImg'],
    data(){
        return{
            moment: moment,
        }
    },
    setup(props) {

        let form = useForm({
            contact_name: props.contact.contact_name,
            contact_email: props.contact.contact_email,
            contact_phone: props.contact.contact_phone,
            contact_message: props.contact.contact_message,
            added_date: props.contact.added_date,
            owner: props.contact.owner,
        })

        return { form }
    },
    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('contact_us_message_module'),
                    url: route('contact.index'),
                },
                {
                    label: trans('core__contact_us_message_detail'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleCancel() {
            this.$inertia.get(route('contact.index'));
        },
    },
})
</script>
