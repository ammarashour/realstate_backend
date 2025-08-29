<template>
    <div class="me-2" v-for="permission in permissions" :key="permission.id">
        <input v-model="arr" @change="handleChange(arr)" class="me-2 border-1 border-secondary-200 rounded" type="checkbox" :id="permission.id" :value="permission.id">
        <ps-label class="text-base font-light" :for="permission.id">{{ permission.title ? permission.title : permission.name }}</ps-label>
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
            if (Array.isArray(this.oldData)){
                this.oldData.map(result => {
                    if (result !== undefined) {
                        let array = result.permission_id.split(',');
                        this.arr = array;
                    }
                })
            }

            if (this.oldData) {
                let array = this.oldData.split(',');
                this.arr = array;
            }
        },
        methods: {
            handleChange(arr) {
                // let value = this.name;
                this.$emit('toParent', {data : arr});
            },
            handleError() {

            }
        },
    }
</script>

<style scoped>

</style>
