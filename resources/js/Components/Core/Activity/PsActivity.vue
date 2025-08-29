<template>
    <div class="rounded py-3 px-4 mb-2 text-base inline-flex items-center w-full shadow-sm" :class="[theme,h]">
        <div class="flex w-full items-center">
           <slot name="content"/>
        </div>
        <ps-icon v-if="icon" name="close"  class="text-secondary-400 text-sm ms-auto justify-end my-auto focus:shadow-none hover:text-purple-500"  />
    </div>
</template>
<script>
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
export default {
    components: {
        PsIcon
    },
    props: {
        theme: {
            type: String,
            default: "bg-white dark:bg-primary-900",
        },
        icon: {
            type: Boolean,
            default: true
        },
        h: {
            type: String,
            default: "h-auto"
        }
    },
}
</script>