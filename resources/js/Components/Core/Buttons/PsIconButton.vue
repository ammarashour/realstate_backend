<template>
    <button :type="type" :class="disabled ? [rounded, colors, padding, border, shadow, textSize , justify, 'cursor-not-allowed opacity-30'] : [rounded, colors, padding, border, shadow, textSize, hover, focus, cursor, opacity , justify]"
    class="font-medium transition duration-150 ease-in-out" >
        <ps-icon v-if="leftIcon!=''" :name="leftIcon" :class="leftPadding" />
        <slot />
        <ps-icon v-if="rightIcon!=''" :name="rightIcon" :class="rightPadding" />
    </button>
</template>

<script>
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

    export default {
        name: "PsIconButton",
        components : {
            PsIcon
        },
        props: {
            leftIcon: {
                type: String,
                default: '',
            },
            rightIcon: {
                type: String,
                default: '',
            },
            type: {
                type: String,
                default: 'submit',
            },
            rounded : {
                type: String,
                default: 'rounded'
            },
            colors : {
                type: String,
                default: 'bg-blue-500 dark:bg-blue-100 text-white dark:text-black'
            },
            padding : {
                type: String,
                default: 'py-2 px-4 '
            },
            leftPadding : {
                type: String,
                default: ''
            },
            rightPadding : {
                type: String,
                default: ''
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
