<template>
    <ps-label class="lg:text-sm sm:text-xs text-xxs font-medium " :textColor="textColor"  >
        <slot></slot>
    </ps-label>
</template>

<script>
import { defineComponent } from 'vue';
import PsLabel from './PsLabel.vue';

export default defineComponent({
    name : 'PsLabelTitle4',
    components: { PsLabel },
    props: {
        textColor : {
            type: String,
            default: 'text-secondary-800 dark:text-secondaryDark-white',
        }
    }
});
</script>
