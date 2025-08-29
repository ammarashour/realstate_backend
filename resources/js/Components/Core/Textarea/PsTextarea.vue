<template>
    <div class="w-full block ">
        <textarea :disabled="disabled" class=" w-full px-4 py-2 text-sm shadow-sm dark:bg-secondaryDark-black placeholder-secondary-500 "
                    :rows="rows"
                    :value="value" 
                    :class="disabled ? [ rounded,disabledTheme, ] : isError ? [theme, rounded, errorBorder] : [theme, rounded, defaultBorder]"
                    :placeholder="placeholder"
                    @input="handleInput($event.target.value)"
        ></textarea>
    </div>
</template>

<script>
import { ref } from 'vue';

export default {
    name : 'PsTextArea',
    props: {
        "rows" : {
            type: Number,
            default: 3
        },
        "placeholder" : {
            type: String,
            default: ""
        },
        "value" : { type: String, default: "" },
        "theme" : {
            type: String,
            default : "text-secondary-500"
        },
        "rounded" : {
            type: String,
            default : "rounded"
        },
        "disabled": {
            type: Boolean,
            default: false
        },
        "disabledTheme": { type: String, default: "bg-none text-secondary-300 border-secondary-200 dark:border-secondary-800 dark:text-secondary-700 shadow-none placeholder-secondary-300 dark:placeholder-secondary-700" },
        "defaultBorder": { type: String, default: "border border-secondary-200 dark:border-secondary-700 focus:outline-none focus:ring-1 focus:ring-primary-500" },
        "errorBorder": { type: String, default: "border border-red-500 focus:border-red-500 focus:ring-1 focus:ring-red-500" },
    },
    setup(_, {emit}) {
        const isError = ref(false);
        function handleInput(v) {
            emit('update:value', v);
        }

        return {
            handleInput,
            isError
        }
    }
};
</script>
