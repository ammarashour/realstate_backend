<template>
    <div class="relative mx-2 w-full">
        <div :class="[theme,border]" class="text-xs rounded px-4 py-2 right-0 bottom-full" >
            <slot />
        </div>
    </div>
</template>

<script>

export default {
    name: 'PsTooltips',
    props: {
        theme: {
            type: String,
            default : "bg-black text-white"
        },
        border:{
            type: String,
            default : "border-none"
        }

    }
}
</script>