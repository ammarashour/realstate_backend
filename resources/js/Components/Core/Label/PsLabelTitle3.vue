<template>
    <ps-label class=" lg:text-md sm:text-sm text-xs font-normal " :textColor="textColor"  >
        <slot></slot>
    </ps-label>
</template>

<script>
import { defineComponent } from 'vue';
import PsLabel from './PsLabel.vue';

export default defineComponent({
    name : 'PsLabelTitle3',
    components: { PsLabel },
    props: {
        textColor : {
            type: String,
            default: 'text-secondary-400 dark:text-secondaryDark-white',
        }
    }
});
</script>
