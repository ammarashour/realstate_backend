<template>
    <div :class="[theme]" v-show="this.visible && openBannerBox">
        <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
            <div class=" sm:text-xs text-md">
                <slot />
            </div>
        </div>
    </div>
</template>
<script>
import { ref } from "vue";
export default {
    name: "PsBanner",
    props: {
        theme: {
            type: String,
            default: "bg-indigo-500"
        },
        duration: {
            type: Number,
            default: 3000
        },
        visible: {
            type: Boolean,
        },
        uicomponent: {
            type: Boolean,
        }
    },
    data() {
        const openBannerBox = ref(this.visible?? false );
        if(!this.uicomponent){
            if(this.visible){
                setTimeout(() => {
                    openBannerBox.value = false;
                }, this.duration);
                this.$emit('update:visible', false)
            }
        } else {
            openBannerBox.value = true;
        }
        
        return {
            openBannerBox
        };
    }
}
</script>