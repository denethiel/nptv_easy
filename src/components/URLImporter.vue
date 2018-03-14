<template>
  <el-row :gutter="20">
    <el-col :span="16">
      <el-card class="box-card" v-loading="loading">
        <el-form ref="form" :model="form" label-width="200px">
          <el-form-item label="URL Noticia">
            <el-input v-model="form.url"></el-input>
          </el-form-item>
          <el-form-item label="Categoria">
            <el-select v-model="form.cat" placeholder="Selecciona una categoria">
              <el-option v-for="(item, key, index) in categories" :label="item" :value="key"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="Etiquetas(separadas por ,)">
            <el-input v-model="form.tags"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">Cargar Noticia</el-button>
            <el-button>Borrar</el-button>
          </el-form-item>
        </el-form>

      </el-card><!-- card -->
    </el-col>
    <el-col :span="8">
      <div class="grid-content bg-purple">
        <el-card>
          <img :src="news.imagen" class="image">
          <div style="padding: 14px;">
            <h3>{{news.titulo}}</h3>
            <div class="bottom clearfix">
             {{news.texto}} 
            </div>
          </div>
        </el-card>
      </div>
    </el-col>
  </el-row>
</template>

<script>
export default {
  name: 'URLImporter',
  data () {
    return {
      form:{
        action:'nptv_add_new',
        url:'',
        cat:'',
        tags:'',
        nonce: NPTV.nonce
      },
      news:{},
      categories:NPTV.data.categories,
      loading: false
    }
  },
  methods:{
    onSubmit(){
      this.loading = true;
      axios.post(NPTV.ajax_url, Qs.stringify(this.form))
        .then(function(response){
          console.log(response)
          this.loading = false;
          this.news = response.data;
          this.form
        }.bind(this))
        .catch(function(error){
          console.log(error);
        })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.grid-content {
    border-radius: 4px;
    min-height: 36px;
  }
  .row-bg {
    padding: 10px 0;
    background-color: #f9fafc;
  }
  .bg-purple {
    background: #d3dce6;
  }
  .image{
    width: 99%;
  }
</style>
