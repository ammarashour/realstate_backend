<style>
.dp__theme_dark {
    --dp-background-color: #121212;

  --dp-menu-border-color: #121212;

    /* etc */
}

.dp__theme_light {
  --dp-background-color: #ffffff;
  --dp-text-color: #1F2937;
  --dp-hover-color: #f3f3f3;
  --dp-hover-text-color: #1F2937;
  --dp-hover-icon-color: #111827;
  --dp-primary-color: #6366F1;
  --dp-primary-text-color: #FFFFFF;
  --dp-secondary-color: #111827;

  --dp-menu-border-color: #ffffff;

  --dp-disabled-color: #f6f6f6;
  --dp-scroll-bar-background: #f3f3f3;
  --dp-scroll-bar-color: #1F2937;
  --dp-success-color: #6366F1;
  --dp-success-color-disabled: #6366F1;
  --dp-icon-color: #1F2937;
  --dp-danger-color: #ff6f60;
}

.dp-custom-menu {
    border-radius: 8px;
    padding: 21px;
    overflow: hidden;
}

</style>
<template>
    <Datepicker
    :format="getFormat()"
    v-model="date"
    @update:modelValue="handleDate"
    :inline="inline"
    :autoApply="autoApply"
    :enableTimePicker="enableTimePicker"
    :range="range"
    :placeholder="placeholder"
    clearable
    menuClassName="dp-custom-menu"
    :cancelText="cancelText" :selectText="selectText"
    :dark="themeStore.isDarkMode"
    >
        <template #calendar-header="{ day }">
            <div class="text-sm font-medium text-secondary-800 dark:text-white">
            {{ day }}
            </div>
        </template>
    </Datepicker>

</template>

<script>

import { ref } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import PsIcon from "@/Components/Core/Icons/PsIcon.vue";
import { useThemeStore } from '../../../store/Utilities/ThemeStore';

export default {
    components: { Datepicker,PsIcon },
    props: {
        "value" : { type: Date, default: null },
        "inline" : { type: Boolean, default: true },
        "autoApply" : { type: Boolean, default : true },
        "enableTimePicker": { type: Boolean, default: false },
        "range": { type: Boolean, default: false },
        "placeholder": { type: String, default: "Select Date" },
        "cancelText": { type: String, default: "Close" },
        "selectText": { type: String, default: "Apply" },


    },
    setup(props, { emit}) {
        
        const themeStore = useThemeStore();
        const date = ref(props.value);
        const handleDate = (modelData) => {
            date.value = modelData;
                emit('update:value', date.value);
                emit('datepick',date.value)

        }
        function getFormat(){
            if(props.range == false){
                return 'dd/MM/yyyy';
            }else{
                return 'MM/dd';
            }
        }
        

        return {
            getFormat,
            date,
            themeStore,
            handleDate,
            // clear
        }
    }
}
</script>
