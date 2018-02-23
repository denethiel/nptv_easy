// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import ElementUI from 'element-ui'
//import '../theme/index.css'
import locale from 'element-ui/lib/locale/lang/es'
import App from './App.vue'
import router from './router'

Vue.use(ElementUI, {locale})

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
