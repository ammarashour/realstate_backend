<template>
  <div
    @dragenter.prevent="toggleActive"
    @dragleave.prevent="toggleActive"
    @dragover.prevent
    @drop.prevent="toggleActive($event)"
    class="dropzonecustom px-2 border-dotted border-2 border-secondary-500 dark:border-secondary-700"
    :class="{ 'active-dropzonecustom': active, ' dark:bg-secondaryDark-black': disabled, ' dark:bg-secondaryDark-black': !disabled}"
  >
    <slot></slot>
  </div>
</template>

<script>
import { ref } from "vue";

export default {
    name: "PsDraggable",
    props: {
        'disabled': { type: Boolean, default: false },
    },
    setup() {
        const active = ref(false);
        const toggleActive = (e) => {
          
          let files = e.dataTransfer.files
          console.log(files);
        active.value = !active.value;
        };
        return { active, toggleActive };
    },
};
</script>

<style>
.dropzonecustom {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  row-gap: 16px;
  transition: 0.3s ease all;
}
.active-dropzonecustom {
  color: #fff;
  border-color: #fff;
  background-color: #41b883;
}
</style>