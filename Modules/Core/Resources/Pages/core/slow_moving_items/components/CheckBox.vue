<template>
    <div class="w-full grid grid-cols-2">
        <div class="me-2" v-for="customizeDetail in customizeDetails.filter(customizeDetail => customizeDetail.core_keys_id === customizeHeader.core_keys_id )" :key="customizeDetail.id">
            <input v-model="arr" @change="handleChange(arr,index)" class="me-2" type="checkbox" :id="customizeDetail.id" :value="customizeDetail.id">
            <label :for="customizeDetail.id">{{ customizeDetail.name }}</label>
        </div>
    </div>
</template>

<script>
export default {
    name: "CheckBox",
    props: ['customizeDetails','customizeHeader','oldData', 'index'],
    data() {
        return {
            arr : [],
            error : '',
        }
    },
    mounted() {
        // this.oldData = [];
        if (this.oldData) {
            let array = this.oldData.split(',');
            this.arr = array;
        }
    },
    methods: {
        handleChange(arr,id) {
            // let value = this.name;
            this.$emit('toParent', {data : arr, id : id});
        },
        handleError() {

        }
    },
}
</script>

<style scoped>

</style>
