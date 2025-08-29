<template>
    <!-- <div @click="saveData()" v-if="editorData" class="np-save-btn">{{ $t('core__be_btn_save') }}</div>
    <div @click="clearData()" v-if="editorData" class="np-save-btn">{{ $t('core__be_btn_clear') }}</div> -->
    <!-- <div id="app">
            <ckeditor :editor="editor" v-model="editorData" @ready="onReady"  :config="editorConfig" ></ckeditor>

        </div> -->
    <div class="form-group row">
        <div id="app">
            <div class="document-editor__toolbar"></div>
            <div class="document-editor__editable-container">
                <ckeditor :editor="editor" v-model="editorData" @ready="onReady" :config="editorConfig"></ckeditor>
            </div>
        </div>
    </div>
    <!-- <div v-if="dataToSave"> -->
    <!-- <h4>Parsed data:</h4>
      {{ dataToSave }} -->
    <!-- </div> -->


    <!-- <table><tbody><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>1</td><td>2</td><td>&nbsp;</td><td>3</td><td>&nbsp;</td><td>asdfasdfasfasfdasdfazzxcvzxcvzxcv</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr></tbody></table> -->
</template>

<script>
import { watch, ref } from 'vue';
import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
//import EssentialsPlugin from "@ckeditor/ckeditor5-essentials/src/essentials";
import CKEditor from '@ckeditor/ckeditor5-vue';

// import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    name: 'app',
    props: {
        modelValue: {
            type: String,
            default: ''
        },
        url: String,
        enableImageUpload: {
            type: Boolean,
            default: true
        }
    },
    components : {
        ckeditor:CKEditor.component
    },
    mediaEmbed: {previewsInData: true},
    data(props) {
        return {
            editor: DecoupledEditor,
            editorConfig: {
                ckfinder: {

                    uploadUrl: props.url + '?_token=' + this.$page.props.csrf,
                    options: {
                        resourceType: 'Images'
                    }
                },
                //plugins: [ EssentialsPlugin],
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
                        props.enableImageUpload ? 'imageUpload' : '',
                        'blockQuote',
                        // 'insertTable',
                        'mediaEmbed',
                        'undo',
                        'redo',
                        'alignment',
                        'fontBackgroundColor',
                        'fontColor',
                        'fontFamily',
                        'fontSize',
                        'strikethrough',
                        'underline',
                    ]
                },
                mediaEmbed: { previewsInData: true },

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
                heading: {
                    options: [
                        { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                        { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                        { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' }
                    ]
                },
                fontSize: {
                    options: [
                        {
                            title: 'tiny',
                            model: '12px'
                        },
                        {
                            title: 'small',
                            model: '16px'
                        },
                        {
                            title: 'big',
                            model: '22px'
                        },
                        {
                            title: 'huge',
                            model: '29px'
                        },

                    ]
                },


                licenseKey: ''
            }
        };
    },
    setup(props, { emit }) {
        const editorData = ref(props.modelValue || '');

        watch(editorData, () => {
            emit('update:modelValue', editorData.value);

        })

        return {
            editorData,

        }
    },

    methods: {
        onReady(editor) {
            console.log(editor)
            const toolbarContainer = document.querySelector('.document-editor__toolbar');
            let options = [
                'textsm',
                'textxs'
            ]

            // editor.config._config.fontSize.options= options;
            // console.log(editor.ui.view.toolbar.element)
            // editor.execute( 'fontSize', { value: 'huge' } );

            toolbarContainer.appendChild(editor.ui.view.toolbar.element);


            editor.ui.getEditableElement().parentElement.insertBefore(
                editor.ui.view.toolbar.element,
                editor.ui.getEditableElement()
            );
        },

    },
}
</script>

<style>
h1 {
    display: block;
    font-size: 2em;
    margin-top: 0.67em;
    margin-bottom: 0.67em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

h2 {
    display: block;
    font-size: 1.5em;
    margin-top: 0.83em;
    margin-bottom: 0.83em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

h3 {
    display: block;
    font-size: 1.17em;
    margin-top: 1em;
    margin-bottom: 1em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

h4 {
    display: block;
    font-size: 1em;
    margin-top: 1.33em;
    margin-bottom: 1.33em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

h5 {
    display: block;
    font-size: .83em;
    margin-top: 1.67em;
    margin-bottom: 1.67em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

h6 {
    display: block;
    font-size: .67em;
    margin-top: 2.33em;
    margin-bottom: 2.33em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
}

ol,
ul {
    margin-left: 2em;
}

.document-editor__editable-container {
    border: 1px solid var(--ck-color-base-border);
    border-radius: var(--ck-border-radius);

    /* Set vertical boundaries for the document editor. */
    max-height: 400px;

    /* This element is a flex container for easier rendering. */
    display: flex;
    flex-flow: column nowrap;
}
</style>
