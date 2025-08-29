<template>
    <div class="m-1 me-2 relative flex justify-center items-center" :class="[w,h,rounded,theme]">
        <slot />
    </div>
</template>

<script>
export default {
    name: "PsAvtar",
    props: {
        h: {
            type: String,
            default : "w-12"
        },
        w: {
            type: String,
            default : "h-12"
        },
        rounded: {
            type: String,
            default : "rounded-full"
        },
        theme: {
            type: String,
            default : " bg-indigo-500"
        }
    }
}
</script>