<template>
  <div class="overflow-hidden lg:rounded-2xl rounded-xl dark:bg-gray-900  dark:border-black p-3" 
    :class="[enabledHover ? 'hover:shadow-lg' : '', cursorPointer?'cursor-pointer':'', theme] " key="">
    <div class="max-w-sm rounded overflow-hidden shadow-lg">
        <div class="relative">
            <slot name="relative"></slot>
        </div>
        <slot class=""></slot>
    </div>
  </div>
</template>

<script>
export default {
    name : "PsCard1",
    props: {
        enabledHover : {
            type : Boolean,
            default : false 
        },
        cursorPointer : {
            type : Boolean,
            default : false 
        },
        theme: {
            type : String,
            default : '' 
        },
    }
};
</script>