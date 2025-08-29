<template>
    <div class="me-2" v-for="permission in permissions" :key="permission.id">
        <input v-model="arr" @change="handleChange(arr,module_id)" class="me-2 border-1 border-secondary-200 rounded" type="checkbox" :id="permission.id" :value="permission.id">
        <ps-label class="text-base font-light" :for="permission.id">{{ permission.title }}</ps-label>
    </div>
</template>

<script>
import PsLabel from "@/Components/Core/Label/PsLabel.vue";

    export default {
        name: "CheckBox",
        component : {
            PsLabel
        },
        props: ['permissions','customizeHeader','module_id', 'oldData'],
        data() {
            return {
                arr : [],
                error : '',
            }
        },
        mounted() {
            if (this.oldData){
                this.oldData.map(result => {
                    if (result !== undefined && result.module_id === this.module_id) {
                        let array = result.permission_id.split(',');
                        this.arr = array;
                    }
                })
            }

        },
        methods: {
            handleChange(arr,id) {
                this.$emit('toParent', {data : arr, id : id});
            },
            handleError() {

            }
        },
    }
</script>

<style scoped>

</style>
