<template>
    <div class="w-full inline-flex justify-center border border-grey-100 leading-sm" :class="[ rounded, padding, textColor]">
        <div class="flex items-center  rtl:space-x-reverse space-x-1">
            <slot name="iconText"/>
        </div>
    </div>
</template>

<script>
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
export default {
    name : 'PsTagIcon',
    components: {
        PsIcon
    },
    props: {
        rounded: {
            type: String,
            default: "rounded"
        },
        textColor: {
            type: String,
            default: '',   
        },
        padding: {
            type: String,               
            default: 'py-1 px-2',    
        }

    }
}
</script>