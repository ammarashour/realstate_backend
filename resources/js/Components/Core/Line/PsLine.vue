<template>

<div class="border border-t-0 " :class="textColor" />

</template>

<script>
export default {
    name : 'PsLine',
    props: {
        theme : {
            type: String,
            default: 'text-fePrimary-500 dark:text-feSecondary-900',            
        }
    }
}
</script>
