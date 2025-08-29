
<style scoped>
.vue-neat-modal {
    max-height: 95%;
   
}
</style> 


<template>
    <Modal
        v-model="isOpen"
        :fullscreen="isFullscreen"
        :modal-transition="modalTransition"
        :click-out="isClickOut"
        :disable-motion="isMotionDisabled"
        :max-width="maxWidth"
        :remove-backdrop="isBackdropRemoved"
        class="mx-auto"      
    >            
        <div :class="themeStore.isDarkMode ? 'dark' : ''" class=" w-screem" :dir="languageStore.getLanguageDir()">
            <slot name="body"  />
        </div>                
    </Modal>
</template>

<script>
import { Modal } from "vue-neat-modal";
import 'vue-neat-modal/dist/vue-neat-modal.css'
import { ref } from 'vue';
import { useThemeStore } from "../../../store/Utilities/ThemeStore";
import { useLanguageStore } from "../../../store/Localization/LanguageStore";

export default {
    name : 'PsImageModal',
    components : {
        Modal,
    },
    props : {       
        maxWidth : {
            type : String,
            default : "100%"
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
        }
    },
    setup() {
        const isOpen = ref(false);

        const themeStore = useThemeStore();
        const languageStore = useLanguageStore();
        
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