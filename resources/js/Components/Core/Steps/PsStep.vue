<template>
    <div class="w-full items-center justify-center">
        <div class="flex flex-row justify-center mt-4 ">
            <div class="w-full bg-gray-200 rounded-full my-auto h-0.5 dark:bg-gray-700"></div>
            <div class="rounded-full bg-white p-1.5 mx-2" :class="theme">
                <slot name="icon" />
            </div>
            <div class="w-full bg-gray-200 rounded-full my-auto h-0.5 dark:bg-gray-700"></div>
        </div>
        <slot name="text" />
    </div>
</template>
<script>

export default {
    name: "PsStep",
    props: {
        theme: {
            type: String,
            default : ""
        }
    }
}
</script>