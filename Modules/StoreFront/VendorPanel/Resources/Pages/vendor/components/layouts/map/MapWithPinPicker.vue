<template>
    <div v-if="dataReady == true">

        <GoogleMap id="map" ref="map_ref" :api-key="map.key"
            :center="map.center" :zoom="map.zoom"  style="width: 100%; height: 280px">

            <Marker :options="mcenter" draggable="false" ref="marker" @drag="onChange"/>

        </GoogleMap>

    </div>
</template>

<script lang='ts'>
import { defineComponent, ref, onMounted } from 'vue';
import { GoogleMap , Marker  } from 'vue3-google-map';
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';
import AppInfoParameterHolder from '@templateCore/object/holder/AppInfoParameterHolder';
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";

export default defineComponent({
    name : "MapWithPinPicker",
    components: {
        GoogleMap,
        Marker,
    },
    props : {
        lat :{
            type: Number,
            default :0
            } ,
        lng: {
            type: Number,
            default :0
            } ,
        onChange : Function,
        draggable: {
            type: Boolean,
            default : true
            }
    },
    setup(props) {

        const map_ref = ref();
        const marker = ref();

        const lat =ref();
        const lng = ref();
        const mcenter = ref({
            position : {
            lat: 40.876945,
            lng: 77.387978
            },
            draggable: props.draggable
        });

        const coordinates = ref({
            lat: 40.876945,
            lng: 77.387978
        });

        const psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();
        const appInfoStore = usePsAppInfoStoreState();
        const appInfoParameterHolder = new AppInfoParameterHolder();
        appInfoParameterHolder.userId = loginUserId;
        const map = ref({
            key: '000',
            center: coordinates,
            zoom: 15
        });

        const dataReady = ref(false);

        async function loadMap(){

            if(lat.value != null && lng.value != null) {

                mcenter.value.position.lat = lat.value;
                mcenter.value.position.lng = lng.value;
                map.value.center = mcenter.value.position;
                coordinates.value = mcenter.value.position;

            }
            // await appInfoStore.loadAppInfo(appInfoParameterHolder);
            map.value.key = appInfoStore.appInfo.data.frontendConfigSetting.mapKey;

            dataReady.value = true;

        }

        onMounted( async () => {
            lat.value = props.lat == null || isNaN(props.lat) ? 0 : props.lat;
            lng.value = props.lng == null || isNaN(props.lng) ? 0 : props.lng;
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

         }
    },
})
</script>
