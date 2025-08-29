<!-- <script>
import { defineComponent, ref } from "vue";
import { mapActions, mapGetters } from "vuex";
import TitleBar from "@/Components/Layouts/TitleBar.vue";
import SidebarMenu from "@/Components/Layouts/SidebarMenu.vue";
import { Head, Link } from '@inertiajs/vue3';

export default defineComponent({
    components: { TitleBar, SidebarMenu, Head, Link },
    computed: {
        ...mapGetters([
            "isDarkMode",
            "activeLanguage",
            "dir",
            "sidebarNavOpen",
            "sidebarFull",
            "showMenu",
            "sidebarActive",
        ])
    },
    setup(){
        function getDir(){
            if(localStorage.activeLanguage != null && localStorage.activeLanguage != undefined && localStorage.activeLanguage == 'ar'){
                return 'rtl';
            }else{
                return 'ltr';
            }
        }
        return{
            getDir
        }
    },
    mounted() {
        // for dark or light mode local storage
        this.loadIsDarkMode();

        // for language switch local storage
        this.loadActiveLanguage();

        // for rtl or ltr directory switch local storage
        this.loadDirectory();

    },
    methods: {
        ...mapActions([
            "handleSidebarFull",
            "handleSidebarNavOpen",
            "handleShowMenu",
            "loadIsDarkMode",
            "loadActiveLanguage",
            "loadDirectory",
        ]),
    },
});
</script>

<template>


    <div class="flex overflow-hidden" :class="isDarkMode ? 'dark' : ''" :dir="getDir()">
        <!-- left -->
        <div class="flex min-h-screen">
            <sidebar-menu />
        </div>

        <!-- right -->
        <div class="flex-1 h-screen overflow-x-hidden overflow-y-auto">
            <title-bar />

            <!-- content -->
            <div class="p-6">
                <slot />
            </div>
        </div>
    </div>
</template> -->
