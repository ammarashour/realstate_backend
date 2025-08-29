<template>
    <select
    class="appearance-none mt-1 block w-full text-sm shadow-sm border placeholder-slate-500 focus:outline-none focus:ring-1
disabled:bg-slate-50 disabled:text-slate-500 disabled:border-slate-200 disabled:shadow-none
invalid:border-pink-500 invalid:text-pink-600
focus:invalid:border-pink-500 focus:invalid:ring-pink-500"
    :class="[theme, rounded]"
    :value="getValue(value)" @change="handleInput($event.target.value)"  >
        <option v-for=" selectData  in dataList" :key="selectData.id"> {{selectData.name}} </option>
    </select>
</template>

<script>
// import { PropType } from 'vue-demi';
// class PsSelectDataHolder  {
//     id: string = '';
//     name: string = '';
// }

export default {
    name: "PsSelect",
    props: {
        dataList : {
            type: Array,
            default:[]
        },
        value : {
            type: Array,
            default:[]
        },
        "theme" : {
            type: String,
            default : "input-primary"
        },
        "rounded": {
            type: String,
            default: "rounded"
        },
    },
    setup(props, {emit}) {
        function getValue(id) {
            let name = "";
            for(let i = 0; i<props.dataList.length; i++) {
                if(props.dataList[i].id == id) {
                    name = props.dataList[i].name;
                    break;
                }
            }
            return name;
        }
        function handleInput(v) {
            let id = "";
            for(let i = 0; i<props.dataList.length; i++) {
                if(props.dataList[i].name == v) {
                    id = props.dataList[i].id;
                    break;
                }
            }
            emit('update:value', id);
        }

        return {
            getValue,
            handleInput
        }
    }
}
</script>

<style scoped>
</style>
