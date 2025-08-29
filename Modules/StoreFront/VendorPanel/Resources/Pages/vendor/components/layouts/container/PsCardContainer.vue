<template>
    <div class="flex items-center justify-between leading-none p-4">
        <div class="flex items-center no-underline hover:underline text-feAchromatic-900">
            <slot name="cardtitle" />
        </div>
    </div>
    <hr>
    <div>
        <slot name="cardbody" />
    </div>
</template>
<script lang='ts'>
export default {
    name : "PsCardContainer"
}
</script>
