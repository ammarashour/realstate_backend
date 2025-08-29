<template>
    <div v-if="dataReady == true">

        <GoogleMap id="map" ref="map_ref" :api-key="$page.props.mapKey"
            :center="map.center" :zoom="map.zoom"  class="map-width-height"
           
            
            >

            <Marker :options="mcenter" draggable="false" ref="marker" @drag="onChange"/>

        </GoogleMap>

    </div>
</template>

<script lang='ts'>
import { defineComponent, ref, onMounted } from 'vue';
import { GoogleMap , Marker  } from 'vue3-google-map';

export default defineComponent({
    name : "GoogleMapPinPicker",
    components: {
        GoogleMap,
        Marker,
    },
    props : {
        lat :{
            type: Number,
            default :16.8409
            } ,
        lng: {
            type: Number,
            default :96.1735
            } ,
        onChange : Function,
        draggable: {
            type: Boolean,
            default : true
            },
        widthHeight: {
            type: String,
            default : 'width: 300px; height: 300px'
        },
        mapKey: {
            type: String,
            default : 'AIzaSyCtBHbqTWRgh9O8veCOJNnCMG56lXTdGJw'
        },
    },
    setup(props) {

        const map_ref = ref();
        const marker = ref();

        const lat =ref();
        const lng = ref();
        const mcenter = ref({
            position : {
            lat: 16.8409,
            lng: 96.1735
            },
            draggable: props.draggable
        });

        const coordinates = ref({
            lat: 16.8409,
            lng: 96.1735
        });

        const map = ref({
            key: props.mapKey,
            center: coordinates,
            zoom: 10
        });

        const dataReady = ref(false);

        function setPlace() {

        }

        async function loadMap(){

            console.log(props.mapKey);

            if(lat.value != null && lng.value != null) {

                mcenter.value.position.lat = lat.value;
                mcenter.value.position.lng = lng.value;
                map.value.center = mcenter.value.position;
                coordinates.value = mcenter.value.position;

            }

            map.value.key = props.mapKey;

            dataReady.value = true;

        }

        onMounted( async () => {
            lat.value = props.lat == null || isNaN(props.lat) ? 16.8409 : props.lat;
            lng.value = props.lng == null || isNaN(props.lng) ? 96.1735 : props.lng;
            map.value.center = mcenter.value.position;
            await loadMap();
        });

        return {

            mcenter,
            dataReady,
            map,
            map_ref,
            coordinates,
            marker,
            setPlace

         }
    },
})
</script>
