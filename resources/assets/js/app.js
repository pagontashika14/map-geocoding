
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Element from 'element-ui';
import locale from 'element-ui/lib/locale/lang/en';
import 'element-ui/lib/theme-default/index.css';
import { GoogleMapService } from './service/google-map-service';


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(Element, {locale});

Vue.component('ggmap', require('./components/GoogleMap.vue'));
Vue.component('address-point', require('./components/AddressPoint.vue'));
Vue.component(
    'passport-clients',
    require('./components/passport/Clients.vue')
);

Vue.component(
    'passport-authorized-clients',
    require('./components/passport/AuthorizedClients.vue')
);

Vue.component(
    'passport-personal-access-tokens',
    require('./components/passport/PersonalAccessTokens.vue')
);

window.Event = new Vue();

window.googleLoaded = false;

const app = new Vue({
    el: '#app',
    data: {
    	maps: 1,
    	addresses: [],
    	lat: 0,
    	lng: 0
    },
    methods: {
    	mapClick (event) {
    		this.lat = event.lat();
    		this.lng = event.lng();
    		let ggGeoCoder = new GoogleMapService();
    		ggGeoCoder.getGeoCoding(event.lat(),event.lng(),e => {
    			this.addresses = e;
    		});
    	}
    }
});
