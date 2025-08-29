<template>

    <div class="flex flex-row " :class="[background ]">

        <div class="flex-grow-0 flex flex-row">
            <slot name="leftButton"/>

            <ps-line :theme="line" />

            <ps-line v-if="istwoline" class="ms-1" :theme="line"/>
        </div>

        <input
        class="flex-grow block w-full px-4 py-2.25 text-sm shadow-sm border border-none placeholder-secondary-500"
        :type="type"
        :value="value"
        :disabled="disabled"
        :class="disabled ? [ opacity,disabledTheme, ] : [theme,invalid, focus, opacity]"
        :maxlength="maxlength"
        :placeholder="placeholder"
        @input="handleInput($event.target.value)" />

         <div class="flex-grow-0 flex flex-row">
        
            <ps-line :theme="line"/>

            <ps-line v-if="istwoline" class="ms-1" :theme="line"/>

            <slot name="rightButton"/>
         </div>

    </div>
    
</template>

<script>
import PsLine from "@/Components/Core/Line/PsLine.vue";

export default {
    name : "PsInputGroup",
    components : {
            PsLine
        },
    props: {
        "value" : { type: String, default: "" },
        "type" : { type: String, default: "text" },
        "theme" : { type: String, default : "text-secondary-800" },
        "maxlength" : { type : Number, default : 99999999 },
        "placeholder": { type: String, default: "" },
        "disabled": { type: Boolean, default: false },
        "disabledTheme": { type: String, default: " text-secondary-300 border-secondary-200 shadow-none placeholder-secondary-300" },
        "invalid": { type: String, default: "invalid:border-pink-500 invalid:text-pink-600 focus:invalid:border-pink-500 focus:invalid:ring-pink-500" },
        "focus": { type: String, default: " focus:outline-none focus:ring-0" },
        "onInput" : Function,
        background : {
            type: String,
            default: 'bg-white dark:bg-blue-100 rounded-md border border-gray-200 shadow-sm'
        },
        opacity: {
            type: String,
            default: 'opacity-100'
        },
        line: {
            type: String,
            default: 'border-gray-200 dark:border-white'
        },
        istwoline: {
            type: Boolean,
            default: false
        },
    },

    setup( props , { emit}) {
        function handleInput(v) {
            emit('update:value', v);

            if(props.onInput != null) {
                props.onInput(v);
            }
        }

        return {
            handleInput
        }
    }
}
</script>
