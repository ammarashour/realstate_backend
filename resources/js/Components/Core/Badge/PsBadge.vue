<template>
    <span :style="{color : textColorCode, background : bgColorCode}" class="text-xs font-medium inline-block leading-none text-center whitespace-nowrap align-baseline" :class="[theme, rounded, padding]">
        <slot />
    </span>
</template>

<script>

export default {
    name: "PsBadge",
    props: {
        theme: {
            type: String,
            default: "text-green-500 bg-green-100"
        },
        rounded: {
            type: String,
            default: "rounded"
        },
        padding: {
            type: String,
            default: "p-1.5"
        },
        textColorCode: {
            type: String,
            default: "#000000"
        },
        bgColorCode: {
            type: String,
            default: "#000000" + "20"
        }
    }
}
</script>

