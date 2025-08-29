<template>
<div> </div>
<!-- <ckeditor :editor="editor" v-model="editorData"></ckeditor> -->
</template>

<script>
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import CKEditor from '@ckeditor/ckeditor5-vue';

export default {
    components : {
        ckeditor:CKEditor.component
    },
    data() {
            return {
                // editor: ClassicEditor,
                editorData: ''
            };
        }
}
</script>

