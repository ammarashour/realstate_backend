<script setup>
import { ref, onMounted, watch, defineComponent } from 'vue'
import { Head, useForm, usePage } from "@inertiajs/vue3";
import Dropzone from "dropzone";
import "dropzone/dist/dropzone.css";
import Sortable from 'sortablejs';

const props = defineProps({
    images: Object,
    edit_mode: {
        type: Number,
        default: 0,
    },
    item_id: {
        type: Number,
        default: 0,
    },
    max_image_upload: {
        type: Number,
    },
    autoProcessQueue: {
        default: false
    },
    dir: String
})
const caption = ref();

defineExpose({
    autoProcessQueuestart,
    acctiveFiles,
    currentFiles,
});


const dropRef = ref(null)
const emit = defineEmits(['clicked']);
let myDropzone = ref();
let form = useForm({

    extra_caption: [],

})
const customPreview = `

<div class="dz-preview dz-file-preview " >
  <div class="dz-image flex justify-center items-center"><img data-dz-thumbnail class="w-full"/></div>
  <div class="dz-details">
    <div class="dz-size"><span data-dz-size></span></div>
    <div class="dz-filename"><span data-dz-name></span></div>
  </div>
  <div class="dz-progress mt-2">
    <span class="dz-upload" data-dz-uploadprogress></span>
  </div>
  <div class="dz-error-message"><span data-dz-errormessage></span></div>
  <div class="dz-success-mark">
    <svg
      width="54"
      height="54"
      viewBox="0 0 54 54"
      fill="white"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M10.2071 29.7929L14.2929 25.7071C14.6834 25.3166 15.3166 25.3166 15.7071 25.7071L21.2929 31.2929C21.6834 31.6834 22.3166 31.6834 22.7071 31.2929L38.2929 15.7071C38.6834 15.3166 39.3166 15.3166 39.7071 15.7071L43.7929 19.7929C44.1834 20.1834 44.1834 20.8166 43.7929 21.2071L22.7071 42.2929C22.3166 42.6834 21.6834 42.6834 21.2929 42.2929L10.2071 31.2071C9.81658 30.8166 9.81658 30.1834 10.2071 29.7929Z"
      />
    </svg>
  </div>
  <div class="dz-error-mark">
    <svg
      width="54"
      height="54"
      viewBox="0 0 54 54"
      fill="white"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        d="M26.2929 20.2929L19.2071 13.2071C18.8166 12.8166 18.1834 12.8166 17.7929 13.2071L13.2071 17.7929C12.8166 18.1834 12.8166 18.8166 13.2071 19.2071L20.2929 26.2929C20.6834 26.6834 20.6834 27.3166 20.2929 27.7071L13.2071 34.7929C12.8166 35.1834 12.8166 35.8166 13.2071 36.2071L17.7929 40.7929C18.1834 41.1834 18.8166 41.1834 19.2071 40.7929L26.2929 33.7071C26.6834 33.3166 27.3166 33.3166 27.7071 33.7071L34.7929 40.7929C35.1834 41.1834 35.8166 41.1834 36.2071 40.7929L40.7929 36.2071C41.1834 35.8166 41.1834 35.1834 40.7929 34.7929L33.7071 27.7071C33.3166 27.3166 33.3166 26.6834 33.7071 26.2929L40.7929 19.2071C41.1834 18.8166 41.1834 18.1834 40.7929 17.7929L36.2071 13.2071C35.8166 12.8166 35.1834 12.8166 34.7929 13.2071L27.7071 20.2929C27.3166 20.6834 26.6834 20.6834 26.2929 20.2929Z"
      />
    </svg>
  </div>
</div>
`

onMounted(function (e) {

    if (dropRef.value !== null) {
        // Dropzone.autoDiscover = false;
        let sub_domain_url = '';
        let clearSlash = props.dir;

        let subFolder = clearSlash.replaceAll("\\", "");
        
        if (import.meta.env.PROD) {

            if (subFolder != null && subFolder != '') {
                sub_domain_url = '/' + props.dir;
            }
            else {
                sub_domain_url = '';
            }


        } else {
            sub_domain_url = '';
        }
        myDropzone.value = new Dropzone(dropRef.value, {

            previewTemplate: customPreview,
            url: sub_domain_url + '/item/upload-multi-images',
            method: 'Post',
            maxFiles: props.max_image_upload,
            thumbnailWidth: 250,
            thumbnailHeight: 250,

            headers: {
                'X-CSRF-TOKEN': usePage().props.csrf,
            },
            acceptedFiles: "image/jpeg,image/png,image/jpg",
            autoProcessQueue: false,
            addRemoveLinks: true,


            init: function (file) {

                this.on("thumbnail", function (file, dataUrl) {
                    var node = file.previewElement.childNodes[1].childNodes[0];
                })

                this.on('queuecomplete', function () {

                    // alert(props.edit_mode);

                        // alert("here");
                        if (this.getActiveFiles().length == 0) {
                            emit("queueComplete", true);
                        }


                });


                this.on(
                    "addedfile", function (file) {
                        var caption = file.caption == undefined ? "" : file.caption;
                        file._captionLabel = Dropzone.createElement("<div class='flex mt-6'><p>File Info:</p>")
                        if (myDropzone.value.getAcceptedFiles().length == 0) {
                            const newElement = document.createElement('div');
                            newElement.innerHTML = '<label for="image" class="image-label text-xxs" id="defaultText">Default</label>';
                            console.log(file.previewElement.firstElementChild);
                            file.previewElement.firstElementChild.appendChild(newElement)
                            file._defaultLabel = newElement;
                        }
                        else {
                            file._defaultLabel = '';
                        }
                        file._captionBox = Dropzone.createElement("<input class='block dark:bg-feSecondaryDark-black w-full px-3 py-2 text-sm shadow-sm dark:placeholder-feSecondary-300 placeholder-feSecondary-500 text-feSecondary-500 dark:text-feSecondary-400 rounded border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focus:border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400 opacity-100' id='" + file.upload.uuid + "' type='text' name='caption[]'  value='" + caption + "'>");
                        file._order = Dropzone.createElement("<input class='block dark:bg-feSecondaryDark-black w-full px-3 py-2 text-sm shadow-sm dark:placeholder-feSecondary-300 placeholder-feSecondary-500 text-feSecondary-500 dark:text-feSecondary-400 rounded border border-feSecondary-200 hover:border-feSecondary-400 dark:border-feSecondary-400 hover:dark:border-feSecondary-50 focus:outline-none focus:border-none focus:ring-2 focus:ring-fePrimary-300 ring-fePrimary-300 placeholder-feSecondary-500 dark:placeholder-feSecondary-400 opacity-100' id='" + file.upload.uuid + "' type='hidden' name='order[]'  value='" + (myDropzone.value.getAcceptedFiles().length + 1) + "'>");
                        // file._preview = Dropzone.createElement("<div class='flex justify-center'> <button type='button' >Preview</button></div>");
                        file.previewElement.appendChild(file._captionLabel);

                        file.previewElement.appendChild(file._captionBox);
                        file.previewElement.appendChild(file._order);
                        // file.previewElement.appendChild(file._preview);
                        file.previewElement.querySelector('[data-dz-name]').textContent = file.upload.filename;

                        file._captionBox.name = file.filename;
                        file._captionBox.onkeyup = function () { myMethod() };
                        emit("fileLength", myDropzone.value.getAcceptedFiles().length + 1)
                        function myMethod() {
                            emit("caption", file._captionBox);
                        }
                    }),
                    this.on(
                        "sending", function (file, xhr, formData) {
                            formData.append('edit_mode', props.edit_mode);
                            formData.append('item_id', props.item_id);
                            formData.append('max_files', 3);
                            formData.append('caption', file._captionBox.value);
                            console.log(file);
                            formData.append('order', file._order.value)
                            console.log(formData)
                        })

                this.on("removedfile", function (file) {
                    // console.log(file);
                    myDropzone.value.files = myDropzone.value.files.filter(function (el) {
                        return el.name !== file.name;
                    })

                    if (myDropzone.value.files[0] != undefined) {
                        const defaultlabel = document.createElement('div');
                        defaultlabel.innerHTML = '<label for="image" class="image-label text-xxs" id="defaultText">Default</label>';
                        myDropzone.value.files[0].previewElement.firstElementChild.appendChild(defaultlabel)
                        myDropzone.value.files[0]._defaultLabel = defaultlabel;
                    }
                    // console.log(myDropzone.value.getAcceptedFiles());

                    emit("removeImage", file.filename);
                });
                this.on("complete", function (file) {
                });
                this.on("processing", function () {
                    this.options.autoProcessQueue = true;
                });
                this.on("maxfilesexceeded", function (file) {
                    file.previewElement.remove();
                    emit('maxfilesexceeded');

                });
                this.on("success", function (file, responseText) {




                    if (responseText.msg == "success") {


                        file.filename = responseText.success;
                        file._captionBox.name = file.filename;
                        file._captionBox.onkeyup = function () { myMethod() };
                        function myMethod() {

                            emit("caption", file._captionBox);

                        }
                        emit("clicked", file.filename);

                    }
                    else if (responseText.msg == "fail") {
                        var node, _i, _len, _ref, _results;
                        var message = responseText.success // modify it to your error message
                        file.previewElement.classList.add("dz-error");
                        _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                        _results = [];
                        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                            node = _ref[_i];
                            _results.push(node.textContent = message);
                        }
                        return _results;
                    }




                });



            }
        })

        Sortable.create(document.getElementById('dropzone'), {
            items: '.dz-preview',
            cursor: 'move',
            opacity: 0.5,
            containment: '#dropzone',
            distance: 20,
            tolerance: 'pointer',
            onEnd: function (evt) {
                // alert('here');
                // console.log(evt.item.querySelector("input[name='order[]']").value);
                // console.log(evt.newDraggableIndex);
                var queue = myDropzone.value.files;
                var newQueue = [];
                var newOrder = [];
                // const dropzoneElements = document.querySelectorAll('.dropzone .dz-preview .dz-filename [data-dz-name]');
                // const dropzoneElementsOrder = document.querySelectorAll(".dropzone .dz-preview input[name='order[]']");
                // console.log(dropzoneElementsOrder);
                // dropzoneElementsOrder.forEach(function (el, count) {
                //     console.log(el.value)
                // })

                const dropzoneElements = document.querySelectorAll('.dropzone .dz-preview');
                const dropzoneElementsOrder = document.querySelectorAll(".dropzone .dz-preview ");
                // console.log(dropzoneElementsOrder);
                // dropzoneElementsOrder.forEach(function (el, count) {
                //     console.log(el.querySelector('.dz-filename [data-dz-name]').innerHTML)
                // })

                dropzoneElements.forEach(function (el, count) {
                    // console.log(el);

                    var name = el.querySelector('.dz-filename [data-dz-name]').innerHTML;
                    var order = el.querySelector("input[name='order[]']").value;
                    // order= evt.newDraggableIndex;
                    // console.log(name + 'is' + order);
                    // console.log(order);
                    // console.log(count+1);

                    queue.every(function (file, index) {
                        // console.log(file);

                        if (file.name === name) {
                            // console.log(index);
                            file._order.value = count + 1
                            newQueue.push(file);
                            newOrder.push({ id: file.upload.uuid, order: file._order.value });

                            const referenceElement = document.querySelector('#defaultText');
                            console.log(referenceElement);
                            if (referenceElement != null) {
                                referenceElement.remove();
                            }




                            return false;
                        }
                        return true;
                    });
                });
                console.log(newQueue);
                emit("newOrder", newOrder);
                // console.log(newOrder);
                myDropzone.value.files = newQueue;
                if (myDropzone.value.files[0] != undefined) {
                    const defaultlabel = document.createElement('div');
                    defaultlabel.innerHTML = '<label for="image" class="image-label text-xxs" id="defaultText">Default</label>';
                    myDropzone.value.files[0].previewElement.firstElementChild.appendChild(defaultlabel)
                    myDropzone.value.files[0]._defaultLabel = defaultlabel;
                }

            }
        });









        if (props.images) {
            const sortedImages = props.images.sort((a, b) => a.ordering - b.ordering);

            props.images.forEach((element) => {
                var url = usePage().props.uploadUrl + '/' + element.imgPath;
                let mockFile = { name: element.imgPath, filename: element.imgPath, caption: element.imgDesc, upload: { filename: element.imgPath, uuid: element.imgId }, size: 12345, accepted: true };
                myDropzone.value.displayExistingFile(mockFile, url);
                myDropzone.value.createThumbnailFromUrl(mockFile, url);
                myDropzone.value.files.push(mockFile);
                myDropzone.value.emit("complete", mockFile);



            })
        }
        function autoProcessQueuestartfrom() {
            myDropzone.value.autoProcessQueue = true;
        }

    }
})



function autoProcessQueuestart(id) {
    myDropzone.value.on("sending", function (file, xhr, formData) {
        formData.append('item_id', id);

    });

    myDropzone.value.processQueue()
}

function acctiveFiles() {
    myDropzone.on('queuecomplete', function () {
        return myDropzone.value.getActiveFiles().length;
    })

}

function currentFiles() {

        return myDropzone.value.getAcceptedFiles().length;


}




</script>

<template>
    <div>
        <div ref="dropRef" id="dropzone" class="dropzone custom-dropzone "></div>

    </div>
</template>

<style>
.dropzone .dz-preview.dz-image-preview {
    background: none;
}

.dropzone .dz-preview .dz-progress {
    margin-top: 12px !important;
}

.dropzone .dz-preview .dz-image {
    width: 80px;
    height: 80px;
}

.dz-details {
    padding: 0 !important;
}

.dropzone {
    --tw-text-opacity: 1;
    border: 2px dotted rgb(107 114 128 / var(--tw-text-opacity));
    color: rgb(107 114 128 / var(--tw-text-opacity));
    margin: 2rem 0;
    row-gap: 16px;
}

.dz-image {
    margin-left: auto;
    margin-right: auto;
}

small {
    display: block;
    text-align: center;
}

h1 {
    font-size: 1em;
}

.image-label {
    position: absolute;
    top: 10px;
    /* Adjust the top position as needed */
    left: 10px;
    /* Adjust the left position as needed */
    background-color: rgba(255, 255, 255, 0.8);
    /* Set the background color and opacity for the label */
    padding: 5px;
    border-radius: 5px;
}</style>
