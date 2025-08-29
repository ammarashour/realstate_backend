<template>
    <button :type="type" :disabled="disabled" :class="disabled ? [ colors, shadow, textSize, hover, focus, 'cursor-not-allowed opacity-30',padding] : [ colors, shadow, textSize, hover, focus, cursor, opacity,padding]"
    class="font-medium transition duration-150 ease-in-out" >
        <slot />
    </button>
</template>

<script>
    export default {
        name: "PsTextButton",
        props: {
            type: {
                type: String,
                default: 'submit',
            },
            padding : {
                type: String,
                default: 'py-2 px-4'
            },
            colors : {
                type: String,
                default: 'text-blue-500 dark:text-white'
            },
            shadow : {
                type: String,
                default: 'shadow-none'
            },
            textSize : {
                type: String,
                default: 'text-sm '
            },
            hover : {
                type: String,
                default: ''
            },
            focus : {
                type: String,
                default: ''
            },
            cursor: {
                type: String,
                default: 'cursor-pointer'
            },
            opacity: {
                type: String,
                default: 'opacity-100'
            },
            disabled: {
                type: Boolean,
                default: false
            },
        }
    }
</script>
