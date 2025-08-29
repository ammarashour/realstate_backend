<template>
  <div class="me-4">
      
    <input
      type="radio"
      class="w-4 h-4 focus:ring-1 bg-gray-100 dark:bg-gray-700 dark:border-gray-600
      "
      :disabled="disabled"
      :class="[disabled ? 'text-primary-400 border-gray-400 focus:ring-primary-400' : color]"
     
      :id="title"
      :checked="title === value"
      @change="handleInput(title)"
    />
    <ps-label :for="title" class="ms-2 select-none inline-flex items-center"   :class="[disabled ? 'text-opacity-50': '']">
      {{ title_label == "" ? title : title_label }}
    </ps-label>
  </div>
</template>

<script>
import PsLabel from "../Label/PsLabel.vue";

export default {
  name: "PsRadioValue",
  components: { PsLabel },
  props: {
    value: String,
    title: {
      type: String,
      default: "",
    },
    title_label:{
        type:String,
        default:"",
    },
    color: {
      type: String,
      default: "text-primary-500 border-gray-300 focus:ring-primary-500",
    },
    disabled:{
        type:Boolean,
        default:false
    }
  },
  setup(_, { emit }) {
    function handleInput(v) {
      emit("update:value", v);
    }

    return {
      handleInput,
    };
  },
};
</script>
