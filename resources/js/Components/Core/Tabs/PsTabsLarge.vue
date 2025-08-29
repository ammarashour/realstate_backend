<template>
     <ps-button class="w-full flex justify-between border border-shadow" :colors="buttonTheme">
        <slot />  
        <slot name="icon" />
    </ps-button>
</template>

<script>
import PsButton from "@/Components/Core/Buttons/PsButton.vue";
export default {
    name: "PsTabsLarge",
    props: {
        "theme" : {
            type: String,
            default: ""
        },
        "buttonTheme": {
            type: String,
            default: "bg-gray-500"
        }
    },
    components: {
        PsButton
    }
};
</script>