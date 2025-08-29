<template>
    <div class="me-2">
        <input type="text" :value="language_string" @input="$emit('update:language_string', $event.target.value)" class="w-60 rounded" :placeholder="placeholder">
    </div>
</template>

<script>
    export default {
        name: "Text",
        props: {
            'language_string' : {type: String, default: ""},
            "placeholder" : { type: String, default: "Please enter " },
        },
        emits: ['update:language_string']
    }
</script>

<style scoped>

</style>
