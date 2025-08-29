<template>
  <div class="me-4">
      <ps-label class="select-none inline-flex items-center" :class="disabled ? [font,disabledTextColor] : [font,textColor]">
      <input type="checkbox"
        :disabled="disabled"
        :class="disabled ? [w,h,disabledColor] : [w,h,color]" class="form-check-input rounded" 
        :id="title"
        :checked="value"
        @input="handleInput($event.target.checked)"
      />
      <p :class="disabled ? [disabledColor] : [textColor]" >
      {{ title }}</p>
    </ps-label>
  </div>
</template>

<script>
import PsLabel from "../Label/PsLabel.vue";

export default {
  name: "PsCheckboxValue",
  components: { PsLabel },
  props: {
    value: Boolean,
    title: {
      type: String,
      default: "N.A",
    },
    w: {
      type: String,
      default: "w-5",
    },
    h: {
      type: String,
      default: "h-5",
    },
    color: {
      type: String,
      default: "text-primary-500 dark:bg-secondaryDark-dark",
    },
    "disabled": { type: Boolean, default: false },
    "disabledColor": { type: String, default: "text-secondary-300" },
    "disabledTextColor": { type: String, default: "text-secondary-300 dark:text-secondaryDark-white" },
    font : {
      type: String,               
      default: 'font-normal',            
    },  
    textColor : {
      type: String,               
      default: 'text-secondary-800 dark:text-secondary-100',            
    },  
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
