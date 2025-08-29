<template>
    <ps-label class="lg:text-sm sm:text-xs text-xxs font-light" :textColor="textColor"  >
        <slot/>
    </ps-label>
</template>

<script>
import { defineComponent } from 'vue';
import PsLabel from './PsLabel.vue';

export default defineComponent({
    name : 'PsLabelCaption2',
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
