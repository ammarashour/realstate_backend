

<template>
  <div :class="mapSize" id="mapContainer"></div>
</template>

<script>

import L from 'leaflet';
import "leaflet/dist/leaflet.css";
import 'leaflet-control-geocoder/dist/Control.Geocoder.css';
import 'leaflet-control-geocoder/dist/Control.Geocoder.js';
import { onMounted, } from "vue";

export default {
  name: "LeafletMap",
  props: {
    lat :{
      type: Number,
      default :16
    },
    lng: {
      type: Number,
      default :96
    },
    mapSize: {
      type: String,
      default : "h-64 w-64"
    },
    onChange : Function,
    dragValue: {
      type: Boolean,
      default : true
    },
    dir: String
  },
  setup(props) {
    let map = null;
    let marker_a = null;
    // delete L.Icon.Default.prototype._getIconUrl;
    // L.Icon.Default.mergeOptions({
    //     iconRetinaUrl: iconRetinaUrl,
    //     iconUrl: iconUrl,
    //     shadowUrl: shadowUrl
    //     iconRetinaUrl: require('leaflet/dist/images/marker-icon-2x.png'),
    //     iconUrl: require('leaflet/dist/images/marker-icon.png'),
    //     shadowUrl: require('leaflet/dist/images/marker-shadow.png')
    // });

    onMounted(() => {


        let sub_domain_url = '';
        let clearSlash =  props.dir;
        let subFolder = clearSlash.replaceAll("\\", "");
        
         if(import.meta.env.PROD){

            if(subFolder != null && subFolder != '')
            {
                sub_domain_url = '/'  + props.dir;
            }
            else
            {
                sub_domain_url = '';
            }


        }else{
            sub_domain_url = '';
        }
        var greenIcon = L.icon({
            iconUrl: sub_domain_url+'/images/assets/marker-icon.png',
            shadowUrl: sub_domain_url+'/images/assets/marker-shadow.png',
        });

        
        loadTheMap(props.lat, props.lng);
        
        // marker_a = new L.Marker([props.lat, props.lng], {icon: greenIcon, draggable: true}).addTo(map);
        // marker_a.on('dragend', dragEndHandler);

    //   if(props.lat == null || props.lng == null){
    //     marker_a.on('dragend', dragEndHandler);
    //   }

        // Add the geocoder control to the map
        const geocoder = L.Control.geocoder({
            defaultMarkGeocode: false,
        }).addTo(map);

        
        // Handle the result of a geocode search
        geocoder.on('markgeocode', function(e) {
            clearMarker();
            map.flyTo(e.geocode.center, 12);
            marker_a = new L.Marker([e.geocode.properties.lat, e.geocode.properties.lon], {icon: greenIcon, draggable: props.dragValue}).addTo(map);
            var latlng = {
                lat: e.geocode.properties.lat,
                lng: e.geocode.properties.lon
            }
            props.onChange(latlng);
            marker_a.on('dragend', dragEndHandler);
        });

        
        if(props.dragValue) {
            marker_a = new L.Marker([ props.lat, props.lng], {icon: greenIcon, draggable: props.dragValue}).addTo(map);
            marker_a.on('dragend', dragEndHandler);
        } else {
            marker_a = new L.Marker([ props.lat, props.lng], {icon: greenIcon}).addTo(map);
            marker_a.on('dragend');
        }

        

        function dragEndHandler(e) {

            var latlng = e.target.getLatLng();
            if(props.onChange != undefined){
                props.onChange(latlng);
            }
        }

        function clearMarker() {
        if (marker_a) {
          map.removeLayer(marker_a);
        }
      }

    });

    function loadTheMap(lat, lng) {
        
      if (!map) {
        // First time loading the map
        map = L.map("mapContainer").setView([lat, lng], 12);

        L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);
      } else {
          // If the map already exists, just update the view
          map.setView([lat, lng], 12);

          if (marker_a) {
            // If marker exists, update its position
            marker_a.setLatLng([lat, lng]);
        }
      }
    }

    return {
      loadTheMap
    };
  }

};
</script>

<style >
#mapContainer {
    z-index: 0 !important;
    width: 100%;
}
.leaflet-control-geocoder-icon{
     background-image:url('../../images/assets/searchicon.svg') !important;

}

</style>
