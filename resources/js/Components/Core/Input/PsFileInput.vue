<template>
  <input type="file" class="block w-full text-sm text-slate-500 file:ms-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
  @input="handleInput($event.target.value)">
</template>

<script>
export default {
    name: 'PsFileInput',
    props: {
         "onInput" : Function
    },
     setup( props , { emit}) {
        function handleInput(v) {
            emit('update:value', v);

            if(props.onInput != null) {
                props.onInput(v);
            }
        }

        return {
            handleInput
        }
    }
}
</script>

