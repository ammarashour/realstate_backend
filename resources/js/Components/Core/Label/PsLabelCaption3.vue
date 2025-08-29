<template>
    <ps-label class="lg:text-md sm:text-sm text-xs" :textColor="textColor"  >
        <slot></slot>
    </ps-label>
</template>

<script>
import { defineComponent } from 'vue';
import PsLabel from './PsLabel.vue';

export default defineComponent({
    name : 'PsLabelCaption3',
    components: {
        PsLabel
    },
    props: {
        textColor : {
            type: String,
            default: 'text-secondary-800 dark:text-secondaryDark-grey',
        }
    }
});
</script>
