<template>
    <div class="relative">
        <div class="flex absolute inset-y-0 left-0 items-center ps-3 pointer-events-none items-center">
            <ps-icon name="search" class='cursor-pointer '/> 
        </div>
        <input type="search" id="default-search" class="block p-2.5 ps-10 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-100 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Search" required>
        <div class="absolute inset-y-0 flex items-center ms-2 me-4 end-0">
            <slot name="icon"></slot>
        </div>
    </div>
</template>

<script>
import PsIcon from '../Icons/PsIcon.vue';

export default {
    name: "PsSearchIcon",
    components: {
        PsIcon
    },
    props: {
        "value" : { type: String, default: "" },
        "theme" : { type: String, default : "input-primary" },
        "rounded": { type: String, default: "rounded" },
        "maxlength" : { type : Number, default : 99999999 },
        "placeholder": { type: String, default: "" },
        "onInput" : Function
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