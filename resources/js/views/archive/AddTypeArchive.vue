<template>
    <div>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>nuevo tipo de archivo</span>
        </div>
        <h1>bienvenido/a</h1>
        <h5>esta pagina a sido intencionalmente puesta en blanco</h5>
        <el-button type="primary" @click="test">Reporte</el-button>
         <div slot="header" class="clearfix">
          <span>Archivo</span>
          <el-button style="float: right; padding: 3px 0" type="text" @click="initAddTypeArchive"
            >nuevo tipo de archivo</el-button>
        </div>
        <div>
          <el-form ref="form" :model="form" label-width="120px">
          <el-form-item label="Tipo es:">
            <el-radio-group v-model="form.resource">
                  <el-radio-button label="Archivo"></el-radio-button>
                  <el-radio-button label="Contenedor"></el-radio-button>
                  <el-radio-button label="Ubicacion"></el-radio-button>
            </el-radio-group>
          </el-form-item>
            <el-form-item label="Codificacion">
            <el-input v-model="form.name"></el-input>
          </el-form-item> 
          <el-form-item label="Descripcion">
            <el-input type="textarea" v-model="form.desc"></el-input>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onSubmit">Guardar</el-button>
            <el-button>Cancelar</el-button>
          </el-form-item>
        </el-form>
        </div>
      </el-card>
    </div>
  </template>
  
  <script>
  export default {
    name: "tiposdearchivos",
    components: {
    },
    data() {
      return {
        message: 'Hola mundo',
          form: {
          name: '',
          delivery: false,
          type: [],
          resource: '',
          desc: ''
        }
      };
    },
    mounted() {},
    methods: {
      test() {
        alert("bienvenido al modulo");
      },
      initAddTypeArchive(){
        this.$router.push({
        name: "addtypearchive",
        /*
        params: {
          id: id_archive,
        },*/
      });
      },
       onSubmit() {
       console.log('submit!');
      }
    },
  };
  </script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped></style>
  