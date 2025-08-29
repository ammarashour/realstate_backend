<template>
    <ps-label class="select-none  inline-flex items-center">
        <input type="radio" class="form-check-input focus:ring-1 me-2" :class="color" :id="title" :checked="getValue()" @change="handleInput()" />
        {{title}}
    </ps-label>
</template>

<script>
import PsLabel from "../Label/PsLabel.vue";

export default {
    name: "PsRadio",
    components: { PsLabel },
    props: {
        "value" : {
            type: Object
        },
        "selectedValue" : {
            type: Object
        },
        "title" : {
            type : String,
            default : "N.A"
        },
        onChange : Function,
        color: {
            type: String,
            default: "text-primary-500 border-gray-300 focus:ring-primary-500 hover:bg-primary-500",
            },
    },
    setup(props, {emit}) {
        function getValue() {
            let isSelected = false;
            if(props.selectedValue != undefined &&  props.value != undefined && props.selectedValue.id == props.value.id) {
                isSelected = true;
            }
            return isSelected;
        }
        function handleInput() {
            const tmpSelectedValue = props.selectedValue;

            Object.assign(tmpSelectedValue, props.value);

            emit('update:selectedValue', tmpSelectedValue);

            if(props.onChange != undefined){
                props.onChange(tmpSelectedValue);
            }

        }
        return {
            getValue,
            handleInput
        }
    }
}
</script>
