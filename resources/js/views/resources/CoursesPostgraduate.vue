<template>
    <div>
      <el-card class="box-card">  
        <div slot="header" class="clearfix">
        <span>LISTAR CURSO</span>
        <el-button
          style="text-align: right; float: right"
          size="small"
          type="primary"
          icon="el-icon-plus"
          @click="initAddPerson"
        >Agregar Curso</el-button>
      </div>
      </el-card>
    </div>
  </template>
  
  <script>
  
  export default {
    data() {
      return {
        mensaje: '',
        data: {},
      };
    },
    mounted() { },
    methods: {
      test() {
        alert("bienvenido al modulo");
      },
    },
  };
  </script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped>
  
  </style>
  