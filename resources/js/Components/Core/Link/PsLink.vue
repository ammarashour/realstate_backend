<template>
    <a :href="url" class="text-base" target="_blank" :class="textColor === '' ? 'text-secondary-500 dark:text-secondaryDark-white' : textColor" rel="noreferrer"> 
        <slot></slot>
    </a>      
</template>

<script>
export default {
    name : 'PsLink',
    props: {
        textColor : {
            type: String,               
            default: 'text-secondary-500 dark:text-secondaryDark-white',                
        },
        url : {
            type : String
        }        
    }
}
</script>
