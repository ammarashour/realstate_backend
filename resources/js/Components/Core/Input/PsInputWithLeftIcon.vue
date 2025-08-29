<template>
    <div class="relative ">
        <input :class="disabled ? [ rounded,disabledTheme, ] : [theme, rounded,defaultBorder]"
            class="mt-1 block w-full ps-10 py-2.25 text-sm shadow-sm"
            :type="type"
            :value="value"
            :disabled="disabled"
            :maxlength="maxlength"
            @input="handleInput($event.target.value)" :placeholder="placeholder"/>
        <div class="absolute inset-y-0 flex items-center ltr:ms-4 rtl:me-4 ltr:start-0 rtl:end-0">
            <slot name="icon" class="me-4"></slot>
        </div>
    </div>
</template>

<script>
// import { PsValueProvider } from '@/store/modules/core/PsValueProvider';
export default {
    name: "PsInputWithLeftIcon",
    props: {
        "value" : { type: String, default: "" },
        "type" : { type: String, default: "search" },
        "theme" : { type: String, default : "input-white text-secondary-800" },
        "rounded": { type: String, default: "rounded" },
        "maxlength" : { type : Number, default : 99999999 },
        "placeholder": { type: String, default: "" },
        "onInput" : Function,
        "disabled": { type: Boolean, default: false },
        "defaultBorder": { type: String, default: "border border-secondary-200 focus:outline-none focus:ring-1 focus:ring-primary-500" },
        "disabledTheme": { type: String, default: "text-secondary-300 border-secondary-200 shadow-none placeholder-secondary-300" },
    },
    setup(props, {emit}) {
        // const psValueHolder = PsValueProvider.psValueHolder;
        function handleInput(v) {
            emit('update:value', v);

            if(props.onInput != null) {
                props.onInput(v);
            }
        }

        return {
            handleInput,
            // psValueHolder
        };
    }
}
</script>
