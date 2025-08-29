<template>
      <!-- <ColorPicker
        theme="light"
        :color="color"
        :sucker-hide="false"
        :sucker-canvas="suckerCanvas"
        :sucker-area="suckerArea"
        :colors-default="colors_default"
        @changeColor="changeColor"
        @openSucker="openSucker"
      /> -->
      <ColorPicker
        theme="light"
        :color="color"
        :sucker-hide="false"
        :colors-default="colors_default"
        @changeColor="changeColor"
      />
</template>

<script>
import { ref } from 'vue'
import { ColorPicker } from 'vue-color-kit'
// stylesheet
import 'vue-color-kit/dist/vue-color-kit.css'

export default {
  components: {
    ColorPicker,
  },
  props:{
    color: {
        type: String,
        default: '#000000',
    },
    colors_default: {
        type: Array,
        default: [], // or 'light'
    },
    theme: {
        type: String,
        default: 'dark', // or 'light'
    },
  },
  setup(props , { emit}){

    // let suckerCanvas = null;
    // let suckerArea = [];
    // let isSucking = ref(false);

    function changeColor(v) {
        const hexColor = v.hex;
        console.log(hexColor);
        emit('update:color', hexColor);
    }
    // function openSucker(isOpen) {
    //   if (isOpen) {
        // ... canvas be created
        // this.suckerCanvas = canvas
        // this.suckerArea = [x1, y1, x2, y2]
    //   } else {
        // this.suckerCanvas && this.suckerCanvas.remove
    //   }
    // }

    return {
        changeColor,
    //   suckerCanvas,
    //   openSucker,
    //   suckerArea,
    //   isSucking,
    }

  },
}
</script>
