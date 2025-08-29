<template>
    <div class="relative">
        <input
        class="w-full py-2.5 px-4 text-sm shadow-sm text-secondary-800 dark:text-secondary-100 focus:outline-none focus:ring-1
                    "
        :class="disabled ? [ rounded,disabledTheme, padding] : [theme, rounded,defaultBorder,placeholderColor, padding]"
        :disabled="disabled"
         :type="type" :value="value" :maxlength="maxlength" @input="handleInput($event.target.value)" :placeholder="placeholder"/>
        <div class="absolute inset-y-0 flex items-center ltr:ms-2 rtl:me-2 ltr:me-4 rtl:ms-4 ltr:right-0 rtl:left-0">
            <slot name="icon"></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "PsInputWithRightIcon",
    props: {
        "value" : { type: String, default: "" },
        "type" : { type: String, default: "text" },
        "theme" : { type: String, default : "bg-secondary-100 dark:bg-secondary-900 text-secondary-800 dark:text-secondary-100" },
        "rounded": { type: String, default: "rounded-lg" },
        "maxlength" : { type : Number, default : 99999999 },
        "placeholder": { type: String, default: "" },
        "placeholderColor": {type: String, default: "placeholder-secondary-800 dark:placeholder-secondary-400"},
        "onInput" : Function,
        "disabled": { type: Boolean, default: false },
        "padding": { type:String, default: "py-2.25 px-4"},
        "defaultBorder": { type: String, default: "border border-secondary-200 focus:outline-none focus:ring-1 focus:ring-primary-500" },
        "disabledTheme": { type: String, default: "text-secondary-300 border-secondary-200 shadow-none placeholder-secondary-300" },
    },
    setup(_, {emit}) {
        // const psValueHolder = PsValueProvider.psValueHolder;
        function handleInput(v) {
            emit('update:value', v)
        }

        return {
            handleInput,
            // psValueHolder
        }
    }
}
</script>
