<style scoped>
    .vue-neat-modal {
        max-height: 80%;
    }
</style>

<template>


    <Modal
        v-model="isOpen"
        :fullscreen="isFullscreen"
        :modal-transition="modalTransition"
        :click-out="isClickOut"
        :disable-motion="isMotionDisabled"
        teleportTarget="#mainbody"
        :max-width="!isFullscreen ? maxWidth : undefined"
        :remove-backdrop="isBackdropRemoved"
        >
        <div :class="themeStore.isDarkMode ? 'dark' : ''" :dir="languageStore.getLanguageDir()">
            <div :class="theme" class="max-h-auto bg-background dark:bg-backgroundDark "  >
                <slot name="title"   />
                <!-- <ps-line class="mb-4" :class='line' /> -->
                <div class="mb-4 " :class="bodyHeight" >
                    <slot name="body"  />
                </div>
                <slot name="footer" />

            </div>
        </div>
    </Modal>

</template>
<script>
import { Modal } from "vue-neat-modal";
import 'vue-neat-modal/dist/vue-neat-modal.css';
import { ref } from 'vue';
import PsLine from '../Line/PsLine.vue';
import { useThemeStore } from "../../../store/Utilities/ThemeStore";
import { useLanguageStore } from "../../../store/Localization/LanguageStore";

export default {
    name:"PsModal",
    components : {
        Modal,
        PsLine
    },
    props : {
        maxWidth : {
            type : String,
            default : "500px"
        },
        bodyHeight : {
            type : String,
            default : "max-h-80"
        },
        isBackdropRemoved : {
            type : Boolean,
            default : false
        },
        isMotionDisabled : {
            type : Boolean,
            default : false
        },
        isClickOut : {
            type : Boolean,
            default : true
        },
        modalTransition : {
            type : String,
            default : "scale"
        },
        isFullscreen : {
            type : Boolean,
            default : false
        },
        theme : {
            type : String,
            default : " p-6 border lg:rounded-2xl rounded-xl  "
        },
        line : {
            type : String,
            default : "mt-4 "
        }
    },
    setup() {
        
        const themeStore = useThemeStore();
        const languageStore = useLanguageStore();

        const isOpen = ref(
            
        );

        function toggle(status) {
            isOpen.value = status;
        }

        return {
            isOpen,
            toggle,
            themeStore,
            languageStore
        }
    }
}
</script>
