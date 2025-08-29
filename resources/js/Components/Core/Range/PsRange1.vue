<template>
  <Slider
    v-model="value"
  />
</template>

<script>
  import Slider from '@vueform/slider'

  export default {
    components: { Slider },
    data: () => ({
      value: [20, 40]
    })
  }
</script>

<style src="@vueform/slider/themes/default.css"></style>