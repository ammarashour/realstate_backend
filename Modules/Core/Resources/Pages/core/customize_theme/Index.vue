<template>

    <Head :title="$t('customize_theme_group')" />
    <ps-layout>
        <div class="min-h-screen">
            <ps-breadcrumb-2 :items="breadcrumb" class="mb-4 sm:mb-0" />
            <ps-banner-icon v-if="visible" :visible="visible"
                :theme="(status.flag) == 'danger' ? 'bg-red-500' : (status.flag) == 'warning' ? 'bg-yellow-500' : 'bg-green-500'"
                :iconName="(status.flag) == 'danger' ? 'close-circle' : (status.flag) == 'warning' ? 'alert-triangle' : 'rightalert'"
                class="text-white mb-5 sm:mb-6 lg:mb-8" iconColor="white">{{ status.msg }}</ps-banner-icon>

            <ps-table2 :object="themeScreens" :columns="showCoreAndCustomFieldArr" :eye_filter="false"
                :searchable="true" :row="row">
                <template #Filter>
                    <div class="flex gap-2">
                        <ps_button v-for="themePlatform in props.themePlatforms" :key="themePlatform.id" :class="[
                            'px-7 py-1 rounded-lg cursor-pointer',
                            themePlatform.id == platformId ? 'bg-primary-500 text-white dark:text-black' : 'bg-secondary-300 text-black dark:text-white dark:bg-secondary-900'
                        ]" type="button" @click="handlePlatformChange(themePlatform.id)">
                            {{ themePlatform.name }}
                        </ps_button>
                    </div>
                </template>
                <template #tableRow="rowProps">
                    <!-- <ps-toggle  :disabled="rowProps.row.authorization.update ? false : true" v-if="rowProps.field == 'status'" :selectedValue="rowProps.row.status == 1 ? true : false" @click="handlePublish(rowProps.row.id,rowProps.row.authorization.update)"></ps-toggle> -->
                    <ps-label v-if="rowProps.field == 'platform_id'">
                        {{ rowProps.row['platform_id@@name'] }}
                    </ps-label>
                    <ps-toggle v-if="rowProps.field == 'is_publish'"
                        :selectedValue="rowProps.row.is_publish == 1 ? true : false"
                        @click="handlePublish(rowProps.row.id)"></ps-toggle>
                    <ps-link-1 v-if="rowProps.field == 'core__be_detail'" textColor="text-blue-400"
                        :url="route('component_attribute.index', { screen_id: rowProps.row.id, platform_id: rowProps.row.platform_id })">
                        {{ $t('core__be_detail') }}
                    </ps-link-1>
                </template>
            </ps-table2>
        </div>
    </ps-layout>
</template>

<script setup>
import PsLayout from "@/Components/PsLayout.vue";
import PsBreadcrumb2 from "@/Components/Core/Breadcrumbs/PsBreadcrumb2.vue";
import PsTable2 from "@/Components/Core/Table/PsTable2.vue";
import PsBannerIcon from "@/Components/Core/Banners/PsBannerIcon.vue";
import PsToggle from '@/Components/Core/Toggle/PsToggle.vue';
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsLink1 from '@/Components/Core/Link/PsLink1.vue';
import { Head, router } from '@inertiajs/vue3';
import { computed, ref, watch } from "vue";
import { trans } from 'laravel-vue-i18n';

const props = defineProps({
    query: Object,
    showCoreAndCustomFieldArr: Object,
    themeScreens: Object,
    themePlatforms: Object,
    status: Object
});

const row = ref(props.query.row);
const platformId = ref(props.query.platform_id);
const visible = ref(false);

const breadcrumb = computed(() => [
    {
        label: trans('core__be_dashboard_label'),
        url: route('admin.index')
    },
    {
        label: trans(props.themePlatforms.find(platform => platform.id == platformId.value)['name']),
        color: "text-primary-500"
    }
])

function handlePlatformChange(id) {
    platformId.value = id;
    const query = getQuery();
    router.get(route('customize_theme.index'), query);
}

function handlePublish(id) {
    const query = getQuery();
    router.put(route('customize_theme.statusChange', { ...query, theme_screen: id }));
}

watch(() => props.status, () => {
    console.log('runned');
    console.log(props.status.flag);
    visible.value = true;
    setTimeout(() => {
        visible.value = false;
    }, 3000);
});

function getQuery() {
    return {
        row: row.value,
        platform_id: platformId.value
    }
}

</script>
