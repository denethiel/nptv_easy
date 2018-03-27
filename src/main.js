// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
//import Vue2Filters from 'vue2-filters'
import ElementUI from 'element-ui'
// var axios = require( 'axios' )
// var Qs = require( 'qs' )
// import Qs from 'qs'
import locale from 'element-ui/lib/locale/lang/es'
import App from './App.vue'
import router from './router'


Vue.use(ElementUI, {locale})
//Vue.use(Vue2Filters)

Vue.mixin({
	computed:{
		nonce: function(){
			return NPTV.nonce;
		},
		resource:function(){
			return this.$resource('/wp-admin/admin-ajax.php');
		}
	}
})



Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#nptv-easy-main',
  data: NPTV.data,
  router,
  components: { App },
  template: '<App/>'
})
