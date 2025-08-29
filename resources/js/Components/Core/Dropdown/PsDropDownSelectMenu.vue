<template>
    <button :type="type" :class="disabled ? [rounded, colors, padding, border, shadow, textSize , 'cursor-not-allowed opacity-30'] : [rounded, colors, padding, border, shadow, textSize, hover, focus, cursor, opacity ]" 
    class=" transition ease-in-out duration-150 btn-focus flex justify-between items-center" >
        <div class="flex flex-row  rtl:space-x-reverse space-x-1 items-center">
            <ps-icon v-if="leftIcon!=''" :name="leftIcon" class="my-auto" />
            <slot />
        </div>
        <ps-icon v-if="rightIcon!=''" :name="rightIcon" class="text-center mt-1" />
    </button>
</template>

<script>
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

    export default {
        name: "PsDropDownSelectMenu",
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
                default: 'button',
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
                default: 'py-2 px-2 '
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
            hover : {
                type: String,
                default: ''
            },
            focus : {
                type: String,
                default: 'focus:outline-none focus:ring focus:ring-blue-100'
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