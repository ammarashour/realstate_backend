<template>
    <div v-if="isHasLimit">
            <datepicker class="bg-primary-000 dark:bg-primaryDark-black p-3 lg:rounded-2xl rounded-xl w-32 text-secondary-500 dark:text-secondaryDark-white z-0"
            v-model="pickedDate" :lowerLimit="pickedDateProps"  @change="handleChange($event.target.value)" :disabled='isDisabled' inputFormat="MM-dd-yyyy" outputFormat="MM-dd-yyyy" />
    </div>
    <div v-else-if="isHasUpperLimit">
                <datepicker class="bg-primary-000 dark:bg-primaryDark-black p-3 lg:rounded-2xl rounded-xl w-32 text-secondary-500 dark:text-secondaryDark-white z-0"
        v-model="pickedDate" :upperLimit
        ="upperDateProps" @change="handleChange($event.target.value)" :disabled='isDisabled' inputFormat="MM-dd-yyyy" outputFormat="MM-dd-yyyy" />

    </div>
    <div v-else>
        <datepicker class="bg-primary-000 dark:bg-primaryDark-black p-3 lg:rounded-2xl rounded-xl w-32 text-secondary-500 dark:text-secondaryDark-white z-0"
        v-model="pickedDate" @change="handleChange($event.target.value)" :disabled='isDisabled' inputFormat="MM-dd-yyyy" outputFormat="MM-dd-yyyy" />
    </div>
</template>
<script>
import {onMounted, ref} from 'vue';
import Datepicker from 'vue3-datepicker';
import PsUtils from '@templateCore/utils/PsUtils';
// https://icehaunter.github.io/vue3-datepicker/#props-and-attributes


export default {
    name: "PsDatePicker",
    props : {
        pickedDateProps :{
            type: Date,
            default: new Date()
        },
        upperDateProps :{
            type: Date,
            default: new Date()
        },
        isDisabled : {
            type: Boolean,
            default: false
        },
        isHasLimit : {
            type : Boolean,
            default : true
        },
        isHasUpperLimit : {
            type : Boolean,
            default : false
        }
    },
    components : {
        Datepicker
    },
    setup(props) {
        const pickedDate = ref(new Date());
        const startDate = ref(new Date());

        function handleChange(v) {
            PsUtils.log("**** Change Happened!");
            PsUtils.log(v);
        }

        function updatePickDate(date) {
            pickedDate.value = date;
        }

        onMounted(() => {
            pickedDate.value = props.pickedDateProps;
        });

        return {
            pickedDate,
            startDate,
            handleChange,
            updatePickDate
        }
    }
}
</script>
