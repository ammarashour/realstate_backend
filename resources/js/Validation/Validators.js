export default function useValidators() {

    const isEmpty = (fieldName, fieldValue, errorMessage = '',customFieldName = '') => {
        if(customFieldName != ''){
            errorMessage = `The ${customFieldName} field is required.`
        }
        if(errorMessage == '' && !customFieldName){
            errorMessage = `The ${String(fieldName).replace(/_/g, " ")} field is required.`
        }
        return !fieldValue ? errorMessage : "";
    }

    const isZero = (fieldName, fieldValue, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The ${String(fieldName).replace(/_/g, " ")} field is required.`
        }
        return fieldValue == '0' ? errorMessage : "";
    }

    const minLength = (fieldName, fieldValue, min, errorMessage = '',customFieldName) => {
        if(customFieldName != ''){
            errorMessage = `The ${customFieldName} must be at least ${min} characters.`
        }
        if(errorMessage == ''&& !customFieldName){
            errorMessage = `The ${fieldName.replace(/_/g, " ")} field must be at least ${min} characters long.`
        }
        return fieldValue.length < min ? errorMessage : "";
    }

    const maxLength = (fieldName, fieldValue, max, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The ${fieldName.replace(/_/g, " ")} field must be at most ${max} characters long.`
        }
        return fieldValue.length > max ? errorMessage : "";
    }

    const isEmail = (fieldName, fieldValue, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The input is not a valid ${fieldName.replace(/_/g, " ")} address.`
        }
        let re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return !re.test(fieldValue) ? errorMessage : "";
    }

    const isNum = (fieldName, fieldValue, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The ${fieldName.replace(/_/g, " ")} field only have numbers.`
        }
        let isNum = /^\d+$/.test(fieldValue);
        return !isNum ? errorMessage : "";
    }

    const isPrice = (fieldName, fieldValue, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The ${fieldName.replace(/_/g, " ")} field is invalid.`
        }
        let isPrice = /^[-]*\d+(,\d{3})*(\.\d*)?$/.test(fieldValue);
        return !isPrice ? errorMessage : "";
    }

    const minNum = (fieldName, fieldValue, min, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The ${fieldName.replace(/_/g, " ")} field must be greater than ${min}.`
        }
        return fieldValue < min ? errorMessage : "";
    }

    const maxNum = (fieldName, fieldValue, max, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The ${fieldName.replace(/_/g, " ")} field must be less than ${max}.`
        }
        return fieldValue > max ? errorMessage : "";
    }

    const isLatitude = (fieldName, fieldValue, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The ${fieldName.replace(/_/g, " ")} field must be between -90 and 90 degree inclusive.`
        }
        return fieldValue < -90 || fieldValue > 90 ? errorMessage : "";
    }

    const isLongitude = (fieldName, fieldValue, errorMessage = '') => {
        if(errorMessage == ''){
            errorMessage = `The ${fieldName.replace(/_/g, " ")} field must be between -180 and 180 degree inclusive.`
        }
        return fieldValue < -180 || fieldValue > 180 ? errorMessage : "";
    }

    // for only number input validate at keypress
    // const onlyNumber = ($e) => {
    //     let keyCode = ($e.keyCode ? $e.keyCode : $e.which);
    //     if ((keyCode < 48 || keyCode > 57) && keyCode !== 46 && keyCode !== 45) { // 46 is dot, 45 is dash
    //         $e.preventDefault();
    //     }
    // }

    return { isEmpty, isZero, minLength, maxLength, isEmail, isNum, isPrice, minNum, maxNum, isLatitude, isLongitude }
}
