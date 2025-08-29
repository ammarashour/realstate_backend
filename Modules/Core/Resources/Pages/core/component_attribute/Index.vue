<template>

    <Head :title="$t('component_attribute')" />
    <ps-layout>
        <div class="min-h-screen">
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-4 sm:mb-0" />
            <ps-banner-icon v-if="visible" :visible="visible"
                :theme="(status.flag) == 'danger' ? 'bg-red-500' : (status.flag) == 'warning' ? 'bg-yellow-500' : 'bg-green-500'"
                :iconName="(status.flag) == 'danger' ? 'close-circle' : (status.flag) == 'warning' ? 'alert-triangle' : 'rightalert'"
                class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{ status.msg }}</ps-banner-icon>

            <div class="flex flex-col gap-3 mt-6">
                <div v-for="componentAttribute in componentAttributes" :key="componentAttribute.id"
                    class="flex justify-between shadow-sm px-4 py-3 border-2 border-gray-200">
                    <div class="font-bold text-sm">{{ componentAttribute.name }}</div>
                    <ps-toggle :selectedValue="componentAttribute.attributes.is_show == 1 ? true : false"
                        @click="handleVisibility(componentAttribute.id)"></ps-toggle>
                </div>
            </div>
        </div>
    </ps-layout>

    <ps-loading-circle-dialog ref="ps_loading_circle_dialog" />
</template>

<script setup>
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsLoadingCircleDialog from '@/Components/Core/Dialog/PsLoadingCircleDialog.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, watch, ref } from "vue";
import { trans } from "laravel-vue-i18n";
import { route } from "ziggy-js";

const props = defineProps({
    query: Object,
    componentAttributes: Object,
    themePlatform: Object,
    themeScreen: Object,
    status: Object
});

const visible = ref(false);
const ps_loading_circle_dialog = ref();
const breadcrumb = computed(() => [
    {
        label: trans('core__be_dashboard_label'),
        url: route('admin.index')
    },
    {
        label: trans(props.themePlatform.name),
        url: route('customize_theme.index', { platform_id: props.query.platform_id })
    },
    {
        label: trans(props.themeScreen.name),
        color: "text-primary-500"
    }
])

function openLoading() {
    ps_loading_circle_dialog.value.openModal(trans('core__be_updating'), trans('core__be_sync_and_update'));
}

function closeLoading() {
    ps_loading_circle_dialog.value.closeModal();
}

watch(() => props.status, () => {
    visible.value = true;
    setTimeout(() => {
        visible.value = false;
    }, 3000);
});

function handleVisibility(id) {
    openLoading();
    router.put(route('component_attribute.visibilityChange', { component_attribute: id }), {}, {
        onSuccess: () => {
            closeLoading();
        },
        onError: () => {
            closeLoading();
        }
    });
}

</script>
