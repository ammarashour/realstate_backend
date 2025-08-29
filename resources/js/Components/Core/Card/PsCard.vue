<template>
  <div class="dark:bg-secondary-900 dark:border-black"
    :class="[enabledHover ? 'hover:shadow-lg' : '', cursorPointer?'cursor-pointer':'', theme, rounded] " key="">
    <slot class=""></slot>
  </div>
</template>

<script>
export default {
    name : "PsCard",
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
        rounded: {
            type: String,
            default: 'lg:rounded-xl rounded'
        }
    }
};
</script>
