<template>
    <div>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>bienvenido</span>
          <el-button style="float: right; padding: 3px 0" type="text" @click="initAddTypeArchive"
            >nuevo tipo de archivo</el-button
          >
        </div>
        <h1>bienvenido/a</h1>
        <h2>{{message}}</h2>
        <h5>esta pagina a sido intencionalmente puesta en blanco</h5>
        <el-button type="primary" @click="test">Reporte</el-button>
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
      }
    },
  };
  </script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped></style>
  