<template>
    <div class="w-full inline-flex justify-center border border-grey-100 leading-sm" :class="[padding,rounded,textColor]">
        <slot />
    </div>
</template>
<script>
export default {
    name : 'PsTag',
    props: {
        textColor : {
            type: String,               
            default: 'bg-white text-secondary-500 dark:text-secondaryDark-white',            
        },
        padding: {
            type: String,               
            default: 'py-1 px-2',    
        },
        rounded: {
            type: String,               
            default: 'rounded',    
        }        
    }
}
</script>