<template>
    <ps-modal ref="psmodal" maxWidth="370px" :isClickOut='false' line="hidden" theme= "bg-feAchromatic-50 dark:bg-feSecondary-800 lg:px-6 px-4 py-6 rounded-x " class=' z-20'>
        <template #title>
            <div class="flex flex-row justify-between mb-3">
                <ps-label class='flex-grow text-base' textColor="text-feSecondary-800 dark:text-feSecondary-200"> {{ $t("fe_map_with_marker_moadl__title") }}  </ps-label>
                <ps-icon @click="psmodal.toggle(false)" name="close" class="text-feSecondary-800 dark:text-feSecondary-200"/>
            </div>
        </template>
        <template #body>
            <div class="w-full h-68 flex justify-center items-center" >
                 <map-with-makers v-if="mapReady" class="h-68" @onChange="onPointerMove" :dir="$page.props.dir" :dragValue="true" :lat="lat" :lng="lng" :markers="markers" :radius="getMiles()" />
                 <ps-label v-else class="flex-grow-0"> {{ $t("fe_map_with_marker_moadl__loading") }} </ps-label>
            </div>

            <div class="w-full mt-5 flex flex-row justify-between " >
                <ps-label class=' text-base' textColor="text-feSecondary-800 dark:text-feSecondary-200"> {{ $t("fe_map_with_marker_moadl__explore_around") }}  </ps-label>
                <ps-label class='text-base' textColor="text-feSecondary-800 dark:text-feSecondary-200"> {{ getKM()==0? $t("fe_map_with_marker_moadl__all"): getKM() + ' km' }}   </ps-label>
            </div>

        </template>
        <template #footer>
             <datalist id="tickmarks">
                <option value="1"></option>
                <option value="2"></option>
                <option value="3"></option>
                <option value="4"></option>
                <option value="5"></option>
                <option value="6"></option>
                <option value="7"></option>
                <option value="8"></option>
                <option value="9"></option>
                <option value="10"></option>
                <option value="11"></option>
            </datalist>
            <input  type="range" id="vol" list="tickmarks" name="vol" min="1" max="11" v-model="radius" @input="rangeChanged" class='w-full'>

             <div class="w-full mb-4 flex flex-row justify-between " >
                <ps-label class=' text-sm' textColor="text-feSecondary-800 dark:text-feSecondary-200"> 0.5 km  </ps-label>
                <ps-label class='text-sm' textColor="text-feSecondary-800 dark:text-feSecondary-200"> {{$t("fe_map_with_marker_moadl__all")  }} </ps-label>
            </div>
            <div class="flex justify-between  rtl:space-x-reverse space-x-4">
                <ps-feSecondary-button @click="reset" class="w-full" >
                    {{ $t("fe_map_with_marker_moadl__reset") }}
                    </ps-feSecondary-button>
                <ps-button @click="apply" class="w-full">
                     {{ $t("fe_map_with_marker_moadl__apply") }}
                     </ps-button>
            </div>
        </template>
    </ps-modal>
</template>

<script lang='ts'>
import { defineComponent,ref } from 'vue';
import PsModal from '@template1/vendor/components/core/modals/PsModal.vue';
import PsButton from '@template1/vendor/components/core/buttons/PsButton.vue';
import PsSecondaryButton from '@template1/vendor/components/core/buttons/PsSecondaryButton.vue';
import MapWithMakers from "@template1/vendor/components/layouts/map/MapWithMakers.vue";
import PsLabel from '@template1/vendor/components/core/label/PsLabel.vue';
import { useProductStore } from "@templateCore/store/modules/item/ProductStore";
import { PsValueStore } from '@templateCore/store/modules/core/PsValueStore';
import PsIcon from '@template1/vendor/components/core/icons/PsIcon.vue';

export default defineComponent({
    name : "PsConfirmDialog",
    components : {
        PsModal,
        PsButton,
        MapWithMakers,
        PsLabel,
        PsSecondaryButton,
        PsIcon
    },
    setup() {
        const psmodal = ref();

        const radius = ref(1);
        const psValueStore = PsValueStore();

        const lat = ref();
        const lng = ref();
        const markers = ref();
        const mapReady = ref(false);
        const isFirstTime = ref(true);
        let okClicked: Function;

        const productStore = useProductStore('list');

        function actionClicked() {
            psmodal.value.toggle(false);
        }

         function closeModal() {
            psmodal.value.toggle(false);
        }

        async function openModal(latStr, lngStr,mile, clickFunction: Function) {
            lat.value = parseFloat(latStr);
            lng.value = parseFloat(lngStr);
            productStore.paramHolder.mile = mile;

            isFirstTime.value = false;

            okClicked = clickFunction;

            productStore.paramHolder.lat = lat.value;
            productStore.paramHolder.lng = lng.value;
            if(productStore.paramHolder.mile != ''){
                radius.value = getRadius(parseFloat(productStore.paramHolder.mile) / 0.621371);
            }else{
                radius.value = 11;
            }
            markers.value = new Array;

            psmodal.value.toggle(true);
            mapReady.value = false;

            setTimeout(() => {
                mapReady.value = true;
            },500)
        }
        async function apply(){

            mapReady.value = false;

            const km = getKM();
            if(km == 0){
                productStore.paramHolder.mile = '';
            }else{
                productStore.paramHolder.mile = km * 0.621371;
            }

            // await productStore.resetProductList(psValueStore.getLoginUserId(), productStore.paramHolder);
            markers.value = new Array;

            mapReady.value = true;
            actionClicked();
            okClicked(productStore.paramHolder.lat,productStore.paramHolder.lng,productStore.paramHolder.mile);
        }

        async function reset(){

            isFirstTime.value = true;

            mapReady.value = false;
            radius.value = 11;
            const km = getKM();
            // if(km == 0){
                productStore.paramHolder.mile = '';
            // }else{
            //     productStore.paramHolder.mile = km * 0.621371;
            // }
            // await productStore.resetProductList(psValueStore.getLoginUserId(), productStore.paramHolder);
            markers.value = new Array;

            mapReady.value = true;
            actionClicked();
            okClicked(productStore.paramHolder.lat,productStore.paramHolder.lng,productStore.paramHolder.mile);


        }
        function rangeChanged() {
            mapReady.value = false;
            setTimeout(() => {
                mapReady.value = true;
            },500)
        }

        function getMiles(){
            const km = getKM();
            if(km == 0){
                return 0;
            }else{
                return km * 0.621371;
            }
        }

        function getRadius(km){
            if(km <= 0.5){
                return 1;
            }
            else if(km <= 1){
                return 2;
            }
            else if(km <= 2.5){
                return 3;
            }
            else if(km <= 5){
                return 4;
            }
            else if(km <= 10){
                return 5;
            }
            else if(km <= 25){
                return 6;
            }
            else if(km <= 50){
                return 7;
            }
            else if(km <= 100){
                return 8;
            }
            else if(km <= 200){
                return 9;
            }
            else if(km <= 500){
                return 10;
            }
            else{
                return 11;
            }
        }

        function getKM(){
            if(radius.value == 1){
                return 0.5;
            }
            if(radius.value == 2){
                return 1;
            }
            if(radius.value == 3){
                return 2.5;
            }
            if(radius.value == 4){
                return 5;
            }
            if(radius.value == 5){
                return 10;
            }
            if(radius.value == 6){
                return 25;
            }
            if(radius.value == 7){
                return 50;
            }
            if(radius.value == 8){
                return 100;
            }
            if(radius.value == 9){
                return 200;
            }
            if(radius.value == 10){
                return 500;
            }
            if(radius.value == 11){
                return 0;
            }
        }

        function onPointerMove(v){
            lat.value = v.lat;
            lng.value = v.lng;
            productStore.paramHolder.lat = v.lat;
            productStore.paramHolder.lng = v.lng;

        }


        return {
            psmodal,
            getMiles,
            openModal,
            actionClicked,
            lat,
            lng,
            markers,
            radius,
            apply,
            reset,
            mapReady,
            rangeChanged,
            getKM,
            onPointerMove,
            closeModal,
            isFirstTime
        }
    },
})
</script>

<style scoped>

input[type=range]::-webkit-slider-runnable-track {
  -webkit-appearance: none;
  width: 100%;
  height: 10px;
  cursor: pointer;
  background: #A92428;
  border-radius: 5px;
}


#vol::-webkit-slider-thumb {
  -webkit-appearance: none;
  /* appearance: none; */
  margin-top: -3.5px;
}

</style>
