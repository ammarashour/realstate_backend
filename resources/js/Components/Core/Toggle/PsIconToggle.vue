<template>
    <div class="flex items-center">
        
        <button v-if="selectedValue" class="text-white" @click="toggle">
            <ps-icon name="sun" textColor="text-white" class="" w='20' h='20' viewBox="0 0 20 20"/>
        </button>
        <button v-else class="text-dark" @click="toggle">
            <ps-icon name="moon" textColor="text-white" class="" w='20' h='20' viewBox="0 0 20 20"/>
        </button>
    </div>
</template>

<script>

import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

export default {
    name: "PsIconToggle",
    components: {
        PsIcon
    },
    props: {
        selectedValue : {
            type : Boolean,
            default: false
        },
        toggleOnTheme : {
            type : String,
            default: 'bg-secondary-100 text-secondary-800 border border-secondary-100'
        },
        toggleOffTheme : {
            type : String,
            default: 'bg-secondary-800 text-secondary-100 border border-secondary-800'
        },
        toggleOnIcon: {
            type : String,
            default: 'night'
        },
        toggleOffIcon: {
            type : String,
            default: 'day'
        },
    },
    emit : ['onChange'],
    setup(props, context) {
        function toggle(){
            context.emit('onChange')
        }
        return{
            toggle
        }
    },
}
</script>
