<template>
    <ps-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Tokens
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <api-token-manager :tokens="tokens"
                                   :available-permissions="availablePermissions"
                                   :default-permissions="defaultPermissions" />
            </div>
        </div>
    </ps-layout>
</template>

<script>
    import { defineComponent } from 'vue'
import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue'
import PsLayout from "@/Components/PsLayout.vue";

    export default defineComponent({
        props: [
            'tokens',
            'availablePermissions',
            'defaultPermissions',
        ],

        layout: PsLayout,
        components: {
            ApiTokenManager,
        },
    })
</script>
