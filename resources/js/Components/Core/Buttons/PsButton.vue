<template>
    <button :type="type" :disabled="disabled" :class="disabled ? [rounded, colors, padding, border, shadow, textSize , justify, 'cursor-not-allowed opacity-30'] : [rounded, colors, padding, border, shadow, textSize, hover, focus, cursor, opacity , justify]"
    class="font-medium transition duration-150 ease-in-out" >
        <slot />
    </button>
</template>

<script>
    export default {
        name: "PsCustomButton",
        props: {
            type: {
                type: String,
                default: 'submit',
            },
            rounded : {
                type: String,
                default: 'rounded-lg'
            },
            colors : {
                type: String,
                default: 'bg-primary-500 text-white dark:text-secondaryDark-black'
            },
            padding : {
                type: String,
                default: 'py-2 px-4'
            },
            border : {
                type: String,
                default: 'border-none'
            },
            shadow : {
                type: String,
                default: 'shadow-none'
            },
            textSize : {
                type: String,
                default: 'text-sm '
            },
            justify : {
                type: String,
                default: 'flex justify-center items-center '
            },
            hover : {
                type: String,
                default: 'hover:outline-none hover:ring hover:ring-blue-100'
            },
            focus : {
                type: String,
                default: 'focus:outline-none focus:ring focus:ring-blue-300'
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




