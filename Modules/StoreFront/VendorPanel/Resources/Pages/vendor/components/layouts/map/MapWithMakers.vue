<template>
  <div id="mapMarkerContainer"></div>
</template>

<script lang='ts'>

import L from "leaflet";
import "leaflet/dist/leaflet.css";
import 'leaflet-control-geocoder/dist/Control.Geocoder.css';
import 'leaflet-control-geocoder/dist/Control.Geocoder.js';
import { onMounted } from "vue";
import { PropType } from 'vue-demi';


class MarkerHolder {
  lat: string = '';
  lng: string = '';
  name: string = '';
  id: string = '';
}

export default {
  name: "MapWithMakers",
  props: {
    lat: {
      type: Number,
      default: 0
    },
    lng: {
      type: Number,
      default: 0
    },
    markers: {
      type: Array as PropType<Array<MarkerHolder>>,
      default: () => [new MarkerHolder()]
    },
    radius: {
      type: Number,
      default: 1
    },
    // onChange : Function,
    dragValue: {
      type: Boolean,
      default: true
    },
    dir: String
  },
  emit: ['onChange'],
  setup(props, { emit }) {
    let map = null;
    const m_to_mi = 1610;

    onMounted(() => {
      delete L.Icon.Default.prototype._getIconUrl
      let sub_domain_url = '';
      let clearSlash =  props.dir;
      let subFolder = clearSlash.replaceAll("\\", "");

      if (import.meta.env.VITE_APP_ENV == "production") {

        if (subFolder != null && subFolder != '') {
          sub_domain_url = '/' + props.dir;
        }
        else {
          sub_domain_url = '';
        }


      } else {
        sub_domain_url = '';
      }
      L.Icon.Default.mergeOptions({
        iconRetinaUrl: sub_domain_url + '/images/assets/marker-icon-2x.png',
        iconUrl: sub_domain_url + '/images/assets/marker-icon.png',
        shadowUrl: sub_domain_url + '/images/assets/marker-shadow.png'
      });

      let zoom = 17;
      const km = props.radius / 0.621371;

      if (km == 0) {
        zoom = 13;
      }
      else if (km <= 0.5) {
        zoom = 15;
      }
      else if (km <= 1) {
        zoom = 14;
      }
      else if (km <= 2.5) {
        zoom = 13;
      }
      else if (km <= 5) {
        zoom = 12;
      }
      else if (km <= 10) {
        zoom = 11;
      }
      else if (km <= 25) {
        zoom = 9;
      }
      else if (km <= 50) {
        zoom = 8;
      }
      else if (km <= 100) {
        zoom = 7;
      }
      else if (km <= 200) {
        zoom = 6;
      }
      else if (km <= 500) {
        zoom = 5;
      }
      else {
        zoom = 13;
      }
      


      map = L.map("mapMarkerContainer").setView([props.lat, props.lng], zoom);
      var marker_a;
      L.tileLayer("http://{s}.tile.osm.org/{z}/{x}/{y}.png", {
        attribution:
          '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      }).addTo(map);

      if (props.dragValue) {
        marker_a = new L.Marker([props.lat == null || isNaN(props.lat) ? 0 : props.lat, props.lng == null || isNaN(props.lng) ? 0 : props.lng], { draggable: props.dragValue }).addTo(map);
        marker_a.on('dragend', dragEndHandler);
      } else {
        marker_a = new L.Marker([props.lat == null || isNaN(props.lat) ? 0 : props.lat, props.lng == null || isNaN(props.lng) ? 0 : props.lng]).addTo(map);
        marker_a.on('dragend');
      }

      var group1 = L.featureGroup();
      // circles to be clicked
      var circle1 = L.circle([44.6663888888889, 6.775], { radius: 150, color: 'white', weight: .5, opacity: 1, fillColor: '#ff9900', fillOpacity: 1 }).addTo(group1);
      // circles to be shown with event
      map.addLayer(group1);
      // handle event of the group you choosed to be clicked

      //  L.marker( [props.lat, props.lng] )
      //         .bindPopup( 'Current Location' )
      //         .addTo( map );

      var location = L.circle([props.lat, props.lng], props.radius * m_to_mi, {
        color: 'red',
        weight: .5,
        // opacity: 1,
        fillColor: '#93C5FD',
        fillOpacity: .3
      });
      map.addLayer(location);

      // Add the geocoder control to the map
      const geocoder = L.Control.geocoder({
        defaultMarkGeocode: false,
      }).addTo(map);

      // Handle the result of a geocode search
      geocoder.on('markgeocode', function (e) {
        map.removeLayer(marker_a);

        map.flyTo(e.geocode.center, 12);
        marker_a = new L.Marker([e.geocode.properties.lat, e.geocode.properties.lon], { draggable: props.dragValue }).addTo(map);
        var latlng = {
          lat: e.geocode.properties.lat,
          lng: e.geocode.properties.lon
        }
        emit('onChange', latlng);
        map.removeLayer(location);
        location = new L.circle([latlng.lat, latlng.lng], props.radius * m_to_mi, {
          color: 'red',
          weight: .5,
          // opacity: 1,
          fillColor: '#93C5FD',
          fillOpacity: .3
        });
        map.addLayer(location);

        marker_a.on('dragend', dragEndHandler);
      });

      function dragEndHandler(e) {
        var latlng = e.target.getLatLng();
        emit('onChange', latlng);
        map.removeLayer(location);
        location = new L.circle([latlng.lat, latlng.lng], props.radius * m_to_mi, {
          color: 'red',
          weight: .5,
          // opacity: 1,
          fillColor: '#93C5FD',
          fillOpacity: .3
        });
        map.addLayer(location);
      }

    });


  }

};
</script>

<style scoped>
#mapMarkerContainer {
  z-index: 0 !important;
  width: 100%;
}

.leaflet-control-geocoder-icon {
  background-image: url('../../images/assets/searchicon.svg') !important;

}
</style>