<template>
    <span class="text-xs inline-block leading-none text-center whitespace-nowrap align-baseline font-bold text-white" :class="[theme, rounded, padding]">
        <div class="flex items-center gap-1">
            <ps-icon-1 :name="iconName" w='16' h='16'  :theme="iconColor" />
            <slot />
            <ps-icon-1 name="close"  w='16' h='16'  class="text-xxs ms-auto my-auto focus:shadow-none hover:text-purple-500" theme="#FFFFFF" />
        </div>
    </span>
</template>

<script>
import PsIcon1 from "@/Components/Core/Icons/PsIcon1.vue";
export default {
    name: "PsBadgeIcon1",
    components: {
        PsIcon1
    },
    props: {
        theme: {
            type: String,
            default: "badge-primary"
        },
        rounded: {
            type: String,
            default: "rounded-full"
        },
        padding: {
            type: String,
            default: "px-2 py-1.5"
            
        },
        iconName: {
            type: String,
            default: "badge"
        },
        iconColor: {
            type: String,
            default: "#4B5563"
        }
    }
}
</script>

