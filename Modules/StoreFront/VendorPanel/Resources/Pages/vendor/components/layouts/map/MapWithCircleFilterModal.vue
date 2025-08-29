<template>
    <ps-modal ref="psmodal" line="hidden" bodyHeight="max-h-76" :isClickOut="true" >
        <template #title>
            <ps-label class='mb-2'> {{ $t("map_with_circle_filter_moadl__title") }} </ps-label>
        </template>
        <template #body>
            <GoogleMap v-if="appInfoStore?.appInfo?.data.frontendConfigSetting.googleMap == PsConst.ONE" id="map" ref="map_ref" :api-key="map.key" :draggable="true"
                :center="map.center" :zoom="map.zoom"  style="width: 100%; height: 300px">
                <Circle id="circle" :options="circleCenter" ref="cir_ref" @dragend="dragend" @click="radius_changed" />
            </GoogleMap>

            <open-street-map :dir="$page.props.dir" v-if="appInfoStore?.appInfo?.data.frontendConfigSetting.openStreetMap == PsConst.ONE" class="h-68" :lat="map.center.lat" :lng="map.center.lng" :dragValue="false" :onChange="updateLeafletCoordinates" />

            <ps-label class='mt-1'> {{ radius }} {{ $t("map_with_circle_filter_moadl__miles") }} </ps-label>
            <input  type="range" id="vol" name="vol" min="1" max="300" v-model="radius" @input="rangeChanged" class=' w-full'>
        </template>
        <template #footer>
            <div class="flex justify-between">
                <ps-button @click="psmodal.toggle(false)" class='' theme='btn-second'> {{ $t("map_with_circle_filter_moadl__cancel") }} </ps-button>
                <ps-button @click="pickedLocation"> {{ $t("map_with_circle_filter_moadl__pick_location") }} </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script lang='ts'>
import { defineComponent, ref, defineAsyncComponent } from 'vue';
import { GoogleMap , Circle  } from 'vue3-google-map';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import PsUtils from '@templateCore/utils/PsUtils';
import { usePsAppInfoStoreState } from '@templateCore/store/modules/appinfo/AppInfoStore';
import AppInfoParameterHolder from '@templateCore/object/holder/AppInfoParameterHolder';
import { PsValueStore } from "@templateCore/store/modules/core/PsValueStore";
import PsConst from '@templateCore/object/constant/ps_constants';

const OpenStreetMap = defineAsyncComponent(() => import('@template1/vendor/components/layouts/map/OpenStreetMap.vue'))

export default defineComponent({
    name : "MapWithCircleFilterModal",
    components: {
        PsModal,
        GoogleMap,
        Circle,
        PsButton,
        PsLabel,
        OpenStreetMap
    },
    setup() {
        const psmodal = ref();
        const map_ref = ref();
        const cir_ref = ref();
        const m_to_mi = 1610;
        let updateLatLng : Function;
        const lat =ref();
        const lng = ref();
        const radius = ref(1);

        const mcenter = ref({
            position : {
            lat: 40.876945,
            lng: 77.387978
            },
            draggable: true
        });



        const psValueStore = PsValueStore();
        const loginUserId = psValueStore.getLoginUserId();
        const appInfoStore = usePsAppInfoStoreState();
        const appInfoParameterHolder = new AppInfoParameterHolder();
        appInfoParameterHolder.userId = loginUserId;
        const map = ref({
            key: '000',
            center: mcenter.value.position,
            zoom: 15
        });

        async function loadData(){
            // await appInfoStore.loadAppInfo(appInfoParameterHolder);
            map.value.key = appInfoStore.appInfo.data.frontendConfigSetting.mapKey;
        }
        loadData();

        function pickedLocation() {
            if(cir_ref.value != null) {
                lat.value = cir_ref.value.circle.component.value.getCenter().lat();
                lng.value = cir_ref.value.circle.component.value.getCenter().lng();
            } else {
                lat.value = mcenter.value.position.lat;
                lng.value = mcenter.value.position.lng;
            }

            updateLatLng(lat.value, lng.value, radius.value);
            psmodal.value.toggle(false);
        }

        function openModal(lat: number, lng: number, mile: number, updateLocation: Function) {

            if(lat != 0) {
                mcenter.value.position.lat = lat;
            }

            if(lng != 0) {
                mcenter.value.position.lng = lng;
            }

            if(lat != 0 && lng != 0) {
                map.value.center = mcenter.value.position;

                if(map_ref.value != null && map_ref.value.center != null) {
                    map_ref.value.map.setCenter(map_ref.value.map.center);
                    cir_ref.value.circle.component.value.setCenter(mcenter.value.position);
                }
            }

            updateLatLng = updateLocation;

            if(mile != 0) {
                radius.value = mile;

            }

            psmodal.value.toggle(true);


        }

        function rangeChanged() {
            cir_ref.value.circle.component.value.setRadius(radius.value * m_to_mi);
        }

        // Radius * 2 = meters
        const circleCenter = ({
            editable : false,
            center: mcenter.value.position,
            radius: radius.value * m_to_mi,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 2,
            draggable: true,
            crossOnDrag: false
        })

        function dragend(event) {

            lat.value = event.latLng.lat();
            lng.value = event.latLng.lng();
        }

        function radius_changed() {
            if(cir_ref.value != null) {
                PsUtils.log(cir_ref.value.circle.component.value.getRadius());
                PsUtils.log(cir_ref.value.circle.component.value.getCenter().lat() + " <> " + cir_ref.value.circle.component.value.getCenter().lng());
            }

        }

        function updateLeafletCoordinates(location) {

            mcenter.value.position.lat = location.lat.toString();
            mcenter.value.position.lng = location.lng.toString();

        }

        return {
            psmodal,
            updateLeafletCoordinates,
            mcenter,
            map,
            openModal,
            map_ref,
            pickedLocation,
            cir_ref,
            circleCenter,
            radius_changed,
            dragend,
            radius,
            rangeChanged,
            appInfoStore,
            PsConst
         }
    },
})
</script>
