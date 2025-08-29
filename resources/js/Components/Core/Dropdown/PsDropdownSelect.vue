<style scoped>
    .child2{
        display: none;
    }
    .parent:focus > .child > .child1{
        display: none;
    }
    .parent:focus  > .child > .child2{
        display: block;
    }


</style>

<template>
    <button
        type="button" :disabled="disabled"
        class="parent inline-flex items-center leading-5 transition ease-in-out duration-150 btn-focus"
        :id="id"
        :class="[bgColor, w,h,padding,shadow,rounded,border,hover,focus, cursor,opacity]"
        aria-haspopup="true"
        aria-expanded="true">

        <div class="w-full flex flex-row child items-center " :class="justify" >
            <div class="flex flex-row" >
                <ps-icon v-if="leftIcon!=''" :name="leftIcon" :class="leftIconTheme" />
                <p :class="disabled ? 'text-xs lg:text-sm font-medium text-secondary-300 dark:text-secondary-100' : text" v-if="placeholder==''">
                    {{selectedValue ? selectedValue : $t('ps_dropdown_select__chooseone')}}
                </p>
                <p :class="disabled ? 'text-xs lg:text-sm font-medium text-secondary-300 dark:text-secondary-100' : text" v-else>{{ selectedValue ? selectedValue : placeholder }}</p>
            </div>

            <ps-icon name="downChervon" class="child1" :class="disabled ? 'text-secondary-300 dark:text-secondary-100' : iconTheme"  />
            <ps-icon name="upChervon" class="child2" :class="disabled ? 'text-secondary-300 dark:text-secondary-100' : iconTheme"  />
        </div>

    </button>

</template>

<script>
import PsIcon from '../Icons/PsIcon.vue';
// import PsLabel from '../Label/PsLabel.vue';
export default {
    name : "PsDropdownSelect",
    components : {
        PsIcon,
        // PsLabel
    },
    props : {
        selectedValue : {
            type : String,
            default : ''
        },
        bgColor : {
            type : String,
            default :''
        },
        w: {
            type : String,
            default : 'w-full'
        },
        h: {
            type : String,
            default : '10'
        },
        id : {
            type : String
        },
        rounded : {
            type: String,
            default: ' rounded'
        },
        text : {
            type: String,
            default: 'text-xs lg:text-sm font-medium text-secondary-600 dark:text-secondary-100'
        },
        iconTheme : {
            type: String,
            default: 'text-secondary-500 dark:text-secondary-200 '
        },
        padding : {
            type: String,
            default: ' px-4 py-2 '
        },
        border : {
            type: String,
            default: 'border border-1 border-secondary-200 dark:border-secondary-800'
        },
        shadow : {
            type: String,
            default: 'shadow-sm'
        },
        justify : {
            type: String,
            default: 'flex justify-between '
        },
        hover : {
            type: String,
            default: ''
        },
        focus : {
            type: String,
            default: ' focus:outline-none focus:shadow-outline-blue text-secondary-800 dark:text-secondary-400 active:bg-primary-200 active:text-secondary-800 dark:active:bg-primary-200 dark:active:text-secondary-800'
        },
        cursor: {
            type: String,
            default: 'cursor-pointer'
        },
        opacity: {
            type: String,
            default: 'opacity-100'
        },
        leftIcon: {
            type: String,
            default: '',
        },
        leftIconTheme: {
            type: String,
            default: 'me-1',
        },
        placeholder: {
            type : String,
            default : ''
        },
        disabled:{
            type : Boolean,
            default : false
        }
    },
    setup() {
    }

}
</script>
