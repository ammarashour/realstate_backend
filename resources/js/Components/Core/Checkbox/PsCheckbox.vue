<template>
    <ps-label class="text-base inline-flex items-center" :class="disabled ? [font,disabledTextColor] : [font,textColor]">
        <input type="checkbox"
        class="form-check-input dark:bg-secondaryDark-black"
        :disabled="disabled"
        :class="disabled ? [w,h,disabledColor] : [w,h,color,rounded]" 
        :id="title"
        :checked="getValue()" @input="handleInput($event.target.checked)" />
        <label :class="disabled ? [padding,font,disabledColor] : [padding,font,textColor]"  >{{title}}</label>
    </ps-label>
</template>

<script>
// import { PropType } from 'vue-demi';
import PsLabel from "../Label/PsLabel.vue";

// class PsCheckboxDataHolder  {
//     id: string = '';
//     name: string = '';
// }
export default {
    name: "PsCheckbox",
    components: { PsLabel },
    props: {
        "value" : {
            type: Object,
            default : {}
        },
        selectedValue : {
            type: Array,
            default: []
        },
        "title" : {
            type : String,
            default : "N.A"
        },
        w: {
            type: String,
            default: "w-5",
        },
        h: {
            type: String,
            default: "h-5",
        },
        rounded: {
            type: String,
            default: "rounded",
        },
        color: {
            type: String,
            default: "text-secondary-800 dark:text-secondary-100",
        },
        "disabled": { type: Boolean, default: false },
        "disabledColor": { type: String, default: "text-secondary-300" },
        "disabledTextColor": { type: String, default: "text-secondary-300 dark:text-secondaryDark-white" },
        font : {
            type: String,               
            default: 'font-normal',            
        },  
        padding : {
            type: String,
            default: ''
        },
        textColor : {
            type: String,               
            default: 'text-secondary-800 dark:text-secondary-100',            
        }
    },
    setup(props, { emit }) {
        function getValue() {
            let isSelected = false;
            if(props.selectedValue != null) {
                for(let i = 0; i < props.selectedValue.length; i++) {
                    if(props.selectedValue[i].id == props.value.id) {
                        isSelected = true;
                        break;
                    }
                }
            }
            return isSelected;
        }
        function handleInput(v) {
            const tmpSelectedValue = props.selectedValue;
            let isFoundKey = false;
            for(let i = 0; i<tmpSelectedValue.length ; i++) {
                if(tmpSelectedValue[i].name == props.value.name) {
                    isFoundKey = true;
                    if(!v) {
                        tmpSelectedValue.splice(i, 1);
                    }
                    break;
                }
            }

            if(!isFoundKey) {
                tmpSelectedValue.push(props.value);
            }

            emit('update:selectedValue', tmpSelectedValue);
        }

        return {
            getValue,
            handleInput
        }
    }
}
</script>























