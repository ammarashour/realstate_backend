<template>
    <!--<input type="range" list="tickmarks" value="50" min="0" max="100" class="w-full">-->
    <input type="range" min="0" max="100" step="5" list="tickmarks" class="w-full h-1 range-sm">
    <datalist id="tickmarks" class="inline-flex range">
        <option value="0">0</option>
        <option value="10">10</option>
        <option value="20">20</option>
        <option value="30">30</option>
        <option value="40">40</option>
        <option value="50">50</option>
        <option value="60">60</option>
        <option value="70">70</option>
        <option value="80">80</option>
        <option value="90">90</option>
        <option value="100">100</option>
    </datalist>
</template>
<style scope>


.range option {
  margin: 0;
  width: 42px;
  text-align: center;
}

.range option:first-child {
  margin-left: -8px;
}

.range option:last-child {
  margin-right: -8px;
}
</style>
<script>

export default {
    name: "PsInputRange",
}
</script>
