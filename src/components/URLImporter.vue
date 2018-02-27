<template>
  <el-row :gutter="20">
    <el-col :span="16">
      <el-card class="box-card">
        <el-form ref="form" :model="form" label-width="200px">
          <el-form-item label="URL Noticia">
            <el-input v-model="form.url"></el-input>
          </el-form-item>
          <el-form-item label="Categoria">
            <el-select v-model="form.cat" placeholder="Selecciona una categoria">
              <el-option label="Local" value="local"></el-option>
              <el-option label="Nacional" value="nacional"></el-option>
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
        url:'',
        cat:'',
        tags:''
      }
    }
  },
  methods:{
    onSubmit(){
      console.log('submit!');
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
</style>
