<template>
  <Bubble
    :chart-options="chartOptions"
    :chart-data="chartData"
    :chart-id="chartId"
    :dataset-id-key="datasetIdKey"
    :plugins="plugins"
    :css-classes="cssClasses"
    :styles="styles"
    :width="width"
    :height="height"
  />
</template>

<script>
import { Bubble } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, PointElement, LinearScale } from 'chart.js'

ChartJS.register(Title, Tooltip, Legend, PointElement, LinearScale)

export default {
  name: 'BubbleChart',
  components: { Bubble },
  props: {
    chartId: {
      type: String,
      default: 'bubble-chart'
    },
    datasetIdKey: {
      type: String,
      default: 'label'
    },
    width: {
      type: Number,
      default: 300
    },
    height: {
      type: Number,
      default: 100
    },
    cssClasses: {
      default: '',
      type: String
    },
    styles: {
      type: Object,
      default: () => {}
    },
    plugins: {
      type: Array,
      default: () => []
    },
    chartData: {
      type: Object,
      default: () => {}
    },
    chartOptions: {
      type: Object,
      default: () => {}
    }
  },
}
</script>
