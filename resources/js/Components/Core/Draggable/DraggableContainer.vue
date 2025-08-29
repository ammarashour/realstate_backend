<template>
  <!-- <draggable class="flex flex-nowrap" :list="list" @change="log"> -->
    <slot></slot>
  <!-- </draggable> -->
</template>

<script>
import { defineComponent } from 'vue'
// import { VueDraggableNext } from 'vue-draggable-next'

export default defineComponent({
  components: {
    // draggable: VueDraggableNext,
  },
  props : {
    list : [] as any ,
},
  data() {
   return {
      enabled: true,
      dragging: false,
    }
  },
  methods: {
    log(event) {
    },
  },
})
</script>

<style scoped></style>
