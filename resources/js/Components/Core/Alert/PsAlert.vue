<template>
  <div
    class="rounded py-3 px-3 text-base inline-flex items-center w-full border-t-2 "
    :class="theme"
    v-show="this.visible">
    <slot />

    <ps-icon @click="close()" :class="[textTheme]" name="close" class="text-sm ms-auto my-auto focus:shadow-none hover:text-purple-500"  />

  </div>
</template>

<script>
import { ref } from "vue";
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
export default {
  components: {
    PsIcon
  },
  props: {
    theme: {
      type: String,
      default: "bg-primary-100",
    },
    textTheme: {
      type: String,
      default: "text-primary-100",
    },
    duration: {
      type: Number,
      default: 3000
    },
    visible: {
      type: Boolean,
      default: true
    }
  },
  data() {
    const openAlertBox = ref(this.visible);

    if(this.visible){
      setTimeout(() => {
          openAlertBox.value = false;
      }, this.duration);
      this.$emit('update:visible', false)
    }

    function close() {
      if (this.visible == true) {
        return this.visible = false;
      }
    }
        
    return {
      close,
      openAlertBox
    };
  }
};
</script>
