import Vue from 'vue'
import Router from 'vue-router'
import URLImporter from '../components/URLImporter.vue'
import Automatic from '../components/Automatic.vue'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'URLImporter',
      component: URLImporter
    },
    {
    	path:'/automatic',
    	name:'Automatic',
    	component: Automatic
    }
  ]
})
