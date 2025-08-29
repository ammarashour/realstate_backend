<template>
    <!-- <div @click="saveData()" v-if="editorData" class="np-save-btn">{{ $t('core__be_btn_save') }}</div>
    <div @click="clearData()" v-if="editorData" class="np-save-btn">{{ $t('core__be_btn_clear') }}</div> -->
        <div id="app">
            <ckeditor :editor="editor" v-model:update="editorData" @ready="onReady"  :config="editorConfig" ></ckeditor>
        </div>
        <!-- <div v-if="dataToSave"> -->
      <!-- <h4>Parsed data:</h4>
      {{ dataToSave }} -->
    <!-- </div> -->
</template>

<script>
import { watch,ref } from 'vue';

// import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
// import ckeditor5test from 'ckeditor5test/src/ckeditor';
// import EditorToobar from '@/Components/Core/Editor/EditorToobar.ts';
// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
// import CKFinder from '@ckeditor/ckeditor5-ckfinder/src/ckfinder'
import CKEditor from '@ckeditor/ckeditor5-vue';


// import Base64UploadAdapter from '@ckeditor/ckeditor5-upload/src/adapters/base64uploadadapter';


    export default {
        name: 'app',
        props: {
            value : {
                type: String,
                default: ''
            }
        },
        components : {
        ckeditor:CKEditor.component
        },
        data() {
            // return {
                // editorConfig: {
                    //         plugins: [
                        //             CKFinder
            //         ]
            //     }
            // // dataToSave: "",
            // };
            return{
                editor: DecoupledEditor,
                editorConfig: {
                ckfinder: {
                    uploadUrl:  route("privacy_policy.ckUpload"),
                },
                toolbar: {
                    items: [
                    'heading',
                    '|',
                    'bold',
                    'italic',
                    'link',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'outdent',
                    'indent',
                    '|',
                    'imageUpload',
                    'blockQuote',
                    'insertTable',
                    'mediaEmbed',
                    'undo',
                    'redo',
                    'alignment',
                    'codeBlock',
                    'fontBackgroundColor',
                    'fontColor',
                    'fontFamily',
                    'fontSize',
                    'highlight',
                    'horizontalLine',
                    'htmlEmbed',
                    'imageInsert',
                    'pageBreak',
                    'removeFormat',
                    'strikethrough',
                    'underline',
                    'style'
                    ]
                },
                language: 'cs',
                image: {
                    toolbar: [
                    'imageTextAlternative',
                    'imageStyle:inline',
                    'imageStyle:block',
                    'imageStyle:side',
                    'imageStyle:alignLeft',
                    'imageStyle:alignRight',
                    'imageStyle:alignCenter',
                    'imageStyle:alignBlockLeft',
                    'imageStyle:alignBlockRight',
                    'linkImage'
                    ]
                },
                table: {
                    contentToolbar: [
                    'tableColumn',
                    'tableRow',
                    'mergeTableCells',
                    'tableCellProperties',
                    'tableProperties'
                    ]
                },
                    fontFamily: {
                        options: [
                            'default',
                            'indieflowerregular',
                            'Arial, sans-serif',
                            'Verdana, sans-serif',
                            'Trebuchet MS',
                            'Apple Color Emoji',
                            'Segoe UI Emoji',
                            'Segoe UI Symbol',
                        ]
                    },
                licenseKey: ''
                }
            };
        },
        setup(props,{ emit}){
            const editorData = ref('');
            // const editorConfig = ref()
            editorData.value = props.value;
            watch(editorData.value,value=>{

                emit('update:value', value);
            })

            return{
                editorData,
                // editorConfig
            }
        },

        methods: {
            onReady( editor )  {
                // Insert the toolbar before the editable area.
                // let domNode = document.createRange().createContextualFragment(EditorToobar.toolbar);

                editor.ui.getEditableElement().parentElement.insertBefore(
                    editor.ui.view.toolbar.element,
                    editor.ui.getEditableElement()
                );
            },
            // saveData() {
            // this.dataToSave = this.editorData;
            // },
            // clearData() {
            // this.dataToSave = this.editorData = "";
            // },
        },
    }
</script>
