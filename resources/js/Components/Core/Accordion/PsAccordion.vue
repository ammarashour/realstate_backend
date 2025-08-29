<template>
<div>
    <div class="py-4 px-4 " :class="theme">
        <div class="flex flex-row items-center cursor-pointer"  @click="clickTitle" :class="titletheme">
            <ps-icon name="downChervon"  v-if="show"  />
            <ps-icon name="rightChervon" v-if="!show" />
            <span class="ps-4 flex-grow-0 " >
                <slot name="title" />
            </span>
        </div>
        <div v-if="show" class="mt-4 transition-all duration-1000 " >
            <span :class="descriptiontheme">
                <slot name="description" />
            </span>
        </div>
    </div>
</div>
</template>

<script>
import { ref } from 'vue';
import PsLabel from "@/Components/Core/Label/PsLabel.vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";

export default {
    name: "PsAccordion",
    props: {
        theme: {
            type: String,
            default: 'border-b border-b-indigo-700',
        },
        titletheme: {
            type: String,
            default: 'text-sm font-semibold text-secondary-500 dark:text-secondaryDark-accent',
        },
        descriptiontheme: {
            type: String,
            default: 'text-sm leading-6 text-secondary-500 dark:text-secondaryDark-accent',
        },
    },
    components: {
        PsLabel,
        PsIcon
    },
    setup() {
        const show = ref(false);
        function clickTitle(){
            show.value = !show.value;
        }
        return{
            show,
            clickTitle
        }
    },
}
</script>
