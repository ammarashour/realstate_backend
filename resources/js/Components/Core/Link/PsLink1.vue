<template>
    <a :href="url" class="text-sm font-medium" :target="target" :class="textColor === '' ? 'text-secondary-500 dark:text-secondaryDark-white' : textColor">
        <slot></slot>
    </a>    
</template>

<script>
export default {
    name : 'PsLink1',
    props: {
        textColor : {
            type: String,               
            default: 'text-secondary-500 dark:text-secondaryDark-white',                
        },
        url : {
            type : String
        },
        target : {
            type : String,
            default : '_parent'
        }        
    }
}
</script>