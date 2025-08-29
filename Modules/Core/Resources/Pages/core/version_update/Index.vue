<template>
    <Head :title="$t('user_module')" />
    <ps-layout>
        <!-- breadcrumb start -->
        <ps-breadcrumb-2 :items="breadcrumb" class="mb-5 sm:mb-6 lg:mb-8" />
        <!-- breadcrumb end -->

        <!-- alert banner start -->
        <ps-banner-icon v-if="visible" :visible="visible"
            :theme="(status.flag) == 'danger' ? 'bg-red-500' : (status.flag) == 'warning' ? 'bg-yellow-500' : 'bg-green-500'"
            :iconName="(status.flag) == 'danger' ? 'close-circle' : (status.flag) == 'warning' ? 'alert-triangle' : 'rightalert'"
            class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{ status.msg }}</ps-banner-icon>
        <!-- alert banner end -->

        <div class="">
            <ps-button @click="handleVersionUpdate" class="transition-all duration-300 min-w-3xs" padding="px-7 py-2" rounded="rounded" hover="" focus="" >
                <ps-label textColor="text-white dark:text-secondaryDark-black" > {{ $t('version_update') }} </ps-label>
            </ps-button>
        </div>

    </ps-layout>
</template>

<script>
import { ref, defineComponent } from "vue";
import PsLayout from "@/Components/PsLayout.vue";
import { Head } from "@inertiajs/vue3";
import { router } from '@inertiajs/vue3';
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import PsTextButton from "@/Components/Core/Buttons/PsTextButton.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsDangerDialog from "@/Components/Core/Dialog/PsDangerDialog.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import PsDropdown from "@/Components/Core/Dropdown/PsDropdown.vue";
import PsDropdownSelect from "@/Components/Core/Dropdown/PsDropdownSelect.vue";
import Dropdown from "@/Components/Core/DropdownModal/Dropdown.vue";
import PsIconButton from "@/Components/Core/Buttons/PsIconButton.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsRating from "@/Components/Core/Rating/PsRating.vue";
import DatePicker from "@/Components/Core/DateTime/DatePicker.vue";
import { trans } from 'laravel-vue-i18n';

export default defineComponent({
    name: "Index",
    components: {
        Head,
        PsButton,
        PsTextButton,
        PsBannerIcon,
        PsBreadcrumb2,
        PsDangerDialog,
        PsIcon,
        PsDropdown,
        PsDropdownSelect,
        Dropdown,
        PsIconButton,
        PsLabel,
        PsLink1,
        PsToggle,
        PsTable2,
        PsRating,
        DatePicker
    },
    layout: PsLayout,
    props: {
        can: Object,
        status: Object,
        users: Object,
        roles: Object,
        customizeHeaders: Object,
        customizeDetails: Object,
        hideShowFieldForFilterArr: Object,
        showCoreAndCustomFieldArr: Object,
        selectedRole: { type: String, default: '' },
        authUser: Object,
        usrIsVerifyBlueMark : String,
        uis: Object,
        sort_field: {
            type: String,
            default: "",
        },
        sort_order: {
            type: String,
            default: 'desc',
        },
        search: String,
    },
    methods: {
        handleVersionUpdate() {
            this.$inertia.post(route("versionUpdate.handleVersionUpdate"));
        },
    },

    computed: {
        breadcrumb() {
            return [
                {
                    label: trans('core__be_dashboard_label'),
                    url: route('admin.index')
                },
                {
                    label: trans('version_update'),
                    color: "text-primary-500"
                }
            ]
        }
    },
    setup(props){


        return{

        }
    }
})
</script>
