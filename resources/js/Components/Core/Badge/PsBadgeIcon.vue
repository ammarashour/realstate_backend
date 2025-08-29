<template>
    <span class="text-xs inline-block leading-none text-center whitespace-nowrap align-baseline font-bold text-white" :class="[theme, rounded, padding]">
        <div class="flex items-center">
            <ps-icon-1 :name="iconName"  w='16' h='16' class="" :theme="iconColor" />
            <slot />
            <ps-icon name="close"  w='16' h='16' class="text-sm ms-auto my-auto focus:shadow-none hover:text-purple-500"  />
        </div>
    </span>
</template>

<script>
import PsIcon1 from "@/Components/Core/Icons/PsIcon1.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

export default {
    name: "PsBadgeIcon",
    components: {
        PsIcon1,
        PsIcon
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
            default: "px-2 py-1"
            
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

