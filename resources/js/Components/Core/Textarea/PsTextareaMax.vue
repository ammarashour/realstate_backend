<template>
    <div class="w-full block relative" >
        <textarea  :disabled="disabled" class=" w-full px-4 py-2 text-sm shadow-sm  placeholder-secondary-500 "
                :rows="rows"
                :class="disabled ? [ rounded,disabledTheme ] : isError ? [theme, rounded, errorBorder] : [theme, rounded, defaultBorder]"
                v-model="textAreaValue"
                :placeholder="placeholder"
                @keyup="handleInput() "
                :maxlength="maxLength" >
        </textarea>
        <!-- v-on:keyup="checkText($event)" -->
        <span class="w-auto h-8 absolute bottom-0 right-4 flex flex-row" >{{ strLength }} / {{ maxLength }}</span>
    </div>
</template>

<script>
import { ref } from 'vue'
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
            default : "white text-secondary-800"
        },
        "rounded" : {
            type: String,
            default : "rounded"
        },
        "disabled": {
            type: Boolean,
            default: false
        },
        "maxLength": {
            type: Number,
            default: 100
        },
        "defaultBorder": { type: String, default: "border border-secondary-200 focus:outline-none focus:ring-1 focus:ring-primary-500" },
        "errorBorder": { type: String, default: "border border-red-500 focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500" },
        "disabledTheme": { type: String, default: "text-secondary-300 border-secondary-200 shadow-none placeholder-secondary-300" },
    },
    setup(props , { emit}) {
        const isError = ref(false);
        const strLength = ref(0);
        const textAreaValue = ref(props.value)
        
        function handleInput() {
            if(textAreaValue.value.length <= props.maxLength){
                strLength.value = textAreaValue.value.length;
                emit('update:value', textAreaValue);
            }
        }

        return {
            handleInput,
            strLength,
            textAreaValue,
            isError
        }
    }
};
</script>