<template>
    <p class="text-sm font-medium" :class="textColor === '' ? 'text-secondary-800 dark:text-secondaryDark-white' : textColor">
        <slot />
    </p>
</template>

<script>
export default {
    name : 'PsLabel',
    props: {
        textColor : {
            type: String,
            default: 'text-secondary-800 dark:text-secondary-100',
        },
    }
}
</script>
