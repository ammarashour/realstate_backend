<template>
    <Head :title="$t('category_module')" />
    <ps-layout>
        <ps-button :onClick="refreshClicked">Refresh</ps-button>
        {{ qq }}
        <div class="mt-4" v-if="loadingData != ''">{{ loadingData }}</div>
        <div class="flex flex-col mt-4" v-else v-for="data in list">
            <div>Est Exec Time : {{ data.time }}s ( {{ data.name }} )</div>
            <vue-json-pretty
                class="mt-4"
                :data="data.data"
                :showLength="true"
                :showLineNumber="true"
                :showIcon="true"
            />
            
        </div>
    </ps-layout>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import PsLayout from "@/Components/PsLayout.vue";
import VueJsonPretty from "vue-json-pretty";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import "vue-json-pretty/lib/styles.css";

export default defineComponent({
    name: "Index",
    components: {
        VueJsonPretty,
        PsButton,
    },
    layout: PsLayout,
    props: {
        list: Object,
        qq : Object
    },

    setup() {
        const loadingData = ref("");

        function refreshClicked() {
            loadingData.value = "Loading...";
            router.get(route("query-test.index"));
        }

        onMounted(() => {
            loadingData.value = "";
        });
        return {
            refreshClicked,
            loadingData,
        };
    },
});
</script>
