<template>
    <div>
      <el-card class="box-card">
        <div slot="header" class="clearfix">
          <span>Archivo</span>
          <el-button 
            style="text-align: right; float: right"
            size="small"
            type="primary"
            icon="el-icon-plus"
            @click="initAddTypeArchive"
            >nuevo tipo de archivo</el-button>
        </div>
        <div style="margin-top: 15px;">
        <el-input
          placeholder="INSERTE UNA DESCRIPCION"
          v-model="writtenTextParameter"
          class="input-with-select">
          <el-button slot="append" icon="el-icon-search" @click="initSearchPerson"></el-button>
        </el-input>
      </div>
      <br />
        <div>
          <el-form ref="form" :model="form" label-width="120px">
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
  