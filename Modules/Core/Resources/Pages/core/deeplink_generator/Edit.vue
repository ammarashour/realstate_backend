<template>
    <Head :title="$t('deeplink_generator_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
                <ps-banner-icon  v-if="visible" :visible="visible"
        :theme="(status.flag)=='danger'?'bg-red-500':(status.flag)=='warning'?'bg-yellow-500':'bg-green-500'"
        :iconName="(status.flag)=='danger'?'close-circle':(status.flag)=='warning'?'alert-triangle':'rightalert'"
        class="text-white mb-5 sm:mb-6 lg:mb-8"
         iconColor="white">{{status.msg}}</ps-banner-icon>
        <!-- breadcrumb end -->

        <ps-card class="w-full h-auto">
            <div class="rounded-xl">
                <!-- card header start -->
                <div class="bg-primary-50 dark:bg-primary-900 py-2.5 ps-4 rounded-t-xl">
                    <ps-label-header-6 textColor="text-secondary-800 dark:text-secondary-100">{{$t('deeplink_generator_module')}}</ps-label-header-6>
                </div>
                <!-- card header end -->

                <!-- card body start -->
                <div class="px-4 pt-6 dark:bg-backgroundDark">
                    <form @submit.prevent="handleDeeplink()">
                        <div class="grid w-full sm:w-1/2 gap-6">
                            <div>
                                <ps-label>{{ $t('deeplinkGenerator__be_default_dynamic_link') }}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_default_dynamic_link')" v-model:value="form.default_dynamic_link"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <div v-if="!shouldDisableForFirebase()">
                                <ps-label>{{ $t('deeplinkGenerator__be_scheme_name') }}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_scheme_name')" v-model:value="form.scheme_name"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <div v-if="shouldDisableForFirebase()">
                                <ps-label>{{ $t('deeplinkGenerator__be_dyn_link_key') }}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_dyn_link_key')" v-model:value="form.dyn_link_key"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <div v-if="shouldDisableForFirebase()">
                                <ps-label>{{$t('deeplinkGenerator__be_dyn_link_domain')}}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_dyn_link_domain')" v-model:value="form.dyn_link_domain"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <div v-if="shouldDisableForFirebase()">
                                <ps-label>{{$t('deeplinkGenerator__be_dyn_link_url')}}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_dyn_link_url')" v-model:value="form.dyn_link_url"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <div v-if="shouldDisableForFirebase()">
                                <ps-label>{{$t('deeplinkGenerator__be_deep_link_url')}}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_deep_link_url')" v-model:value="form.dyn_link_deep_url"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <div>
                                <ps-label>{{$t('deeplinkGenerator__be_dyn_link_pkg_name')}}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_dyn_link_pkg_name')"
                                    v-model:value="form.dyn_link_package_name"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <div v-if="shouldDisableForFirebase()">
                                <ps-label>{{$t('deeplinkGenerator__be_dyn_link_ios_pkg_name')}}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_dyn_link_ios_pkg_name')" v-model:value="form.ios_boundle_id"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <div>
                                <ps-label>{{$t('deeplinkGenerator__be_dyn_link_ios_appstore_id')}}</ps-label>
                                <ps-input type="text" :placeholder="$t('deeplinkGenerator__be_dyn_link_ios_appstore_id')" v-model:value="form.ios_appstore_id"
                                    class="rounded flex-1 disabled:bg-gray-200 disabled:cursor-not-allowed disabled:text-gray-800" disabled />
                            </div>
                            <ps-label>{{ $t('deeplinkGenerator__be_note') }}
                                <a :href="route('backend_setting.index')" target="_blank" class="text-blue-600">{{ $t('deeplinkGenerator__be_goto_be_setting') }}</a>
                            </ps-label>
                            <div class=" px-4 py-3 flex flex-row justify-end mb-2.5 mt-4">
                                <ps-button padding="py-1 px-2" @click="handleCancel()" textSize="text-base" type="reset" class="me-4" colors="text-primary-500" focus="" hover="">{{$t('core__be_btn_cancel')}}</ps-button>
                                <ps-button :disabled="!can.updateGenerateDeepLink" padding="p-1 px-2">{{ $t('deeplinkGenerator__be_generate_deeplink') }}</ps-button>
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
import { defineComponent,watch,ref } from 'vue'
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import { Head, useForm } from "@inertiajs/vue3";
import PsInput from "@/Components/Core/Input/PsInput.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextarea from '@/Components/Core/Textarea/PsTextarea.vue';
import PsLabelHeader4 from "@/Components/Core/Label/PsLabelHeader4.vue";
import { trans } from 'laravel-vue-i18n';
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";



export default defineComponent({
    name: "Edit",
    components: {
        Head,
        PsInput,
        PsLabel,
        PsButton,
        PsTextarea,
        PsLabelHeader4,
        PsBreadcrumb2,
        PsBannerIcon
    },
    layout: PsLayout,
    props: ['errors', 'backend_setting', 'status','can', 'dynamic_link_setting'],
    setup(props){
        const visible = ref(false);
        const currentDynamicLink = JSON.parse(props.dynamic_link_setting.setting);
        const dynamicLinkOptions = JSON.parse(props.dynamic_link_setting.ref_selection).default_dynamic_link;
        let form = useForm({
            default_dynamic_link: currentDynamicLink.default_dynamic_link.id,
            scheme_name: currentDynamicLink.scheme_name,
            dyn_link_key: props.backend_setting.dyn_link_key,
            dyn_link_domain: props.backend_setting.dyn_link_domain,
            dyn_link_url: props.backend_setting.dyn_link_url,
            dyn_link_deep_url: props.backend_setting.dyn_link_deep_url,
            dyn_link_package_name: props.backend_setting.dyn_link_package_name,
            ios_boundle_id: props.backend_setting.ios_boundle_id,
            ios_appstore_id: props.backend_setting.ios_appstore_id,
        })

        function shouldDisableForFirebase() {
            const firebaseLink = dynamicLinkOptions.find(link => link.id === 'FIREBASE');
            return form.default_dynamic_link === firebaseLink?.id;
        }

        function handleCancel() {
            this.$inertia.get(route('deeplink_generator.index'));
        }
         watch(() => props.status, () => {
                visible.value = true;
                    setTimeout(() => {
                        visible.value = false;
                    }, 3000);
            });


        return {
            form,
            handleCancel,
            visible,
            shouldDisableForFirebase
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
                    label: trans('deeplink_generator_module'),
                    color: "text-primary-500"
                }
            ]

        }
    },
    methods: {
        handleDeeplink() {
            this.$inertia.put(route('deeplink_generator.update'));
        },
    },
})
</script>
