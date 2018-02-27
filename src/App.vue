<template>
  <div id="nptv">
    <el-container>
      <h2>NeoPoliticaTV Control Panel</h2>
      <el-header>
        <el-menu
          :default-active="activeIndex"
          class="el-menu"
          mode="horizontal"
          @select="handleSelect"
          background-color="#545c64"
          text-color="#fff"
          active-text-color="#ffd04b">
          <el-menu-item index="url" router-link to="/">URL Importer</el-menu-item>
          <el-menu-item index="automatic" router-link to="/automatic">Automatico</el-menu-item>
        </el-menu>
      </el-header>
      <el-main>
        <router-view/>
      </el-main>
    </el-container>
    
  </div>
</template>

<script>
export default {
  data(){
    return {
      activeIndex:'url'
    }
  },
  methods:{
    handleSelect(key, keyPath){
      switch(key){
        case 'url':
          this.$router.push('/')
          break;
        case 'automatic':
          this.$router.push('Automatic')
          break;
      }
      console.log(key, keyPath);
    }
  },
  name: 'App'
}
</script>

<style>
</style>
