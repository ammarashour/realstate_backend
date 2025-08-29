<template>
    <ps-label class="text-xl sm:text-xl " :textColor="textColor"  >
        <slot></slot>
    </ps-label>
</template>

<script>
import { defineComponent } from 'vue';
import PsLabel from './PsLabel.vue';

export default defineComponent({
    name : 'PsLabelHeader5',
    components: { PsLabel },
    props: {
        textColor : {
            type: String,
            default: 'text-secondary-800 dark:text-secondaryDark-white',
        }
    }

});
</script>
