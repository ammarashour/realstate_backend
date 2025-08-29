<template>
    <input
        class="block dark:bg-secondaryDark-black w-full px-4 py-2.25 text-sm shadow-sm placeholder-secondary-500"
        :type="type"
        :value="value"
        :disabled="disabled"
        :readonly="readonly"
        :class="disabled ? [ opacity,rounded,disabledTheme, ] : isError ? [theme, rounded, , errorBorder, opacity] : [theme, rounded, defaultBorder, opacity]"
        :maxlength="maxlength"
        :placeholder="placeholder"
        @input="handleInput($event.target.value)" />
</template>

<script>
import { ref } from 'vue';

export default {
    name : "PsInput",
    props: {
        "value" : { type: String, default: "" },
        "type" : { type: String, default: "text" },
        "theme" : { type: String, default : "text-secondary-500" },
        "rounded": { type: String, default: "rounded" },
        "maxlength" : { type : Number, default : 99999999 },
        "placeholder": { type: String, default: "" },
        "disabled": { type: Boolean, default: false },
        "readonly": { type: Boolean, default: false },
        "disabledTheme": { type: String, default: "bg-none text-secondary-300 border-secondary-300 dark:border-secondary-800 dark:text-secondary-700 shadow-none placeholder-secondary-300 dark:placeholder-secondary-700" },
        "defaultBorder": { type: String, default: "border border-secondary-200 dark:border-secondary-800 focus:outline-none focus:ring-1 focus:ring-primary-500" },
        "errorBorder": { type: String, default: "border border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500" },
        "onInput" : Function,
        opacity: {
                type: String,
                default: 'opacity-100'
            },

    },

    setup( props , { emit}) {

        const isError = ref(false);
        function handleInput(v) {
            emit('update:value', v);

            if(props.onInput != null) {
                props.onInput(v);
            }
        }

        return {
            handleInput,
            isError
        }
    }
}
</script>
