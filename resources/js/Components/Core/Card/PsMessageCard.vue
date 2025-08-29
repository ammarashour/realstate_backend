<template>
    <div :class="[computedTheme]" class="rounded-md" v-show="openBannerBox">
        <div class="px-3 py-3 mx-auto max-w-7xl sm:px-6 lg:px-8 ">
            <div class=" sm:text-xs text-md">
                <slot />
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, computed } from "vue";

const props = defineProps({
    flag: {
        type: String,
        default: ''
    },
    theme: {
        type: String,
        default: 'bg-red-500'
    },
    visible: {
        type: Boolean,
    },
});

const openBannerBox = ref(props.visible);
           
function show() {
    openBannerBox.value = true;
}
    
const computedTheme = computed(() => {
    // If theme is provided, use it, otherwise compute it based on flag
    return (props.flag === 'danger' ? 'bg-red-500' :
                        props.flag === 'warning' ? 'bg-yellow-500' :
                        'bg-green-500');
}); 

defineExpose({
  show
});

</script>
