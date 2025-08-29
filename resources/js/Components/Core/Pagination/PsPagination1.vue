<template>
  <div class="flex items-center  rtl:space-x-reverse space-x-1">
    <a href="#" :class="[colors, border]" class="flex w-10 h-10 items-center p-3 text-gray-500 bg-secondary-100 rounded-md">
      <ps-icon name="doubleArrowLeft" w="16" h="16" :disabled="isPreviousButtonDisabled"
      @click.native="previousPage"  />
    </a>

    <a  v-for="paginationTrigger in paginationTriggers" href="#" :key="paginationTrigger" class="p-2.5 text-gray-700 bg-gray-200 rounded-md hover:bg-blue-400 hover:text-white">
        {{paginationTrigger}}
    </a>
  
    <a href="#" :class="[colors, border]" class="w-10 h-10 p-4 text-gray-500 rounded-md hover:bg-blue-400 hover:text-white">
      <ps-icon name="doubleArrowRight" w="16" h="16" :disabled="isNextButtonDisabled"
      @click.native="nextPage"  />
    </a>
  </div>
</template>

<script>
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
export default {
  name: "PsPagination1",
  components : {
    PsIcon
  },
  props: {
    visiblePagesCount: {
      type: Number,
      default: 5
    },
    currentPage: {
      type: Number,
      required: true
    },
    pageCount: {
      type: Number,
      required: true
    },
    colors: {
      type: String, 
      default: "bg-secondary-100"
    },
    borders: {
      type: String,
      default: "border-none"
    }
  },
  computed: {
    paginationTriggers() {
      const currentPage = this.currentPage
      const pageCount = this.pageCount
      const visiblePagesCount = this.visiblePagesCount
      const visiblePagesThreshold = (visiblePagesCount - 1) / 2
      const pagintationTriggersArray = Array(this.visiblePagesCount - 1).fill(0)
    },

    isPreviousButtonDisabled() {
      return this.currentPage === 1
    },
    isNextButtonDisabled() {
      return this.currentPage === this.pageCount
    }
  },
  methods: {
    nextPage() {
      this.$emit('nextPage')
    },
    previousPage() {
      this.$emit('previousPage')
    }
  }
}
</script>