<template>
    <div class="w-full lg:w-large lg:mx-auto px-4 md:px-6 lg:px-0 xl:w-feLarge">
        <slot name="content" />
    </div>
</template>

<script lang='ts'>

export default {
    name : "PsContentContainer",
    setup() {
        return {
        }
    }
}
</script>
