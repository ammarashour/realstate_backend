<template>

    <div  :dir="languageStore.getLanguageDir()" :class="themeStore.isDarkMode ? 'dark' : ''">
        <div class="flex flex-col items-center justify-center min-h-screen">
            <div class="mb-8">
                <expired-image />
            </div>
            <div class="mb-1">
                <ps-label-title>{{ $t('your_free_trial_have_expired') }}</ps-label-title>
            </div>
            <div class="mb-6">
                <ps-label textColor="text-secondary-500 dark:text-secondary-100">{{ $t('pls_activate_to_continue') }}</ps-label>
            </div>
            <ps-button @click="goToDashboard()" textSize="text-xs lg:text-sm" class="" > {{ $t('back_to_dashboard') }} </ps-button>
        </div>
    </div>
</template>

<script>
import { defineComponent, ref, computed, onMounted } from 'vue'
import { Head, Link } from '@inertiajs/vue3';
import PsLabelTitle from "@/Components/Core/Label/PsLabelTitle.vue";
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
import ExpiredImage from "@/Components/Svgs/ExpiredImage.vue";
import { trans } from 'laravel-vue-i18n';
import { useLanguageStore } from '../../store/Localization/LanguageStore';
import { useThemeStore } from '../../store/Utilities/ThemeStore';

export default defineComponent({
    components: {
        PsLabelTitle,
        PsLabel,
        PsButton,
        ExpiredImage
    },
    setup(){
        
        const languageStore = useLanguageStore();
        const themeStore = useThemeStore();

        return{
            languageStore,
            themeStore
        }
    },
    methods: {
        goToDashboard() {
            this.$inertia.get(route('admin.index'));
        }
    },
})
</script>
