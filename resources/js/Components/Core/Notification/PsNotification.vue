<template>
  <div
    class="rounded-none py-3 px-4 mb-3 text-base inline-flex items-center w-full border-s-2 "
    :class="theme"
    v-show="visible"
  >
    <slot />
    
    <ps-icon :textColor="textTheme" name="close" class="text-sm ms-auto my-auto focus:shadow-none hover:text-purple-500"  />
    
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
      default: "bg-white",
    },
    textTheme: {
      type: String,
      default: "text-primary-100",
    }
  },
  setup() {
    const visible = ref(true);

    function close() {
      if (this.visible == true) {
        this.visible = false;
      }
    }

    return {
      visible,
      close,
    };
  },
};
</script>
