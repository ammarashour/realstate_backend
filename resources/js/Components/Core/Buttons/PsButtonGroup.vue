<template>
    
    <div class="flex flex-row " :class="[colors , bgradius,padding]">
        <button :type="type" :class="disabled ? [ border, shadow, textSize , justify, 'cursor-not-allowed opacity-30'] : [ border, shadow, textSize, hover, focus, cursor, opacity , justify]" 
        class="font-medium transition duration-150 ease-in-out" >
            <slot name="leftButton"/>
        </button>

        <ps-line :theme="line" />

        <ps-line v-if="twoline" class="ms-1" :theme="line"/>

        <button :type="type" :class="disabled ? [ border, shadow, textSize , justify, 'cursor-not-allowed opacity-30'] : [ border, shadow, textSize, hover, focus, cursor, opacity , justify]" 
        class="font-medium transition duration-150 ease-in-out" >
            <slot name="middleButton"/>
        </button>
        
        <ps-line :theme="line"/>

        <ps-line v-if="twoline" class="ms-1" :theme="line"/>

        <button :type="type" :class="disabled ? [ border, shadow, textSize , justify, 'cursor-not-allowed opacity-30'] : [ border, shadow, textSize, hover, focus, cursor, opacity , justify]" 
        class="font-medium transition duration-150 ease-in-out" >
            <slot name="rightButton"/>
        </button>
    </div>
    
</template>


<script>
import PsLine from "@/Components/Core/Line/PsLine.vue";

    export default {
        name: "PsButtonGroup",
        components : {
            PsLine
        },
        props: {
            type: {
                type: String,
                default: 'submit',
            },
            bgradius : {
                type: String,
                default: 'rounded'
            },
            colors : {
                type: String,
                default: 'bg-primary-500 dark:bg-primary-100 text-white dark:text-black'
            },
            padding : {
                type: String,
                default: 'py-1 px-2 '
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
                default: ''
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
            line: {
                type: String,
                default: 'border-white dark:border-black'
            },
            twoline: {
                type: Boolean,
                default: false
            },
        }
    }
</script>




